<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'triggerEvent' => 'open-command-palette',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'triggerEvent' => 'open-command-palette',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $navCommands = [
        [
            'category' => 'Quick Actions',
            'items' => [
                ['title' => 'Create New Post / Schedule', 'icon' => 'fa-light fa-paper-plane-top', 'url' => route('portal.publishing.calendar'), 'badge' => 'Publishing'],
                ['title' => 'Launch AI Content Studio', 'icon' => 'fa-light fa-sparkles', 'url' => route('portal.ai-studio'), 'badge' => 'AI Tools'],
                ['title' => 'Repurpose Social Content', 'icon' => 'fa-light fa-arrows-repeat', 'url' => route('portal.ai-studio'), 'badge' => 'AI Tools'],
                ['title' => 'Check Unified Omnichannel Inbox', 'icon' => 'fa-light fa-inbox-in', 'url' => route('portal.dashboard'), 'badge' => 'Inbox'],
            ],
        ],
        [
            'category' => 'Publishing & Content',
            'items' => [
                ['title' => 'Publishing Calendar', 'icon' => 'fa-light fa-calendar-lines', 'url' => route('portal.publishing.calendar'), 'badge' => 'Schedule'],
                ['title' => 'Drafts & Saved Ideas', 'icon' => 'fa-light fa-file-pen', 'url' => route('portal.publishing.drafts'), 'badge' => 'Drafts'],
                ['title' => 'Publishing Queue', 'icon' => 'fa-light fa-list-check', 'url' => route('portal.publishing.queue'), 'badge' => 'Queue'],
                ['title' => 'Campaigns & Labels', 'icon' => 'fa-light fa-tags', 'url' => route('portal.publishing.campaigns'), 'badge' => 'Campaigns'],
            ],
        ],
        [
            'category' => 'Settings & Workspace',
            'items' => [
                ['title' => 'Workspace & Team Settings', 'icon' => 'fa-light fa-users-gear', 'url' => route('portal.dashboard'), 'badge' => 'Team'],
                ['title' => 'User & Role Access Management', 'icon' => 'fa-light fa-user-shield', 'url' => route('admin-users.index'), 'badge' => 'Admin'],
                ['title' => 'AI Studio Settings & Keys', 'icon' => 'fa-light fa-sliders', 'url' => route('portal.ai-studio.settings'), 'badge' => 'AI Config'],
            ],
        ],
    ];
?>

<div
    x-data="{
        open: false,
        search: '',
        selectedIndex: 0,
        groups: <?php echo \Illuminate\Support\Js::from($navCommands)->toHtml() ?>,
        get allItems() {
            let items = [];
            const query = this.search.trim().toLowerCase();
            this.groups.forEach(group => {
                group.items.forEach(item => {
                    if (!query || item.title.toLowerCase().includes(query) || item.badge.toLowerCase().includes(query)) {
                        items.push(item);
                    }
                });
            });
            return items;
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.search = '';
                this.selectedIndex = 0;
                $nextTick(() => $refs.searchInput?.focus());
            }
        },
        selectCurrent() {
            const items = this.allItems;
            if (items.length > 0 && items[this.selectedIndex]) {
                window.location.href = items[this.selectedIndex].url;
                this.open = false;
            }
        },
        next() {
            const total = this.allItems.length;
            if (total > 0) {
                this.selectedIndex = (this.selectedIndex + 1) % total;
            }
        },
        prev() {
            const total = this.allItems.length;
            if (total > 0) {
                this.selectedIndex = (this.selectedIndex - 1 + total) % total;
            }
        }
    }"
    x-on:keydown.window.cmd.k.prevent="toggle()"
    x-on:keydown.window.ctrl.k.prevent="toggle()"
    x-on:<?php echo e($triggerEvent); ?>.window="toggle()"
    <?php echo e($attributes); ?>

