<?php
    $dayCount = $days->count();
    $composerCanShortenUrls = url_shortening_enabled(auth()->user());
    $providerCardsByKey = collect(channel_provider_cards())->keyBy('key');
    $publishingApprovalTeam = \Modules\AppTeams\Support\TeamWorkspaceAccess::activeTeam(auth()->user());
    $publishingCanApprovePosts = auth()->user()
        && $publishingApprovalTeam
        && \Modules\AppTeams\Support\TeamWorkspaceAccess::hasPermission(auth()->user(), 'post.approve', $publishingApprovalTeam);

    $statusMeta = [
        'pending' => ['label' => __('Pending'), 'surface' => 'rgba(59, 130, 246, 0.12)', 'text' => '#2563eb'],
        'waiting_approve' => ['label' => __('Waiting approve'), 'surface' => 'rgba(245, 158, 11, 0.14)', 'text' => '#d97706'],
        'processing' => ['label' => __('Processing'), 'surface' => 'rgba(99, 102, 241, 0.12)', 'text' => '#4f46e5'],
        'published' => ['label' => __('Published'), 'surface' => 'rgba(16, 185, 129, 0.14)', 'text' => '#059669'],
        'failed' => ['label' => __('Failed'), 'surface' => 'rgba(239, 68, 68, 0.12)', 'text' => '#dc2626'],
        'draft' => ['label' => __('Draft'), 'surface' => 'rgba(99, 102, 241, 0.14)', 'text' => '#4f46e5'],
    ];
?>

