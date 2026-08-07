<?php

namespace Modules\AppInbox\Livewire;

use App\Models\CrmLead;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use Modules\AppInbox\Support\InboxService;

#[Title('Inbox - Ascend Systems')]
class Inbox extends Component
{
    public string $channel = 'all';

    public string $search = '';

    public int $selectedConversationId = 1;

    public string $draft = '';

    public bool $showContext = true;

    public bool $showMobileList = true;

    /** @var array<int, array<string, mixed>> */
    public array $conversations = [];

    public function mount(InboxService $service): void
    {
        $this->conversations = $service->seededConversations();
    }

    public function selectConversation(int $id): void
    {
        $this->selectedConversationId = $id;
        $this->showMobileList = false;

        foreach ($this->conversations as &$conversation) {
            if ((int) $conversation['id'] === $id) {
                $conversation['unread'] = 0;
            }
        }
    }

    public function showConversationList(): void
    {
        $this->showMobileList = true;
    }

    public function toggleContext(): void
    {
        $this->showContext = ! $this->showContext;
    }

    public function toggleAi(): void
    {
        foreach ($this->conversations as &$conversation) {
            if ((int) $conversation['id'] === $this->selectedConversationId) {
                $conversation['mode'] = ($conversation['mode'] ?? 'ai') === 'ai' ? 'human' : 'ai';
                $conversation['assigned'] = $conversation['mode'] === 'ai' ? 'AI Autoresponder' : 'Sarah Williams';
            }
        }
    }

    public function sendMessage(): void
    {
        $body = trim($this->draft);

        if ($body === '') {
            return;
        }

        foreach ($this->conversations as &$conversation) {
            if ((int) $conversation['id'] !== $this->selectedConversationId) {
                continue;
            }

            $conversation['messages'][] = ['from' => 'human', 'body' => $body, 'time' => now()->format('h:i A')];
            $conversation['preview'] = Str::limit($body, 56);
            $conversation['time'] = 'Just now';
            $conversation['mode'] = 'human';
            $conversation['assigned'] = 'Sarah Williams';
        }

        $this->draft = '';
        $this->dispatch('inbox-message-sent');
    }

    public function applyQuickReply(string $text): void
    {
        $this->draft = $text;
    }

    public function createCrmDealFromConversation(): void
    {
        $selected = collect($this->conversations)->firstWhere('id', $this->selectedConversationId);

        if ($selected) {
            CrmLead::create([
                'company_name' => $selected['name'],
                'contact_person' => $selected['name'],
                'email' => str_contains($selected['handle'], '@') ? $selected['handle'] : 'client@ascendsystems.ng',
                'phone' => str_contains($selected['handle'], '+') ? $selected['handle'] : '+234 800 000 0000',
                'deal_value' => 1500000.00,
                'status' => 'new',
                'notes' => 'Created directly from Omnichannel Inbox conversation #'.$selected['id'],
            ]);

            session()->flash('status', __('CRM Deal created for :name (₦1,500,000)!', ['name' => $selected['name']]));
        }
    }

    public function render(): View
    {
        $filtered = collect($this->conversations)
            ->when($this->channel !== 'all', fn ($items) => $items->where('provider', $this->channel))
            ->when(trim($this->search) !== '', function ($items): mixed {
                $term = Str::lower(trim($this->search));

                return $items->filter(fn (array $item): bool => Str::contains(Str::lower($item['name'].' '.$item['preview'].' '.$item['handle']), $term));
            })
            ->values()
            ->all();

        $selected = collect($this->conversations)->firstWhere('id', $this->selectedConversationId) ?: $this->conversations[0] ?? null;

        return view('appinbox::livewire.inbox', [
            'filteredConversations' => $filtered,
            'selected' => $selected,
            'channelCounts' => collect($this->conversations)->groupBy('provider')->map->count()->all(),
        ])->layout(theme_view('layouts.app', 'app'), [
            'title' => __('Inbox - Ascend Systems'),
            'fullWorkspace' => false,
            'fullWorkspacePaddingBottom' => false,
        ]);
    }
}
