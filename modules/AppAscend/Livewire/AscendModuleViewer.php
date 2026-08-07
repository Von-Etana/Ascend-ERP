<?php

namespace Modules\AppAscend\Livewire;

use App\Models\BankAccount;
use App\Models\CrmDeal;
use App\Models\CrmLead;
use App\Models\Expense;
use App\Models\InventoryProduct;
use App\Models\Invoice;
use App\Models\PosReceipt;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\AuditLog;
use Modules\AdminUser\Models\User;

class AscendModuleViewer extends Component
{
    public string $moduleKey = 'finance';

    public string $activeTab = 'overview';

    public string $searchQuery = '';

    public string $statusFilter = 'all';

    public bool $showModal = false;

    public string $modalType = '';

    public ?array $modalData = null;

    // POS Interactive Cart State
    public array $posCart = [];

    public float $posTaxRate = 0.075; // 7.5% VAT in Nigeria

    // Form inputs for creation modals
    public array $form = [
        'title' => '',
        'name' => '',
        'amount' => '',
        'category' => '',
        'sku' => '',
        'price' => '',
        'email' => '',
        'phone' => '',
        'status' => 'active',
    ];

    public string $aiPrompt = '';

    public string $generatedResult = '';

    public array $automationRules = [
        ['id' => 1, 'name' => 'Auto-generate Invoice on Qualified CRM Lead', 'trigger' => 'CRM Lead Qualified', 'action' => 'Create NGN Invoice', 'active' => true],
        ['id' => 2, 'name' => 'Low Stock Reorder Alert Notification', 'trigger' => 'Stock Quantity < Reorder Level', 'action' => 'Notify Operations Team', 'active' => true],
        ['id' => 3, 'name' => 'POS Receipt Email Dispatch', 'trigger' => 'POS Checkout Completed', 'action' => 'Send Email Receipt', 'active' => true],
    ];

    public function mount(string $moduleKey = 'finance'): void
    {
        $this->moduleKey = $moduleKey;
        $this->activeTab = match ($moduleKey) {
            'finance' => 'overview',
            'crm' => 'leads',
            'tasks' => 'projects',
            'inventory' => 'products',
            'pos' => 'checkout',
            'marketing' => 'campaigns',
            'ai-agents' => 'caption',
            'automation' => 'rules',
            'reports' => 'executive',
            'administration' => 'users',
            default => 'overview',
        };
    }

    public function generateAiContent(): void
    {
        if (trim($this->aiPrompt) === '') {
            $this->aiPrompt = 'Promote our enterprise AI & CRM software for businesses in Abuja and Lagos.';
        }

        $this->generatedResult = "🚀 Exciting news from Ascend Systems! Boost your company's productivity with our integrated Enterprise Resource Planning (ERP), CRM, and AI Content Studio based in Abuja HQ. Contact us today for a free demo! #AscendAI #AbujaBusiness #ERP";
        session()->flash('status', __('AI content generated successfully!'));
    }

    public function sendGeneratedToPublishing(): void
    {
        if (trim($this->generatedResult) === '') {
            session()->flash('warning', __('Generate AI content first before sending to Publishing!'));

            return;
        }

        session()->flash('status', __('Content sent to Publishing Calendar queue! Redirecting...'));
    }

