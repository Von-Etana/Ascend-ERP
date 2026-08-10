<?php

namespace App\Livewire\Portal;

use App\Models\CrmDeal;
use App\Models\InventoryProduct;
use App\Models\Invoice;
use App\Models\PosReceipt;
use App\Models\ProjectTask;
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
        // Live Revenue & Order Calculations from DB
        $invoiceTotal = (float) Invoice::sum('total');
        $posTotal = (float) PosReceipt::sum('total_amount');
        $totalRevenue = $invoiceTotal + $posTotal;
        if ($totalRevenue == 0) {
            $totalRevenue = 1245780.00;
        }
        $netRevenue = $totalRevenue * 0.90;

        $invoiceCount = Invoice::count();
        $posCount = PosReceipt::count();
        $totalOrders = $invoiceCount + $posCount;
        if ($totalOrders == 0) {
            $totalOrders = 3248;
        }
        $avgOrder = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 385.40;

        // Live Open Deals & Pipeline
        $dbDeals = CrmDeal::all();
        $openDealsCount = $dbDeals->where('stage', '!=', 'closed_lost')->count();
        if ($openDealsCount == 0) {
            $openDealsCount = 56;
        }
        $openDealsValue = (float) $dbDeals->where('stage', '!=', 'closed_lost')->sum('value');
        if ($openDealsValue == 0) {
            $openDealsValue = 1240000.00;
        }

        // Pipeline Stages Configuration
        $pipelineStages = [];
        $stagesConfig = [
            'prospecting' => ['name' => 'Prospecting', 'color' => 'text-blue-600', 'default_count' => 12, 'default_amount' => 287400],
            'qualified' => ['name' => 'Qualified', 'color' => 'text-violet-600', 'default_count' => 8, 'default_amount' => 415600],
            'proposal' => ['name' => 'Proposal', 'color' => 'text-amber-600', 'default_count' => 6, 'default_amount' => 261300],
            'negotiation' => ['name' => 'Negotiation', 'color' => 'text-teal-600', 'default_count' => 4, 'default_amount' => 198750],
            'closed_won' => ['name' => 'Closed won', 'color' => 'text-emerald-600', 'default_count' => 7, 'default_amount' => 556730],
        ];

        foreach ($stagesConfig as $stageKey => $config) {
            $stageDeals = $dbDeals->where('stage', $stageKey);
            $count = $stageDeals->count() ?: $config['default_count'];
            $amountVal = $stageDeals->count() > 0 ? $stageDeals->sum('value') : $config['default_amount'];

            $items = $stageDeals->take(3)->map(fn ($d) => [
                'name' => $d->deal_name,
                'amount' => '₦'.number_format($d->value, 2),
            ])->values()->all();

            if (empty($items)) {
                $items = [
                    ['name' => 'Northbridge Ltd', 'amount' => '₦45,000.00'],
                    ['name' => 'Brighton Labs', 'amount' => '₦78,400.00'],
                    ['name' => 'Omega Corp', 'amount' => '₦92,000.00'],
                ];
            }

            $pipelineStages[] = [
                'key' => $stageKey,
                'name' => $config['name'],
                'color' => $config['color'],
                'count' => $count,
                'amount' => '₦'.number_format($amountVal, 2),
                'deals' => $items,
            ];
        }

        // Live Inventory Low Stock Alert
        $dbLowStockCount = InventoryProduct::whereColumn('stock_quantity', '<=', 'reorder_level')->orWhere('stock_quantity', '<', 10)->count();
        $lowStockCount = $dbLowStockCount > 0 ? $dbLowStockCount : 23;

        // Live Tasks Due
        $dbTasksCount = ProjectTask::where('status', '!=', 'done')->count();
        $tasksDueCount = $dbTasksCount > 0 ? $dbTasksCount : 19;

        return view(theme_view('livewire.portal.ascend-dashboard', 'app'), [
            'dashboardItems' => user_dashboard_items(auth()->user(), 'main'),
            'socialItems' => user_dashboard_items(auth()->user(), 'main'),
            'totalRevenueFormatted' => '₦'.number_format($totalRevenue, 2),
            'netRevenueFormatted' => '₦'.number_format($netRevenue, 2),
            'avgOrderFormatted' => '₦'.number_format($avgOrder, 2),
            'totalOrdersFormatted' => number_format($totalOrders),
            'openDealsCount' => $openDealsCount,
            'openDealsValueFormatted' => '₦'.number_format($openDealsValue / 1000000, 2).'M',
            'pipelineStages' => $pipelineStages,
            'lowStockCount' => $lowStockCount,
            'tasksDueCount' => $tasksDueCount,
        ])->layout(theme_view('layouts.app', 'app'), [
            'title' => __('Ascend Systems'),
        ]);
    }
}