>
    <template x-teleport="body">
        <div
            x-cloak
            x-show="open"
            class="fixed inset-0 z-[150] flex items-start justify-center pt-16 sm:pt-24 px-4 pb-6"
            x-on:keydown.escape.window="open = false"
        >
            <div
                class="absolute inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity"
                x-show="open"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-on:click="open = false"
            ></div>

            <div
                x-show="open"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-2xl overflow-hidden rounded-2xl border border-slate-200/80 bg-white/95 shadow-2xl backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/95"
            >
                <!-- Search Input Header -->
                <div class="relative flex items-center border-b border-slate-200/80 px-4 dark:border-slate-800">
                    <i class="fa-light fa-magnifying-glass text-lg text-slate-400 dark:text-slate-500"></i>
                    <input
                        x-ref="searchInput"
                        type="text"
                        x-model="search"
                        x-on:keydown.down.prevent="next()"
                        x-on:keydown.up.prevent="prev()"
                        x-on:keydown.enter.prevent="selectCurrent()"
                        placeholder="Type a command or search portal..."
                        class="w-full bg-transparent py-4 pl-3 pr-10 text-base text-slate-900 outline-none placeholder:text-slate-400 dark:text-white dark:placeholder:text-slate-500"
                    />
                    <button
                        type="button"
                        x-on:click="open = false"
                        class="rounded-lg p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                    >
                        <kbd class="hidden sm:inline-block rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500 dark:border-slate-800 dark:bg-slate-800 dark:text-slate-400">ESC</kbd>
                    </button>
                </div>

                <!-- Results List -->
                <div class="max-h-[60vh] overflow-y-auto p-2">
                    <template x-if="allItems.length === 0">
                        <div class="py-12 text-center text-sm text-slate-500 dark:text-slate-400">
                            <i class="fa-light fa-magnifying-glass-minus mb-2 text-2xl"></i>
                            <p>No matching actions or portal routes found for "<span x-text="search"></span>"</p>
                        </div>
                    </template>

                    <template x-for="(group, gIdx) in groups" :key="gIdx">
                        <div x-show="group.items.some(item => !search.trim() || item.title.toLowerCase().includes(search.trim().toLowerCase()) || item.badge.toLowerCase().includes(search.trim().toLowerCase()))">
                            <div class="px-3 py-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500" x-text="group.category"></div>

                            <template x-for="(item, iIdx) in group.items" :key="iIdx">
                                <template x-if="!search.trim() || item.title.toLowerCase().includes(search.trim().toLowerCase()) || item.badge.toLowerCase().includes(search.trim().toLowerCase())">
                                    <?php
                                        // calculate global index
                                    ?>
                                    <a
                                        :href="item.url"
                                        x-on:mouseenter="selectedIndex = allItems.findIndex(i => i.title === item.title)"
                                        :class="{
                                            'bg-indigo-50/80 text-indigo-900 dark:bg-indigo-950/50 dark:text-indigo-200': allItems[selectedIndex]?.title === item.title,
                                            'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50': allItems[selectedIndex]?.title !== item.title
                                        }"
                                        class="group flex items-center justify-between rounded-xl px-3 py-2.5 transition-colors cursor-pointer"
                                    >
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 group-hover:bg-indigo-100 group-hover:text-indigo-600 dark:group-hover:bg-indigo-900 dark:group-hover:text-indigo-300 transition-colors">
                                                <i :class="item.icon"></i>
                                            </div>
                                            <span class="truncate text-sm font-medium" x-text="item.title"></span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="rounded-md border border-slate-200/80 bg-slate-100/70 px-2 py-0.5 text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-800 dark:text-slate-400" x-text="item.badge"></span>
                                            <i class="fa-light fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-opacity text-indigo-500"></i>
                                        </div>
                                    </a>
                                </template>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Footer Hints -->
                <div class="flex items-center justify-between border-t border-slate-200/80 bg-slate-50/60 px-4 py-2.5 text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-400">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1"><kbd class="rounded border bg-white px-1 dark:border-slate-800 dark:bg-slate-800">↑</kbd><kbd class="rounded border bg-white px-1 dark:border-slate-800 dark:bg-slate-800">↓</kbd> Navigate</span>
                        <span class="flex items-center gap-1"><kbd class="rounded border bg-white px-1 dark:border-slate-800 dark:bg-slate-800">↵</kbd> Select</span>
                    </div>
                    <span class="font-medium text-slate-400">Ascend AI Workspace</span>
                </div>
            </div>
        </div>
    </template>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\resources\themes/app/default/resources/views/components/ui/command-palette.blade.php ENDPATH**/ ?>