<div
    class="flex h-full min-h-full flex-col"
    x-data="{
        mobileFiltersOpen: false,
        trackMinHeight: 264,
        confirmDeleteOpen: false,
        confirmDeleteType: '',
        confirmDeletePostId: '',
        dayPostsModalLoadingDate: '',
        dayPostsModalOpen: false,
        dayPostsModalDateLabel: '',
        dayPostsModalItems: [],
        draggingCalendarPost: null,
        dragTargetDate: '',
        dragTargetDateLabel: '',
        dragMoveModalOpen: false,
        dragMoveMode: 'keep',
        dragMoveTime: '',
        dragMoveSubmitting: false,
        recentDropDate: '',
        recentDropTimer: null,
        captionPickerOpen: false,
        saveCaptionOpen: false,
        captionPickerSearch: '',
        captionLibrary: <?php echo \Illuminate\Support\Js::from($composerCaptionLibrary)->toHtml() ?>,
        localPreviewCaption: <?php echo \Illuminate\Support\Js::from((string) ($composer['caption'] ?? ''))->toHtml() ?>,
        captionTypingTimer: null,
        captionTypingToken: 0,
        composerClosing: false,
        composerSavingAction: '',
        composerPublishBlocked: false,
        composerPublishBlockedMessage: '',
        localPreviewMediaItems: <?php echo \Illuminate\Support\Js::from(collect((array) ($composer['media_items'] ?? []))->values()->all())->toHtml() ?>,
        localScheduleSlots: <?php echo \Illuminate\Support\Js::from(collect((array) ($composer['schedule_slots'] ?? []))->values()->all())->toHtml() ?>,
        activePreviewAccountId: <?php echo \Illuminate\Support\Js::from((string) (($composer['preview_account_id'] ?? '') ?: (($composer['account_ids'][0] ?? '') ?: '')))->toHtml() ?>,
        localSelectedPreviewAccountIds: <?php echo \Illuminate\Support\Js::from(collect($composer['account_ids'] ?? [])->map(fn ($id) => (string) $id)->values()->all())->toHtml() ?>,
        previewOptionLimit: 8,
        previewOptionsExpanded: false,
        localSelectedPreviewOptions: <?php echo \Illuminate\Support\Js::from($selectedComposerAccounts->map(fn ($account) => [
            'id' => (string) $account->id,
            'label' => (string) $account->display_name,
            'avatarUrl' => (string) ($account->avatar_url ?? ''),
            'initials' => (string) str($account->display_name)->substr(0, 2)->upper(),
            'providerKey' => (string) $account->provider_key,
            'providerIcon' => (string) data_get($providerCardsByKey->get((string) $account->provider_key, []), 'icon', ''),
            'providerColor' => (string) data_get($providerCardsByKey->get((string) $account->provider_key, []), 'color', ''),
            'providerToneSurface' => (string) publishing_provider_tone((string) $account->provider_key)['surface'],
            'providerToneText' => (string) publishing_provider_tone((string) $account->provider_key)['text'],
        ])->values()->all())->toHtml() ?>,
        filteredCaptionLibrary() {
            const needle = String(this.captionPickerSearch || '').trim().toLowerCase();

            return (Array.isArray(this.captionLibrary) ? this.captionLibrary : []).filter((caption) => {
                const searchable = [
                    String(caption?.name || ''),
                    String(caption?.content || ''),
                    String(caption?.notes || ''),
                    ...(Array.isArray(caption?.tags) ? caption.tags : []),
                ].join(' ').toLowerCase();

                if (needle !== '' && !searchable.includes(needle)) {
                    return false;
                }

                return true;
            });
        },
        composerCaptionTextarea() {
            const textarea = document.getElementById('composer-caption-textarea');

            return textarea instanceof HTMLTextAreaElement ? textarea : null;
        },
        syncComposerCaptionTextarea(value) {
            const textarea = this.composerCaptionTextarea();

            if (!textarea) {
                return;
            }

            if (textarea.value !== value) {
                textarea.value = value;
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
            }

            const caretPosition = textarea.value.length;

            if (typeof textarea.setSelectionRange === 'function') {
                textarea.setSelectionRange(caretPosition, caretPosition);
            }

            textarea.scrollTop = textarea.scrollHeight;
        },
        stopComposerCaptionTyping() {
            this.captionTypingToken += 1;

            if (this.captionTypingTimer) {
                clearTimeout(this.captionTypingTimer);
                this.captionTypingTimer = null;
            }
        },
        applyComposerCaption(value) {
            const nextCaption = String(value || '');

            this.stopComposerCaptionTyping();
            this.localPreviewCaption = nextCaption;
            this.syncComposerCaptionTextarea(nextCaption);
        },
        animateComposerCaption(value) {
            const nextCaption = String(value || '');
            const characters = Array.from(nextCaption);
            const textarea = this.composerCaptionTextarea();

            if (!textarea || characters.length === 0) {
                this.applyComposerCaption(nextCaption);
                return;
            }

            this.stopComposerCaptionTyping();
            this.localPreviewCaption = '';
            this.syncComposerCaptionTextarea('');

            const token = this.captionTypingToken + 1;
            let cursor = 0;
            const chunkSize = characters.length > 420 ? 4 : (characters.length > 180 ? 2 : 1);
            const delay = characters.length > 420 ? 18 : 26;

            textarea.focus();

            const step = () => {
                if (token !== this.captionTypingToken) {
                    return;
                }

                cursor = Math.min(characters.length, cursor + chunkSize);
                const partial = characters.slice(0, cursor).join('');

                this.localPreviewCaption = partial;
                this.syncComposerCaptionTextarea(partial);

                if (cursor < characters.length) {
                    this.captionTypingTimer = setTimeout(step, delay);
                    return;
                }

                this.captionTypingTimer = null;
            };

            this.captionTypingToken = token;
            this.captionTypingTimer = setTimeout(() => {
                if (token !== this.captionTypingToken) {
                    return;
                }

                step();
            }, 80);
        },
        selectComposerLibraryCaption(caption) {
            const nextCaption = String(caption?.content || '');

            this.animateComposerCaption(nextCaption);
            this.captionPickerOpen = false;
        },
        previewPrimaryMedia() {
            return Array.isArray(this.localPreviewMediaItems) && this.localPreviewMediaItems.length > 0
                ? (this.localPreviewMediaItems[0] || null)
                : null;
        },
        previewMediaUrl() {
            const media = this.previewPrimaryMedia();

            if (!media || typeof media !== 'object') {
                return '';
            }

            return String(media.previewUrl || media.url || '');
        },
        previewMediaIsVideo() {
            const media = this.previewPrimaryMedia();

            if (!media || typeof media !== 'object') {
                return false;
            }

            const mime = String(media.mimeType || '').toLowerCase();
            const category = String(media.category || '').toLowerCase();
            const extension = String(media.extension || '').toLowerCase();
            const preview = String(media.previewUrl || media.url || '').toLowerCase();

            return mime.startsWith('video/')
                || category === 'video'
                || ['mp4', 'mov', 'webm', 'm4v', 'avi', 'mkv'].includes(extension)
                || /\.(mp4|mov|webm|m4v|avi|mkv)(\?.*)?$/.test(preview);
        },
        defaultScheduleSlot(baseValue = null) {
            let date = null;

            if (baseValue) {
                const parsed = new Date(baseValue);
                if (!Number.isNaN(parsed.getTime())) {
                    date = parsed;
                    date.setDate(date.getDate() + 1);
                }
            }

            if (!date) {
                date = new Date();
                date.setMinutes(0, 0, 0);
                date.setHours(date.getHours() + 1);
            }

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hour = String(date.getHours()).padStart(2, '0');
            const minute = String(date.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hour}:${minute}`;
        },
        addLocalScheduleSlot() {
            const lastSlot = this.localScheduleSlots.length > 0 ? this.localScheduleSlots[this.localScheduleSlots.length - 1] : null;
            this.localScheduleSlots = [...this.localScheduleSlots, this.defaultScheduleSlot(lastSlot)];
        },
        visiblePreviewOptions() {
            if (this.previewOptionsExpanded) {
                return this.localSelectedPreviewOptions;
            }

            return this.localSelectedPreviewOptions.slice(0, this.previewOptionLimit);
        },
        hiddenPreviewOptionsCount() {
            return Math.max(0, this.localSelectedPreviewOptions.length - this.previewOptionLimit);
        },
        removeLocalScheduleSlot(index) {
            const nextSlots = this.localScheduleSlots.filter((_, slotIndex) => slotIndex !== index);
            this.localScheduleSlots = nextSlots.length > 0 ? nextSlots : [this.defaultScheduleSlot()];
        },
        repeatDayMap() {
            return {
                mon: 1,
                tue: 2,
                wed: 3,
                thu: 4,
                fri: 5,
                sat: 6,
                sun: 7,
            };
        },
        repeatCountPreview(repeatRule, repeatUntil, repeatDays = []) {
            const untilValue = String(repeatUntil || '').trim();

            if (!untilValue || repeatRule === 'none') {
                return this.localScheduleSlots.length;
            }

            const until = new Date(`${untilValue}T23:59:59`);
            if (Number.isNaN(until.getTime())) {
                return this.localScheduleSlots.length;
            }

            const selectedDays = (Array.isArray(repeatDays) ? repeatDays : [])
                .map((day) => this.repeatDayMap()[String(day || '').toLowerCase()] || null)
                .filter((day) => day !== null);

            let total = 0;

            for (const slotValue of this.localScheduleSlots) {
                const base = new Date(slotValue);

                if (Number.isNaN(base.getTime())) {
                    continue;
                }

                let cursor = new Date(base);
                let safety = 0;

                while (cursor <= until && safety < 366) {
                    const dayOfWeekIso = ((cursor.getDay() + 6) % 7) + 1;
                    const include = repeatRule === 'weekday'
                        ? dayOfWeekIso >= 1 && dayOfWeekIso <= 5
                        : selectedDays.includes(dayOfWeekIso);

                    if (include && cursor >= base) {
                        total += 1;
                    }

                    cursor.setDate(cursor.getDate() + 1);
                    safety += 1;
                }
            }

            return total || this.localScheduleSlots.length;
        },
        recurringPreviewText(repeatRule, repeatUntil, repeatDays = []) {
            if (repeatRule === 'none') {
                return '';
            }

            const untilValue = String(repeatUntil || '').trim();
            if (!untilValue) {
                return '';
            }

            const until = new Date(`${untilValue}T12:00:00`);
            if (Number.isNaN(until.getTime())) {
                return '';
            }

            const dayLabels = {
                mon: 'Mon',
                tue: 'Tue',
                wed: 'Wed',
                thu: 'Thu',
                fri: 'Fri',
                sat: 'Sat',
                sun: 'Sun',
            };

            const repeatLabel = repeatRule === 'weekday'
                ? 'Repeats on weekdays'
                : `Repeats on ${(Array.isArray(repeatDays) ? repeatDays : []).map((day) => dayLabels[String(day || '').toLowerCase()] || '').filter(Boolean).join('/')}`;

            if (repeatLabel.trim() === 'Repeats on') {
                return '';
            }

            const formattedDate = until.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
            });

            const generatedCount = this.repeatCountPreview(repeatRule, repeatUntil, repeatDays);
            const itemLabel = generatedCount === 1 ? 'post' : 'posts';

            return `${repeatLabel} until ${formattedDate} • ${generatedCount} ${itemLabel} generated`;
        },
        syncComposerScheduleSlots() {
            if (this.$wire) {
                this.$wire.set('composer.schedule_slots', this.localScheduleSlots, false);
            }
        },
        async closeComposerLocal() {
            if (this.composerClosing || this.composerSavingAction !== '' || !this.$wire) {
                return;
            }

            this.composerClosing = true;

            try {
                await this.$wire.closeComposer();
            } finally {
                this.composerClosing = false;
            }
        },
        async saveComposerLocal(mode) {
            if (this.composerClosing || this.composerSavingAction !== '' || !this.$wire || (mode === 'scheduled' && this.composerPublishBlocked)) {
                if (mode === 'scheduled' && this.composerPublishBlocked && this.composerPublishBlockedMessage) {
                    window.dispatchEvent(new CustomEvent('app-toast', {
                        detail: {
                            type: 'error',
                            title: 'Publishing failed',
                            message: this.composerPublishBlockedMessage,
                        },
                    }));
                }

                return;
            }

            this.composerSavingAction = String(mode || 'scheduled');
            this.syncComposerScheduleSlots();

            try {
                await this.$wire.saveComposer(mode);
            } catch (error) {
                console.error('Publishing composer save failed', error);

                const message = String(
                    error?.message ||
                    error?.detail?.message ||
                    'The publishing request could not be completed.'
                ).trim();

                window.dispatchEvent(new CustomEvent('app-toast', {
                    detail: {
                        type: 'error',
                        title: 'Publishing failed',
                        message,
                    },
                }));
            } finally {
                this.composerSavingAction = '';
            }
        },
        openDeleteDialog(type, postId) {
            this.confirmDeleteType = type;
            this.confirmDeletePostId = postId;
            this.confirmDeleteOpen = true;
        },
        closeDeleteDialog() {
            this.confirmDeleteOpen = false;
            this.confirmDeleteType = '';
            this.confirmDeletePostId = '';
        },
        openDayPostsModal(dateLabel, items) {
            this.dayPostsModalLoadingDate = '';
            this.dayPostsModalDateLabel = String(dateLabel || '');
            this.dayPostsModalItems = Array.isArray(items) ? items : [];
            this.dayPostsModalOpen = true;
        },
        closeDayPostsModal() {
            this.dayPostsModalLoadingDate = '';
            this.dayPostsModalOpen = false;
            this.dayPostsModalDateLabel = '';
            this.dayPostsModalItems = [];
        },
        startCalendarPostDrag(item) {
            if (!item || !item.can_edit) {
                this.draggingCalendarPost = null;
                return;
            }

            this.draggingCalendarPost = {
                id: String(item.post_id || ''),
                title: String(item.title || ''),
                time: String(item.time || ''),
                date: String(item.date || ''),
            };
        },
        clearCalendarPostDrag() {
            this.draggingCalendarPost = null;
            this.dragTargetDate = '';
            this.dragTargetDateLabel = '';
        },
        canDropCalendarPost(dayDate, isMoveTarget) {
            if (!isMoveTarget || !this.draggingCalendarPost) {
                return false;
            }

            return String(this.draggingCalendarPost.date || '') !== String(dayDate || '');
        },
        handleCalendarDayDragOver(event, dayDate, dayLabel, isMoveTarget) {
            if (!this.canDropCalendarPost(dayDate, isMoveTarget)) {
                return;
            }

            event.preventDefault();
            this.dragTargetDate = String(dayDate || '');
            this.dragTargetDateLabel = String(dayLabel || '');
        },
        handleCalendarDayDragLeave(dayDate) {
            if (String(this.dragTargetDate || '') === String(dayDate || '')) {
                this.dragTargetDate = '';
                this.dragTargetDateLabel = '';
            }
        },
        openMovePostDialog(dayDate, dayLabel, isMoveTarget) {
            if (!this.draggingCalendarPost || !this.canDropCalendarPost(dayDate, isMoveTarget)) {
                return;
            }

            this.dragTargetDate = String(dayDate || '');
            this.dragTargetDateLabel = String(dayLabel || '');
            this.dragMoveMode = 'keep';
            this.dragMoveTime = String(this.draggingCalendarPost.time || '').trim();
            this.dragMoveModalOpen = true;
        },
        pulseDroppedDay(dayDate) {
            this.recentDropDate = String(dayDate || '');

            if (this.recentDropTimer) {
                clearTimeout(this.recentDropTimer);
            }

            this.recentDropTimer = setTimeout(() => {
                this.recentDropDate = '';
                this.recentDropTimer = null;
            }, 850);
        },
        closeMovePostDialog(resetDrag = true) {
            this.dragMoveModalOpen = false;
            this.dragMoveMode = 'keep';
            this.dragMoveTime = '';
            this.dragMoveSubmitting = false;

            if (resetDrag) {
                this.clearCalendarPostDrag();
            }
        },
        async confirmMovePost() {
            if (this.dragMoveSubmitting || !this.$wire || !this.draggingCalendarPost || !this.dragTargetDate) {
                return;
            }

            this.dragMoveSubmitting = true;

            try {
                const completedDropDate = String(this.dragTargetDate || '');
                await this.$wire.movePostToDate(
                    this.draggingCalendarPost.id,
                    this.dragTargetDate,
                    this.dragMoveMode === 'change',
                    this.dragMoveMode === 'change' ? String(this.dragMoveTime || '').trim() : ''
                );
                this.closeMovePostDialog(true);
                this.pulseDroppedDay(completedDropDate);
            } finally {
                this.dragMoveSubmitting = false;
            }
        },
        setActivePreviewAccount(accountId, sync = true) {
            const nextId = String(accountId || '');

            this.activePreviewAccountId = nextId;

            if (sync && this.$wire) {
                this.$wire.set('composer.preview_account_id', nextId, false);
            }
        },
    }"
    x-on:publishing-composer-scroll-lock.window="document.documentElement.style.overflow = 'hidden'; document.body.style.overflow = 'hidden';"
    x-on:publishing-composer-scroll-unlock.window="document.documentElement.style.overflow = ''; document.body.style.overflow = '';"
    x-on:publishing-post-preview-scroll-lock.window="document.documentElement.style.overflow = 'hidden'; document.body.style.overflow = 'hidden';"
    x-on:publishing-post-preview-scroll-unlock.window="document.documentElement.style.overflow = ''; document.body.style.overflow = '';"
    x-on:publishing-caption-save-finished.window="saveCaptionOpen = false"
    x-on:tiktok-composer-policy.window="
        composerPublishBlocked = Boolean($event.detail?.blocked);
        composerPublishBlockedMessage = String($event.detail?.message || '');
    "
    x-on:media-browser:change.window="
        if ($event.detail?.model === 'composer.media_items') {
            localPreviewMediaItems = Array.isArray($event.detail.items) ? $event.detail.items : [];
        }
    "
    x-on:publishing-media-updated.window="localPreviewMediaItems = Array.isArray($event.detail?.items) ? $event.detail.items : []"
    x-on:channel-selector:change.window="
        if ($event.detail?.model === 'composer.account_ids') {
            localSelectedPreviewAccountIds = ($event.detail.selectedKeys || []).map(String);
            localSelectedPreviewOptions = ($event.detail.selectedOptions || []).map((option) => ({
                id: String(option.key || ''),
                label: String(option.label || ''),
                avatarUrl: String(option.avatarUrl || ''),
                initials: String((option.label || '').split(/\s+/).filter(Boolean).slice(0, 2).map((part) => part.charAt(0)).join('').toUpperCase()),
                providerKey: String(option.providerKey || ''),
                providerIcon: String(option.providerIcon || ''),
                providerColor: String(option.providerColor || ''),
                providerToneSurface: String(option.providerToneSurface || ''),
                providerToneText: String(option.providerToneText || ''),
            })).filter((option) => option.id !== '');
            previewOptionsExpanded = false;
            const changedKey = String($event.detail.changedKey || '');

            if (changedKey && localSelectedPreviewAccountIds.includes(changedKey)) {
                setActivePreviewAccount(changedKey);
            } else if (!localSelectedPreviewAccountIds.includes(String(activePreviewAccountId || ''))) {
                setActivePreviewAccount(localSelectedPreviewAccountIds[0] || '');
            }
        }
    "
    x-on:publishing-ai-caption-updated.window="
        const nextCaption = String($event.detail?.caption || '');
        if (!composerCaptionTextarea()) {
            localPreviewCaption = nextCaption;
        }
    "
    x-on:publishing-day-posts-modal-open.window="openDayPostsModal($event.detail?.dateLabel || '', $event.detail?.items || [])"
    x-on:publishing-ai-schedule-updated.window="localScheduleSlots = Array.isArray($event.detail?.slots) && $event.detail.slots.length ? $event.detail.slots : localScheduleSlots"
>
    <div class="flex h-full min-h-full flex-1 flex-col overflow-hidden border-b xl:min-h-[calc(100dvh-5.5rem)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background: linear-gradient(180deg, var(--theme-surface-soft) 0%, var(--theme-surface-base) 18%, var(--theme-surface-subtle) 100%);">
        <section class="border-b px-4 py-3 sm:px-5 xl:px-6" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 86%, transparent);">
            <div class="mb-3 flex items-center justify-between gap-3 md:hidden">
                <div class="flex min-w-0 items-center gap-2.5">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-[0.95rem] shadow-[0_18px_45px_-28px_rgba(15,23,42,0.45)]" style="background: linear-gradient(135deg, rgba(var(--theme-accent-rgb), 0.18), rgba(var(--theme-accent-rgb), 0.08)); color: var(--theme-accent);">
                        <i class="fa-light fa-calendar-lines-pen text-base"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Publishing')); ?></p>
                        <h1 class="truncate text-[1.1rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e($calendarTitle); ?></h1>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'primary','size' => 'md','class' => 'shadow-[0_18px_40px_-30px_rgba(var(--theme-accent-rgb),0.35)]','wire:click' => 'openComposer','wire:loading.attr' => 'disabled','wire:target' => 'openComposer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','size' => 'md','class' => 'shadow-[0_18px_40px_-30px_rgba(var(--theme-accent-rgb),0.35)]','wire:click' => 'openComposer','wire:loading.attr' => 'disabled','wire:target' => 'openComposer']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <span class="inline-flex items-center gap-2" wire:loading.remove wire:target="openComposer">
                            <i class="fa-light fa-square-plus"></i>
                            <?php echo e(__('New')); ?>

                        </span>
                        <span class="inline-flex items-center gap-2" wire:loading wire:target="openComposer">
                            <i class="fa-light fa-loader animate-spin"></i>
                            <?php echo e(__('Loading...')); ?>

                        </span>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

                    <div class="relative" x-cloak>
                        <button
                            type="button"
                            x-on:click="mobileFiltersOpen = !mobileFiltersOpen"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-[0.95rem] border shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]"
                            style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 92%, transparent); color: var(--theme-header-text-color);"
                        >
                            <i class="fa-light fa-filters"></i>
                        </button>

                        <div
                            x-show="mobileFiltersOpen"
                            x-transition.opacity.duration.150ms
                            x-on:click.outside="mobileFiltersOpen = false"
                            class="absolute right-0 top-full z-40 mt-2 w-[min(18rem,calc(100vw-1rem))] max-h-[76vh] overflow-y-auto rounded-[1rem] border p-3.5 shadow-[0_24px_60px_-34px_rgba(15,23,42,0.32)]"
                            style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent);"
                        >
                            <div class="mb-2.5 flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Filters')); ?></p>
                                    <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Search and narrow the publishing queue.')); ?></p>
                                </div>
                                <button
                                    type="button"
                                    x-on:click="mobileFiltersOpen = false"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-[0.9rem] border transition hover:bg-slate-900/5"
                                    style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                >
                                    <i class="fa-light fa-xmark"></i>
                                </button>
                            </div>

                            <div class="space-y-2.5">
                                <div class="flex h-10 items-center gap-2.5 rounded-[0.85rem] border px-3 shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 92%, transparent);">
                                    <i class="fa-light fa-magnifying-glass text-sm" style="color: var(--theme-muted-text-color);"></i>
                                    <input
                                        wire:model.live.debounce.300ms="search"
                                        type="text"
                                        class="w-full border-0 bg-transparent p-0 text-sm focus:outline-none focus:ring-0"
                                        style="color: var(--theme-header-text-color);"
                                        placeholder="<?php echo e(__('Campaign, channel, network...')); ?>"
                                    >
                                </div>

                                <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'providerFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'providerFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <option value="all"><?php echo e(__('All networks')); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $providerFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($provider['key']); ?>"><?php echo e($provider['label']); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'statusFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'statusFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <option value="all"><?php echo e(__('All states')); ?></option>
                                    <option value="pending"><?php echo e(__('Pending')); ?></option>
                                    <option value="waiting_approve"><?php echo e(__('Waiting approve')); ?></option>
                                    <option value="processing"><?php echo e(__('Processing')); ?></option>
                                    <option value="published"><?php echo e(__('Published')); ?></option>
                                    <option value="failed"><?php echo e(__('Failed')); ?></option>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'campaignFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'campaignFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <option value="all"><?php echo e(__('All campaigns')); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $campaignFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaignFilterOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($campaignFilterOption->id); ?>"><?php echo e($campaignFilterOption->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'labelFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'labelFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <option value="all"><?php echo e(__('All labels')); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $labelFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $labelFilterOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($labelFilterOption->id); ?>"><?php echo e($labelFilterOption->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

                                <button type="button" wire:click="clearFilters" x-on:click="mobileFiltersOpen = false" class="h-10 w-full rounded-[0.85rem] border px-3 text-sm font-semibold shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)] transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color); background-color: color-mix(in srgb, var(--theme-surface-base) 92%, transparent);">
                                    <?php echo e(__('Reset')); ?>

                                </button>
                                <button
                                    type="button"
                                    x-on:click="mobileFiltersOpen = false; openDeleteDialog('filtered', '')"
                                    class="h-10 w-full rounded-[0.85rem] border px-3 text-sm font-semibold shadow-[0_18px_40px_-30px_rgba(239,68,68,0.24)] transition hover:bg-red-50/50 disabled:cursor-not-allowed disabled:opacity-60"
                                    style="border-color: rgba(239,68,68,0.45); color: #dc2626; background-color: rgba(254,242,242,0.72);"
                                    <?php if(($filteredVisibleCount ?? 0) < 1): echo 'disabled'; endif; ?>
                                >
                                    <?php echo e(__('Delete (:count)', ['count' => (int) ($filteredVisibleCount ?? 0)])); ?>

                                </button>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($component)) { $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu','data' => ['align' => 'right','width' => 'auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => 'auto']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                         <?php $__env->slot('trigger', null, []); ?> 
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-[0.95rem] border shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]"
                                style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 92%, transparent); color: var(--theme-header-text-color);"
                            >
                                <i class="fa-light fa-sliders"></i>
                            </button>
                         <?php $__env->endSlot(); ?>

                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-grid-2','wire:click' => 'setView(\'month\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-grid-2','wire:click' => 'setView(\'month\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Month view')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-table-cells','wire:click' => 'setView(\'week\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-table-cells','wire:click' => 'setView(\'week\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Week view')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-chevron-left','wire:click' => 'goPrevious']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-chevron-left','wire:click' => 'goPrevious']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Previous')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-chevron-right','wire:click' => 'goNext']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-chevron-right','wire:click' => 'goNext']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Next')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-calendar-day','wire:click' => 'goToday']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-calendar-day','wire:click' => 'goToday']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Today')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-tags','href' => ''.e(route('portal.publishing.labels')).'','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-tags','href' => ''.e(route('portal.publishing.labels')).'','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Labels')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-file-pen','href' => ''.e(route('portal.publishing.drafts')).'','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-file-pen','href' => ''.e(route('portal.publishing.drafts')).'','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Drafts')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-list-check','href' => ''.e(route('portal.publishing.queue')).'','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-list-check','href' => ''.e(route('portal.publishing.queue')).'','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Queue')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($publishingCanApprovePosts): ?>
                            <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-badge-check','href' => ''.e(route('portal.publishing.approvals')).'','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-badge-check','href' => ''.e(route('portal.publishing.approvals')).'','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                <?php echo e(__('Approvals')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-bullhorn','href' => ''.e(route('portal.publishing.campaigns')).'','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-bullhorn','href' => ''.e(route('portal.publishing.campaigns')).'','wire:navigate' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Campaigns')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-rotate-left','wire:click' => 'clearFilters']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-rotate-left','wire:click' => 'clearFilters']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Reset filters')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-trash-can-list','xOn:click' => 'openDeleteDialog(\'filtered\', \'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-trash-can-list','x-on:click' => 'openDeleteDialog(\'filtered\', \'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Delete filtered (:count)', ['count' => (int) ($filteredVisibleCount ?? 0)])); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $attributes = $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $component = $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
                </div>
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:flex-1">
                    <div class="hidden flex-wrap items-center gap-3 md:flex">
                        <div class="flex items-center gap-3 pr-1">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-[0.95rem] shadow-[0_18px_45px_-28px_rgba(15,23,42,0.45)]" style="background: linear-gradient(135deg, rgba(var(--theme-accent-rgb), 0.18), rgba(var(--theme-accent-rgb), 0.08)); color: var(--theme-accent);">
                                <i class="fa-light fa-calendar-lines-pen text-base"></i>
                            </span>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.24em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Publishing')); ?></p>
                                <h1 class="mt-0.5 text-[1.1rem] font-semibold tracking-[-0.05em]" style="color: var(--theme-header-text-color);"><?php echo e($calendarTitle); ?></h1>
                            </div>
                        </div>

                        <div class="inline-flex h-11 rounded-[1rem] border p-1 shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.04);">
                        <button type="button" wire:click="setView('month')" class="rounded-[0.8rem] px-4 text-sm font-semibold transition" style="<?php echo e($calendarView === 'month' ? 'background-color: var(--theme-surface-base); color: var(--theme-header-text-color); box-shadow: 0 12px 24px -18px rgba(15,23,42,0.3);' : 'color: var(--theme-muted-text-color);'); ?>"><?php echo e(__('Month')); ?></button>
                        <button type="button" wire:click="setView('week')" class="rounded-[0.8rem] px-4 text-sm font-semibold transition" style="<?php echo e($calendarView === 'week' ? 'background-color: var(--theme-surface-base); color: var(--theme-header-text-color); box-shadow: 0 12px 24px -18px rgba(15,23,42,0.3);' : 'color: var(--theme-muted-text-color);'); ?>"><?php echo e(__('Week')); ?></button>
                        </div>

                        <div class="inline-flex h-11 items-center gap-1 rounded-[1rem] border px-1 py-1 shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 92%, transparent);">
                            <button type="button" wire:click="goPrevious" class="inline-flex h-9 w-9 items-center justify-center rounded-[0.8rem] transition hover:bg-slate-900/5" style="color: var(--theme-header-text-color);">
                                <i class="fa-light fa-chevron-left text-xs"></i>
                            </button>
                            <button type="button" wire:click="goNext" class="inline-flex h-9 w-9 items-center justify-center rounded-[0.8rem] transition hover:bg-slate-900/5" style="color: var(--theme-header-text-color);">
                                <i class="fa-light fa-chevron-right text-xs"></i>
                            </button>
                        </div>

                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','size' => 'lg','wire:click' => 'goToday','class' => 'shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','size' => 'lg','wire:click' => 'goToday','class' => 'shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Today')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                    </div>

                    <div class="hidden items-center gap-2 md:ml-auto md:flex xl:justify-end">
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'primary','size' => 'md','class' => 'h-10 rounded-[0.95rem] px-4 shadow-[0_18px_40px_-30px_rgba(var(--theme-accent-rgb),0.35)]','wire:click' => 'openComposer','wire:loading.attr' => 'disabled','wire:target' => 'openComposer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','size' => 'md','class' => 'h-10 rounded-[0.95rem] px-4 shadow-[0_18px_40px_-30px_rgba(var(--theme-accent-rgb),0.35)]','wire:click' => 'openComposer','wire:loading.attr' => 'disabled','wire:target' => 'openComposer']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <span class="inline-flex items-center gap-2" wire:loading.remove wire:target="openComposer">
                                <i class="fa-light fa-square-plus"></i>
                                <?php echo e(__('New Post')); ?>

                            </span>
                            <span class="inline-flex items-center gap-2" wire:loading wire:target="openComposer">
                                <i class="fa-light fa-loader animate-spin"></i>
                                <?php echo e(__('Loading...')); ?>

                            </span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                        <div class="relative" x-cloak>
                            <button
                                type="button"
                                x-on:click="mobileFiltersOpen = !mobileFiltersOpen"
                                class="inline-flex h-10 items-center gap-2 rounded-[0.95rem] border px-4 text-sm font-semibold shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]"
                                style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 92%, transparent); color: var(--theme-header-text-color);"
                            >
                                <i class="fa-light fa-filters"></i>
                                <?php echo e(__('Filters')); ?>

                            </button>

                        <div
                            x-show="mobileFiltersOpen"
                            x-transition.opacity.duration.150ms
                            x-on:click.outside="mobileFiltersOpen = false"
                            class="absolute right-0 top-full z-40 mt-2 w-[min(18rem,calc(100vw-1rem))] max-h-[76vh] overflow-y-auto rounded-[1rem] border p-3.5 shadow-[0_24px_60px_-34px_rgba(15,23,42,0.32)]"
                            style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent);"
                        >
                            <div class="mb-2.5 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Filters')); ?></p>
                                        <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Search and narrow the publishing queue.')); ?></p>
                                    </div>
                                    <button
                                        type="button"
                                        x-on:click="mobileFiltersOpen = false"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-[0.9rem] border transition hover:bg-slate-900/5"
                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                    >
                                        <i class="fa-light fa-xmark"></i>
                                    </button>
                                </div>

                                <div class="space-y-2.5">
                                    <div class="flex h-10 items-center gap-2.5 rounded-[0.85rem] border px-3 shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 92%, transparent);">
                                        <i class="fa-light fa-magnifying-glass text-sm" style="color: var(--theme-muted-text-color);"></i>
                                        <input
                                            wire:model.live.debounce.300ms="search"
                                            type="text"
                                            class="w-full border-0 bg-transparent p-0 text-sm focus:outline-none focus:ring-0"
                                            style="color: var(--theme-header-text-color);"
                                            placeholder="<?php echo e(__('Campaign, channel, network...')); ?>"
                                        >
                                    </div>

                                    <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'providerFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'providerFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                        <option value="all"><?php echo e(__('All networks')); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $providerFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <option value="<?php echo e($provider['key']); ?>"><?php echo e($provider['label']); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

                                    <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'statusFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'statusFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                        <option value="all"><?php echo e(__('All states')); ?></option>
                                        <option value="pending"><?php echo e(__('Pending')); ?></option>
                                        <option value="waiting_approve"><?php echo e(__('Waiting approve')); ?></option>
                                        <option value="processing"><?php echo e(__('Processing')); ?></option>
                                        <option value="published"><?php echo e(__('Published')); ?></option>
                                        <option value="failed"><?php echo e(__('Failed')); ?></option>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

                                    <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'campaignFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'campaignFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                        <option value="all"><?php echo e(__('All campaigns')); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $campaignFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaignFilterOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <option value="<?php echo e($campaignFilterOption->id); ?>"><?php echo e($campaignFilterOption->name); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

                                    <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.live' => 'labelFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live' => 'labelFilter','class' => '[&>div>select]:h-10 [&>div>select]:rounded-[0.85rem] [&>div>select]:shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                        <option value="all"><?php echo e(__('All labels')); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $labelFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $labelFilterOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <option value="<?php echo e($labelFilterOption->id); ?>"><?php echo e($labelFilterOption->name); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>

                                    <button type="button" wire:click="clearFilters" x-on:click="mobileFiltersOpen = false" class="h-10 w-full rounded-[0.85rem] border px-3 text-sm font-semibold shadow-[0_18px_40px_-30px_rgba(15,23,42,0.22)] transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color); background-color: color-mix(in srgb, var(--theme-surface-base) 92%, transparent);">
                                        <?php echo e(__('Reset')); ?>

                                    </button>
                                    <button
                                        type="button"
                                        x-on:click="mobileFiltersOpen = false; openDeleteDialog('filtered', '')"
                                        class="h-10 w-full rounded-[0.85rem] border px-3 text-sm font-semibold shadow-[0_18px_40px_-30px_rgba(239,68,68,0.24)] transition hover:bg-red-50/50 disabled:cursor-not-allowed disabled:opacity-60"
                                        style="border-color: rgba(239,68,68,0.45); color: #dc2626; background-color: rgba(254,242,242,0.72);"
                                        <?php if(($filteredVisibleCount ?? 0) < 1): echo 'disabled'; endif; ?>
                                    >
                                        <?php echo e(__('Delete (:count)', ['count' => (int) ($filteredVisibleCount ?? 0)])); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <div class="flex flex-1 min-h-0 flex-col xl:flex-row xl:items-stretch">
            <section
                x-data="{
                    syncing: false,
                    resizeHandler: null,
                    resizeObserver: null,
                    syncFrame: null,
                    lastCalendarKey: '',
                    isPointerPanning: false,
                    pointerPanStartX: 0,
                    pointerPanStartScrollLeft: 0,
                    shouldStartPointerPan(event) {
                        if (!event || event.button !== 0) {
                            return false;
                        }

                        const target = event.target;

                        if (!(target instanceof HTMLElement)) {
                            return false;
                        }

                        if (target.closest('a, button, input, textarea, select, option, label, summary, [role=button], [draggable=true], [data-no-pan]')) {
                            return false;
                        }

                        const scroller = this.$refs.mainScroller;

                        return !!scroller && scroller.scrollWidth > scroller.clientWidth;
                    },
                    startPointerPan(event) {
                        if (!this.shouldStartPointerPan(event)) {
                            return;
                        }

                        this.isPointerPanning = true;
                        this.pointerPanStartX = event.clientX;
                        this.pointerPanStartScrollLeft = this.$refs.mainScroller.scrollLeft;

                        document.body.style.cursor = 'grabbing';
                        document.body.style.userSelect = 'none';
                        event.preventDefault();
                    },
                    movePointerPan(event) {
                        if (!this.isPointerPanning || !this.$refs.mainScroller) {
                            return;
                        }

                        this.$refs.mainScroller.scrollLeft = this.pointerPanStartScrollLeft - (event.clientX - this.pointerPanStartX);
                    },
                    stopPointerPan() {
                        if (!this.isPointerPanning) {
                            return;
                        }

                        this.isPointerPanning = false;
                        document.body.style.removeProperty('cursor');
                        document.body.style.removeProperty('user-select');
                    },
                    sync(source, target) {
                        if (this.syncing || !source || !target) {
                            return;
                        }

                        this.syncing = true;

                        if (this.syncFrame) {
                            cancelAnimationFrame(this.syncFrame);
                        }

                        this.syncFrame = requestAnimationFrame(() => {
                            target.scrollLeft = source.scrollLeft;
                            this.syncing = false;
                            this.syncFrame = null;
                        });
                    },
                    syncWidth() {
                        if (!this.$refs.mainTrack || !this.$refs.bottomTrack) {
                            return;
                        }

                        this.$refs.bottomTrack.style.width = `${this.$refs.mainTrack.scrollWidth}px`;
                    },
                    syncHeight() {
                        const rect = this.$el.getBoundingClientRect();
                        const available = Math.max(window.innerHeight - rect.top, 320);
                        this.$el.style.setProperty('--publishing-calendar-height', `${available}px`);
                        this.$nextTick(() => {
                            const scrollerHeight = this.$refs.mainScroller?.clientHeight ?? (available - 56);
                            this.trackMinHeight = Math.max(scrollerHeight, 264);
                        });
                    },
                    centerToday() {
                        const scroller = this.$refs.mainScroller;
                        const todayColumn = this.$refs.todayColumn;

                        if (!scroller || !todayColumn) {
                            return;
                        }

                        if ($wire.calendarView !== 'month') {
                            return;
                        }

                        const targetLeft = todayColumn.offsetLeft + (todayColumn.offsetWidth / 2) - (scroller.clientWidth / 2);
                        const maxLeft = Math.max(scroller.scrollWidth - scroller.clientWidth, 0);

                        scroller.scrollLeft = Math.max(0, Math.min(targetLeft, maxLeft));

                        if (this.$refs.bottomScroller) {
                            this.$refs.bottomScroller.scrollLeft = scroller.scrollLeft;
                        }
                    },
                    init() {
                        this.$nextTick(() => {
                            this.lastCalendarKey = `${$wire.calendarView}:${$wire.calendarTitle}`;
                            this.syncWidth();
                            this.syncHeight();
                            this.centerToday();

                            if (window.ResizeObserver) {
                                this.resizeObserver = new ResizeObserver(() => {
                                    this.syncWidth();
                                    this.syncHeight();
                                });
                                this.resizeObserver.observe(this.$refs.mainTrack);
                                this.resizeObserver.observe(this.$el);
                            }

                            this.resizeHandler = () => {
                                this.syncWidth();
                                this.syncHeight();
                            };

                            window.addEventListener('resize', this.resizeHandler);
                        });
                    },
                    destroy() {
                        if (this.syncFrame) {
                            cancelAnimationFrame(this.syncFrame);
                        }

                        if (this.resizeObserver) {
                            this.resizeObserver.disconnect();
                        }

                        if (this.resizeHandler) {
                            window.removeEventListener('resize', this.resizeHandler);
                        }

                        this.stopPointerPan();
                    },
                }"
                x-effect="
                    const nextCalendarKey = `${$wire.calendarView}:${$wire.calendarTitle}`;

                    if (lastCalendarKey !== nextCalendarKey) {
                        lastCalendarKey = nextCalendarKey;
                        $nextTick(() => {
                            syncWidth();
                            syncHeight();
                            centerToday();
                        });
                    }
                "
                class="relative flex h-[var(--publishing-calendar-height,24rem)] min-w-0 flex-1 flex-col border-r"
                style="border-color: rgba(var(--theme-border-color-rgb), 0.68);"
            >
                <div
                    x-ref="mainScroller"
                    x-on:scroll="sync($refs.mainScroller, $refs.bottomScroller)"
                    x-on:mousedown="startPointerPan($event)"
                    x-on:mousemove.window="movePointerPan($event)"
                    x-on:mouseup.window="stopPointerPan()"
                    x-on:mouseleave.window="stopPointerPan()"
                    x-on:dragstart="stopPointerPan()"
                    x-bind:class="isPointerPanning ? 'cursor-grabbing select-none' : 'cursor-grab'"
                    class="flex min-h-0 flex-1 flex-col overflow-x-auto overflow-y-auto [touch-action:pan-x_pan-y] [-webkit-overflow-scrolling:touch]"
                    style="overscroll-behavior-x: contain;"
                >
                    <div x-ref="mainTrack" class="relative grid flex-1 items-stretch" x-bind:style="`min-width: <?php echo e(max($dayCount * 17, 120)); ?>rem; grid-template-columns: repeat(<?php echo e($dayCount); ?>, minmax(0, 1fr));`">
                        <div aria-hidden="true" class="pointer-events-none absolute inset-0 grid" style="grid-template-columns: repeat(<?php echo e($dayCount); ?>, minmax(0, 1fr));">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="h-full border-r last:border-r-0" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);"></div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $items = $itemsByDate->get($day['date'], collect());
                                $visibleItems = $items->take(5);
                                $remainingItemsCount = max(0, $items->count() - 5);
                            ?>
                            <div
                                <?php if($day['is_today']): ?> x-ref="todayColumn" <?php endif; ?>
                                class="group/day relative flex h-full flex-col transition-all duration-200 ease-out"
                                style="background-color: <?php echo e($day['is_today'] ? 'color-mix(in srgb, rgba(var(--theme-accent-rgb), 0.10) 55%, var(--theme-surface-base))' : ($day['is_current_period'] ? 'color-mix(in srgb, var(--theme-surface-base) 76%, transparent)' : 'rgba(var(--theme-border-color-rgb), 0.03)')); ?>;"
                                x-on:dragover="handleCalendarDayDragOver($event, '<?php echo e($day['date']); ?>', '<?php echo e($day['long_label']); ?>', <?php echo e($day['is_move_target'] ? 'true' : 'false'); ?>)"
                                x-on:dragleave="handleCalendarDayDragLeave('<?php echo e($day['date']); ?>')"
                                x-on:drop.prevent="openMovePostDialog('<?php echo e($day['date']); ?>', '<?php echo e($day['long_label']); ?>', <?php echo e($day['is_move_target'] ? 'true' : 'false'); ?>)"
                                x-bind:class="{
                                    'scale-[1.003] -translate-y-[1px]': canDropCalendarPost('<?php echo e($day['date']); ?>', <?php echo e($day['is_move_target'] ? 'true' : 'false'); ?>) && dragTargetDate === '<?php echo e($day['date']); ?>',
                                    'ring-2 ring-[rgba(var(--theme-accent-rgb),0.18)] ring-inset': canDropCalendarPost('<?php echo e($day['date']); ?>', <?php echo e($day['is_move_target'] ? 'true' : 'false'); ?>) && dragTargetDate === '<?php echo e($day['date']); ?>',
                                    'animate-pulse': recentDropDate === '<?php echo e($day['date']); ?>',
                                }"
                            >
                                <div
                                    x-show="recentDropDate === '<?php echo e($day['date']); ?>'"
                                    x-transition.opacity.duration.300ms
                                    class="pointer-events-none absolute inset-0 z-10"
                                    style="background: radial-gradient(circle at center, rgba(var(--theme-accent-rgb), 0.12), transparent 65%);"
                                ></div>
                                <div class="sticky top-0 z-30 overflow-hidden border-b px-3 py-3 backdrop-blur-xl <?php if(! $loop->last): ?> border-r <?php endif; ?>" style="border-bottom-color: <?php echo e($day['is_today'] ? 'rgba(var(--theme-accent-rgb), 0.22)' : 'rgba(var(--theme-border-color-rgb), 0.68)'); ?>; border-right-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: <?php echo e($day['is_today'] ? 'color-mix(in srgb, rgba(var(--theme-accent-rgb), 0.16) 55%, var(--theme-surface-overlay))' : 'color-mix(in srgb, var(--theme-surface-overlay) 92%, transparent)'); ?>;">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em]" style="color: var(--theme-muted-text-color);"><?php echo e($day['label']); ?></p>
                                            <p class="mt-1 text-lg font-semibold tracking-[-0.04em]" style="color: <?php echo e($day['is_today'] ? 'var(--theme-accent)' : 'var(--theme-header-text-color)'); ?>;"><?php echo e($day['day_number']); ?></p>
                                        </div>
                                        <div class="flex flex-col items-end gap-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($day['is_today']): ?>
                                                <span class="rounded-full px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.16em]" style="background-color: rgba(var(--theme-accent-rgb), 0.12); color: var(--theme-accent);"><?php echo e(__('Today')); ?></span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->isNotEmpty()): ?>
                                                <span class="rounded-full px-2 py-1 text-[9px] font-semibold uppercase tracking-[0.14em]" style="background-color: rgba(var(--theme-border-color-rgb), 0.08); color: var(--theme-muted-text-color);">
                                                    <?php echo e(trans_choice('{1} :count item|[2,*] :count items', $items->count(), ['count' => $items->count()])); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->isNotEmpty() && $day['can_compose']): ?>
                                        <div class="pointer-events-none absolute inset-0 z-40">
                                            <div class="h-full w-full -translate-y-full opacity-0 transition-all duration-220 ease-out group-hover/day:translate-y-0 group-hover/day:opacity-100 group-focus-within/day:translate-y-0 group-focus-within/day:opacity-100">
                                                <div class="flex h-full items-center px-3" style="background-color: color-mix(in srgb, rgba(var(--theme-accent-rgb), 0.18) 64%, var(--theme-surface-overlay));">
                                                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'primary','size' => 'sm','wire:click' => 'openComposer(\''.e($day['date']).'\')','wire:loading.attr' => 'disabled','wire:target' => 'openComposer','class' => 'pointer-events-auto w-full shadow-[0_12px_30px_-22px_rgba(var(--theme-accent-rgb),0.8)]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','size' => 'sm','wire:click' => 'openComposer(\''.e($day['date']).'\')','wire:loading.attr' => 'disabled','wire:target' => 'openComposer','class' => 'pointer-events-auto w-full shadow-[0_12px_30px_-22px_rgba(var(--theme-accent-rgb),0.8)]']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                        <span class="inline-flex items-center gap-2" wire:loading.remove wire:target="openComposer">
                                                            <i class="fa-light fa-square-plus"></i>
                                                            <?php echo e(__('Compose')); ?>

                                                        </span>
                                                        <span class="inline-flex items-center gap-2" wire:loading wire:target="openComposer">
                                                            <i class="fa-light fa-loader animate-spin"></i>
                                                            <?php echo e(__('Loading...')); ?>

                                                        </span>
                                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="flex-1 space-y-3 p-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $visibleItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <?php ($status = $statusMeta[$item['status_key']] ?? $statusMeta['pending']); ?>
                                        <?php ($primaryMedia = collect($item['media_items'] ?? [])->first()); ?>
                                        <?php ($primaryPreview = is_array($primaryMedia) ? ($primaryMedia['previewUrl'] ?? $primaryMedia['url'] ?? null) : null); ?>
                                        <?php ($primaryMime = strtolower((string) (is_array($primaryMedia) ? ($primaryMedia['mimeType'] ?? '') : ''))); ?>
                                        <?php ($hasVisual = filled($primaryPreview)); ?>
                                        <?php ($isVideo = str_starts_with($primaryMime, 'video/')); ?>
                                        <?php ($dragPayload = json_encode([
                                            'post_id' => (string) $item['post_id'],
                                            'title' => (string) $item['title'],
                                            'time' => (string) $item['time'],
                                            'date' => (string) $item['date'],
                                            'can_edit' => !empty($item['can_edit']),
                                        ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE)); ?>
                                        <?php ($footerStatusLabel = $status['label'] ?? __('Pending')); ?>
                                        <?php ($footerStatusTitle = match ($item['status_key'] ?? 'pending') {
                                            'published' => __('This post is published, but the direct link is not available yet.'),
                                            'processing' => __('This post is still being processed by the social network.'),
                                            'failed' => __('This post failed to publish.'),
                                            default => __('This post has not been published yet.'),
                                        }); ?>
                                        <article
                                            class="relative z-0 overflow-visible rounded-[1rem] border shadow-[0_18px_36px_-30px_rgba(15,23,42,0.2)] transition-all duration-200 ease-out hover:z-10 focus-within:z-10 <?php echo e(!empty($item['can_edit']) ? 'cursor-grab active:cursor-grabbing' : ''); ?>"
                                            style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent);"
                                            draggable="<?php echo e(!empty($item['can_edit']) ? 'true' : 'false'); ?>"
                                            data-drag-payload='<?php echo e($dragPayload); ?>'
                                            x-on:dragstart="startCalendarPostDrag(JSON.parse($el.dataset.dragPayload || '{}'))"
                                            x-on:dragend="if (!dragMoveModalOpen) clearCalendarPostDrag()"
                                            x-bind:class="draggingCalendarPost && draggingCalendarPost.id === '<?php echo e($item['post_id']); ?>'
                                                ? 'opacity-70 scale-[1.02] -rotate-[1.2deg] shadow-[0_28px_60px_-30px_rgba(var(--theme-accent-rgb),0.45)]'
                                                : ''"
                                        >
                                            <div class="flex items-center justify-between gap-2 border-b px-3 py-2.5" style="border-color: rgba(var(--theme-border-color-rgb), 0.55); background-color: rgba(var(--theme-border-color-rgb), 0.04);">
                                                <div class="flex min-w-0 items-center gap-2.5">
                                                    <div class="inline-flex items-center gap-1.5 rounded-full px-1.5 py-1" style="background-color: rgba(var(--theme-border-color-rgb), 0.06);">
                                                        <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px]" style="background-color: color-mix(in srgb, var(--theme-surface-base) 95%, transparent); color: var(--theme-header-text-color);">
                                                        <i class="<?php echo e($item['provider_icon']); ?>"></i>
                                                        </span>
                                                        <span
                                                            class="inline-flex h-5 w-5 shrink-0 items-center justify-center overflow-hidden rounded-full border text-[9px] font-semibold"
                                                            style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: color-mix(in srgb, var(--theme-surface-base) 98%, transparent); color: var(--theme-header-text-color);"
                                                            title="<?php echo e($item['provider']); ?> | <?php echo e($item['channel']); ?><?php echo e($item['handle'] ? ' | '.$item['handle'] : ''); ?> | <?php echo e($item['time']); ?>"
                                                        >
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['avatar_url']): ?>
                                                                <img src="<?php echo e($item['avatar_url']); ?>" alt="<?php echo e($item['channel']); ?>" class="h-full w-full object-cover">
                                                            <?php else: ?>
                                                                <?php echo e($item['initials']); ?>

                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </span>
                                                    </div>
                                                    <span class="text-[11px] font-semibold uppercase tracking-[0.12em]" style="color: var(--theme-muted-text-color);"><?php echo e($item['time']); ?></span>
                                                </div>
                                                <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em]" style="background-color: <?php echo e($status['surface']); ?>; color: <?php echo e($status['text']); ?>;">
                                                    <?php echo e($item['status']); ?>

                                                </span>
                                            </div>

                                            <div class="space-y-3 p-3">
                                                <div class="flex items-start gap-3">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-start gap-2">
                                                            <p class="min-w-0 text-[14px] font-semibold leading-5" style="color: var(--theme-header-text-color); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                                <?php echo e($item['title']); ?>

                                                            </p>
                                                        </div>

                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['source_label']) || !empty($item['campaign']) || !empty($item['tags'])): ?>
                                                            <div class="mt-2 flex flex-wrap gap-1.5">
                                                                <span
                                                                    class="rounded-full px-2 py-0.5 text-[10px] font-medium"
                                                                    style="background-color: <?php echo e($item['source_surface']); ?>; color: <?php echo e($item['source_text']); ?>;"
                                                                >
                                                                    <?php echo e($item['source_label']); ?>

                                                                </span>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['campaign'])): ?>
                                                                    <span
                                                                        class="rounded-full px-2 py-0.5 text-[10px] font-medium"
                                                                        style="background-color: color-mix(in srgb, <?php echo e($item['campaign_color'] ?: '#c9802a'); ?> 14%, white); color: <?php echo e($item['campaign_color'] ?: '#c9802a'); ?>;"
                                                                    >
                                                                        <?php echo e($item['campaign']); ?>

                                                                    </span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = collect($item['tags'])->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-medium" style="background-color: rgba(var(--theme-accent-rgb), 0.08); color: var(--theme-muted-text-color);"><?php echo e($tag); ?></span>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                            </div>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVisual): ?>
                                                        <div
                                                            class="relative h-[4.5rem] w-[4.5rem] shrink-0 overflow-hidden rounded-[0.9rem] border"
                                                            style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.08);"
                                                            x-data="{ imageLoaded: false, imageFailed: false }"
                                                        >
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVideo): ?>
                                                                <video class="h-full w-full object-cover" muted playsinline preload="metadata">
                                                                    <source src="<?php echo e($primaryPreview); ?>" type="<?php echo e($primaryMime ?: 'video/mp4'); ?>">
                                                                </video>
                                                                <span class="absolute inset-x-0 bottom-0 flex justify-center pb-2">
                                                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-950/70 text-[10px] text-white">
                                                                        <i class="fa-solid fa-play"></i>
                                                                    </span>
                                                                </span>
                                                            <?php else: ?>
                                                                <img
                                                                    src="<?php echo e($primaryPreview); ?>"
                                                                    alt="<?php echo e($item['title']); ?>"
                                                                    class="h-full w-full object-cover transition-opacity duration-300 ease-out"
                                                                    loading="lazy"
                                                                    decoding="async"
                                                                    x-show="!imageFailed"
                                                                    x-bind:style="imageLoaded ? 'opacity: 1; visibility: visible;' : 'opacity: 0; visibility: hidden;'"
                                                                    x-on:load="imageLoaded = true"
                                                                    x-on:error="imageFailed = true; imageLoaded = false"
                                                                >
                                                                <span
                                                                    x-show="!imageLoaded && !imageFailed"
                                                                    class="absolute inset-0 flex items-center justify-center"
                                                                >
                                                                    <i class="fa-solid fa-spinner animate-spin text-sm" style="color: var(--theme-muted-text-color);"></i>
                                                                </span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($item['excerpt'])): ?>
                                                    <p class="text-[12px] leading-5" style="color: var(--theme-muted-text-color); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                                        <?php echo e($item['excerpt']); ?>

                                                    </p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                <div class="relative z-30 flex items-center justify-between gap-3 border-t pt-2.5 text-[11px]" style="border-color: rgba(var(--theme-border-color-rgb), 0.45); color: var(--theme-muted-text-color);">
                                                    <button
                                                        type="button"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-[0.7rem] border transition hover:bg-slate-900/5"
                                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                                        wire:click="openPostPreview('<?php echo e($item['post_id']); ?>')"
                                                        title="<?php echo e(__('Preview post')); ?>"
                                                    >
                                                        <i class="fa-light fa-eye"></i>
                                                    </button>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['post_url'])): ?>
                                                        <div class="flex items-center gap-2">
                                                            <a
                                                                href="<?php echo e($item['post_url']); ?>"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="rounded-[0.7rem] border px-2.5 py-1 text-[11px] font-semibold transition hover:bg-slate-900/5"
                                                                style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color);"
                                                                title="<?php echo e(__('Open published post')); ?>"
                                                            >
                                                                <?php echo e(__('View Post')); ?>

                                                            </a>

                                                            <?php if (isset($component)) { $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu','data' => ['align' => 'right','width' => 'auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => 'auto']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                 <?php $__env->slot('trigger', null, []); ?> 
                                                                    <button
                                                                        type="button"
                                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-[0.7rem] border transition hover:bg-slate-900/5"
                                                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                                                    >
                                                                        <i class="fa-light fa-ellipsis"></i>
                                                                    </button>
                                                                 <?php $__env->endSlot(); ?>

                                                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-copy','wire:click' => 'copyPost(\''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-copy','wire:click' => 'copyPost(\''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                    <?php echo e(__('Copy post')); ?>

                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>

                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['can_delete_remote'])): ?>
                                                                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-trash-arrow-up','variant' => 'danger','close' => false,'xOn:click.stop' => 'open = false; openDeleteDialog(\'remote\', \''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-trash-arrow-up','variant' => 'danger','close' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'x-on:click.stop' => 'open = false; openDeleteDialog(\'remote\', \''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                        <?php echo e(__('Delete on social network')); ?>

                                                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-trash-can','variant' => 'danger','close' => false,'xOn:click.stop' => 'open = false; openDeleteDialog(\'local\', \''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-trash-can','variant' => 'danger','close' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'x-on:click.stop' => 'open = false; openDeleteDialog(\'local\', \''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                    <?php echo e(__('Delete post')); ?>

                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $attributes = $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $component = $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
                                                        </div>
                                                    <?php elseif(!empty($item['open_error'])): ?>
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="cursor-help rounded-[0.7rem] border px-2.5 py-1 text-[11px] font-semibold"
                                                                style="border-color: rgba(239, 68, 68, 0.22); background-color: rgba(239, 68, 68, 0.08); color: #b91c1c;"
                                                                title="<?php echo e($item['open_error']); ?>"
                                                            >
                                                                <?php echo e(__('Failed')); ?>

                                                            </span>

                                                            <?php if (isset($component)) { $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu','data' => ['align' => 'right','width' => 'auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => 'auto']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                 <?php $__env->slot('trigger', null, []); ?> 
                                                                    <button
                                                                        type="button"
                                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-[0.7rem] border transition hover:bg-slate-900/5"
                                                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                                                    >
                                                                        <i class="fa-light fa-ellipsis"></i>
                                                                    </button>
                                                                 <?php $__env->endSlot(); ?>

                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['can_edit'])): ?>
                                                                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-pen-to-square','wire:click' => 'editPost(\''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-pen-to-square','wire:click' => 'editPost(\''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                        <?php echo e(__('Edit post')); ?>

                                                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-copy','wire:click' => 'copyPost(\''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-copy','wire:click' => 'copyPost(\''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                    <?php echo e(__('Copy post')); ?>

                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>

                                                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-trash-can','variant' => 'danger','close' => false,'xOn:click.stop' => 'open = false; openDeleteDialog(\'local\', \''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-trash-can','variant' => 'danger','close' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'x-on:click.stop' => 'open = false; openDeleteDialog(\'local\', \''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                    <?php echo e(__('Delete post')); ?>

                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $attributes = $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $component = $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="flex items-center gap-2">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['can_edit'])): ?>
                                                                <button
                                                                    type="button"
                                                                    class="rounded-[0.7rem] border px-2.5 py-1 text-[11px] font-semibold transition hover:bg-slate-900/5"
                                                                    style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color);"
                                                                    wire:click="editPost('<?php echo e($item['post_id']); ?>')"
                                                                >
                                                                    <?php echo e(__('Edit Post')); ?>

                                                                </button>
                                                            <?php else: ?>
                                                                <span
                                                                    class="rounded-[0.7rem] border px-2.5 py-1 text-[11px] font-semibold"
                                                                    style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color);"
                                                                    title="<?php echo e($footerStatusTitle); ?>"
                                                                >
                                                                    <?php echo e(__('Preview Post')); ?>

                                                                </span>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                            <?php if (isset($component)) { $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu','data' => ['align' => 'right','width' => 'auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => 'auto']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                 <?php $__env->slot('trigger', null, []); ?> 
                                                                    <button
                                                                        type="button"
                                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-[0.7rem] border transition hover:bg-slate-900/5"
                                                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                                                    >
                                                                        <i class="fa-light fa-ellipsis"></i>
                                                                    </button>
                                                                 <?php $__env->endSlot(); ?>

                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['can_edit'])): ?>
                                                                    <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-pen-to-square','wire:click' => 'editPost(\''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-pen-to-square','wire:click' => 'editPost(\''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                        <?php echo e(__('Edit post')); ?>

                                                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-eye','wire:click' => 'openPostPreview(\''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-eye','wire:click' => 'openPostPreview(\''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                    <?php echo e(__('Preview post')); ?>

                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>

                                                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-copy','wire:click' => 'copyPost(\''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-copy','wire:click' => 'copyPost(\''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                    <?php echo e(__('Copy post')); ?>

                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>

                                                                <?php if (isset($component)) { $__componentOriginale61527cd5af239231438271d50ff42a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale61527cd5af239231438271d50ff42a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.dropdown-menu-item','data' => ['icon' => 'fa-light fa-trash-can','variant' => 'danger','close' => false,'xOn:click.stop' => 'open = false; openDeleteDialog(\'local\', \''.e($item['post_id']).'\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.dropdown-menu-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'fa-light fa-trash-can','variant' => 'danger','close' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'x-on:click.stop' => 'open = false; openDeleteDialog(\'local\', \''.e($item['post_id']).'\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                                    <?php echo e(__('Delete post')); ?>

                                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $attributes = $__attributesOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__attributesOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale61527cd5af239231438271d50ff42a5)): ?>
<?php $component = $__componentOriginale61527cd5af239231438271d50ff42a5; ?>
<?php unset($__componentOriginale61527cd5af239231438271d50ff42a5); ?>
<?php endif; ?>
                                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $attributes = $__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__attributesOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b)): ?>
<?php $component = $__componentOriginalfb0facb2aa98dc94afaec95e8f63118b; ?>
<?php unset($__componentOriginalfb0facb2aa98dc94afaec95e8f63118b); ?>
<?php endif; ?>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </div>
                                        </article>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <div class="rounded-[1.1rem] border border-dashed px-4 py-7 text-center" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.02);">
                                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-[0.95rem]" style="background-color: rgba(var(--theme-border-color-rgb), 0.06); color: var(--theme-muted-text-color);">
                                                <i class="fa-light fa-calendar-plus text-base"></i>
                                            </span>
                                            <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.18em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Open slot')); ?></p>
                                            <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('No scheduled content in this lane yet.')); ?></p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($day['can_compose']): ?>
                                                <div class="mt-4">
                                                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','size' => 'sm','wire:click' => 'openComposer(\''.e($day['date']).'\')','wire:loading.attr' => 'disabled','wire:target' => 'openComposer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','size' => 'sm','wire:click' => 'openComposer(\''.e($day['date']).'\')','wire:loading.attr' => 'disabled','wire:target' => 'openComposer']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                        <span wire:loading.remove wire:target="openComposer"><?php echo e(__('Compose')); ?></span>
                                                        <span class="inline-flex items-center gap-2" wire:loading wire:target="openComposer">
                                                            <i class="fa-light fa-loader animate-spin"></i>
                                                            <?php echo e(__('Loading...')); ?>

                                                        </span>
                                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($remainingItemsCount > 0): ?>
                                        <button
                                            type="button"
                                            class="w-full cursor-pointer rounded-[0.9rem] border px-3 py-2.5 text-sm font-semibold transition duration-150 ease-out hover:-translate-y-[1px] hover:bg-slate-900/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[rgba(var(--theme-accent-rgb),0.28)] active:translate-y-0"
                                            style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent);"
                                            wire:click="openDayPosts('<?php echo e($day['date']); ?>')"
                                            x-on:click="dayPostsModalLoadingDate = '<?php echo e($day['date']); ?>'"
                                            x-bind:disabled="dayPostsModalLoadingDate === '<?php echo e($day['date']); ?>'"
                                            x-bind:class="{ 'cursor-wait opacity-70 hover:translate-y-0 hover:bg-transparent': dayPostsModalLoadingDate === '<?php echo e($day['date']); ?>' }"
                                            data-no-loading
                                        >
                                            <span
                                                class="inline-flex items-center gap-2"
                                                x-show="dayPostsModalLoadingDate !== '<?php echo e($day['date']); ?>'"
                                            >
                                                <?php echo e(__('View more (:count)', ['count' => $remainingItemsCount])); ?>

                                            </span>
                                            <span
                                                class="inline-flex items-center gap-2"
                                                x-show="dayPostsModalLoadingDate === '<?php echo e($day['date']); ?>'"
                                                x-cloak
                                            >
                                                <i class="fa-solid fa-spinner animate-spin"></i>
                                                <?php echo e(__('Loading...')); ?>

                                            </span>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                <div aria-hidden="true" class="hidden sticky bottom-0 z-30 border-t backdrop-blur-xl" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent);">
                    <div x-ref="bottomScroller" x-on:scroll="sync($refs.bottomScroller, $refs.mainScroller)" class="overflow-x-auto px-2">
                        <div x-ref="bottomTrack" class="h-1"></div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <div>
        <template x-teleport="body">
            <div
                x-cloak
                x-show="dayPostsModalOpen"
                class="fixed inset-0 z-[118] flex items-center justify-center p-4 sm:p-6"
                x-on:keydown.escape.window="closeDayPostsModal()"
            >
                <div class="absolute inset-0 bg-white/55 backdrop-blur-[6px] dark:bg-slate-950/55" x-on:click="closeDayPostsModal()"></div>

                <div x-show="dayPostsModalOpen" x-transition.opacity.scale.95 class="relative w-full max-w-[44rem]">
                    <div class="overflow-hidden rounded-[1.15rem] border shadow-[0_32px_80px_-34px_rgba(15,23,42,0.32)]" style="border-color: color-mix(in srgb, var(--theme-border-color) 58%, transparent); background-color: var(--theme-surface-overlay);">
                        <div class="flex items-start justify-between gap-4 border-b px-5 py-4 sm:px-6 sm:py-5" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
                            <div class="min-w-0">
                                <h3 class="text-[1.05rem] font-semibold tracking-[-0.02em] text-slate-950 dark:text-white"><?php echo e(__('Posts for')); ?> <span x-text="dayPostsModalDateLabel"></span></h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><span x-text="`${dayPostsModalItems.length} <?php echo e(__('items')); ?>`"></span></p>
                            </div>

                            <button type="button" class="text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200" x-on:click="closeDayPostsModal()">
                                <i class="fa-light fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <div class="max-h-[70vh] overflow-y-auto px-5 py-4 sm:px-6">
                            <div class="space-y-3">
                                <template x-for="modalItem in dayPostsModalItems" :key="`day-post-${modalItem.post_id}`">
                                    <div
                                        class="rounded-[0.95rem] border px-3 py-3"
                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent);"
                                        x-data="{
                                            expanded: false,
                                            primaryMedia() {
                                                return modalItem?.media_items?.[0] || {};
                                            },
                                            primaryPreview() {
                                                const media = this.primaryMedia();
                                                return String(media.previewUrl || media.preview_url || media.url || '').trim();
                                            },
                                            primaryMime() {
                                                const media = this.primaryMedia();
                                                return String(media.mimeType || media.mime_type || '').toLowerCase().trim();
                                            },
                                            videoPoster() {
                                                const media = this.primaryMedia();
                                                return String(media.thumbnail || media.thumbnail_url || media.poster || media.poster_url || '').trim();
                                            },
                                            hasVisual() {
                                                return this.primaryPreview() !== '';
                                            },
                                            isVideo() {
                                                return this.primaryMime().startsWith('video/');
                                            },
                                            imageLoaded: false,
                                            imageFailed: false,
                                        }"
                                    >
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0 flex-1 space-y-3">
                                                <div class="min-w-0">
                                                    <div class="flex items-start gap-3">
                                                        <div class="relative h-11 w-11 shrink-0">
                                                            <div
                                                                class="h-11 w-11 overflow-hidden rounded-full border"
                                                                style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.08);"
                                                            >
                                                                <img
                                                                    x-show="Boolean(modalItem.avatar_url)"
                                                                    x-bind:src="modalItem.avatar_url"
                                                                    alt=""
                                                                    class="h-full w-full object-cover"
                                                                >
                                                                <div
                                                                    x-show="!modalItem.avatar_url"
                                                                    class="flex h-full w-full items-center justify-center text-sm"
                                                                    style="color: var(--theme-muted-text-color);"
                                                                >
                                                                    <i class="fa-light fa-user"></i>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class="absolute -bottom-1 -right-1 inline-flex h-5 w-5 items-center justify-center rounded-full border text-[10px] shadow-sm"
                                                                x-bind:style="`border-color: rgba(255,255,255,0.92); background-color: ${modalItem.provider_color || '#2563eb'}; color: #ffffff;`"
                                                                x-bind:title="modalItem.provider || modalItem.channel || ''"
                                                            >
                                                                <i x-bind:class="modalItem.provider_icon || 'fa-light fa-share-nodes'"></i>
                                                            </span>
                                                        </div>

                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex min-w-0 flex-wrap items-center gap-x-2 gap-y-1">
                                                                <p class="truncate text-sm font-semibold" style="color: var(--theme-header-text-color);" x-text="modalItem.channel"></p>
                                                                <span class="text-xs" style="color: var(--theme-muted-text-color);" x-text="modalItem.provider"></span>
                                                            </div>
                                                            <p class="mt-1 text-xs" style="color: var(--theme-muted-text-color);" x-text="modalItem.time"></p>
                                                        </div>
                                                    </div>
                                                <p
                                                    class="mt-1 whitespace-pre-line text-[0.95rem] font-normal leading-7"
                                                    style="color: var(--theme-muted-text-color);"
                                                    x-bind:class="expanded ? '' : 'max-h-[7em] overflow-hidden'"
                                                    x-show="String(modalItem.excerpt || '').trim() !== ''"
                                                    x-text="String(modalItem.excerpt || '').trim()"
                                                ></p>
                                                <p
                                                    class="mt-2 inline-flex max-w-full items-start gap-1.5 rounded-[0.65rem] border px-2 py-1 text-xs leading-5"
                                                    style="border-color: rgba(239, 68, 68, 0.28); background-color: rgba(239, 68, 68, 0.08); color: #b91c1c;"
                                                    x-show="String(modalItem.status_key || '').toLowerCase() === 'failed' && String(modalItem.open_error || '').trim() !== ''"
                                                    x-bind:title="String(modalItem.open_error || '')"
                                                >
                                                    <i class="fa-light fa-triangle-exclamation mt-[2px]"></i>
                                                    <span
                                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                                        x-text="String(modalItem.open_error || '')"
                                                    ></span>
                                                </p>
                                                <button
                                                    type="button"
                                                    class="mt-1 text-xs font-semibold transition hover:opacity-80"
                                                    style="color: var(--theme-accent);"
                                                    x-show="String(modalItem.excerpt || '').trim().length > 220"
                                                    x-on:click="expanded = !expanded"
                                                    x-text="expanded ? <?php echo \Illuminate\Support\Js::from(__('Show less'))->toHtml() ?> : <?php echo \Illuminate\Support\Js::from(__('Show more'))->toHtml() ?>"
                                                ></button>
                                                </div>

                                                <div class="flex flex-wrap items-center gap-2">
                                                    <button type="button" class="rounded-[0.7rem] border px-2.5 py-1 text-[11px] font-semibold transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color);" x-on:click="closeDayPostsModal(); $wire.openPostPreview(modalItem.post_id)">
                                                        <?php echo e(__('Preview')); ?>

                                                    </button>
                                                    <button type="button" class="rounded-[0.7rem] border px-2.5 py-1 text-[11px] font-semibold transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color);" x-on:click="closeDayPostsModal(); $wire.copyPost(modalItem.post_id)">
                                                        <?php echo e(__('Copy')); ?>

                                                    </button>
                                                    <button type="button" class="rounded-[0.7rem] border px-2.5 py-1 text-[11px] font-semibold transition hover:bg-red-50/50" style="border-color: rgba(239, 68, 68, 0.35); color: #b91c1c;" x-on:click="openDeleteDialog('local', modalItem.post_id); closeDayPostsModal();">
                                                        <?php echo e(__('Delete')); ?>

                                                    </button>
                                                    <a x-show="Boolean(modalItem.post_url)" x-bind:href="modalItem.post_url" target="_blank" rel="noopener noreferrer" class="rounded-[0.7rem] border px-2.5 py-1 text-[11px] font-semibold transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color);">
                                                        <?php echo e(__('View Post')); ?>

                                                    </a>
                                                </div>
                                            </div>

                                            <div class="flex w-[6.25rem] shrink-0 flex-col items-end gap-2 self-start">
                                                <span
                                                    class="rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em]"
                                                    x-bind:style="(() => {
                                                        const key = String(modalItem.status_key || '').toLowerCase();
                                                        if (key === 'published') return 'background-color: rgba(16, 185, 129, 0.14); color: #059669;';
                                                        if (key === 'failed') return 'background-color: rgba(239, 68, 68, 0.12); color: #dc2626;';
                                                        if (key === 'processing') return 'background-color: rgba(99, 102, 241, 0.12); color: #4f46e5;';
                                                        if (key === 'waiting_approve') return 'background-color: rgba(245, 158, 11, 0.14); color: #d97706;';
                                                        if (key === 'draft') return 'background-color: rgba(99, 102, 241, 0.14); color: #4f46e5;';
                                                        return 'background-color: rgba(59, 130, 246, 0.12); color: #2563eb;';
                                                    })()"
                                                    x-text="modalItem.status"
                                                ></span>
                                                <div
                                                    x-show="hasVisual()"
                                                    class="relative h-14 w-14 overflow-hidden rounded-[0.75rem] border"
                                                    style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.06);"
                                                >
                                                    <div
                                                        x-show="isVideo()"
                                                        class="absolute inset-0"
                                                        style="background: linear-gradient(180deg, rgba(15,23,42,0.10) 0%, rgba(15,23,42,0.22) 100%);"
                                                    ></div>
                                                    <video
                                                        x-show="isVideo()"
                                                        x-bind:poster="videoPoster() || null"
                                                        autoplay
                                                        loop
                                                        muted
                                                        playsinline
                                                        preload="metadata"
                                                        class="h-full w-full object-cover"
                                                        x-init="$nextTick(() => { try { $el.play?.(); } catch (e) {} })"
                                                        x-on:loadedmetadata="
                                                            try {
                                                                if (($el.duration || 0) > 0.1) {
                                                                    $el.currentTime = 0.1;
                                                                }
                                                            } catch (e) {}
                                                        "
                                                        x-on:loadeddata="
                                                            try {
                                                                if (($el.currentTime || 0) === 0 && ($el.duration || 0) > 0.1) {
                                                                    $el.currentTime = 0.1;
                                                                }
                                                                $el.play?.();
                                                            } catch (e) {}
                                                        "
                                                    >
                                                        <source x-bind:src="primaryPreview()" x-bind:type="primaryMime() || 'video/mp4'">
                                                    </video>
                                                    <img
                                                        x-show="!isVideo() && !imageFailed"
                                                        x-bind:src="primaryPreview()"
                                                        alt=""
                                                        class="h-full w-full object-cover"
                                                        x-on:load="imageLoaded = true"
                                                        x-on:error="imageFailed = true; imageLoaded = false"
                                                    >
                                                    <span
                                                        x-show="!isVideo() && !imageLoaded && !imageFailed"
                                                        class="absolute inset-0 flex items-center justify-center"
                                                    >
                                                        <i class="fa-solid fa-spinner animate-spin text-sm" style="color: var(--theme-muted-text-color);"></i>
                                                    </span>
                                                    <span
                                                        x-show="isVideo()"
                                                        class="absolute inset-0 flex items-center justify-center"
                                                    >
                                                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-950/70 text-[10px] text-white shadow-sm">
                                                            <i class="fa-solid fa-play"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-teleport="body">
            <div
                x-cloak
                x-show="dragMoveModalOpen"
                class="fixed inset-0 z-[119] flex items-center justify-center p-4 sm:p-6"
                x-on:keydown.escape.window="closeMovePostDialog()"
            >
                <div class="absolute inset-0 bg-white/55 backdrop-blur-[6px] dark:bg-slate-950/55" x-on:click="closeMovePostDialog()"></div>

                <div x-show="dragMoveModalOpen" x-transition.opacity.scale.90 class="relative w-full max-w-[28rem]">
                    <div class="overflow-hidden rounded-[1.15rem] border shadow-[0_32px_80px_-34px_rgba(15,23,42,0.32)]" style="border-color: color-mix(in srgb, var(--theme-border-color) 58%, transparent); background-color: var(--theme-surface-overlay);">
                        <div class="flex items-start justify-between gap-4 border-b px-5 py-4 sm:px-6 sm:py-5" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
                            <div class="min-w-0">
                                <h3 class="text-[1.05rem] font-semibold tracking-[-0.02em] text-slate-950 dark:text-white"><?php echo e(__('Move post')); ?></h3>
                                <p class="mt-2 text-[15px] leading-7 text-slate-500 dark:text-slate-400">
                                    <span x-text="draggingCalendarPost ? `${draggingCalendarPost.title || <?php echo \Illuminate\Support\Js::from(__('Untitled post'))->toHtml() ?>} -> ${dragTargetDateLabel || dragTargetDate}` : ''"></span>
                                </p>
                            </div>

                            <button type="button" class="text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200" x-on:click="closeMovePostDialog()">
                                <i class="fa-light fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <div class="space-y-4 px-5 py-4 sm:px-6">
                            <label class="flex cursor-pointer items-start gap-3 rounded-[0.95rem] border px-3 py-3 transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.6);">
                                <input type="radio" name="move-time-choice" class="mt-1" x-model="dragMoveMode" value="keep">
                                <span class="min-w-0">
                                    <span class="block text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Keep current time')); ?></span>
                                    <span class="mt-1 block text-sm" style="color: var(--theme-muted-text-color);" x-text="draggingCalendarPost ? `<?php echo e(__('Publish at')); ?> ${draggingCalendarPost.time}` : ''"></span>
                                </span>
                            </label>

                            <label class="flex cursor-pointer items-start gap-3 rounded-[0.95rem] border px-3 py-3 transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.6);">
                                <input type="radio" name="move-time-choice" class="mt-1" x-model="dragMoveMode" value="change">
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Change time')); ?></span>
                                    <span class="mt-1 block text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Choose a new publish time for the target day.')); ?></span>
                                    <div x-show="dragMoveMode === 'change'" x-cloak class="mt-3">
                                        <?php if (isset($component)) { $__componentOriginal14b3a8eb0e237daa9bc5e4b4af953475 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14b3a8eb0e237daa9bc5e4b4af953475 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.time-picker','data' => ['xModel' => 'dragMoveTime','placeholder' => __('Select time'),'pickerAlign' => 'left','pickerPosition' => 'top','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.time-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-model' => 'dragMoveTime','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Select time')),'pickerAlign' => 'left','pickerPosition' => 'top','class' => 'w-full']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14b3a8eb0e237daa9bc5e4b4af953475)): ?>
<?php $attributes = $__attributesOriginal14b3a8eb0e237daa9bc5e4b4af953475; ?>
<?php unset($__attributesOriginal14b3a8eb0e237daa9bc5e4b4af953475); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14b3a8eb0e237daa9bc5e4b4af953475)): ?>
<?php $component = $__componentOriginal14b3a8eb0e237daa9bc5e4b4af953475; ?>
<?php unset($__componentOriginal14b3a8eb0e237daa9bc5e4b4af953475); ?>
<?php endif; ?>
                                    </div>
                                </span>
                            </label>
                        </div>

                        <div class="border-t bg-slate-50/70 px-5 py-4 sm:px-6" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
                            <div class="flex items-center justify-end gap-3">
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xOn:click' => 'closeMovePostDialog()','xBind:disabled' => 'dragMoveSubmitting']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-on:click' => 'closeMovePostDialog()','x-bind:disabled' => 'dragMoveSubmitting']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <?php echo e(__('Cancel')); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'primary','xOn:click' => 'confirmMovePost()','xBind:disabled' => 'dragMoveSubmitting || (dragMoveMode === \'change\' && String(dragMoveTime || \'\').trim() === \'\')','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','x-on:click' => 'confirmMovePost()','x-bind:disabled' => 'dragMoveSubmitting || (dragMoveMode === \'change\' && String(dragMoveTime || \'\').trim() === \'\')','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <span class="inline-flex items-center gap-2" x-show="!dragMoveSubmitting">
                                        <i class="fa-light fa-arrows-up-down-left-right"></i>
                                        <?php echo e(__('Move post')); ?>

                                    </span>
                                    <span class="inline-flex items-center gap-2" x-show="dragMoveSubmitting" x-cloak>
                                        <i class="fa-light fa-loader animate-spin"></i>
                                        <?php echo e(__('Moving...')); ?>

                                    </span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-teleport="body">
            <div
                x-cloak
                x-show="confirmDeleteOpen"
                class="fixed inset-0 z-[120] flex items-center justify-center p-4 sm:p-6"
                x-on:keydown.escape.window="closeDeleteDialog()"
            >
                <div class="absolute inset-0 bg-white/55 backdrop-blur-[6px] dark:bg-slate-950/55" x-on:click="closeDeleteDialog()"></div>

                <div x-show="confirmDeleteOpen" x-transition.opacity.scale.90 class="relative w-full max-w-[26rem]">
                    <div class="overflow-hidden rounded-[1.15rem] border shadow-[0_32px_80px_-34px_rgba(15,23,42,0.32)]" style="border-color: color-mix(in srgb, var(--theme-border-color) 58%, transparent); background-color: var(--theme-surface-overlay);">
                        <div class="flex items-start justify-between gap-4 border-b px-5 py-4 sm:px-6 sm:py-5" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
                            <div class="min-w-0">
                                <h3 class="text-[1.05rem] font-semibold tracking-[-0.02em] text-slate-950 dark:text-white">
                                    <span x-text="confirmDeleteType === 'remote'
                                        ? <?php echo \Illuminate\Support\Js::from(__('Delete this post on the social network?'))->toHtml() ?>
                                        : (confirmDeleteType === 'filtered'
                                            ? <?php echo \Illuminate\Support\Js::from(__('Delete all filtered posts?'))->toHtml() ?>
                                            : <?php echo \Illuminate\Support\Js::from(__('Delete this post?'))->toHtml() ?>)"></span>
                                </h3>
                                <p class="mt-2 text-[15px] leading-7 text-slate-500 dark:text-slate-400">
                                    <span x-text="confirmDeleteType === 'remote'
                                        ? <?php echo \Illuminate\Support\Js::from(__('This will remove the published post from the connected social network and reset it locally.'))->toHtml() ?>
                                        : (confirmDeleteType === 'filtered'
                                            ? <?php echo \Illuminate\Support\Js::from(__('This permanently removes all posts matching the current filters in this view.'))->toHtml() ?>
                                            : <?php echo \Illuminate\Support\Js::from(__('This permanently removes the publishing item from your queue.'))->toHtml() ?>)"></span>
                                </p>
                            </div>

                            <button type="button" class="text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200" x-on:click="closeDeleteDialog()">
                                <i class="fa-light fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <div class="border-t bg-slate-50/70 px-5 py-4 sm:px-6" style="border-color: color-mix(in srgb, var(--theme-border-color) 52%, transparent);">
                            <div class="flex items-center justify-end gap-3">
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xOn:click' => 'closeDeleteDialog()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-on:click' => 'closeDeleteDialog()']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <?php echo e(__('Cancel')); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'danger','xOn:click' => '
                                        if (confirmDeleteType === \'remote\') {
                                            $wire.deleteRemotePost(confirmDeletePostId);
                                        } else if (confirmDeleteType === \'filtered\') {
                                            $wire.deleteFilteredPosts();
                                        } else {
                                            $wire.deletePost(confirmDeletePostId);
                                        }
                                        closeDeleteDialog();
                                    ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'danger','x-on:click' => '
                                        if (confirmDeleteType === \'remote\') {
                                            $wire.deleteRemotePost(confirmDeletePostId);
                                        } else if (confirmDeleteType === \'filtered\') {
                                            $wire.deleteFilteredPosts();
                                        } else {
                                            $wire.deletePost(confirmDeletePostId);
                                        }
                                        closeDeleteDialog();
                                    ']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <span x-text="confirmDeleteType === 'remote'
                                        ? <?php echo \Illuminate\Support\Js::from(__('Delete on social network'))->toHtml() ?>
                                        : (confirmDeleteType === 'filtered'
                                            ? <?php echo \Illuminate\Support\Js::from(__('Delete filtered posts'))->toHtml() ?>
                                            : <?php echo \Illuminate\Support\Js::from(__('Delete post'))->toHtml() ?>)"></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($postPreviewOpen && $postPreviewAccount): ?>
    <div
        class="fixed inset-0 z-[130] flex items-center justify-center p-4 sm:p-6"
        x-data="{
            localPreviewCaption: <?php echo \Illuminate\Support\Js::from((string) ($postPreviewComposer['caption'] ?? ''))->toHtml() ?>,
            localPreviewMediaItems: <?php echo \Illuminate\Support\Js::from(collect((array) ($postPreviewComposer['media_items'] ?? []))->values()->all())->toHtml() ?>,
            previewPrimaryMedia() {
                return Array.isArray(this.localPreviewMediaItems) && this.localPreviewMediaItems.length > 0
                    ? (this.localPreviewMediaItems[0] || null)
                    : null;
            },
            previewMediaUrl() {
                const media = this.previewPrimaryMedia();

                if (!media || typeof media !== 'object') {
                    return '';
                }

                return String(media.previewUrl || media.url || '');
            },
            previewMediaIsVideo() {
                const media = this.previewPrimaryMedia();

                if (!media || typeof media !== 'object') {
                    return false;
                }

                const mime = String(media.mimeType || '').toLowerCase();
                const category = String(media.category || '').toLowerCase();
                const extension = String(media.extension || '').toLowerCase();
                const preview = String(media.previewUrl || media.url || '').toLowerCase();

                return mime.startsWith('video/')
                    || category === 'video'
                    || ['mp4', 'mov', 'webm', 'm4v', 'avi', 'mkv'].includes(extension)
                    || /\.(mp4|mov|webm|m4v|avi|mkv)(\?.*)?$/.test(preview);
            },
        }"
        x-on:keydown.escape.window="$wire.closePostPreview()"
    >
        <div class="absolute inset-0 bg-slate-950/55 backdrop-blur-[5px]" wire:click="closePostPreview"></div>

        <div class="relative z-10 flex max-h-[calc(100dvh-2rem)] w-full max-w-5xl flex-col overflow-hidden rounded-[1.35rem] border shadow-[0_32px_90px_-36px_rgba(15,23,42,0.42)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: var(--theme-surface-base);">
            <div class="flex items-start justify-between gap-4 border-b px-5 py-4 sm:px-6" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Preview post')); ?></p>
                    <h2 class="mt-1 text-[1.1rem] font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);">
                        <?php echo e($postPreviewMeta['title'] ?: $postPreviewAccount->display_name); ?>

                    </h2>
                    <p class="mt-2 text-sm" style="color: var(--theme-muted-text-color);">
                        <?php echo e($postPreviewAccount->display_name); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($postPreviewAccount->username): ?>
                            <span>&middot; <?php echo e('@'.$postPreviewAccount->username); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($postPreviewMeta['post_url'])): ?>
                        <a
                            href="<?php echo e($postPreviewMeta['post_url']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex h-10 items-center justify-center rounded-[0.9rem] border px-4 text-sm font-semibold transition hover:bg-slate-900/5"
                            style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-header-text-color);"
                        >
                            <?php echo e(__('View Post')); ?>

                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition hover:bg-slate-900/5"
                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                        wire:click="closePostPreview"
                    >
                        <i class="fa-light fa-xmark"></i>
                    </button>
                </div>
            </div>

            <div class="overflow-y-auto px-5 py-6 sm:px-6">
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
                    <div class="flex min-w-0 items-start justify-center rounded-[1.15rem] border px-4 py-6" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                        <?php ($previewView = app(\Modules\AppPublishing\Support\PublishingPreviewRegistry::class)->get((string) $postPreviewAccount->provider_key, 'apppublishing::livewire.partials.network-preview-generic')); ?>
                        <?php echo $__env->make($previewView, [
                            'composer' => $postPreviewComposer,
                            'composerAccount' => $postPreviewAccount,
                            'composerProvider' => $postPreviewProvider,
                            'composerCampaigns' => $composerCampaigns,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>

                    <aside class="space-y-4">
                        <div class="rounded-[1rem] border p-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Network')); ?></p>
                            <div class="mt-3 flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-[0.95rem]" style="background-color: rgba(var(--theme-accent-rgb), 0.08); color: <?php echo e($postPreviewProvider['color'] ?? 'var(--theme-accent)'); ?>;">
                                    <i class="<?php echo e($postPreviewProvider['icon'] ?? 'fa-light fa-share-nodes'); ?> text-lg"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($postPreviewProvider['label'] ?? str((string) $postPreviewAccount->provider_key)->headline()); ?></p>
                                    <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e($postPreviewAccount->display_name); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[1rem] border p-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Caption')); ?></p>
                            <p class="mt-3 whitespace-pre-line text-sm leading-7" style="color: var(--theme-header-text-color);"><?php echo e($postPreviewComposer['caption'] ?: __('No caption')); ?></p>
                        </div>

                        <div class="rounded-[1rem] border p-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Media')); ?></p>
                            <p class="mt-3 text-sm" style="color: var(--theme-muted-text-color);">
                                <?php echo e(trans_choice(':count file selected|:count files selected', count((array) ($postPreviewComposer['media_items'] ?? [])), ['count' => count((array) ($postPreviewComposer['media_items'] ?? []))])); ?>

                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($postPreviewMeta['open_error'])): ?>
                                <p class="mt-3 text-sm" style="color: var(--theme-danger-color);"><?php echo e($postPreviewMeta['open_error']); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($composerOpen): ?>
    <div class="fixed inset-0 z-[140]" x-data="{ mobilePreviewOpen: false, mobileMediaOpen: false }" x-on:attached-media:open-mobile.window="mobileMediaOpen = true">
        <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-[3px]" x-on:click="closeComposerLocal()"></div>

        <div class="absolute inset-3 flex min-h-0 flex-col overflow-hidden rounded-[1.35rem] border shadow-[0_32px_90px_-36px_rgba(15,23,42,0.42)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: var(--theme-surface-base);">
            <div class="flex items-center justify-between gap-4 border-b px-5 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Schedule Composer')); ?></p>
                    <h2 class="mt-1 text-[1.1rem] font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);"><?php echo e(__('New Publishing Item')); ?></h2>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition hover:bg-slate-900/5 xl:hidden"
                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                        x-on:click="mobilePreviewOpen = !mobilePreviewOpen"
                        x-bind:aria-label="mobilePreviewOpen ? '<?php echo e(__('Hide preview')); ?>' : '<?php echo e(__('Show preview')); ?>'"
                    >
                        <i class="fa-light" x-bind:class="mobilePreviewOpen ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>

                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition hover:bg-slate-900/5 disabled:opacity-70 disabled:cursor-not-allowed"
                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                        x-bind:disabled="composerClosing || composerSavingAction !== ''"
                        x-on:click="closeComposerLocal()"
                    >
                        <template x-if="composerClosing">
                            <i class="fa-light fa-loader animate-spin"></i>
                        </template>
                        <template x-if="!composerClosing">
                            <i class="fa-light fa-xmark"></i>
                        </template>
                    </button>
                </div>
            </div>

            <div class="grid min-h-0 flex-1 xl:grid-cols-[24rem_minmax(0,1fr)] 2xl:grid-cols-[24rem_minmax(0,1fr)_32rem]">
                <aside class="hidden min-h-0 border-b xl:block xl:border-b-0 xl:border-r" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                    <div class="h-full min-h-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($composerMediaBrowserReady): ?>
                            <?php if (isset($component)) { $__componentOriginal0cc696c009f3b9c0a412f469f41e8522 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0cc696c009f3b9c0a412f469f41e8522 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.media-browser','data' => ['wire:key' => 'composer-media-browser-'.e((int) ($composer['media_refresh_token'] ?? 0)).'','wire:model.live' => 'composer.media_items','context' => 'portal','layout' => 'library','error' => $errors->first('composer.media_items'),'type' => 'all','multiple' => true,'value' => $composer['media_items'] ?? [],'libraryTitle' => __('Media'),'showLibraryHeader' => false,'frameless' => true,'compactToolbar' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.media-browser'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:key' => 'composer-media-browser-'.e((int) ($composer['media_refresh_token'] ?? 0)).'','wire:model.live' => 'composer.media_items','context' => 'portal','layout' => 'library','error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.media_items')),'type' => 'all','multiple' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($composer['media_items'] ?? []),'library-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Media')),'show-library-header' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'frameless' => true,'compact-toolbar' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0cc696c009f3b9c0a412f469f41e8522)): ?>
<?php $attributes = $__attributesOriginal0cc696c009f3b9c0a412f469f41e8522; ?>
<?php unset($__attributesOriginal0cc696c009f3b9c0a412f469f41e8522); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0cc696c009f3b9c0a412f469f41e8522)): ?>
<?php $component = $__componentOriginal0cc696c009f3b9c0a412f469f41e8522; ?>
<?php unset($__componentOriginal0cc696c009f3b9c0a412f469f41e8522); ?>
<?php endif; ?>
                        <?php else: ?>
                            <div wire:init="loadComposerMediaBrowser" class="flex h-full min-h-[16rem] items-center justify-center">
                                <div class="inline-flex items-center gap-2 text-sm font-medium" style="color: var(--theme-muted-text-color);">
                                    <i class="fa-light fa-loader animate-spin"></i>
                                    <span><?php echo e(__('Loading media library...')); ?></span>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </aside>

                <section class="min-h-0 min-w-0 overflow-y-auto">
                    <div class="mx-auto w-full max-w-[68rem] space-y-5 px-5 py-5 pb-8">
                        <div class="space-y-2.5">
                            <?php
                            $composerTeam = \Modules\AppTeams\Support\TeamWorkspaceAccess::activeTeam(auth()->user());
                            $composerCanViewChannels = auth()->user()
                                && \Modules\AppTeams\Support\TeamWorkspaceAccess::hasPermission(auth()->user(), 'channel.view', $composerTeam);
                            $composerCanManageChannels = auth()->user()
                                && \Modules\AppTeams\Support\TeamWorkspaceAccess::hasPermission(auth()->user(), 'channel.manage', $composerTeam);
                            $channelProviderRegistry = collect(channel_provider_cards())->keyBy('key');
                            $composerChannelOptions = $composerAccounts
                                ->map(function ($account) use ($channelProviderRegistry) {
                                    $provider = $channelProviderRegistry->get((string) $account->provider_key, []);
                                    $capability = channel_capability((string) ($account->capability_key ?: $account->provider_key));
                                    $providerLabel = (string) data_get($provider, 'label', str($account->provider_key)->headline());
                                    $capabilityLabel = (string) data_get($capability, 'title', data_get($capability, 'label', __('Channel')));

                                    return [
                                        'key' => (string) $account->id,
                                        'label' => (string) $account->display_name,
                                        'subtitle' => trim($providerLabel.' '.str($capabilityLabel)->lower()),
                                        'avatarUrl' => (string) ($account->avatar_url ?? ''),
                                        'providerKey' => (string) $account->provider_key,
                                        'providerLabel' => $providerLabel,
                                        'providerIcon' => (string) data_get($provider, 'icon', ''),
                                        'providerColor' => (string) data_get($provider, 'color', ''),
                                    ];
                                })
                                ->values()
                                ->all();

                            $composerChannelNetworks = $channelProviderRegistry
                                ->only($composerAccounts->pluck('provider_key')->filter()->unique()->values()->all())
                                ->map(fn ($provider) => [
                                    'key' => (string) ($provider['key'] ?? ''),
                                    'label' => (string) ($provider['label'] ?? ''),
                                    'icon' => (string) ($provider['icon'] ?? ''),
                                    'color' => (string) ($provider['color'] ?? ''),
                                ])
                                ->filter(fn ($provider) => $provider['key'] !== '' && $provider['label'] !== '')
                                ->values()
                                ->all();
                            ?>

                            <?php if (isset($component)) { $__componentOriginalca64138d11d57aceab7a854324f70d66 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalca64138d11d57aceab7a854324f70d66 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.channel-selector','data' => ['name' => 'composer_account_ids','wireModel' => 'composer.account_ids','options' => $composerChannelOptions,'networkOptions' => $composerChannelNetworks,'selected' => collect($composer['account_ids'] ?? [])->map(fn ($id) => (string) $id)->all(),'label' => __('Channel'),'error' => $errors->first('composer.account_ids'),'placeholder' => __('Choose one or more accounts'),'emptyLabel' => __('No matching channels found.'),'multiple' => true,'live' => true,'syncOnClose' => false,'connectHref' => $composerCanViewChannels && $composerCanManageChannels ? route('portal.channels') : null,'connectLabel' => __('Connect a channel')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.channel-selector'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'composer_account_ids','wire-model' => 'composer.account_ids','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($composerChannelOptions),'network-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($composerChannelNetworks),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(collect($composer['account_ids'] ?? [])->map(fn ($id) => (string) $id)->all()),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Channel')),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.account_ids')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Choose one or more accounts')),'empty-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('No matching channels found.')),'multiple' => true,'live' => true,'sync-on-close' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'connect-href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($composerCanViewChannels && $composerCanManageChannels ? route('portal.channels') : null),'connect-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Connect a channel'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalca64138d11d57aceab7a854324f70d66)): ?>
