@php
    $providerMeta = [
        'all' => ['label' => __('All Channels'), 'icon' => 'fa-light fa-inbox', 'color' => '#2563eb'],
        'whatsapp' => ['label' => __('WhatsApp'), 'icon' => 'fa-brands fa-whatsapp', 'color' => '#18b46b'],
        'instagram' => ['label' => __('Instagram'), 'icon' => 'fa-brands fa-instagram', 'color' => '#ed4b87'],
        'messenger' => ['label' => __('Messenger'), 'icon' => 'fa-brands fa-facebook-messenger', 'color' => '#1696f3'],
        'telegram' => ['label' => __('Telegram'), 'icon' => 'fa-brands fa-telegram', 'color' => '#168acb'],
        'email' => ['label' => __('Email'), 'icon' => 'fa-light fa-envelope', 'color' => '#475569'],
    ];
    $selectedProvider = $providerMeta[$selected['provider'] ?? 'all'] ?? $providerMeta['all'];
@endphp

<div class="inbox-workspace flex min-h-[calc(100dvh-var(--app-shell-header-height,79px))] flex-col overflow-hidden bg-white text-slate-900 dark:bg-slate-950 dark:text-slate-100 lg:flex-row" x-data="{ mobileList: @entangle('showMobileList'), showContext: @entangle('showContext'), openQuickReply: false }">

    <!-- Left Sub-Navigation Sidebar -->
    <aside class="inbox-navigation hidden w-[15.5rem] shrink-0 border-r border-slate-200 bg-slate-50/70 lg:flex lg:flex-col dark:border-slate-800 dark:bg-slate-950">
        <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-5 dark:border-slate-800">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-600/20">
                <i class="fa-solid fa-headset text-sm"></i>
            </span>
            <div>
                <p class="text-sm font-bold tracking-tight text-slate-950 dark:text-white">{{ __('Omnichannel Inbox') }}</p>
                <p class="text-[10px] text-slate-400">{{ __('Abuja HQ Center') }}</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 p-3">
            <a href="{{ route('portal.inbox') }}" wire:navigate class="flex items-center gap-3 rounded-xl bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-600/20">
                <i class="fa-light fa-inbox w-5 text-center text-base"></i>
                {{ __('Active Inbox') }}
                <span class="ml-auto flex h-5 min-w-5 items-center justify-center rounded-full bg-white/20 px-1 text-[10px] font-bold text-white">6</span>
            </a>
            <a href="{{ url('/portal/ascend/crm') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-white">
                <i class="fa-light fa-users-line w-5 text-center text-base"></i>
                {{ __('CRM Leads') }}
            </a>
            <a href="{{ url('/portal/ascend/automation') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-white hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-white">
                <i class="fa-light fa-robot w-5 text-center text-base"></i>
                {{ __('AI Autoresponder') }}
            </a>

            <div class="px-3.5 pb-2 pt-6 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">
                {{ __('Filter Channels') }}
            </div>

            @foreach (['all', 'whatsapp', 'messenger', 'telegram', 'instagram', 'email'] as $key)
                <button type="button" wire:click="$set('channel', '{{ $key }}')" class="flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-left text-sm font-medium transition {{ $channel === $key ? 'bg-white font-bold text-blue-600 shadow-xs dark:bg-slate-900 dark:text-blue-400' : 'text-slate-600 hover:bg-white hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-900' }}">
                    <i class="{{ $providerMeta[$key]['icon'] }} w-5 text-center" style="color: {{ $providerMeta[$key]['color'] }}"></i>
                    {{ $providerMeta[$key]['label'] }}
                    @if ($key !== 'all' && ($channelCounts[$key] ?? 0) > 0)
                        <span class="ml-auto text-[11px] font-semibold text-slate-400">{{ $channelCounts[$key] }}</span>
                    @endif
                </button>
            @endforeach
        </nav>

        <div class="border-t border-slate-200 p-4 text-center dark:border-slate-800">
            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">{{ __('Ascend Systems') }}</p>
            <p class="mt-1 text-[11px] text-slate-500">{{ __('Abuja HQ · Connected Channels') }}</p>
        </div>
    </aside>

    <!-- Conversation List Panel -->
    <section class="inbox-conversations flex w-full shrink-0 flex-col border-r border-slate-200 bg-white lg:w-[21rem] dark:border-slate-800 dark:bg-slate-900" x-show="mobileList || window.innerWidth >= 1024" x-cloak>
        <div class="flex h-16 items-center justify-between border-b border-slate-200 px-4 dark:border-slate-800">
            <h1 class="text-lg font-bold tracking-tight text-slate-950 dark:text-white">{{ __('Conversations') }}</h1>
            <button type="button" wire:click="$refresh" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-slate-800" title="{{ __('Refresh') }}">
                <i class="fa-light fa-arrows-rotate"></i>
            </button>
        </div>

        <div class="space-y-3 border-b border-slate-100 p-3.5 dark:border-slate-800">
            <label class="relative block">
                <span class="sr-only">{{ __('Search conversations') }}</span>
                <i class="fa-light fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                <input wire:model.live.debounce.250ms="search" type="search" placeholder="{{ __('Search messages or contacts...') }}" class="h-9 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
            </label>

            <div class="flex gap-1.5 overflow-x-auto pb-0.5">
                @foreach ($providerMeta as $key => $meta)
                    <button type="button" wire:click="$set('channel', '{{ $key }}')" @class([
                        'shrink-0 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition',
                        'bg-blue-600 text-white shadow-xs' => $channel === $key,
                        'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800' => $channel !== $key
                    ])>
                        {{ $meta['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Conversations List Items -->
        <div class="inbox-scroll flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse ($filteredConversations as $conversation)
                <button type="button" wire:click="selectConversation({{ $conversation['id'] }})" @class([
                    'group flex w-full gap-3 p-4 text-left transition',
                    'bg-blue-50/90 dark:bg-blue-950/30' => $selectedConversationId === $conversation['id'],
                    'hover:bg-slate-50 dark:hover:bg-slate-800/70' => $selectedConversationId !== $conversation['id']
                ])>
                    <span class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white shadow-xs" style="background: {{ $conversation['color'] }}">
                        {{ $conversation['initials'] }}
                        <span class="absolute -bottom-0.5 -right-0.5 flex h-4.5 w-4.5 items-center justify-center rounded-full border-2 border-white bg-white text-[9px] dark:border-slate-900" style="color: {{ $providerMeta[$conversation['provider']]['color'] }}">
                            <i class="{{ $providerMeta[$conversation['provider']]['icon'] }}"></i>
                        </span>
                    </span>

                    <span class="min-w-0 flex-1">
                        <span class="flex items-center justify-between gap-2">
                            <span class="truncate text-sm font-bold text-slate-900 dark:text-white">{{ $conversation['name'] }}</span>
                            <span class="shrink-0 text-[10px] font-semibold text-slate-400">{{ $conversation['time'] }}</span>
                        </span>
                        <span class="mt-1 flex items-center gap-2">
                            <span class="min-w-0 flex-1 truncate text-xs text-slate-500 dark:text-slate-400">{{ $conversation['preview'] }}</span>
                            @if ($conversation['unread'] > 0)
                                <span class="flex h-4.5 min-w-4.5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-extrabold text-white">{{ $conversation['unread'] }}</span>
                            @endif
                        </span>
                        <span class="mt-2 flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">
                            <i class="{{ $providerMeta[$conversation['provider']]['icon'] }}" style="color: {{ $providerMeta[$conversation['provider']]['color'] }}"></i>
                            {{ $conversation['mode'] === 'ai' ? __('AI Autoresponder') : __('Human Mode') }}
                        </span>
                    </span>
                </button>
            @empty
                <div class="p-8 text-center text-sm text-slate-500">{{ __('No conversations match filters.') }}</div>
            @endforelse
        </div>
    </section>

    <!-- Main Message Thread Panel -->
    <main class="inbox-thread flex min-w-0 flex-1 flex-col bg-slate-50/50 dark:bg-slate-950" x-show="!mobileList || window.innerWidth >= 1024" x-cloak>
        @if ($selected)
            <!-- Thread Header -->
            <header class="flex h-16 items-center justify-between gap-4 border-b border-slate-200 bg-white px-4 sm:px-6 dark:border-slate-800 dark:bg-slate-900">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" wire:click="showConversationList" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 lg:hidden">
                        <i class="fa-light fa-arrow-left"></i>
                    </button>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white shadow-xs" style="background: {{ $selected['color'] }}">
                        {{ $selected['initials'] }}
                    </span>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h2 class="truncate text-sm font-bold text-slate-900 dark:text-white">{{ $selected['name'] }}</h2>
                            <span class="rounded-md px-2 py-0.5 text-[10px] font-bold" style="background: color-mix(in srgb, {{ $selectedProvider['color'] }} 12%, white); color: {{ $selectedProvider['color'] }}">
                                <i class="{{ $selectedProvider['icon'] }} mr-1"></i>{{ $selectedProvider['label'] }}
                            </span>
                        </div>
                        <p class="truncate text-xs text-slate-500">{{ $selected['handle'] }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" wire:click="toggleAi" @class([
                        'inline-flex items-center gap-2 rounded-xl border px-3 py-1.5 text-xs font-semibold transition shadow-2xs',
                        'border-purple-200 bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300' => $selected['mode'] === 'ai',
                        'border-slate-200 bg-white text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200' => $selected['mode'] !== 'ai'
                    ])>
                        <i class="fa-light fa-sparkles text-purple-600"></i>
                        <span>{{ $selected['mode'] === 'ai' ? __('AI Active (Switch to Human)') : __('Human Mode (Enable AI)') }}</span>
                    </button>
                    <button type="button" wire:click="toggleContext" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <i class="fa-light fa-sidebar-flip"></i>
                    </button>
                </div>
            </header>

            <!-- Status Flash Banner -->
            @if (session('status'))
                <div class="bg-emerald-500/10 border-b border-emerald-500/20 px-6 py-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                    <i class="fa-light fa-circle-check mr-2"></i> {{ session('status') }}
                </div>
            @endif

            <!-- Chat Messages Bubble List -->
            <div class="inbox-scroll flex-1 overflow-y-auto px-4 py-6 sm:px-8">
                <div class="mx-auto flex max-w-3xl flex-col gap-4">
                    @foreach ($selected['messages'] as $message)
                        <div @class(['flex', 'justify-start' => $message['from'] === 'contact', 'justify-end' => $message['from'] !== 'contact'])>
                            <div @class(['max-w-[88%] sm:max-w-[72%]', 'items-start' => $message['from'] === 'contact', 'items-end' => $message['from'] !== 'contact'])>
                                <div @class([
                                    'rounded-2xl px-4 py-3 text-sm leading-6 shadow-xs',
                                    'rounded-tl-sm border border-slate-200 bg-white text-slate-800 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200' => $message['from'] === 'contact',
                                    'rounded-tr-sm bg-blue-600 text-white' => $message['from'] === 'human',
                                    'rounded-tr-sm border border-purple-200 bg-purple-50 text-purple-950 dark:border-purple-900/60 dark:bg-purple-950/40 dark:text-purple-100' => $message['from'] === 'ai'
                                ])>
                                    {!! nl2br(e($message['body'])) !!}
                                </div>
                                <div class="mt-1 flex items-center gap-2 text-[10px] text-slate-400">
                                    @if ($message['from'] === 'ai')
                                        <span class="font-bold text-purple-600 dark:text-purple-400"><i class="fa-light fa-sparkles mr-1"></i>{{ __('AI Auto-Reply') }}</span>
                                    @elseif ($message['from'] === 'human')
                                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ __('Staff Agent (Abuja HQ)') }}</span>
                                    @endif
                                    <span>{{ $message['time'] }}</span>
                                    @if ($message['from'] !== 'contact')
                                        <i class="fa-light fa-check-double text-blue-400"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Message Input & Quick Replies -->
            <footer class="relative border-t border-slate-200 bg-white p-4 sm:px-8 dark:border-slate-800 dark:bg-slate-900">
                <!-- Quick Reply Selector Popup -->
                <div x-show="openQuickReply" @click.away="openQuickReply = false" class="absolute bottom-full left-8 right-8 z-30 mb-2 max-w-xl rounded-2xl border border-slate-200 bg-white p-3 shadow-xl dark:border-slate-800 dark:bg-slate-900" x-cloak>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">{{ __('Select Quick Reply Template') }}</p>
                    <div class="space-y-1.5">
                        @foreach ([
                            'Thanks for contacting Ascend Systems! Our Abuja HQ team is reviewing your inquiry.',
                            'Your invoice has been generated. You can view or download your PDF receipt anytime.',
                            'A sales representative will call you shortly to discuss your request.'
                        ] as $template)
                            <button type="button" wire:click="applyQuickReply('{{ $template }}')" @click="openQuickReply = false" class="block w-full rounded-xl px-3 py-2 text-left text-xs text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                "{{ $template }}"
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mx-auto max-w-3xl">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                            <span class="flex h-5 w-5 items-center justify-center rounded-md bg-purple-50 text-purple-600 dark:bg-purple-950/40">
                                <i class="fa-light fa-sparkles text-xs"></i>
                            </span>
                            {{ $selected['mode'] === 'ai' ? __('AI Autoresponder active') : __('Human Agent Mode active') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="openQuickReply = !openQuickReply" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                                <i class="fa-light fa-bolt text-amber-500 mr-1"></i>{{ __('Quick Template') }}
                            </button>
                        </div>
                    </div>

                    <form wire:submit="sendMessage" class="flex items-end gap-3">
                        <textarea wire:model="draft" rows="2" placeholder="{{ __('Type a reply to send to client...') }}" class="min-h-12 flex-1 resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:bg-slate-900"></textarea>
                        <button type="submit" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700">
                            <i class="fa-light fa-paper-plane-top"></i>
                        </button>
                    </form>
                </div>
            </footer>
        @else
            <div class="flex flex-1 items-center justify-center p-8 text-center text-slate-500">
                <i class="fa-light fa-inbox mb-3 text-4xl text-slate-300"></i>
                <p>{{ __('Select a conversation to view the thread.') }}</p>
            </div>
        @endif
    </main>

    <!-- Right Side Customer Profile & CRM Context Drawer -->
    @if ($selected)
        <aside class="inbox-context hidden w-[18.5rem] shrink-0 border-l border-slate-200 bg-white p-5 lg:block dark:border-slate-800 dark:bg-slate-900" x-show="showContext" x-cloak>
            <div class="flex items-center justify-between border-b pb-4 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-950 dark:text-white">{{ __('Customer Details') }}</h3>
                <button type="button" wire:click="toggleContext" class="text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <i class="fa-light fa-xmark"></i>
                </button>
            </div>

            <div class="mt-6 text-center">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full text-lg font-bold text-white shadow-md" style="background: {{ $selected['color'] }}">
                    {{ $selected['initials'] }}
                </span>
                <h4 class="mt-3 text-base font-bold text-slate-900 dark:text-white">{{ $selected['name'] }}</h4>
                <p class="mt-1 text-xs text-slate-500">{{ $selected['handle'] }}</p>
            </div>

            <div class="mt-6 space-y-4 text-xs">
                <div>
                    <p class="font-bold uppercase tracking-wider text-slate-400 text-[10px]">{{ __('Assigned Staff') }}</p>
                    <p class="mt-1 font-semibold text-slate-800 dark:text-slate-200">{{ $selected['assigned'] }}</p>
                </div>
                <div>
                    <p class="font-bold uppercase tracking-wider text-slate-400 text-[10px]">{{ __('Branch Location') }}</p>
                    <p class="mt-1 font-semibold text-slate-800 dark:text-slate-200">Abuja HQ, Nigeria</p>
                </div>
                <div>
                    <p class="font-bold uppercase tracking-wider text-slate-400 text-[10px]">{{ __('Channel Provider') }}</p>
                    <p class="mt-1 font-semibold text-slate-800 dark:text-slate-200">{{ $selectedProvider['label'] }}</p>
                </div>

                <div class="pt-4 border-t dark:border-slate-800">
                    <button type="button" wire:click="createCrmDealFromConversation" class="w-full rounded-xl bg-blue-600 py-2.5 text-center font-bold text-white shadow-sm hover:bg-blue-700">
                        <i class="fa-light fa-user-plus mr-1.5"></i>{{ __('Convert to CRM Deal (₦1.5M)') }}
                    </button>
                </div>
            </div>
        </aside>
    @endif
</div>
