@props([
    'user' => null,
])

@php
    $currentUser = $user ?? auth()->user();

    $hasChannels = class_exists(\Modules\AppChannels\Models\SocialAccount::class)
        ? \Modules\AppChannels\Models\SocialAccount::query()->where('user_id', (int) $currentUser?->id)->exists()
        : false;

    $hasPosts = class_exists(\Modules\AppPublishing\Models\ScheduledPost::class)
        ? \Modules\AppPublishing\Models\ScheduledPost::query()->where('user_id', (int) $currentUser?->id)->exists()
        : false;

    $hasCompany = class_exists(\App\Models\Company::class)
        ? \App\Models\Company::query()->exists()
        : true;

    $hasAiConfig = filled(config('services.openai.api_key')) || filled(config('ai.default'));

    $steps = [
        [
            'id'          => 'channels',
            'title'       => 'Connect Social Channels',
            'description' => 'Link Facebook, Instagram, LinkedIn, TikTok, or X accounts.',
            'completed'   => $hasChannels,
            'url'         => Route::has('portal.channels') ? route('portal.channels') : route('portal.dashboard'),
            'icon'        => 'fa-light fa-share-nodes',
        ],
        [
            'id'          => 'ai',
            'title'       => 'Configure AI Model',
            'description' => 'Enable AI content generation & automated reply rules.',
            'completed'   => $hasAiConfig,
            'url'         => Route::has('portal.ai-studio.settings') ? route('portal.ai-studio.settings') : route('portal.dashboard'),
            'icon'        => 'fa-light fa-brain-circuit',
        ],
        [
            'id'          => 'company',
            'title'       => 'Set Up Company & Branch',
            'description' => 'Define company profiles, operating currency, and branches.',
            'completed'   => $hasCompany,
            'url'         => Route::has('portal.admin.settings') ? route('portal.admin.settings') : route('portal.dashboard'),
            'icon'        => 'fa-light fa-building-user',
        ],
        [
            'id'          => 'first_post',
            'title'       => 'Create First Post',
            'description' => 'Draft or schedule your first multi-channel post.',
            'completed'   => $hasPosts,
            'url'         => Route::has('portal.publishing.calendar') ? route('portal.publishing.calendar') : route('portal.dashboard'),
            'icon'        => 'fa-light fa-paper-plane-top',
        ],
    ];

    $completedCount = collect($steps)->filter(fn($s) => $s['completed'])->count();
    $totalSteps = count($steps);
    $percent = (int) round(($completedCount / $totalSteps) * 100);
@endphp

@if ($percent < 100)
    <div
        x-data="{ dismissed: localStorage.getItem('ascend-onboarding-dismissed') === 'true' }"
        x-show="!dismissed"
        class="mb-6 overflow-hidden rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 p-6 text-white shadow-xl dark:border-indigo-900/50"
    >
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-2 max-w-xl">
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-indigo-300">
                    <span class="inline-flex h-2 w-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    <span>Workspace Setup Progress</span>
                </div>
                <h3 class="text-xl font-bold tracking-tight text-white">Welcome to Ascend AI</h3>
                <p class="text-sm text-indigo-200/80 leading-relaxed">
                    Complete these {{ $totalSteps }} essential steps to unlock full automated social publishing, omnichannel inbox handling, and AI agent execution.
                </p>
                
                <!-- Progress Bar -->
                <div class="pt-2">
                    <div class="flex items-center justify-between text-xs font-medium text-indigo-200 mb-1.5">
                        <span>Progress ({{ $completedCount }}/{{ $totalSteps }} completed)</span>
                        <span class="font-bold text-white">{{ $percent }}%</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-800">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-indigo-400 to-emerald-400 transition-all duration-500"
                            style="width: {{ $percent }}%"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Action Trigger / Dismiss Button -->
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('open-command-palette')"
                    class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-xs font-semibold text-white backdrop-blur-md hover:bg-white/20 transition-all border border-white/10"
                >
                    <i class="fa-light fa-command"></i>
                    <span>Quick Command Palette (Cmd+K)</span>
                </button>
                <button
                    type="button"
                    x-on:click="dismissed = true; localStorage.setItem('ascend-onboarding-dismissed', 'true')"
                    class="rounded-xl p-2.5 text-indigo-300 hover:bg-white/10 hover:text-white transition-all"
                    title="Dismiss Banner"
                >
                    <i class="fa-light fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Checklist Grid -->
        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4 pt-4 border-t border-indigo-800/40">
            @foreach ($steps as $step)
                <a
                    href="{{ $step['url'] }}"
                    class="group relative flex items-start gap-3 rounded-xl border p-3.5 transition-all {{ $step['completed'] ? 'border-emerald-500/40 bg-emerald-950/20 text-emerald-100' : 'border-indigo-800/50 bg-indigo-950/40 text-indigo-100 hover:border-indigo-500/60 hover:bg-indigo-900/40' }}"
                >
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $step['completed'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-indigo-800/50 text-indigo-300 group-hover:bg-indigo-700/60 group-hover:text-white' }}">
                        @if ($step['completed'])
                            <i class="fa-light fa-circle-check text-base"></i>
                        @else
                            <i class="{{ $step['icon'] }} text-sm"></i>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 font-semibold text-xs text-white">
                            <span class="truncate">{{ $step['title'] }}</span>
                            @if ($step['completed'])
                                <span class="rounded bg-emerald-500/20 px-1.5 py-0.2 text-[10px] font-medium text-emerald-300">Done</span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-[11px] text-indigo-200/70 line-clamp-1">{{ $step['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