<?php $attributes = $__attributesOriginalca64138d11d57aceab7a854324f70d66; ?>
<?php unset($__attributesOriginalca64138d11d57aceab7a854324f70d66); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalca64138d11d57aceab7a854324f70d66)): ?>
<?php $component = $__componentOriginalca64138d11d57aceab7a854324f70d66; ?>
<?php unset($__componentOriginalca64138d11d57aceab7a854324f70d66); ?>
<?php endif; ?>
                        </div>

                        <div data-no-loading>
                            <?php if (isset($component)) { $__componentOriginalfb1e3fa1af7bea08d324200830cddcf1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfb1e3fa1af7bea08d324200830cddcf1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.emoji-textarea','data' => ['wire:key' => 'composer-caption-'.e(md5((string) ($composer['caption'] ?? ''))).'','id' => 'composer-caption-textarea','wire:model.live.debounce.250ms' => 'composer.caption','xOn:input' => 'localPreviewCaption = $event.target.value','xOn:publishingAiCaptionUpdated.window' => '
                                    const nextCaption = String($event.detail?.caption || \'\');
                                    if ($event.detail?.animate) {
                                        animateComposerCaption(nextCaption);
                                    } else {
                                        applyComposerCaption(nextCaption);
                                    }
                                ','label' => __('Caption'),'error' => $errors->first('composer.caption'),'triggerPosition' => 'inside-top-right','pickerAlign' => 'right','pickerTitle' => ''.e(__('Post caption')).'','rows' => '5','class' => '[&>div>div>textarea]:min-h-[9rem]','placeholder' => ''.e(__('Write the main caption, CTA, hashtags, and any publishing notes for this slot...')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.emoji-textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:key' => 'composer-caption-'.e(md5((string) ($composer['caption'] ?? ''))).'','id' => 'composer-caption-textarea','wire:model.live.debounce.250ms' => 'composer.caption','x-on:input' => 'localPreviewCaption = $event.target.value','x-on:publishing-ai-caption-updated.window' => '
                                    const nextCaption = String($event.detail?.caption || \'\');
                                    if ($event.detail?.animate) {
                                        animateComposerCaption(nextCaption);
                                    } else {
                                        applyComposerCaption(nextCaption);
                                    }
                                ','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Caption')),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.caption')),'trigger-position' => 'inside-top-right','picker-align' => 'right','picker-title' => ''.e(__('Post caption')).'','rows' => '5','class' => '[&>div>div>textarea]:min-h-[9rem]','placeholder' => ''.e(__('Write the main caption, CTA, hashtags, and any publishing notes for this slot...')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($composer['caption'] ?? ''); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfb1e3fa1af7bea08d324200830cddcf1)): ?>
