<?php

namespace App\Livewire\Portal;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ascend Systems')]
class Dashboard extends Component
{
    public string $branch = 'All branches';

    public string $period = 'This month';

    public string $timeframe = 'Daily';

    public ?string $activeModal = null;

    public ?array $modalData = null;

    public array $tasks = [
        ['id' => 1, 'title' => 'Follow up with Northbridge Ltd', 'due' => 'Today at 4:00 PM', 'completed' => false, 'category' => 'CRM'],
        ['id' => 2, 'title' => 'Approve purchase order PO-30215', 'due' => 'Tomorrow', 'completed' => false, 'category' => 'Approval'],
        ['id' => 3, 'title' => 'Reconcile bank account', 'due' => 'Friday', 'completed' => false, 'category' => 'Finance'],
    ];

    public array $alerts = [
        'low_stock' => [
            'title' => '23 items are low in stock',
            'severity' => 'amber',
            'type' => 'inventory',
            'items' => [
                ['sku' => 'ENT-LIC-001', 'name' => 'Enterprise Server License', 'stock' => 2, 'reorder' => 10],
                ['sku' => 'POS-HDW-004', 'name' => 'Barcode Scanner Unit', 'stock' => 1, 'reorder' => 5],
                ['sku' => 'SaaS-CRED-100', 'name' => 'AI Generation Credits Pack', 'stock' => 4, 'reorder' => 15],
            ],
        ],
        'po_approval' => [
            'title' => '3 purchase orders need approval',
            'severity' => 'blue',
            'type' => 'purchasing',
            'items' => [
                ['po' => 'PO-30215', 'vendor' => 'TechServe Ltd', 'amount' => '₦1,450,000', 'date' => '2026-08-06'],
                ['po' => 'PO-30218', 'vendor' => 'Lagos Hardware Hub', 'amount' => '₦820,000', 'date' => '2026-08-07'],
                ['po' => 'PO-30221', 'vendor' => 'Cloud Host Solutions', 'amount' => '₦3,100,000', 'date' => '2026-08-07'],
            ],
        ],
        'invoices_overdue' => [
            'title' => '2 invoices are past due',
            'severity' => 'rose',
            'type' => 'finance',
            'items' => [
                ['inv' => 'INV-20410', 'client' => 'Omega Global Systems', 'amount' => '₦2,450,000', 'due_date' => '2026-07-28'],
                ['inv' => 'INV-20415', 'client' => 'Brighton Analytics', 'amount' => '₦1,180,000', 'due_date' => '2026-08-01'],
            ],
        ],
    ];

    public array $activities = [
        'so_created' => [
            'title' => 'Sales order SO-10458 was created',
            'time' => '10 minutes ago',
            'details' => 'Created by Sales Dept for Northbridge Ltd · Total Value: ₦4,500,000',
            'icon' => 'fa-light fa-cart-shopping',
        ],
        'inv_paid' => [
            'title' => 'Invoice INV-20431 was paid',
            'time' => '1 hour ago',
            'details' => 'Payment received via Bank Transfer · Amount: ₦1,245,780',
            'icon' => 'fa-light fa-circle-check',
        ],
        'lead_added' => [
            'title' => 'New lead added: Horizon Media',
            'time' => '3 hours ago',
            'details' => 'Added by Marketing Automation · Potential Deal Size: ₦7,800,000',
            'icon' => 'fa-light fa-user-plus',
        ],
    ];

    public function updatedBranch(): void
    {
        $this->dispatch('ascend-dashboard-filtered');
    }

    public function updatedPeriod(): void
    {
        $this->dispatch('ascend-dashboard-filtered');
    }

    public function setTimeframe(string $timeframe): void
    {
        if (in_array($timeframe, ['Daily', 'Weekly', 'Monthly', 'Yearly'], true)) {
            $this->timeframe = $timeframe;
        }
    }

    public function toggleTask(int $taskId): void
    {
        foreach ($this->tasks as $index => $task) {
            if ($task['id'] === $taskId) {
                $this->tasks[$index]['completed'] = ! $this->tasks[$index]['completed'];
                session()->flash('status', $this->tasks[$index]['completed'] ? __('Task marked as complete!') : __('Task re-opened.'));
                break;
            }
        }
    }

    public function openAlertModal(string $key): void
    {
        if (isset($this->alerts[$key])) {
            $this->activeModal = 'alert';
            $this->modalData = $this->alerts[$key];
        }
    }

    public function openActivityModal(string $key): void
    {
        if (isset($this->activities[$key])) {
            $this->activeModal = 'activity';
            $this->modalData = $this->activities[$key];
        }
    }

    public function closeModal(): void
    {
        $this->activeModal = null;
        $this->modalData = null;
    }

    public function saveLayout(array $itemIds): void
    {
        $payload = Validator::make([
            'item_ids' => $itemIds,
        ], [
            'item_ids' => ['required', 'array'],
            'item_ids.*' => ['string'],
        ])->validate();

        save_user_dashboard_layout(auth()->user(), $payload['item_ids']);
    }

    public function render(): View
    {
        return view(theme_view('livewire.portal.ascend-dashboard', 'app'), [
            'dashboardItems' => user_dashboard_items(auth()->user(), 'main'),
            'socialItems' => user_dashboard_items(auth()->user(), 'main'),
        ])->layout(theme_view('layouts.app', 'app'), [
            'title' => __('Ascend Systems'),
        ]);
    }
}
