<?php

namespace Modules\AppInbox\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AdminUser\Models\User;
use Modules\AppAutomation\Services\AutomationApiKeyService;
use Modules\AppInbox\Models\InboxConversation;

class InboxApiController extends Controller
{
    public function __construct(protected AutomationApiKeyService $keys) {}

    public function index(Request $request): JsonResponse
    {
        $this->authenticate($request);
        $query = InboxConversation::query()->with(['messages', 'tags'])->latest('last_message_at');

        if ($request->filled('provider')) {
            $query->where('provider_key', $request->string('provider'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->where('contact_name', 'like', '%'.$request->string('search').'%')->orWhere('last_message_preview', 'like', '%'.$request->string('search').'%'));
        }

        return response()->json(['ok' => true, 'data' => $query->paginate((int) $request->integer('per_page', 25))]);
    }

    public function show(InboxConversation $conversation): JsonResponse
    {
        return response()->json(['ok' => true, 'data' => $conversation->load(['messages', 'participants', 'tags', 'assignee'])]);
    }

    public function sendMessage(Request $request, InboxConversation $conversation): JsonResponse
    {
        $user = $this->authenticate($request, 'inbox:write');
        $data = $request->validate(['body' => ['nullable', 'string', 'max:10000'], 'attachments' => ['nullable', 'array']]);
        $message = $conversation->messages()->create(['direction' => 'outbound', 'sender_type' => 'human', 'sender_user_id' => $user->id, 'body' => $data['body'] ?? null, 'attachments' => $data['attachments'] ?? [], 'delivery_status' => 'queued', 'sent_at' => now()]);
        $conversation->update(['handling_mode' => 'human', 'assigned_user_id' => $user->id, 'last_message_at' => now(), 'last_message_preview' => $message->body]);

        return response()->json(['ok' => true, 'data' => $message], 201);
    }

    public function assign(Request $request, InboxConversation $conversation): JsonResponse
    {
        $user = $this->authenticate($request, 'inbox:assign');
        $data = $request->validate(['user_id' => ['required', 'integer', 'exists:users,id']]);
        $conversation->update(['assigned_user_id' => $data['user_id'], 'handling_mode' => 'human']);
        $conversation->assignments()->create(['user_id' => $data['user_id'], 'assigned_by_user_id' => $user->id, 'assigned_at' => now()]);

        return response()->json(['ok' => true, 'data' => $conversation->fresh()->load('assignee')]);
    }

    public function takeover(Request $request, InboxConversation $conversation): JsonResponse
    {
        $user = $this->authenticate($request, 'inbox:write');
        $conversation->update(['handling_mode' => 'human', 'assigned_user_id' => $user->id]);

        return response()->json(['ok' => true, 'data' => $conversation->fresh()]);
    }

    public function returnToAi(Request $request, InboxConversation $conversation): JsonResponse
    {
        $this->authenticate($request, 'inbox:write');
        $conversation->update(['handling_mode' => 'ai']);

        return response()->json(['ok' => true, 'data' => $conversation->fresh()]);
    }

    public function status(Request $request, InboxConversation $conversation): JsonResponse
    {
        $this->authenticate($request, 'inbox:write');
        $data = $request->validate(['status' => ['required', 'in:open,pending,closed,snoozed']]);
        $conversation->update(['status' => $data['status']]);

        return response()->json(['ok' => true, 'data' => $conversation->fresh()]);
    }

    public function events(InboxConversation $conversation): JsonResponse
    {
        return response()->json(['ok' => true, 'data' => $conversation->load('messages')->messages->map->metadata]);
    }

    public function settings(): JsonResponse
    {
        return response()->json(['ok' => true, 'data' => config('modules.appinbox', ['providers' => ['whatsapp', 'instagram', 'messenger', 'telegram']])]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        return response()->json(['ok' => true, 'data' => $request->validate(['enabled' => ['sometimes', 'boolean'], 'confidence_threshold' => ['sometimes', 'numeric', 'between:0,1'], 'handoff_keywords' => ['sometimes', 'array']])]);
    }

    protected function authenticate(Request $request, ?string $permission = null): User
    {
        $key = $this->keys->resolveFromRequest($request);
        abort_unless($key, 401, 'Missing or invalid automation API key.');
        $user = User::query()->find((int) $key->user_id);
        abort_unless($user, 401, 'API key owner is unavailable.');
        if ($permission !== null) {
            abort_unless(in_array($permission, (array) $key->permissions, true), 403, 'Inbox API permission required.');
        }
        $this->keys->touch($key);

        return $user;
    }
}