<?php $attributes = $__attributesOriginalfb1e3fa1af7bea08d324200830cddcf1; ?>
<?php unset($__attributesOriginalfb1e3fa1af7bea08d324200830cddcf1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb1e3fa1af7bea08d324200830cddcf1)): ?>
<?php $component = $__componentOriginalfb1e3fa1af7bea08d324200830cddcf1; ?>
<?php unset($__componentOriginalfb1e3fa1af7bea08d324200830cddcf1); ?>
<?php endif; ?>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-[1rem] border px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                            <div class="flex flex-wrap items-center gap-2">
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xOn:click' => 'captionPickerOpen = true','size' => 'sm','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-on:click' => 'captionPickerOpen = true','size' => 'sm','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <i class="fa-light fa-wand-magic-sparkles"></i>
                                    <span><?php echo e(__('Get Caption')); ?></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($composerCanShortenUrls): ?>
                                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','wire:click' => 'shortenComposerLinks','wire:loading.attr' => 'disabled','wire:target' => 'shortenComposerLinks','size' => 'sm','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','wire:click' => 'shortenComposerLinks','wire:loading.attr' => 'disabled','wire:target' => 'shortenComposerLinks','size' => 'sm','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                        <i class="fa-light fa-link"></i>
                                        <span wire:loading.remove wire:target="shortenComposerLinks"><?php echo e(__('Shorten Links')); ?></span>
                                        <span wire:loading wire:target="shortenComposerLinks"><?php echo e(__('Shortening...')); ?></span>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xOn:click' => 'saveCaptionOpen = true','size' => 'sm','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-on:click' => 'saveCaptionOpen = true','size' => 'sm','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <i class="fa-light fa-floppy-disk"></i>
                                    <span><?php echo e(__('Save Caption')); ?></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                            </div>

                            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold" style="background-color: rgba(var(--theme-accent-rgb), 0.08); color: var(--theme-muted-text-color);">
                                <i class="fa-light fa-input-text"></i>
                                <span x-text="`${Array.from(String(localPreviewCaption || '')).length}/2200 <?php echo e(__('characters')); ?>`"></span>
                            </div>
                        </div>

                        <template x-teleport="body">
                            <div
                                x-cloak
                                x-show="saveCaptionOpen"
                                class="fixed inset-0 z-[165] flex items-center justify-center p-6"
                                x-on:keydown.escape.window="saveCaptionOpen = false"
                            >
                                <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-[4px]" x-on:click="saveCaptionOpen = false"></div>

                                <div
                                    x-show="saveCaptionOpen"
                                    x-transition.opacity.scale.95
                                    class="relative w-full max-w-xl"
                                >
                                    <div class="overflow-hidden rounded-[1.2rem] border shadow-[0_32px_90px_-36px_rgba(15,23,42,0.42)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: var(--theme-surface-base);">
                                        <div class="border-b px-5 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Caption Library')); ?></p>
                                                    <h3 class="mt-1 text-[1.05rem] font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Save caption')); ?></h3>
                                                    <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Choose a name and source type before adding this caption to the library.')); ?></p>
                                                </div>

                                                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);" x-on:click="saveCaptionOpen = false">
                                                    <i class="fa-light fa-xmark"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="space-y-4 px-5 py-5">
                                            <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.input','data' => ['wire:model.defer' => 'composer.caption_library_name','label' => __('Caption name'),'error' => $errors->first('composer.caption_library_name'),'placeholder' => __('Product teaser set')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'composer.caption_library_name','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Caption name')),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.caption_library_name')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Product teaser set'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

                                            <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.defer' => 'composer.caption_library_source_type','label' => __('Type'),'error' => $errors->first('composer.caption_library_source_type')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'composer.caption_library_source_type','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Type')),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.caption_library_source_type'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                <option value="manual"><?php echo e(__('Manual')); ?></option>
                                                <option value="ai"><?php echo e(__('AI')); ?></option>
                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
                                        </div>

                                        <div class="border-t px-5 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                                            <div class="flex items-center justify-end gap-3">
                                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xOn:click' => 'saveCaptionOpen = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-on:click' => 'saveCaptionOpen = false']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                    <?php echo e(__('Cancel')); ?>

                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','wire:click' => 'saveComposerCaption','wire:loading.attr' => 'disabled','wire:target' => 'saveComposerCaption','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'saveComposerCaption','wire:loading.attr' => 'disabled','wire:target' => 'saveComposerCaption','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                    <span wire:loading.remove wire:target="saveComposerCaption"><?php echo e(__('Save Caption')); ?></span>
                                                    <span wire:loading wire:target="saveComposerCaption"><?php echo e(__('Saving...')); ?></span>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-teleport="body">
                            <div
                                x-cloak
                                x-show="captionPickerOpen"
                                class="fixed inset-0 z-[160] flex justify-end"
                                x-on:keydown.escape.window="captionPickerOpen = false"
                            >
                                <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-[4px]" x-on:click="captionPickerOpen = false"></div>

                                <div
                                    x-show="captionPickerOpen"
                                    x-transition:enter="transform transition ease-out duration-220"
                                    x-transition:enter-start="translate-x-full opacity-0"
                                    x-transition:enter-end="translate-x-0 opacity-100"
                                    x-transition:leave="transform transition ease-in duration-180"
                                    x-transition:leave-start="translate-x-0 opacity-100"
                                    x-transition:leave-end="translate-x-full opacity-0"
                                    class="relative flex h-full w-full max-w-[34rem] flex-col border-l shadow-[-28px_0_70px_-38px_rgba(15,23,42,0.42)]"
                                    style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: var(--theme-surface-base);"
                                >
                                    <div class="border-b px-5 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-[11px] font-semibold uppercase tracking-[0.22em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Caption Library')); ?></p>
                                                <h3 class="mt-1 text-[1.05rem] font-semibold tracking-[-0.03em]" style="color: var(--theme-header-text-color);"><?php echo e(__('Choose a caption')); ?></h3>
                                                <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Select a saved caption and apply it to the composer.')); ?></p>
                                            </div>

                                            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition hover:bg-slate-900/5" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);" x-on:click="captionPickerOpen = false">
                                                <i class="fa-light fa-xmark"></i>
                                            </button>
                                        </div>

                                        <div class="mt-4 space-y-3">
                                            <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.input','data' => ['xModel' => 'captionPickerSearch','placeholder' => __('Search caption library')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-model' => 'captionPickerSearch','placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Search caption library'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="flex-1 overflow-y-auto px-5 py-5">
                                        <div class="space-y-3" x-show="filteredCaptionLibrary().length > 0">
                                            <template x-for="caption in filteredCaptionLibrary()" :key="'composer-caption-'+caption.id">
                                                <button
                                                    type="button"
                                                    class="block w-full rounded-[1rem] border px-4 py-4 text-left transition hover:bg-slate-900/5"
                                                    style="border-color: rgba(var(--theme-border-color-rgb), 0.58); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);"
                                                    x-on:click="selectComposerLibraryCaption(caption)"
                                                >
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div class="min-w-0">
                                                            <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);" x-text="caption.name"></p>
                                                            <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);" x-text="caption.content.length > 220 ? `${caption.content.slice(0, 220)}...` : caption.content"></p>
                                                        </div>
                                                        <span class="shrink-0 text-[11px] font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-accent);" x-text="caption.sourceType"></span>
                                                    </div>

                                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                                        <template x-for="tag in (caption.tags || []).slice(0, 4)" :key="`caption-tag-${caption.id}-${tag}`">
                                                            <span class="rounded-full px-2 py-1 text-[11px]" style="background-color: rgba(var(--theme-accent-rgb), 0.08); color: var(--theme-accent);" x-text="tag"></span>
                                                        </template>
                                                        <span class="text-[11px] font-medium" style="color: var(--theme-muted-text-color);" x-text="caption.updatedLabel"></span>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>

                                        <div x-cloak x-show="filteredCaptionLibrary().length === 0" class="flex h-full min-h-[16rem] items-center justify-center">
                                            <div class="max-w-xs text-center">
                                                <i class="fa-light fa-books text-3xl" style="color: var(--theme-muted-text-color);"></i>
                                                <p class="mt-4 text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('No matching captions')); ?></p>
                                                <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(__('Try another keyword to see more saved captions.')); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="space-y-3 rounded-[1rem] border p-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('AI composer tools')); ?></p>
                                    <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Generate captions, repurpose drafts, review quality, and pull best posting windows without leaving the composer.')); ?></p>
                                </div>
                                <a href="<?php echo e(route('portal.ai-studio')); ?>" wire:navigate class="text-xs font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-accent);"><?php echo e(__('Open studio')); ?></a>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','wire:click' => 'generateComposerCaption','wire:loading.attr' => 'disabled','wire:target' => 'generateComposerCaption','size' => 'sm','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','wire:click' => 'generateComposerCaption','wire:loading.attr' => 'disabled','wire:target' => 'generateComposerCaption','size' => 'sm','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <i class="fa-light fa-wand-magic-sparkles"></i>
                                    <span wire:loading.remove wire:target="generateComposerCaption"><?php echo e(__('AI Caption')); ?></span>
                                    <span wire:loading wire:target="generateComposerCaption"><?php echo e(__('Generating...')); ?></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','wire:click' => 'generateComposerImage','wire:loading.attr' => 'disabled','wire:target' => 'generateComposerImage','size' => 'sm','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','wire:click' => 'generateComposerImage','wire:loading.attr' => 'disabled','wire:target' => 'generateComposerImage','size' => 'sm','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <i class="fa-light fa-image"></i>
                                    <span wire:loading.remove wire:target="generateComposerImage"><?php echo e(__('AI Image')); ?></span>
                                    <span wire:loading wire:target="generateComposerImage"><?php echo e(__('Creating image...')); ?></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','wire:click' => 'repurposeComposer','wire:loading.attr' => 'disabled','wire:target' => 'repurposeComposer','size' => 'sm','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','wire:click' => 'repurposeComposer','wire:loading.attr' => 'disabled','wire:target' => 'repurposeComposer','size' => 'sm','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <i class="fa-light fa-code-branch"></i>
                                    <span wire:loading.remove wire:target="repurposeComposer"><?php echo e(__('Repurpose')); ?></span>
                                    <span wire:loading wire:target="repurposeComposer"><?php echo e(__('Repurposing...')); ?></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','wire:click' => 'reviewComposer','wire:loading.attr' => 'disabled','wire:target' => 'reviewComposer','size' => 'sm','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','wire:click' => 'reviewComposer','wire:loading.attr' => 'disabled','wire:target' => 'reviewComposer','size' => 'sm','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <i class="fa-light fa-shield-check"></i>
                                    <span wire:loading.remove wire:target="reviewComposer"><?php echo e(__('Review')); ?></span>
                                    <span wire:loading wire:target="reviewComposer"><?php echo e(__('Reviewing...')); ?></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','wire:click' => 'suggestComposerBestTimes','wire:loading.attr' => 'disabled','wire:target' => 'suggestComposerBestTimes','size' => 'sm','dataNoLoading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','wire:click' => 'suggestComposerBestTimes','wire:loading.attr' => 'disabled','wire:target' => 'suggestComposerBestTimes','size' => 'sm','data-no-loading' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <i class="fa-light fa-clock"></i>
                                    <span wire:loading.remove wire:target="suggestComposerBestTimes"><?php echo e(__('Best time')); ?></span>
                                    <span wire:loading wire:target="suggestComposerBestTimes"><?php echo e(__('Finding...')); ?></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                            </div>

                            <div wire:loading.flex wire:target="generateComposerCaption,generateComposerImage,repurposeComposer,reviewComposer,suggestComposerBestTimes" class="items-center gap-2 rounded-[0.9rem] border px-3 py-2 text-sm" style="border-color: rgba(var(--theme-border-color-rgb), 0.52); background-color: rgba(var(--theme-accent-rgb), 0.06); color: var(--theme-muted-text-color);">
                                <i class="fa-light fa-loader animate-spin" style="color: var(--theme-accent);"></i>
                                <span><?php echo e(__('AI is processing your request...')); ?></span>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['ai_tags'])): ?>
                                <div class="flex flex-wrap gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ($composer['ai_tags'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span class="rounded-full px-2 py-1 text-[11px]" style="background-color: rgba(var(--theme-accent-rgb), 0.1); color: var(--theme-accent);"><?php echo e($tag); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['ai_caption_variants'])): ?>
                                <div class="space-y-2">
                                    <p class="text-xs font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Generated variants')); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ($composer['ai_caption_variants'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <button
                                            type="button"
                                            wire:click="applyComposerCaptionVariant(<?php echo e($index); ?>)"
                                            class="block w-full rounded-[0.95rem] border px-3 py-3 text-left transition hover:bg-slate-900/5"
                                            style="border-color: rgba(var(--theme-border-color-rgb), 0.52); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent);"
                                        >
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Variant :number', ['number' => $loop->iteration])); ?></span>
                                                <span class="text-[11px] uppercase tracking-[0.16em]" style="color: var(--theme-accent);"><?php echo e(__('Use this')); ?></span>
                                            </div>
                                            <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(\Illuminate\Support\Str::limit((string) ($variant['caption'] ?? ''), 220)); ?></p>
                                        </button>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['ai_repurpose_items'])): ?>
                                <div class="space-y-2">
                                    <p class="text-xs font-semibold uppercase tracking-[0.16em]" style="color: var(--theme-muted-text-color);"><?php echo e(__('Repurpose variants')); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ($composer['ai_repurpose_items'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <button
                                            type="button"
                                            wire:click="applyRepurposeVariant(<?php echo e($index); ?>)"
                                            class="block w-full rounded-[0.95rem] border px-3 py-3 text-left transition hover:bg-slate-900/5"
                                            style="border-color: rgba(var(--theme-border-color-rgb), 0.52); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent);"
                                        >
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($variant['title'] ?: strtoupper((string) ($variant['target'] ?? 'Variant'))); ?></span>
                                                <span class="text-[11px] uppercase tracking-[0.16em]" style="color: var(--theme-accent);"><?php echo e($variant['format'] ?? __('Variant')); ?></span>
                                            </div>
                                            <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e(\Illuminate\Support\Str::limit((string) ($variant['caption'] ?? ''), 180)); ?></p>
                                        </button>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['ai_review'])): ?>
                                <div class="rounded-[0.95rem] border p-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.52); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent);">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($composer['ai_review']['verdict'] ?? __('AI review')); ?></p>
                                        <span class="text-xs uppercase tracking-[0.16em]" style="color: var(--theme-accent);"><?php echo e($composer['ai_review']['score'] ?? 0); ?>/100</span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_slice((array) ($composer['ai_review']['fixes'] ?? []), 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fix): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e($fix); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['review_status'])): ?>
                                <div class="rounded-[0.95rem] border p-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.52); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent);">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Approval status')); ?></p>
                                        <span
                                            class="rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.14em]"
                                            style="<?php echo e(($composer['review_status'] ?? '') === 'pending'
                                                ? 'background-color: rgba(245, 158, 11, 0.14); color: #d97706;'
                                                : (($composer['review_status'] ?? '') === 'rejected'
                                                    ? 'background-color: rgba(239, 68, 68, 0.12); color: #dc2626;'
                                                    : 'background-color: rgba(16, 185, 129, 0.14); color: #059669;')); ?>"
                                        >
                                            <?php echo e($composer['review_badge'] ?: str((string) $composer['review_status'])->headline()); ?>

                                        </span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['review_submitted_at']) || !empty($composer['review_submitted_by'])): ?>
                                        <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);">
                                            <?php echo e(__('Submitted')); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['review_submitted_by'])): ?>
                                                <?php echo e(__('by :name', ['name' => $composer['review_submitted_by']])); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['review_submitted_at'])): ?>
                                                <?php echo e(__('on :date', ['date' => $composer['review_submitted_at']])); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['review_decided_at']) || !empty($composer['review_decided_by'])): ?>
                                        <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);">
                                            <?php echo e(__('Last decision')); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['review_decided_by'])): ?>
                                                <?php echo e(__('by :name', ['name' => $composer['review_decided_by']])); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['review_decided_at'])): ?>
                                                <?php echo e(__('on :date', ['date' => $composer['review_decided_at']])); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['review_note'])): ?>
                                        <p class="mt-2 text-sm leading-6" style="color: var(--theme-muted-text-color);"><?php echo e($composer['review_note']); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if (isset($component)) { $__componentOriginalb24d92d2425ba99148c9a81784b1d95a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb24d92d2425ba99148c9a81784b1d95a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.attached-media','data' => ['wire:key' => 'composer-attached-media-'.e((int) ($composer['media_refresh_token'] ?? 0)).'-'.e(md5(json_encode($composer['media_items'] ?? []))).'','wireModel' => 'composer.media_items','value' => $composer['media_items'] ?? [],'error' => $errors->first('composer.media_items'),'label' => __('Attached Media'),'description' => __('Selected assets from the media library will attach to this scheduled post.'),'emptyLabel' => __('Drag media here or choose files from the media library.'),'mobileButtonLabel' => __('Select media'),'mobileOpenEvent' => 'attached-media:open-mobile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.attached-media'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:key' => 'composer-attached-media-'.e((int) ($composer['media_refresh_token'] ?? 0)).'-'.e(md5(json_encode($composer['media_items'] ?? []))).'','wire-model' => 'composer.media_items','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($composer['media_items'] ?? []),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.media_items')),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Attached Media')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Selected assets from the media library will attach to this scheduled post.')),'empty-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Drag media here or choose files from the media library.')),'mobile-button-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Select media')),'mobile-open-event' => 'attached-media:open-mobile']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb24d92d2425ba99148c9a81784b1d95a)): ?>
