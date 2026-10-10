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
use Throwable;

#[Title('Ascend Systems')]
class Dashboard extends Component
{
    public string $branch = 'All branches';

    public string $period = 'This month';

    public string $timeframe = 'Daily';

    public ?string $activeModal = null;

    public ?array $modalData = null;

    public array $tasks = [];

    public array $alerts = [];

    public array $activities = [];

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
        try {
            $invoiceTotal = (float) Invoice::where('status', 'paid')->sum('total');
            $posTotal = (float) PosReceipt::sum('total_amount');
            if ($posTotal == 0) {
                $posTotal = (float) PosReceipt::sum('total');
            }
            $totalRevenue = $invoiceTotal + $posTotal;
            $invoiceCount = Invoice::count();
            $posCount = PosReceipt::count();
            $totalOrders = $invoiceCount + $posCount;
        } catch (Throwable) {
            $totalRevenue = 0.0;
            $totalOrders = 0;
        }

        $netRevenue = $totalRevenue * 0.90;
        $avgOrder = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0.0;

        // Live Open Deals & Pipeline
        try {
            $dbDeals = CrmDeal::all();
            $openDealsCount = $dbDeals->where('stage', '!=', 'closed_lost')->count();
            $openDealsValue = (float) $dbDeals->where('stage', '!=', 'closed_lost')->sum('value');
        } catch (Throwable) {
            $dbDeals = collect([]);
            $openDealsCount = 0;
            $openDealsValue = 0.0;
        }

        // Pipeline Stages Configuration
        $pipelineStages = [];
        $stagesConfig = [
            'prospecting' => ['name' => 'Prospecting', 'color' => 'text-blue-600'],
            'qualified' => ['name' => 'Qualified', 'color' => 'text-violet-600'],
            'proposal' => ['name' => 'Proposal', 'color' => 'text-amber-600'],
            'negotiation' => ['name' => 'Negotiation', 'color' => 'text-teal-600'],
            'closed_won' => ['name' => 'Closed won', 'color' => 'text-emerald-600'],
        ];

        foreach ($stagesConfig as $stageKey => $config) {
            $stageDeals = $dbDeals->where('stage', $stageKey);
            $count = $stageDeals->count();
            $amountVal = $stageDeals->sum('value');

            $items = $stageDeals->take(3)->map(fn ($d) => [
                'name' => $d->deal_name,
                'amount' => '₦'.number_format((float) $d->value, 2),
            ])->values()->all();

            $pipelineStages[] = [
                'key' => $stageKey,
                'name' => $config['name'],
                'color' => $config['color'],
                'count' => $count,
                'amount' => '₦'.number_format((float) $amountVal, 2),
                'deals' => $items,
            ];
        }

        // Live Inventory Low Stock Alert
        try {
            $lowStockProducts = InventoryProduct::whereColumn('stock_quantity', '<=', 'reorder_level')
                ->orWhere('stock_quantity', '<', 10)
                ->take(5)
                ->get();
            $lowStockCount = $lowStockProducts->count();
        } catch (Throwable) {
            $lowStockProducts = collect();
            $lowStockCount = 0;
        }

        // Live Tasks Due
        try {
            $dbTasks = ProjectTask::where('status', '!=', 'done')->latest()->take(5)->get();
            $tasksDueCount = ProjectTask::where('status', '!=', 'done')->count();
            $this->tasks = $dbTasks->map(fn($t) => [
                'id' => $t->id,
                'title' => $t->title,
                'due' => $t->due_date?->diffForHumans() ?? 'Scheduled',
                'completed' => (bool) $t->completed,
                'category' => $t->priority ?: 'General',
            ])->toArray();
        } catch (Throwable) {
            $tasksDueCount = 0;
            $this->tasks = [];
        }

        // Dynamic Alerts
        $this->alerts = [];
        if ($lowStockCount > 0) {
            $this->alerts['low_stock'] = [
                'title' => $lowStockCount . ' item(s) are low in stock',
                'severity' => 'amber',
                'type' => 'inventory',
                'items' => $lowStockProducts->map(fn($p) => [
                    'sku' => $p->sku,
                    'name' => $p->name,
                    'stock' => $p->stock_quantity ?? 0,
                    'reorder' => $p->reorder_level ?? 10,
                ])->toArray(),
            ];
        }

        return view(theme_view('livewire.portal.ascend-dashboard', 'app'), [
            'dashboardItems' => user_dashboard_items(auth()->user(), 'main'),
            'socialItems' => user_dashboard_items(auth()->user(), 'main'),
            'totalRevenueFormatted' => '₦'.number_format($totalRevenue, 2),
            'netRevenueFormatted' => '₦'.number_format($netRevenue, 2),
            'avgOrderFormatted' => '₦'.number_format($avgOrder, 2),
            'totalOrdersFormatted' => number_format($totalOrders),
            'openDealsCount' => $openDealsCount,
            'openDealsValueFormatted' => '₦'.number_format($openDealsValue, 2),
            'pipelineStages' => $pipelineStages,
            'lowStockCount' => $lowStockCount,
            'tasksDueCount' => $tasksDueCount,
        ])->layout(theme_view('layouts.app', 'app'), [
            'title' => __('Ascend Systems'),
        ]);
    }
}