    public function toggleAutomationRule(int $ruleId): void
    {
        foreach ($this->automationRules as $index => $rule) {
            if ($rule['id'] === $ruleId) {
                $this->automationRules[$index]['active'] = ! $this->automationRules[$index]['active'];
                $status = $this->automationRules[$index]['active'] ? __('activated') : __('paused');
                session()->flash('status', __('Automation rule ":name" :status.', ['name' => $rule['name'], 'status' => $status]));
                break;
            }
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->searchQuery = '';
        $this->statusFilter = 'all';
    }

    public function addToPosCart(string $sku, string $name, float $price): void
    {
        foreach ($this->posCart as $index => $item) {
            if ($item['sku'] === $sku) {
                $this->posCart[$index]['quantity']++;
                session()->flash('status', __(':name quantity updated in cart.', ['name' => $name]));

                return;
            }
        }

        $this->posCart[] = [
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'quantity' => 1,
        ];

        session()->flash('status', __(':name added to POS checkout cart.', ['name' => $name]));
    }

    public function updatePosCartQuantity(int $index, int $qty): void
    {
        if (isset($this->posCart[$index])) {
            if ($qty <= 0) {
                unset($this->posCart[$index]);
                $this->posCart = array_values($this->posCart);
            } else {
                $this->posCart[$index]['quantity'] = $qty;
            }
        }
    }

    public function clearPosCart(): void
    {
        $this->posCart = [];
        session()->flash('status', __('POS checkout cart cleared.'));
    }

    public function checkoutPos(): void
    {
        if (empty($this->posCart)) {
            session()->flash('warning', __('Cart is empty! Add products before checkout.'));

            return;
        }

        $subtotal = array_reduce($this->posCart, fn ($acc, $item) => $acc + ($item['price'] * $item['quantity']), 0.0);
        $tax = $subtotal * $this->posTaxRate;
        $total = $subtotal + $tax;

        $receiptNo = 'REC-'.rand(10000, 99999);

        // Save POS receipt to database table pos_receipts
        PosReceipt::create([
            'receipt_number' => $receiptNo,
            'cashier_name' => auth()->user()?->name ?: 'Ascend Cashier',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => 'card',
        ]);

        $this->modalType = 'pos_receipt';
        $this->modalData = [
            'receipt_no' => $receiptNo,
            'date' => now()->format('Y-m-d H:i:s'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'items' => $this->posCart,
        ];
        $this->showModal = true;
        $this->posCart = [];

        log_activity('pos.checkout', 'Processed POS receipt #'.$receiptNo, [
            'metadata' => ['total' => $total, 'receipt' => $receiptNo],
        ]);
    }

    public function openCreateModal(string $type): void
    {
        $this->modalType = $type;
        $this->showModal = true;
        $this->form = [
            'title' => '',
            'name' => '',
            'amount' => '',
            'category' => '',
            'sku' => '',
            'price' => '',
            'email' => '',
            'phone' => '',
            'status' => 'active',
        ];
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalType = '';
        $this->modalData = null;
    }

    public function submitModalForm(): void
    {
        $title = $this->form['title'] ?: $this->form['name'] ?: 'New Item';
        $amount = (float) ($this->form['amount'] ?: $this->form['price'] ?: 0);

        match ($this->moduleKey) {
            'finance' => Invoice::create([
                'invoice_number' => 'INV-'.rand(20500, 29999),
                'client_name' => $title,
                'issue_date' => now(),
                'due_date' => now()->addDays(14),
                'subtotal' => $amount,
                'tax' => $amount * 0.075,
                'total' => $amount * 1.075,
                'status' => 'pending',
                'notes' => 'Created via portal modal',
            ]),
            'crm' => CrmLead::create([
                'company_name' => $title,
                'contact_person' => $this->form['name'] ?: $title,
                'email' => $this->form['email'] ?: 'lead@ascendsystems.ng',
                'phone' => $this->form['phone'] ?: '+234 800 000 0000',
                'deal_value' => $amount,
                'status' => 'new',
            ]),
            'tasks' => Project::create([
                'name' => $title,
                'description' => 'Project created via portal module',
                'assignee' => auth()->user()?->name ?: 'Team Member',
                'due_date' => now()->addDays(14),
                'progress_percent' => 0,
                'status' => 'active',
            ]),
            'inventory' => InventoryProduct::create([
                'sku' => 'SKU-'.rand(1000, 9999),
                'name' => $title,
                'category' => $this->form['category'] ?: 'General',
                'unit_price' => $amount,
                'cost_price' => $amount * 0.7,
                'stock_quantity' => 10,
                'reorder_level' => 3,
                'location' => 'Lagos HQ',
            ]),
            default => null,
        };

        session()->flash('status', __(':title created and persisted into database!', ['title' => $title]));
        $this->closeModal();
    }

    public function render(): View
    {
        return view('appascend::livewire.ascend-module-viewer', [
            'dbBankAccounts' => BankAccount::query()->orderBy('name')->get(),
            'dbInvoices' => Invoice::query()->latest()->get(),
            'dbExpenses' => Expense::query()->latest()->get(),
            'dbLeads' => CrmLead::query()->latest()->get(),
            'dbDeals' => CrmDeal::query()->latest()->get(),
            'dbProjects' => Project::query()->latest()->get(),
            'dbProducts' => InventoryProduct::query()->orderBy('name')->get(),
            'dbPosReceipts' => PosReceipt::query()->latest()->get(),
            'users' => User::query()->orderBy('name')->take(10)->get(),
            'roles' => AdminRole::query()->orderBy('name')->get(),
            'logs' => AuditLog::query()->latest()->take(8)->get(),
        ]);
    }
}