<?php $attributes = $__attributesOriginalb24d92d2425ba99148c9a81784b1d95a; ?>
<?php unset($__attributesOriginalb24d92d2425ba99148c9a81784b1d95a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb24d92d2425ba99148c9a81784b1d95a)): ?>
<?php $component = $__componentOriginalb24d92d2425ba99148c9a81784b1d95a; ?>
<?php unset($__componentOriginalb24d92d2425ba99148c9a81784b1d95a); ?>
<?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $composerOptionAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php ($providerKey = (string) $optionAccount->provider_key); ?>
                            <?php ($composerOptionsView = app(\Modules\AppPublishing\Support\PublishingOptionsRegistry::class)->get($providerKey)); ?>
                            <?php ($composerNetworkConfig = $this->networkConfigForProvider($providerKey)); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($composerOptionsView): ?>
                                <div class="rounded-[1rem] border" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                                    <div class="border-b px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                                        <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e($composerNetworkConfig['label']); ?></p>
                                    </div>

                                    <div data-no-loading>
                                        <?php echo $__env->make($composerOptionsView, ['providerKey' => $providerKey], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                        <div class="space-y-4">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between gap-3">
                                    <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Campaign')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                                    <a href="<?php echo e(route('portal.publishing.campaigns')); ?>" wire:navigate class="text-xs font-semibold" style="color: var(--theme-accent);"><?php echo e(__('Manage')); ?></a>
                                </div>
                                <p class="text-sm" style="color: var(--theme-muted-text-color);">
                                    <?php echo e(__('Track and report on your social marketing campaigns with the Campaign Planner, notes and more.')); ?>

                                </p>
                                <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['wire:model.defer' => 'composer.campaign_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'composer.campaign_id']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <option value=""><?php echo e(__('No campaign')); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $composerCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($campaign->id); ?>"><?php echo e($campaign->name); ?><?php echo e($campaign->status !== 'active' ? ' ('.str($campaign->status)->headline().')' : ''); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
                            </div>

                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between gap-3">
                                    <?php if (isset($component)) { $__componentOriginalb2c43a998f3174877f99993c62e16bb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2c43a998f3174877f99993c62e16bb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(__('Labels')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $attributes = $__attributesOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__attributesOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2c43a998f3174877f99993c62e16bb4)): ?>
<?php $component = $__componentOriginalb2c43a998f3174877f99993c62e16bb4; ?>
<?php unset($__componentOriginalb2c43a998f3174877f99993c62e16bb4); ?>
<?php endif; ?>
                                    <a href="<?php echo e(route('portal.publishing.labels')); ?>" wire:navigate class="text-xs font-semibold" style="color: var(--theme-accent);"><?php echo e(__('Manage')); ?></a>
                                </div>
                                <?php if (isset($component)) { $__componentOriginala8211961a346ebf140435695fc7d5e13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8211961a346ebf140435695fc7d5e13 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.tag-selector','data' => ['name' => 'composer_label_ids','wireModel' => 'composer.label_ids','options' => $composerLabels->map(fn ($label) => ['key' => (string) $label->id, 'label' => $label->name])->all(),'selected' => collect($composer['label_ids'] ?? [])->map(fn ($id) => (string) $id)->all(),'description' => __('Use Labels to organize, filter and report on your content.'),'placeholder' => __('Search or pick labels...'),'emptyLabel' => __('No matching labels found.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.tag-selector'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'composer_label_ids','wire-model' => 'composer.label_ids','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($composerLabels->map(fn ($label) => ['key' => (string) $label->id, 'label' => $label->name])->all()),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(collect($composer['label_ids'] ?? [])->map(fn ($id) => (string) $id)->all()),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Use Labels to organize, filter and report on your content.')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Search or pick labels...')),'empty-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('No matching labels found.'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8211961a346ebf140435695fc7d5e13)): ?>
<?php $attributes = $__attributesOriginala8211961a346ebf140435695fc7d5e13; ?>
<?php unset($__attributesOriginala8211961a346ebf140435695fc7d5e13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8211961a346ebf140435695fc7d5e13)): ?>
<?php $component = $__componentOriginala8211961a346ebf140435695fc7d5e13; ?>
<?php unset($__componentOriginala8211961a346ebf140435695fc7d5e13); ?>
<?php endif; ?>
                            </div>
                        </div>

                        <?php if (isset($component)) { $__componentOriginal62d1193389a71cd99ff302a00abbf991 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62d1193389a71cd99ff302a00abbf991 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.textarea','data' => ['wire:model.defer' => 'composer.notes','label' => __('Internal notes'),'error' => $errors->first('composer.notes'),'rows' => '4','placeholder' => ''.e(__('Approval notes, coordination reminders, or media instructions...')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.defer' => 'composer.notes','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Internal notes')),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.notes')),'rows' => '4','placeholder' => ''.e(__('Approval notes, coordination reminders, or media instructions...')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($composer['notes'] ?? ''); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $attributes = $__attributesOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__attributesOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $component = $__componentOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__componentOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>

                        <?php ($scheduleMode = (string) ($composer['schedule_mode'] ?? 'specific_days_times')); ?>
                        <?php ($composerRepeatRule = (string) ($composer['repeat_rule'] ?? 'none')); ?>
                        <?php ($composerRepeatDays = collect((array) ($composer['repeat_days'] ?? []))->map(fn ($day) => strtolower((string) $day))->all()); ?>
                        <div
                            class="rounded-[1rem] border"
                            style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.03);"
                            x-data="{
                                scheduleMode: <?php echo \Illuminate\Support\Js::from($scheduleMode)->toHtml() ?>,
                                repeatRule: <?php echo \Illuminate\Support\Js::from($composerRepeatRule)->toHtml() ?>,
                                repeatDays: <?php echo \Illuminate\Support\Js::from($composerRepeatDays)->toHtml() ?>,
                                syncRepeatDays() {
                                    $wire.set('composer.repeat_days', Array.isArray(this.repeatDays) ? this.repeatDays : [], false);
                                },
                                toggleRepeatDay(day) {
                                    const key = String(day || '').toLowerCase();
                                    const order = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
                                    const nextDays = Array.isArray(this.repeatDays) ? [...this.repeatDays] : [];
                                    const index = nextDays.indexOf(key);

                                    if (index >= 0) {
                                        nextDays.splice(index, 1);
                                    } else {
                                        nextDays.push(key);
                                    }

                                    this.repeatDays = nextDays
                                        .filter((item) => order.includes(item))
                                        .sort((left, right) => order.indexOf(left) - order.indexOf(right));

                                    this.syncRepeatDays();
                                },
                                updateRepeatRule() {
                                    if (this.repeatRule === 'none') {
                                        this.repeatDays = [];
                                    } else if (this.repeatRule === 'weekday') {
                                        this.repeatDays = ['mon', 'tue', 'wed', 'thu', 'fri'];
                                    }

                                    $wire.set('composer.repeat_rule', this.repeatRule, false);
                                    this.syncRepeatDays();
                                },
                            }"
                        >
                            <div class="border-b px-4 py-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('When to post')); ?></p>
                                    <div class="min-w-[16rem]" data-no-loading>
                                        <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['xModel' => 'scheduleMode','xOn:change' => '$wire.set(\'composer.schedule_mode\', scheduleMode, false)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-model' => 'scheduleMode','x-on:change' => '$wire.set(\'composer.schedule_mode\', scheduleMode, false)']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                            <option value="immediately"><?php echo e(__('Immediately')); ?></option>
                                            <option value="specific_days_times"><?php echo e(__('Specific Days & Times')); ?></option>
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4 px-4 py-4">
                                <div
                                    x-show="scheduleMode === 'immediately'"
                                    x-cloak
                                    class="rounded-[0.95rem] border px-4 py-3 text-sm"
                                    style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-accent-rgb), 0.04); color: var(--theme-muted-text-color);"
                                >
                                        <?php echo e(__('This post will be queued to publish immediately after you confirm.')); ?>

                                </div>

                                <div x-show="scheduleMode === 'specific_days_times'" x-cloak class="space-y-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($composer['ai_best_times'])): ?>
                                            <div class="rounded-[0.95rem] border p-3" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-base) 94%, transparent);">
                                                <div class="flex items-center justify-between gap-3">
                                                    <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Suggested windows')); ?></p>
                                                    <span class="text-xs uppercase tracking-[0.16em]" style="color: var(--theme-accent);"><?php echo e(__('Local history')); ?></span>
                                                </div>
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ($composer['ai_best_times'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                        <button
                                                            type="button"
                                                            wire:click="applyComposerBestTime(<?php echo e($index); ?>)"
                                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-semibold transition hover:bg-slate-900/5"
                                                            style="border-color: rgba(var(--theme-border-color-rgb), 0.58); color: var(--theme-header-text-color); background-color: color-mix(in srgb, var(--theme-surface-overlay) 92%, transparent);"
                                                        >
                                                            <span><?php echo e($slot['label'] ?? ''); ?></span>
                                                            <span style="color: var(--theme-accent);"><?php echo e($slot['confidence'] ?? 0); ?>%</span>
                                                        </button>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <template x-for="(slotValue, slotIndex) in localScheduleSlots" :key="`schedule-slot-${slotIndex}`">
                                            <div
                                                class="grid items-start gap-3"
                                                x-bind:class="localScheduleSlots.length > 1 ? 'grid-cols-[minmax(0,1fr)_3rem]' : 'grid-cols-1'"
                                            >
                                                <div class="space-y-2.5">
                                                    <template x-if="slotIndex === 0">
                                                        <div class="space-y-2.5">
                                                            <label class="text-sm font-medium" style="color: var(--theme-header-text-color);"><?php echo e(__('Schedule slot')); ?></label>
                                                            <template x-if="<?php echo \Illuminate\Support\Js::from((string) $errors->first('composer.schedule_slots'))->toHtml() ?> !== ''">
                                                                <p class="text-sm font-medium" style="color: var(--theme-danger-color);"><?php echo e($errors->first('composer.schedule_slots')); ?></p>
                                                            </template>
                                                        </div>
                                                    </template>

                                                    <div data-no-loading>
                                                        <?php if (isset($component)) { $__componentOriginalc8fb850d5c832148049474ce8a0f1603 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8fb850d5c832148049474ce8a0f1603 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.datetime-picker','data' => ['xModel' => 'localScheduleSlots[slotIndex]','value' => '','class' => 'flex-1','label' => null,'pickerAlign' => 'auto','pickerPosition' => 'top']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.datetime-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-model' => 'localScheduleSlots[slotIndex]','value' => '','class' => 'flex-1','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'picker-align' => 'auto','picker-position' => 'top']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8fb850d5c832148049474ce8a0f1603)): ?>
<?php $attributes = $__attributesOriginalc8fb850d5c832148049474ce8a0f1603; ?>
<?php unset($__attributesOriginalc8fb850d5c832148049474ce8a0f1603); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8fb850d5c832148049474ce8a0f1603)): ?>
<?php $component = $__componentOriginalc8fb850d5c832148049474ce8a0f1603; ?>
<?php unset($__componentOriginalc8fb850d5c832148049474ce8a0f1603); ?>
<?php endif; ?>
                                                    </div>
                                                </div>

                                                <template x-if="localScheduleSlots.length > 1">
                                                    <button
                                                        type="button"
                                                        x-on:click="removeLocalScheduleSlot(slotIndex)"
                                                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-[0.75rem] border transition hover:bg-slate-900/5"
                                                        x-bind:class="slotIndex === 0 ? 'mt-[2.15rem]' : 'mt-0'"
                                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                                    >
                                                        <i class="fa-light fa-trash-can"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                        <div class="grid gap-3 lg:grid-cols-[minmax(0,16rem)_minmax(0,1fr)]">
                                            <div data-no-loading>
                                                <?php if (isset($component)) { $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.select','data' => ['label' => __('Repeat'),'xModel' => 'repeatRule','xOn:change' => 'updateRepeatRule()','error' => $errors->first('composer.repeat_rule')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Repeat')),'x-model' => 'repeatRule','x-on:change' => 'updateRepeatRule()','error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.repeat_rule'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                                    <option value="none"><?php echo e(__('Does not repeat')); ?></option>
                                                    <option value="weekday"><?php echo e(__('Every weekday')); ?></option>
                                                    <option value="weekly_custom"><?php echo e(__('Custom weekdays')); ?></option>
                                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $attributes = $__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__attributesOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862)): ?>
<?php $component = $__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862; ?>
<?php unset($__componentOriginal231e2c645bf8af0c5c05a5dc5a94c862); ?>
<?php endif; ?>
                                            </div>

                                            <div x-show="repeatRule !== 'none'" x-cloak data-no-loading>
                                                <?php if (isset($component)) { $__componentOriginal7f4c83fa1ea8a66f8f2f54ce6cdbbc4e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f4c83fa1ea8a66f8f2f54ce6cdbbc4e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.date-picker','data' => ['name' => 'composer_repeat_until','wire:model.live' => 'composer.repeat_until','value' => $composer['repeat_until'] ?? '','label' => __('Repeat until'),'placeholder' => __('Choose end date'),'error' => $errors->first('composer.repeat_until'),'pickerAlign' => 'auto','pickerPosition' => 'top']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.date-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'composer_repeat_until','wire:model.live' => 'composer.repeat_until','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($composer['repeat_until'] ?? ''),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Repeat until')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Choose end date')),'error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.repeat_until')),'picker-align' => 'auto','picker-position' => 'top']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7f4c83fa1ea8a66f8f2f54ce6cdbbc4e)): ?>
<?php $attributes = $__attributesOriginal7f4c83fa1ea8a66f8f2f54ce6cdbbc4e; ?>
<?php unset($__attributesOriginal7f4c83fa1ea8a66f8f2f54ce6cdbbc4e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7f4c83fa1ea8a66f8f2f54ce6cdbbc4e)): ?>
<?php $component = $__componentOriginal7f4c83fa1ea8a66f8f2f54ce6cdbbc4e; ?>
<?php unset($__componentOriginal7f4c83fa1ea8a66f8f2f54ce6cdbbc4e); ?>
<?php endif; ?>
                                            </div>
                                        </div>

                                        <div x-show="repeatRule === 'weekly_custom'" x-cloak class="space-y-2.5">
                                            <div class="flex items-center justify-between gap-3">
                                                <label class="text-sm font-medium" style="color: var(--theme-header-text-color);"><?php echo e(__('Repeat on')); ?></label>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((string) $errors->first('composer.repeat_days') !== ''): ?>
                                                    <p class="text-sm font-medium" style="color: var(--theme-danger-color);"><?php echo e($errors->first('composer.repeat_days')); ?></p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                                                    'mon' => __('Mon'),
                                                    'tue' => __('Tue'),
                                                    'wed' => __('Wed'),
                                                    'thu' => __('Thu'),
                                                    'fri' => __('Fri'),
                                                    'sat' => __('Sat'),
                                                    'sun' => __('Sun'),
                                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repeatDayKey => $repeatDayLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                    <label class="inline-flex cursor-pointer items-center" data-no-loading>
                                                        <input type="checkbox" class="sr-only" x-bind:checked="repeatDays.includes('<?php echo e($repeatDayKey); ?>')" x-on:change="toggleRepeatDay('<?php echo e($repeatDayKey); ?>')">
                                                        <span
                                                            class="inline-flex min-w-[3.25rem] items-center justify-center rounded-full border px-3 py-2 text-xs font-semibold transition"
                                                            x-bind:style="repeatDays.includes('<?php echo e($repeatDayKey); ?>')
                                                                ? 'border-color: rgba(var(--theme-accent-rgb), 0.38); background-color: rgba(var(--theme-accent-rgb), 0.10); color: var(--theme-accent);'
                                                                : 'border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-surface-base-rgb,255,255,255),0.92); color: var(--theme-header-text-color);'"
                                                        >
                                                            <?php echo e($repeatDayLabel); ?>

                                                        </span>
                                                    </label>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            </div>
                                        </div>

                                        <div x-show="repeatRule !== 'none'" x-cloak class="rounded-[0.95rem] border px-4 py-3 text-sm" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.02); color: var(--theme-muted-text-color);">
                                            <span x-show="repeatRule === 'weekday'"><?php echo e(__('This will repeat each selected time slot on weekdays until the chosen end date.')); ?></span>
                                            <span x-show="repeatRule === 'weekly_custom'"><?php echo e(__('This will repeat each selected time slot on the chosen weekdays until the chosen end date.')); ?></span>
                                        </div>

                                        <div
                                            x-show="repeatRule !== 'none' && recurringPreviewText(repeatRule, $wire.composer?.repeat_until || '', repeatDays || []).trim() !== ''"
                                            x-cloak
                                            class="rounded-[0.95rem] border px-4 py-3 text-sm font-medium"
                                            style="border-color: rgba(var(--theme-accent-rgb), 0.18); background-color: rgba(var(--theme-accent-rgb), 0.05); color: var(--theme-header-text-color);"
                                        >
                                            <span x-text="recurringPreviewText(repeatRule, $wire.composer?.repeat_until || '', repeatDays || [])"></span>
                                        </div>

                                        <div class="flex justify-start">
                                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xOn:click' => 'addLocalScheduleSlot()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-on:click' => 'addLocalScheduleSlot()']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                            <i class="fa-light fa-plus"></i>
                                            <?php echo e(__('Add time slot')); ?>

                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        </div>

                    <div class="sticky bottom-0 z-20 border-t px-5 py-4 backdrop-blur-xl" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 96%, transparent);">
                        <div class="mx-auto flex w-full max-w-[68rem] items-center justify-between gap-4">
                            <div class="hidden text-sm xl:block" style="color: var(--theme-muted-text-color);">
                                <p x-show="$wire.composer?.schedule_mode !== 'immediately'" x-cloak><?php echo e(__('Publishing items are saved into the post queue and scheduled to the selected channels.')); ?></p>
                                <p x-show="$wire.composer?.schedule_mode === 'immediately'" x-cloak><?php echo e(__('Publishing items are sent to the selected channels immediately after you confirm.')); ?></p>
                            </div>

                            <div class="flex items-center gap-3">
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'outline','xBind:disabled' => 'composerClosing || composerSavingAction !== \'\'','xOn:click' => 'closeComposerLocal()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline','x-bind:disabled' => 'composerClosing || composerSavingAction !== \'\'','x-on:click' => 'closeComposerLocal()']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <span class="inline-flex items-center gap-2" x-show="!composerClosing">
                                        <?php echo e(__('Cancel')); ?>

                                    </span>
                                    <span class="inline-flex items-center gap-2" x-cloak x-show="composerClosing">
                                        <i class="fa-light fa-loader animate-spin"></i>
                                        <?php echo e(__('Loading...')); ?>

                                    </span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'secondary','xBind:disabled' => 'composerClosing || composerSavingAction !== \'\'','xOn:click' => 'saveComposerLocal(\'draft\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'secondary','x-bind:disabled' => 'composerClosing || composerSavingAction !== \'\'','x-on:click' => 'saveComposerLocal(\'draft\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <span class="inline-flex items-center gap-2" x-show="composerSavingAction !== 'draft'">
                                        <?php echo e(__('Save Draft')); ?>

                                    </span>
                                    <span class="inline-flex items-center gap-2" x-cloak x-show="composerSavingAction === 'draft'">
                                        <i class="fa-light fa-loader animate-spin"></i>
                                        <?php echo e(__('Loading...')); ?>

                                    </span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.button','data' => ['type' => 'button','variant' => 'primary','xBind:disabled' => 'composerClosing || composerSavingAction !== \'\' || composerPublishBlocked','xOn:click' => 'saveComposerLocal(\'scheduled\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'primary','x-bind:disabled' => 'composerClosing || composerSavingAction !== \'\' || composerPublishBlocked','x-on:click' => 'saveComposerLocal(\'scheduled\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    <span class="inline-flex items-center gap-2" x-show="composerSavingAction !== 'scheduled'">
                                        <span x-text="$wire.composer?.schedule_mode === 'immediately' ? <?php echo \Illuminate\Support\Js::from(__('Publish Now'))->toHtml() ?> : <?php echo \Illuminate\Support\Js::from(__('Schedule Post'))->toHtml() ?>"></span>
                                    </span>
                                    <span class="inline-flex items-center gap-2" x-cloak x-show="composerSavingAction === 'scheduled'">
                                        <i class="fa-light fa-loader animate-spin"></i>
                                        <?php echo e(__('Loading...')); ?>

                                    </span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <aside class="hidden min-h-0 border-t xl:block xl:col-span-2 2xl:col-span-1 2xl:border-l 2xl:border-t-0" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: rgba(var(--theme-border-color-rgb), 0.03);">
                    <div class="flex h-full min-h-0 flex-col">
                        <div class="border-b px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                            <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Network Preview')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Quick preview based on the selected profile and current caption.')); ?></p>
                        </div>

                        <div class="flex-1 overflow-y-auto px-4 py-4">
                            <?php ($previewAccounts = $composerAccounts); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($previewAccounts->isNotEmpty()): ?>
                                <div x-effect="if (!localSelectedPreviewAccountIds.includes(String(activePreviewAccountId || ''))) setActivePreviewAccount(localSelectedPreviewAccountIds[0] || '')" class="flex w-full justify-center">
                                <div x-show="localSelectedPreviewAccountIds.length > 0" class="w-full">
                                <div x-show="localSelectedPreviewOptions.length > 1" class="mb-4 flex flex-wrap gap-2">
                                    <template x-for="previewOption in visiblePreviewOptions()" :key="'preview-chip-'+previewOption.id">
                                        <button
                                            type="button"
                                            x-on:click="setActivePreviewAccount(previewOption.id)"
                                            data-no-loading
                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-semibold transition"
                                            x-bind:style="activePreviewAccountId === previewOption.id
                                                ? `border-color: transparent; background-color: ${previewOption.providerToneSurface || 'rgba(var(--theme-accent-rgb), 0.14)'}; color: ${previewOption.providerToneText || 'var(--theme-header-text-color)'};`
                                                : 'border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent); color: var(--theme-muted-text-color);'"
                                        >
                                            <span class="relative inline-flex h-6 w-6 items-center justify-center overflow-visible rounded-full border text-[10px] font-semibold" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: rgba(var(--theme-border-color-rgb), 0.04); color: var(--theme-header-text-color);">
                                                <span class="inline-flex h-full w-full items-center justify-center overflow-hidden rounded-full">
                                                    <template x-if="previewOption.avatarUrl">
                                                        <img x-bind:src="previewOption.avatarUrl" x-bind:alt="previewOption.label" class="h-full w-full object-cover">
                                                    </template>
                                                    <template x-if="!previewOption.avatarUrl">
                                                        <span x-text="previewOption.initials"></span>
                                                    </template>
                                                </span>
                                                <template x-if="previewOption.providerIcon">
                                                    <span
                                                        class="absolute -bottom-1 -right-1 inline-flex h-3.5 w-3.5 items-center justify-center rounded-full border text-[8px]"
                                                        x-bind:style="`border-color: rgba(var(--theme-surface-base-rgb,255,255,255), 0.95); background-color: rgba(var(--theme-surface-base-rgb,255,255,255), 0.98); color: ${previewOption.providerToneText || 'var(--theme-muted-text-color)'};`"
                                                    >
                                                        <i x-bind:class="previewOption.providerIcon"></i>
                                                    </span>
                                                </template>
                                            </span>
                                            <span x-text="previewOption.label"></span>
                                        </button>
                                    </template>
                                    <button
                                        type="button"
                                        x-cloak
                                        x-show="!previewOptionsExpanded && hiddenPreviewOptionsCount() > 0"
                                        x-on:click="previewOptionsExpanded = true"
                                        class="inline-flex items-center rounded-full border px-3 py-2 text-xs font-semibold transition hover:bg-slate-900/5"
                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                    >
                                        <span x-text="`+${hiddenPreviewOptionsCount()} <?php echo e(__('more')); ?>`"></span>
                                    </button>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $previewAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $previewAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php ($previewProvider = $providerCardsByKey->get((string) $previewAccount->provider_key, [])); ?>
                                    <?php ($previewView = app(\Modules\AppPublishing\Support\PublishingPreviewRegistry::class)->get((string) $previewAccount->provider_key, 'apppublishing::livewire.partials.network-preview-generic')); ?>
                                    <div
                                        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'desktop-preview-'.e($previewAccount->id).'-'.e($previewAccount->provider_key).''; ?>wire:key="desktop-preview-<?php echo e($previewAccount->id); ?>-<?php echo e($previewAccount->provider_key); ?>"
                                        x-cloak
                                        x-show="String(activePreviewAccountId || '') === '<?php echo e((string) $previewAccount->id); ?>'"
                                        class="flex w-full justify-center"
                                    >
                                        <?php echo $__env->make($previewView, ['composerAccount' => $previewAccount, 'composerProvider' => $previewProvider], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                                <div x-cloak x-show="localSelectedPreviewAccountIds.length === 0" class="flex h-full items-start justify-center pt-[50px]">
                                    <div class="max-w-xs">
                                        <i class="fa-light fa-rectangle-history-circle-user text-3xl" style="color: var(--theme-muted-text-color);"></i>
                                        <p class="mt-4 text-sm leading-7" style="color: var(--theme-muted-text-color);"><?php echo e(__('Choose a channel and enter the post details to render a live preview.')); ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="flex h-full items-start justify-center pt-[50px]">
                                    <div class="max-w-xs">
                                        <i class="fa-light fa-rectangle-history-circle-user text-3xl" style="color: var(--theme-muted-text-color);"></i>
                                        <p class="mt-4 text-sm leading-7" style="color: var(--theme-muted-text-color);"><?php echo e(__('Choose a channel and enter the post details to render a live preview.')); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </aside>
            </div>

            <div
                x-cloak
                x-show="mobileMediaOpen"
                x-transition.opacity
                class="absolute inset-x-0 top-[5.375rem] bottom-[5.25rem] z-20 xl:hidden"
            >
                <div class="absolute inset-0 bg-slate-950/12" x-on:click="mobileMediaOpen = false"></div>

                <div class="absolute inset-x-3 top-3 bottom-3 overflow-hidden rounded-[1.15rem] border shadow-[0_28px_68px_-34px_rgba(15,23,42,0.42)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent);">
                    <div class="flex h-full min-h-0 flex-col">
                        <div class="flex items-center justify-between gap-3 border-b px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                            <div>
                                <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Select media')); ?></p>
                                <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Browse files and attach media to this publishing item.')); ?></p>
                            </div>
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition hover:bg-slate-900/5"
                                style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                x-on:click="mobileMediaOpen = false"
                            >
                                <i class="fa-light fa-xmark"></i>
                            </button>
                        </div>

                        <div class="min-h-0 flex-1 overflow-hidden">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($composerMediaBrowserReady): ?>
                                <?php if (isset($component)) { $__componentOriginal0cc696c009f3b9c0a412f469f41e8522 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0cc696c009f3b9c0a412f469f41e8522 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '0b51bfe2fc1d77e49da71d18651b1f92::ui.media-browser','data' => ['wire:key' => 'composer-media-browser-mobile-'.e((int) ($composer['media_refresh_token'] ?? 0)).'','wire:model.live' => 'composer.media_items','context' => 'portal','layout' => 'library','error' => $errors->first('composer.media_items'),'type' => 'all','multiple' => true,'value' => $composer['media_items'] ?? [],'libraryTitle' => __('Media'),'showLibraryHeader' => false,'frameless' => true,'compactToolbar' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.media-browser'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:key' => 'composer-media-browser-mobile-'.e((int) ($composer['media_refresh_token'] ?? 0)).'','wire:model.live' => 'composer.media_items','context' => 'portal','layout' => 'library','error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('composer.media_items')),'type' => 'all','multiple' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($composer['media_items'] ?? []),'library-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Media')),'show-library-header' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'frameless' => true,'compact-toolbar' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0cc696c009f3b9c0a412f469f41e8522)): ?>
<?php $attributes = $__attributesOriginal0cc696c009f3b9c0a412f469f41e8522; ?>
<?php unset($__attributesOriginal0cc696c009f3b9c0a412f469f41e8522); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0cc696c009f3b9c0a412f469f41e8522)): ?>
<?php $component = $__componentOriginal0cc696c009f3b9c0a412f469f41e8522; ?>
<?php unset($__componentOriginal0cc696c009f3b9c0a412f469f41e8522); ?>
<?php endif; ?>
                            <?php else: ?>
                                <div wire:init="loadComposerMediaBrowser" class="flex h-full min-h-[14rem] items-center justify-center">
                                    <div class="inline-flex items-center gap-2 text-sm font-medium" style="color: var(--theme-muted-text-color);">
                                        <i class="fa-light fa-loader animate-spin"></i>
                                        <span><?php echo e(__('Loading media library...')); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div
                x-cloak
                x-show="mobilePreviewOpen"
                x-transition.opacity
                class="absolute inset-x-0 top-[5.375rem] bottom-[5.25rem] z-20 xl:hidden"
            >
                <div class="absolute inset-0 bg-slate-950/12" x-on:click="mobilePreviewOpen = false"></div>

                <div class="absolute inset-x-3 top-3 bottom-3 overflow-hidden rounded-[1.15rem] border shadow-[0_28px_68px_-34px_rgba(15,23,42,0.42)]" style="border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent);">
                    <div class="flex h-full min-h-0 flex-col">
                        <div class="border-b px-4 py-4" style="border-color: rgba(var(--theme-border-color-rgb), 0.68);">
                            <p class="text-sm font-semibold" style="color: var(--theme-header-text-color);"><?php echo e(__('Network Preview')); ?></p>
                            <p class="mt-1 text-sm" style="color: var(--theme-muted-text-color);"><?php echo e(__('Quick preview based on the selected profile and current caption.')); ?></p>
                        </div>

                        <div class="flex-1 overflow-y-auto px-4 py-4">
                            <?php ($mobilePreviewAccounts = $composerAccounts); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mobilePreviewAccounts->isNotEmpty()): ?>
                                <div x-effect="if (!localSelectedPreviewAccountIds.includes(String(activePreviewAccountId || ''))) setActivePreviewAccount(localSelectedPreviewAccountIds[0] || '')" class="flex w-full justify-center">
                                <div x-show="localSelectedPreviewAccountIds.length > 0" class="w-full">
                                <div x-show="localSelectedPreviewOptions.length > 1" class="mb-4 flex flex-wrap gap-2">
                                    <template x-for="previewOption in visiblePreviewOptions()" :key="'mobile-preview-chip-'+previewOption.id">
                                        <button
                                            type="button"
                                            x-on:click="setActivePreviewAccount(previewOption.id)"
                                            data-no-loading
                                            class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-semibold transition"
                                            x-bind:style="activePreviewAccountId === previewOption.id
                                                ? `border-color: transparent; background-color: ${previewOption.providerToneSurface || 'rgba(var(--theme-accent-rgb), 0.14)'}; color: ${previewOption.providerToneText || 'var(--theme-header-text-color)'};`
                                                : 'border-color: rgba(var(--theme-border-color-rgb), 0.68); background-color: color-mix(in srgb, var(--theme-surface-overlay) 98%, transparent); color: var(--theme-muted-text-color);'"
                                        >
                                            <span class="relative inline-flex h-6 w-6 items-center justify-center overflow-visible rounded-full border text-[10px] font-semibold" style="border-color: rgba(var(--theme-border-color-rgb), 0.5); background-color: rgba(var(--theme-border-color-rgb), 0.04); color: var(--theme-header-text-color);">
                                                <span class="inline-flex h-full w-full items-center justify-center overflow-hidden rounded-full">
                                                    <template x-if="previewOption.avatarUrl">
                                                        <img x-bind:src="previewOption.avatarUrl" x-bind:alt="previewOption.label" class="h-full w-full object-cover">
                                                    </template>
                                                    <template x-if="!previewOption.avatarUrl">
                                                        <span x-text="previewOption.initials"></span>
                                                    </template>
                                                </span>
                                                <template x-if="previewOption.providerIcon">
                                                    <span
                                                        class="absolute -bottom-1 -right-1 inline-flex h-3.5 w-3.5 items-center justify-center rounded-full border text-[8px]"
                                                        x-bind:style="`border-color: rgba(var(--theme-surface-base-rgb,255,255,255), 0.95); background-color: rgba(var(--theme-surface-base-rgb,255,255,255), 0.98); color: ${previewOption.providerToneText || 'var(--theme-muted-text-color)'};`"
                                                    >
                                                        <i x-bind:class="previewOption.providerIcon"></i>
                                                    </span>
                                                </template>
                                            </span>
                                            <span x-text="previewOption.label"></span>
                                        </button>
                                    </template>
                                    <button
                                        type="button"
                                        x-cloak
                                        x-show="!previewOptionsExpanded && hiddenPreviewOptionsCount() > 0"
                                        x-on:click="previewOptionsExpanded = true"
                                        class="inline-flex items-center rounded-full border px-3 py-2 text-xs font-semibold transition hover:bg-slate-900/5"
                                        style="border-color: rgba(var(--theme-border-color-rgb), 0.68); color: var(--theme-muted-text-color);"
                                    >
                                        <span x-text="`+${hiddenPreviewOptionsCount()} <?php echo e(__('more')); ?>`"></span>
                                    </button>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $mobilePreviewAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $previewAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php ($previewProvider = $providerCardsByKey->get((string) $previewAccount->provider_key, [])); ?>
                                    <?php ($previewView = app(\Modules\AppPublishing\Support\PublishingPreviewRegistry::class)->get((string) $previewAccount->provider_key, 'apppublishing::livewire.partials.network-preview-generic')); ?>
                                    <div
                                        <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'mobile-preview-'.e($previewAccount->id).'-'.e($previewAccount->provider_key).''; ?>wire:key="mobile-preview-<?php echo e($previewAccount->id); ?>-<?php echo e($previewAccount->provider_key); ?>"
                                        x-cloak
                                        x-show="String(activePreviewAccountId || '') === '<?php echo e((string) $previewAccount->id); ?>'"
                                        class="flex w-full justify-center"
                                    >
                                        <?php echo $__env->make($previewView, ['composerAccount' => $previewAccount, 'composerProvider' => $previewProvider], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                                <div x-cloak x-show="localSelectedPreviewAccountIds.length === 0" class="flex h-full items-start justify-center pt-[50px]">
                                    <div class="max-w-xs">
                                        <i class="fa-light fa-rectangle-history-circle-user text-3xl" style="color: var(--theme-muted-text-color);"></i>
                                        <p class="mt-4 text-sm leading-7" style="color: var(--theme-muted-text-color);"><?php echo e(__('Choose a channel and enter the post details to render a live preview.')); ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="flex h-full items-start justify-center pt-[50px]">
                                    <div class="max-w-xs">
                                        <i class="fa-light fa-rectangle-history-circle-user text-3xl" style="color: var(--theme-muted-text-color);"></i>
                                        <p class="mt-4 text-sm leading-7" style="color: var(--theme-muted-text-color);"><?php echo e(__('Choose a channel and enter the post details to render a live preview.')); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\DELL\Downloads\Ascend AI\modules\AppPublishing\Providers/../Resources/views/livewire/calendar.blade.php ENDPATH**/ ?>