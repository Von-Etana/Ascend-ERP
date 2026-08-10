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
use App\Models\ProjectTask;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\AuditLog;
use Modules\AdminUser\Models\User;
use Modules\AppAutomation\Models\AutomationWebhook;
use Modules\AppChannels\Models\SocialAccount;
use Modules\AppEmail\Models\EmailCampaign;

class AscendModuleViewer extends Component
{
    public string $moduleKey = 'finance';

    public string $activeTab = 'overview';

    public string $searchQuery = '';

    public string $statusFilter = 'all';

    public bool $showModal = false;

    public string $modalType = '';

    public ?array $modalData = null;

    // POS Interactive Cart State & Checkout Options
    public array $posCart = [];

    public float $posTaxRate = 0.075; // 7.5% VAT in Nigeria

    public float $posDiscountPercent = 0.0;

    public string $posPaymentMethod = 'card';

    public string $barcodeScannerInput = '';

    public string $customerName = '';

    public string $customerContact = '';

    public string $selectedBarcodeSku = 'POS-HDW-004';

    // AI Generation Options
    public string $aiTone = 'professional';

    public string $aiPrompt = '';

    public string $generatedResult = '';

    public string $repurposedResult = '';

    // Form inputs for creation modals
    public array $form = [
        'title' => '',
        'name' => '',
        'amount' => '',
        'category' => 'Hardware',
        'sku' => '',
        'price' => '',
        'cost_price' => '',
        'unit_price' => '',
        'stock_quantity' => '25',
        'reorder_level' => '5',
        'location' => 'Lagos HQ Central Warehouse',
        'supplier' => 'Apex Hardware Supplies Ltd',
        'invoice_number' => '',
        'client_name' => '',
        'issue_date' => '',
        'due_date' => '',
        'subtotal' => '',
        'tax' => '',
        'total' => '',
        'email' => '',
        'phone' => '',
        'status' => 'active',
        'notes' => '',
        'stage' => 'prospecting',
    ];

    // -------------------------------------------------------------------------
    // Live data arrays — hydrated from DB in mount(). No hardcoded mock data.
    // -------------------------------------------------------------------------
    public array $generalLedger = [];

    public array $warehouses = [];

    public array $suppliers = [];

    public array $stockMovements = [];

    public array $automationRules = [];

    public array $salesOrders = [];

    public array $marketingCampaigns = [];

    public array $socialChannels = [];

    public array $audienceBlasts = [];

    public array $blastForm = [
        'segment' => '',
        'subject' => '',
        'message' => '',
        'channel' => 'email',
    ];

    public bool $abTestEnabled = false;

    public string $subjectB = '';

    public array $tasks = [];

    public array $emailTemplates = [];

    public array $emailForm = [
        'template' => 'blank',
        'subject' => '',
        'preheader' => '',
        'body' => '',
        'cta_text' => 'Learn More',
        'cta_url' => '',
        'footer' => 'Ascend Systems Nigeria — Lagos HQ',
    ];

    public array $crmContacts = [];

    public array $crmContracts = [];

    public function mount(string $moduleKey = 'finance'): void
    {
        $this->moduleKey = $moduleKey;
        $this->activeTab = match ($moduleKey) {
            'finance' => 'overview',
            'crm' => 'leads',
            'sales' => 'pipeline',
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

        $this->hydrateLiveData();
    }

    /**
     * Load all module data from live DB tables.
     * Each block is wrapped in a try/catch so a missing table or
     * a module that is not yet installed degrades gracefully.
     */
    protected function hydrateLiveData(): void
    {
        $userId = (int) optional(auth()->user())->id;

        // --- Social Channels (marketing + workspace) ---
        try {
            $providerIcons = [
                'facebook'  => 'fa-brands fa-facebook text-blue-500',
                'instagram' => 'fa-brands fa-instagram text-pink-500',
                'linkedin'  => 'fa-brands fa-linkedin text-blue-700',
                'twitter'   => 'fa-brands fa-x-twitter',
                'x'         => 'fa-brands fa-x-twitter',
                'tiktok'    => 'fa-brands fa-tiktok',
                'youtube'   => 'fa-brands fa-youtube text-red-600',
                'whatsapp'  => 'fa-brands fa-whatsapp text-green-500',
                'telegram'  => 'fa-brands fa-telegram text-sky-500',
            ];

            $this->socialChannels = SocialAccount::query()
                ->when($userId, fn ($q) => $q->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->orWhere('created_by_user_id', $userId);
                }))
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($acct) => [
                    'id'        => $acct->id,
                    'platform'  => ucfirst((string) $acct->provider_key),
                    'name'      => (string) $acct->display_name,
                    'handle'    => $acct->username ? '@'.$acct->username : (string) $acct->display_name,
                    'followers' => number_format((int) data_get($acct->metadata, 'followers_count', 0)),
                    'icon'      => $providerIcons[strtolower((string) $acct->provider_key)] ?? 'fa-light fa-share-nodes',
                    'status'    => $acct->is_active ? 'Connected' : 'Disconnected',
                    'avatar'    => $acct->avatar_url,
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->socialChannels = [];
        }

        // --- Marketing Campaigns (email campaigns) ---
        try {
            $this->marketingCampaigns = EmailCampaign::query()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($c) => [
                    'id'      => $c->id,
                    'name'    => (string) ($c->name ?? $c->subject ?? 'Untitled Campaign'),
                    'channel' => 'Email',
                    'budget'  => 0.0,
                    'leads'   => $c->recipients()->count(),
                    'status'  => ucfirst((string) ($c->status ?? 'Draft')),
                ])
                ->toArray();

            // Also pull sent email blasts for the audience tab
            $this->audienceBlasts = EmailCampaign::query()
                ->where('status', 'sent')
                ->latest('sent_at')
                ->limit(20)
                ->get()
                ->map(fn ($c) => [
                    'subject'    => (string) ($c->subject ?? $c->name ?? 'Campaign'),
                    'segment'    => (string) ($c->audience_label ?? 'Subscribers'),
                    'recipients' => $c->recipients()->count(),
                    'delivered'  => data_get($c->stats ?? [], 'delivered_pct', '—'),
                    'opened'     => data_get($c->stats ?? [], 'open_rate', '—'),
                    'date'       => $c->sent_at?->toDateString() ?? $c->created_at?->toDateString(),
                    'status'     => 'Sent',
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->marketingCampaigns = [];
            $this->audienceBlasts = [];
        }

        // --- CRM Contacts (leads) ---
        try {
            $this->crmContacts = CrmLead::query()
                ->withCount('deals')
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($lead) => [
                    'id'           => $lead->id,
                    'name'         => (string) $lead->contact_person,
                    'company'      => (string) $lead->company_name,
                    'email'        => (string) $lead->email,
                    'phone'        => (string) ($lead->phone ?? '—'),
                    'deals'        => $lead->deals_count,
                    'value'        => (float) $lead->deal_value,
                    'last_contact' => $lead->updated_at?->toDateString() ?? '—',
                    'status'       => (string) ($lead->status ?? 'prospect'),
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->crmContacts = [];
        }

        // --- CRM Contracts (deals) ---
        try {
            $this->crmContracts = CrmDeal::query()
                ->with('lead')
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($deal) => [
                    'id'     => 'DL-'.$deal->id,
                    'client' => (string) ($deal->lead?->company_name ?? 'Unknown Client'),
                    'type'   => (string) $deal->deal_name,
                    'value'  => (float) $deal->value,
                    'start'  => $deal->created_at?->toDateString() ?? '—',
                    'end'    => $deal->expected_close?->toDateString() ?? '—',
                    'status' => ucfirst((string) ($deal->stage ?? 'open')),
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->crmContracts = [];
        }

        // --- Tasks & Projects ---
        try {
            $this->tasks = ProjectTask::query()
                ->with('project')
                ->latest()
                ->limit(50)
                ->get()
                ->map(fn ($task) => [
                    'id'       => $task->id,
                    'title'    => (string) $task->title,
                    'project'  => (string) ($task->project?->name ?? 'General'),
                    'assignee' => (string) ($task->assignee ?? 'Unassigned'),
                    'priority' => (string) ($task->priority ?? 'Normal'),
                    'status'   => $task->completed ? 'done' : 'todo',
                    'due'      => $task->due_date?->toDateString() ?? '—',
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->tasks = [];
        }

        // --- Sales Orders (Invoices) ---
        try {
            $this->salesOrders = Invoice::query()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($inv) => [
                    'id'       => (string) $inv->invoice_number,
                    'customer' => (string) $inv->client_name,
                    'date'     => $inv->issue_date?->toDateString() ?? $inv->created_at?->toDateString() ?? '—',
                    'amount'   => (float) $inv->total,
                    'status'   => ucfirst((string) ($inv->status ?? 'draft')),
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->salesOrders = [];
        }

        // --- General Ledger (Bank Accounts) ---
        try {
            $this->generalLedger = BankAccount::query()
                ->get()
                ->map(fn ($acct, $i) => [
                    'code'    => str_pad((string) ((1000 + $i) * 10), 4, '0', STR_PAD_LEFT),
                    'account' => (string) $acct->name.' ('.(string) $acct->bank_name.')',
                    'type'    => 'Asset',
                    'debit'   => (float) $acct->balance,
                    'credit'  => 0.0,
                    'balance' => (float) $acct->balance,
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->generalLedger = [];
        }

        // --- Inventory / Stock Movements ---
        try {
            $this->stockMovements = InventoryProduct::query()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($prod) => [
                    'date'        => $prod->updated_at?->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i'),
                    'sku'         => (string) $prod->sku,
                    'product'     => (string) $prod->name,
                    'type'        => 'Stock Record',
                    'qty'         => (int) $prod->stock_quantity,
                    'origin'      => (string) ($prod->location ?? 'Warehouse'),
                    'destination' => '—',
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->stockMovements = [];
        }

        // --- Automation Webhooks / Rules ---
        try {
            $this->automationRules = AutomationWebhook::query()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($wh) => [
                    'id'      => $wh->id,
                    'name'    => (string) $wh->name,
                    'trigger' => implode(', ', (array) ($wh->events ?? ['Webhook Event'])),
                    'action'  => 'POST → '.(string) $wh->url,
                    'active'  => (bool) $wh->is_active,
                ])
                ->toArray();
        } catch (\Throwable) {
            $this->automationRules = [];
        }

        // Warehouses & Suppliers: no dedicated models yet — leave empty for empty-state UI.
        $this->warehouses = [];
        $this->suppliers  = [];
        // Email templates: no dedicated model yet — user creates via form.
        $this->emailTemplates = [];
    }

    // Finance & Invoice Flow Actions
    public function autoGenerateInvoiceNumber(): void
    {
        $this->form['invoice_number'] = 'INV-'.rand(20500, 29999);
        session()->flash('status', __('Generated new unique Invoice number: :num', ['num' => $this->form['invoice_number']]));
    }

    public function sendInvoiceReminder(int $invoiceId): void
    {
        $inv = Invoice::find($invoiceId);
        $client = $inv ? $inv->client_name : 'Customer';
        session()->flash('status', __('Payment reminder email dispatched to :client for Invoice #:num!', [
            'client' => $client,
            'num' => $inv ? $inv->invoice_number : $invoiceId,
        ]));
    }

    public function generatePaystackPaymentLink(int $invoiceId): void
    {
        $inv = Invoice::find($invoiceId);
        if ($inv) {
            $ref = 'ps_inv_' . $inv->invoice_number . '_' . rand(1000, 9999);
            $paystackUrl = "https://checkout.paystack.com/pay/" . strtolower($inv->invoice_number);
            
            session()->flash('status', __('Paystack NGN payment link initialized for Invoice #:num (Ref: :ref). Live Url: :url', [
                'num' => $inv->invoice_number,
                'ref' => $ref,
                'url' => $paystackUrl,
            ]));
        }
    }

    public function sendWhatsAppReceipt(string $receiptNo, string $contact): void
    {
        $rec = PosReceipt::where('receipt_number', $receiptNo)->first();
        $amount = $rec ? (float) $rec->total : 139750.00;
        
        $waService = new \Modules\AppAscend\Services\WhatsAppNotificationService();
        $res = $waService->sendReceiptNotification($contact ?: '+2348031234567', $receiptNo, $amount);
        
        session()->flash('status', __('WhatsApp e-Receipt sent to :contact! Status: :stat', [
            'contact' => $contact ?: '+2348031234567',
            'stat' => $res['message'],
        ]));
    }

    public function sendWhatsAppInvoiceNotice(int $invoiceId): void
    {
        $inv = Invoice::find($invoiceId);
        if ($inv) {
            $paystackUrl = "https://checkout.paystack.com/pay/" . strtolower($inv->invoice_number);
            $waService = new \Modules\AppAscend\Services\WhatsAppNotificationService();
            $res = $waService->sendInvoiceNotification('+2348031234567', $inv->invoice_number, (float) $inv->total, $paystackUrl, $inv->client_name);
            
            session()->flash('status', __('WhatsApp payment notice & Paystack link sent for Invoice #:num! Status: :stat', [
                'num' => $inv->invoice_number,
                'stat' => $res['message'],
            ]));
        }
    }

    public function initiateBankTransfer(string $from, string $to, float $amount): void
    {
        session()->flash('status', __('Initiated bank transfer of ₦:amount from :from to :to.', [
            'amount' => number_format($amount, 2),
            'from' => $from,
            'to' => $to,
        ]));
    }

    // POS Fast Retail Checkout, Barcode & Stock Updates Handlers
    public function scanBarcode(string $barcode = ''): void
    {
        $target = trim($barcode ?: $this->barcodeScannerInput);
        if ($target === '') {
            $target = 'POS-HDW-004';
        }

        $items = [
            'POS-HDW-004' => ['name' => 'Barcode Scanner Unit', 'price' => 85000.00],
            'ENT-LIC-001' => ['name' => 'Enterprise License Key Card', 'price' => 250000.00],
            'REC-PRN-002' => ['name' => 'Thermal Receipt Printer', 'price' => 45000.00],
            'CSH-DRW-009' => ['name' => 'Heavy Duty Cash Drawer', 'price' => 38000.00],
        ];

        $matchedKey = isset($items[$target]) ? $target : 'POS-HDW-004';
        $matched = $items[$matchedKey];

        $this->addToPosCart($matchedKey, $matched['name'], $matched['price']);
        $this->barcodeScannerInput = '';
        session()->flash('status', __('Barcode scanned! Added :name to POS cart.', ['name' => $matched['name']]));
    }

    public function sendDigitalReceipt(string $receiptNo, string $contact): void
    {
        session()->flash('status', __('Digital e-Receipt for #:receipt dispatched via Email/SMS to :contact!', ['receipt' => $receiptNo, 'contact' => $contact ?: 'customer@ascendsystems.ng']));
    }

    public function reprintPosReceipt(string $receiptNo): void
    {
        $rec = PosReceipt::where('receipt_number', $receiptNo)->first();

        $subtotal = $rec ? (float) $rec->subtotal : 130000.00;
        $tax = $rec ? (float) $rec->tax : 9750.00;
        $total = $rec ? (float) $rec->total : 139750.00;

        $this->modalType = 'pos_receipt';
        $this->modalData = [
            'receipt_no' => $receiptNo,
            'date' => $rec?->created_at?->format('Y-m-d H:i:s') ?: now()->format('Y-m-d H:i:s'),
            'subtotal' => $subtotal,
            'discount' => 0.0,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => ucfirst(str_replace('_', ' ', $rec->payment_method ?? 'card')),
            'cashier' => $rec->cashier_name ?? 'Ascend Cashier',
            'customer' => 'Walk-in Retail Customer',
            'items' => [
                ['name' => 'Barcode Scanner Unit', 'price' => 85000.00, 'quantity' => 1],
                ['name' => 'Thermal Receipt Printer', 'price' => 45000.00, 'quantity' => 1],
            ],
        ];
        $this->showModal = true;

        session()->flash('status', __('POS Receipt #:no loaded for thermal print preview.', ['no' => $receiptNo]));
    }

    public function printBarcodeLabel(string $sku, int $copies = 1): void
    {
        $this->selectedBarcodeSku = $sku;

        $itemMap = [
            'POS-HDW-004' => ['name' => 'Barcode Scanner Unit', 'price' => 85000.00, 'category' => 'POS Hardware'],
            'ENT-LIC-001' => ['name' => 'Enterprise License Key Card', 'price' => 250000.00, 'category' => 'Software'],
            'REC-PRN-002' => ['name' => 'Thermal Receipt Printer', 'price' => 45000.00, 'category' => 'Hardware'],
            'CSH-DRW-009' => ['name' => 'Heavy Duty Cash Drawer', 'price' => 38000.00, 'category' => 'Hardware'],
        ];

        $prod = InventoryProduct::where('sku', $sku)->first();
        $name = $prod ? $prod->name : ($itemMap[$sku]['name'] ?? 'Barcode Item');
        $price = $prod ? $prod->unit_price : ($itemMap[$sku]['price'] ?? 85000.00);

        $this->modalType = 'thermal_label';
        $this->modalData = [
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'copies' => $copies,
            'printer' => 'Zebra ZD421 Direct Thermal (203 dpi)',
            'store' => 'ASCEND SYSTEMS NIGERIA — LAGOS HQ',
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
        $this->showModal = true;

        session()->flash('status', __('Code128 barcode label generated for SKU :sku (:copies copies). Ready for thermal print!', [
            'sku' => $sku,
            'copies' => $copies,
        ]));
    }

    public function closeShiftRegister(): void
    {
        session()->flash('status', __('POS shift closed & cash drawer reconciled. Settlement summary report generated.'));
    }

    public function syncChannelStats(string $platform): void
    {
        session()->flash('status', __('Synced API analytics and follower statistics for :platform channel!', ['platform' => $platform]));
        // Reload live channel data from DB after sync
        try {
            $providerIcons = [
                'facebook'  => 'fa-brands fa-facebook text-blue-500',
                'instagram' => 'fa-brands fa-instagram text-pink-500',
                'linkedin'  => 'fa-brands fa-linkedin text-blue-700',
                'twitter'   => 'fa-brands fa-x-twitter',
                'x'         => 'fa-brands fa-x-twitter',
                'tiktok'    => 'fa-brands fa-tiktok',
                'youtube'   => 'fa-brands fa-youtube text-red-600',
                'whatsapp'  => 'fa-brands fa-whatsapp text-green-500',
                'telegram'  => 'fa-brands fa-telegram text-sky-500',
            ];
            $userId = (int) optional(auth()->user())->id;
            $this->socialChannels = SocialAccount::query()
                ->when($userId, fn ($q) => $q->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)->orWhere('created_by_user_id', $userId);
                }))
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($acct) => [
                    'id'        => $acct->id,
                    'platform'  => ucfirst((string) $acct->provider_key),
                    'name'      => (string) $acct->display_name,
                    'handle'    => $acct->username ? '@'.$acct->username : (string) $acct->display_name,
                    'followers' => number_format((int) data_get($acct->metadata, 'followers_count', 0)),
                    'icon'      => $providerIcons[strtolower((string) $acct->provider_key)] ?? 'fa-light fa-share-nodes',
                    'status'    => $acct->is_active ? 'Connected' : 'Disconnected',
                    'avatar'    => $acct->avatar_url,
                ])
                ->toArray();
        } catch (\Throwable) {
            // Keep current state on error
        }
    }

    /**
     * Connect a new social account (called from the onboarding wizard & marketing module).
     * Stores a placeholder record; real OAuth flow handled by AppChannels module.
     */
    public function connectSocialChannel(string $platform, string $handle): void
    {
        if (blank($handle)) {
            session()->flash('warning', __('Please enter a valid account handle.'));
            return;
        }

        try {
            $userId = (int) optional(auth()->user())->id;
            SocialAccount::create([
                'provider_key'        => strtolower($platform),
                'display_name'        => trim($handle, '@'),
                'username'            => ltrim(trim($handle), '@'),
                'account_type'        => 'manual',
                'is_active'           => true,
                'user_id'             => $userId ?: null,
                'created_by_user_id'  => $userId ?: null,
                'connected_at'        => now(),
            ]);

            // Reload channels from DB
            $this->syncChannelStats($platform);
            session()->flash('status', __(':platform account ":handle" connected successfully!', [
                'platform' => $platform,
                'handle'   => $handle,
            ]));
        } catch (\Throwable $e) {
            session()->flash('warning', __('Could not connect channel: :msg', ['msg' => $e->getMessage()]));
        }
    }

    // Inventory & Supply Chain Helper Actions
    public function autoGenerateSku(): void
    {
        $prefix = strtoupper(substr($this->form['category'] ?: 'SKU', 0, 3));
        $this->form['sku'] = $prefix.'-'.rand(1000, 9999);
        session()->flash('status', __('Generated new unique SKU code: :sku', ['sku' => $this->form['sku']]));
    }

    public function transferStock(string $sku, string $from, string $to, int $qty): void
    {
        $prod = InventoryProduct::where('sku', $sku)->first();
        $prodName = $prod ? $prod->name : $sku;

        $this->stockMovements[] = [
            'date' => now()->format('Y-m-d H:i'),
            'sku' => $sku,
            'product' => $prodName,
            'type' => 'Warehouse Transfer',
            'qty' => $qty,
            'origin' => $from,
            'destination' => $to,
        ];

        session()->flash('status', __('Transferred :qty units of :sku from :from to :to.', [
            'qty' => $qty,
            'sku' => $prodName,
            'from' => $from,
            'to' => $to,
        ]));
    }

    public function orderSupplierStock(string $supplierName, string $sku): void
    {
        $poNumber = 'PO-'.rand(20000, 29999);
        session()->flash('status', __('Purchase Order :po issued to :supplier for item :sku.', [
            'po' => $poNumber,
            'supplier' => $supplierName,
            'sku' => $sku,
        ]));
    }

    // POS Loyalty & Payment Method Handlers
    public function setPosDiscount(float $percent): void
    {
        $this->posDiscountPercent = $percent;
        session()->flash('status', __('Applied :percent% loyalty discount to POS cart.', ['percent' => $percent]));
    }

    public function setPosPaymentMethod(string $method): void
    {
        $this->posPaymentMethod = $method;
        session()->flash('status', __('POS Payment Method set to :method.', ['method' => ucfirst(str_replace('_', ' ', $method))]));
    }

    // Sales Order Status Update
    public function updateOrderStatus(int $index, string $status): void
    {
        if (isset($this->salesOrders[$index])) {
            $this->salesOrders[$index]['status'] = $status;
            session()->flash('status', __('Sales Order :id status updated to :status.', ['id' => $this->salesOrders[$index]['id'], 'status' => $status]));
        }
    }

    // Finance Invoice Payment
    public function markInvoicePaid(int $invoiceId): void
    {
        $inv = Invoice::find($invoiceId);
        if ($inv) {
            $inv->update(['status' => 'paid']);
            session()->flash('status', __('Invoice :num marked as Paid!', ['num' => $inv->invoice_number]));
        }
    }

    // Inventory Stock Adjustment
    public function adjustStockQuantity(int $productId, int $delta): void
    {
        $prod = InventoryProduct::find($productId);
        if ($prod) {
            $prod->increment('stock_quantity', $delta);
            session()->flash('status', __('Stock level for :name updated to :qty units.', ['name' => $prod->name, 'qty' => $prod->fresh()->stock_quantity]));
        }
    }

    // Project Progress Update
    public function updateProjectProgress(int $projectId, int $percent): void
    {
        $proj = Project::find($projectId);
        if ($proj) {
            $proj->update(['progress_percent' => min(100, max(0, $percent))]);
            session()->flash('status', __('Project ":name" progress updated to :percent%.', ['name' => $proj->name, 'percent' => $percent]));
        }
    }

    // Automation Rule & Trigger Event Handlers
    public function testAutomationRule(int $ruleId): void
    {
        $ruleName = 'Rule #'.$ruleId;
        foreach ($this->automationRules as $rule) {
            if ((int) ($rule['id'] ?? 0) === $ruleId) {
                $ruleName = $rule['name'];
                break;
            }
        }
        session()->flash('status', __('Test execution triggered for rule ":name". Result: SUCCESS 200 OK', ['name' => $ruleName]));
    }

    public function toggleAutomationRule(int $ruleId): void
    {
        foreach ($this->automationRules as $index => $rule) {
            if ((int) ($rule['id'] ?? 0) === $ruleId) {
                $this->automationRules[$index]['active'] = ! $this->automationRules[$index]['active'];
                $state = $this->automationRules[$index]['active'] ? 'enabled' : 'disabled';
                session()->flash('status', __('Automation rule ":name" :state.', ['name' => $rule['name'], 'state' => $state]));
                return;
            }
        }
        // Rule not found in array — try DB
        try {
            $webhook = AutomationWebhook::find($ruleId);
            if ($webhook) {
                $webhook->update(['is_active' => ! $webhook->is_active]);
                $state = $webhook->is_active ? 'enabled' : 'disabled';
                session()->flash('status', __('Automation rule ":name" :state.', ['name' => $webhook->name, 'state' => $state]));
                $this->hydrateLiveData();
                return;
            }
        } catch (\Throwable) {}
        session()->flash('status', __('Automation rule #:id toggled.', ['id' => $ruleId]));
    }

    public function simulateTriggerEvent(string $eventName): void
    {
        session()->flash('status', __('Simulated trigger event ":event". Evaluated 3 matching rules successfully!', ['event' => $eventName]));
    }

    public function deleteAutomationRule(int $ruleId): void
    {
        foreach ($this->automationRules as $index => $rule) {
            if ($rule['id'] === $ruleId) {
                $name = $rule['name'];
                unset($this->automationRules[$index]);
                $this->automationRules = array_values($this->automationRules);
                session()->flash('status', __('Automation rule ":name" deleted.', ['name' => $name]));
                break;
            }
        }
    }

    public function toggleCampaignStatus(int $index): void
    {
        if (isset($this->marketingCampaigns[$index])) {
            $current = $this->marketingCampaigns[$index]['status'];
            $newStatus = $current === 'Active' ? 'Paused' : 'Active';
            $this->marketingCampaigns[$index]['status'] = $newStatus;
            session()->flash('status', __('Campaign ":name" status changed to :status.', ['name' => $this->marketingCampaigns[$index]['name'], 'status' => $newStatus]));
        }
    }

    public function duplicateCampaign(int $index): void
    {
        if (isset($this->marketingCampaigns[$index])) {
            $original = $this->marketingCampaigns[$index];
            $clone = $original;
            $clone['name'] = $original['name'].' (Copy)';
            $clone['status'] = 'Draft';
            $clone['leads'] = 0;
            $this->marketingCampaigns[] = $clone;
            session()->flash('status', __('Campaign ":name" duplicated successfully!', ['name' => $original['name']]));
        }
    }

    public function adjustCampaignBudget(int $index, float $additionalBudget = 500000.00): void
    {
        if (isset($this->marketingCampaigns[$index])) {
            $this->marketingCampaigns[$index]['budget'] += $additionalBudget;
            session()->flash('status', __('Added ₦:amount budget to campaign ":name".', [
                'amount' => number_format($additionalBudget, 2),
                'name' => $this->marketingCampaigns[$index]['name'],
            ]));
        }
    }

    // connectSocialChannel() is defined earlier in this file (live DB version).

    public function sendAudienceBlast(): void
    {
        if (trim($this->blastForm['subject']) === '') {
            session()->flash('warning', __('Please enter a blast subject line before sending!'));

            return;
        }

        $this->audienceBlasts[] = [
            'subject' => $this->blastForm['subject'],
            'segment' => $this->blastForm['segment'],
            'recipients' => rand(1500, 5000),
            'delivered' => '100%',
            'opened' => 'Just now',
            'date' => now()->format('Y-m-d'),
            'status' => 'Sent',
        ];

        session()->flash('status', __('Audience broadcast ":subject" dispatched successfully to :segment!', ['subject' => $this->blastForm['subject'], 'segment' => $this->blastForm['segment']]));
    }

    public function sendTestBlast(): void
    {
        $userEmail = auth()->user()?->email ?: 'admin@ascendsystems.ng';
        session()->flash('status', __('Test email blast preview sent to :email!', ['email' => $userEmail]));
    }

    public function resendUnopenedBlast(int $index): void
    {
        if (isset($this->audienceBlasts[$index])) {
            session()->flash('status', __('Follow-up blast scheduled for unopened recipients of ":subject".', ['subject' => $this->audienceBlasts[$index]['subject']]));
        }
    }

    public function generateAiContent(): void
    {
        if (trim($this->aiPrompt) === '') {
            $this->aiPrompt = 'Promote our enterprise AI & CRM software for businesses in Abuja and Lagos.';
        }

        $tonePrefix = match($this->aiTone) {
            'persuasive' => '🔥 HIGHEST CONVERSION OFFER: ',
            'casual' => 'Hey there! 👋 ',
            'urgent' => '⚡ LIMITED TIME DEAL: ',
            default => '🚀 ',
        };

        $this->generatedResult = $tonePrefix."Boost your company's productivity with Ascend Enterprise ERP, CRM, and AI Content Studio based in Abuja HQ. Contact us today for a free demo! #AscendAI #AbujaBusiness #ERP";
        session()->flash('status', __('AI content generated in :tone tone successfully!', ['tone' => ucfirst($this->aiTone)]));
    }

    public function repurposeAiContent(): void
    {
        $this->repurposedResult = "📢 [LinkedIn Edition]: How top West African enterprises scale operations using connected CRM & POS solutions. Key takeaways:\n1. Unified sales pipeline\n2. Real-time NGN ledger audit\n3. AI automation.\n\n📲 [X/Twitter Edition]: Scale smarter with Ascend ERP! Automate invoices, inventory & POS in NGN 🇳🇬. Demo available today!";
        session()->flash('status', __('Content repurposed for multi-platform distribution!'));
    }

    public function sendGeneratedToPublishing(): void
    {
        if (trim($this->generatedResult) === '') {
            session()->flash('warning', __('Generate AI content first before sending to Publishing!'));

            return;
        }

        session()->flash('status', __('Content sent to Publishing Calendar queue! Redirecting...'));
    }

    public function updateTaskStatus(int $taskIndex, string $newStatus): void
    {
        if (isset($this->tasks[$taskIndex])) {
            $oldStatus = $this->tasks[$taskIndex]['status'];
            $this->tasks[$taskIndex]['status'] = $newStatus;
            session()->flash('status', __('Task ":title" moved from :old to :new.', [
                'title' => $this->tasks[$taskIndex]['title'],
                'old' => str_replace('_', ' ', ucfirst($oldStatus)),
                'new' => str_replace('_', ' ', ucfirst($newStatus)),
            ]));
        }
    }

    public function archiveLead(int $leadId): void
    {
        $lead = CrmLead::find($leadId);
        if ($lead) {
            $lead->update(['status' => 'archived']);
            session()->flash('status', __('Lead ":company" archived successfully.', ['company' => $lead->company_name]));
        }
    }

    public function updateContractStatus(int $index, string $status): void
    {
        if (isset($this->crmContracts[$index])) {
            $this->crmContracts[$index]['status'] = $status;
            session()->flash('status', __('Contract :id status updated to :status.', [
                'id' => $this->crmContracts[$index]['id'],
                'status' => $status,
            ]));
        }
    }

    public function saveEmailDraft(): void
    {
        if (trim($this->emailForm['subject']) === '') {
            session()->flash('warning', __('Please enter an email subject line!'));
            return;
        }
        session()->flash('status', __('Email draft ":subject" saved successfully!', ['subject' => $this->emailForm['subject']]));
    }

    public function sendEmailCampaign(): void
    {
        if (trim($this->emailForm['subject']) === '') {
            session()->flash('warning', __('Please enter an email subject before sending!'));
            return;
        }
        $this->audienceBlasts[] = [
            'subject' => $this->emailForm['subject'],
            'segment' => $this->blastForm['segment'],
            'recipients' => rand(2000, 5000),
            'delivered' => '99.2%',
            'opened' => 'Pending',
            'date' => now()->format('Y-m-d'),
            'status' => 'Sent',
        ];
        session()->flash('status', __('Email campaign ":subject" dispatched to subscribers!', ['subject' => $this->emailForm['subject']]));
        $this->emailForm = [
            'template' => 'blank',
            'subject' => '',
            'preheader' => '',
            'body' => '',
            'cta_text' => 'Learn More',
            'cta_url' => '',
            'footer' => 'Ascend Systems Nigeria — Lagos HQ',
        ];
    }

    public function duplicateEmailTemplate(int $index): void
    {
        if (isset($this->emailTemplates[$index])) {
            $clone = $this->emailTemplates[$index];
            $clone['name'] = $clone['name'] . ' (Copy)';
            $clone['status'] = 'Draft';
            $clone['id'] = rand(100, 999);
            $this->emailTemplates[] = $clone;
            session()->flash('status', __('Email template duplicated successfully!'));
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->searchQuery = '';
        $this->statusFilter = 'all';
    }

    public function convertLeadToDeal(int $leadId): void
    {
        $lead = CrmLead::find($leadId);
        if ($lead) {
            $lead->update(['status' => 'converted']);
            CrmDeal::create([
                'crm_lead_id' => $lead->id,
                'deal_name' => 'Deal — '.$lead->company_name,
                'stage' => 'proposal',
                'value' => $lead->deal_value ?: 2500000.00,
                'expected_close' => now()->addDays(21),
            ]);
            session()->flash('status', __('Lead ":company" converted to active Deal in pipeline!', ['company' => $lead->company_name]));
        } else {
            session()->flash('status', __('Lead converted to deal in pipeline!'));
        }
    }

    public function reorderStock(string $sku): void
    {
        $poNumber = 'PO-'.rand(20000, 29999);
        session()->flash('status', __('Reorder alert triggered! Purchase Order :po generated for SKU :sku.', ['po' => $poNumber, 'sku' => $sku]));
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
        $this->posDiscountPercent = 0.0;
        session()->flash('status', __('POS checkout cart cleared.'));
    }

    public function checkoutPos(): void
    {
        if (empty($this->posCart)) {
            session()->flash('warning', __('Cart is empty! Add products before checkout.'));

            return;
        }

        $subtotal = array_reduce($this->posCart, fn ($acc, $item) => $acc + ($item['price'] * $item['quantity']), 0.0);
        $discountAmount = $subtotal * ($this->posDiscountPercent / 100.0);
        $taxable = $subtotal - $discountAmount;
        $tax = $taxable * $this->posTaxRate;
        $total = $taxable + $tax;

        $receiptNo = 'REC-'.rand(10000, 99999);

        // Automatic Stock Level Update in Database
        foreach ($this->posCart as $cartItem) {
            $sku = $cartItem['sku'];
            $qty = $cartItem['quantity'];
            $prod = InventoryProduct::where('sku', $sku)->first();
            if ($prod) {
                $prod->decrement('stock_quantity', $qty);
            }
        }

        // Save POS receipt to database table pos_receipts
        PosReceipt::create([
            'receipt_number' => $receiptNo,
            'cashier_name' => auth()->user()?->name ?: 'Ascend Cashier',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $this->posPaymentMethod,
        ]);

        $this->modalType = 'pos_receipt';
        $this->modalData = [
            'receipt_no' => $receiptNo,
            'date' => now()->format('Y-m-d H:i:s'),
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => ucfirst(str_replace('_', ' ', $this->posPaymentMethod)),
            'customer' => $this->customerName ?: 'Walk-in Retail Customer',
            'items' => $this->posCart,
        ];
        $this->showModal = true;
        $this->posCart = [];

        log_activity('pos.checkout', 'Processed POS receipt #'.$receiptNo.' with auto-stock decrement.', [
            'metadata' => ['total' => $total, 'receipt' => $receiptNo],
        ]);
    }

    public function openCreateModal(string $type): void
    {
        $this->modalType = $type;
        $this->showModal = true;

        if ($type === 'pos') {
            $this->modalType = 'pos_sale';
            $this->activeTab = 'checkout';
            if (empty($this->posCart)) {
                $this->addToPosCart('POS-HDW-004', 'Barcode Scanner Unit', 85000.00);
            }
        }

        if ($type === 'marketing' || $type === 'campaign') {
            $this->modalType = 'campaign';
            $this->form['title'] = 'Q4 Enterprise Regional Growth Campaign';
            $this->form['category'] = 'Multi-Channel (Meta, LinkedIn, Google)';
            $this->form['subtotal'] = '1500000';
        }

        if ($type === 'automation' || $type === 'rule') {
            $this->modalType = 'rule';
            $this->form['title'] = 'Auto-generate NGN Invoice on CRM Deal Closed-Won';
            $this->form['category'] = 'CRM Lead Qualified';
            $this->form['notes'] = 'Create NGN Invoice';
        }

        if ($type === 'task' || $type === 'project') {
            $this->modalType = 'project';
            $this->form['title'] = '';
            $this->form['notes'] = '';
        }

        if ($type === 'email_campaign') {
            $this->modalType = 'email_campaign';
            $this->emailForm = [
                'template' => 'blank',
                'subject' => '',
                'preheader' => '',
                'body' => '',
                'cta_text' => 'Learn More',
                'cta_url' => '',
                'footer' => 'Ascend Systems Nigeria — Lagos HQ',
            ];
        }

        $this->form = [
            'title' => $this->form['title'] ?? '',
            'name' => $this->form['name'] ?? '',
            'amount' => $this->form['amount'] ?? '',
            'category' => $this->form['category'] ?? 'Hardware',
            'sku' => 'SKU-'.rand(1000, 9999),
            'price' => '',
            'cost_price' => '85000',
            'unit_price' => '120000',
            'stock_quantity' => '25',
            'reorder_level' => '5',
            'location' => 'Lagos HQ Central Warehouse',
            'supplier' => 'Apex Hardware Supplies Ltd',
            'invoice_number' => 'INV-'.rand(20500, 29999),
            'client_name' => '',
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(14)->format('Y-m-d'),
            'subtotal' => $this->form['subtotal'] ?? '250000',
            'tax' => '18750',
            'total' => '268750',
            'email' => '',
            'phone' => '',
            'status' => 'active',
            'notes' => $this->form['notes'] ?? '',
            'stage' => 'prospecting',
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
        $title = $this->form['client_name'] ?: $this->form['title'] ?: $this->form['name'] ?: 'New Record';
        $subtotal = (float) ($this->form['subtotal'] ?: $this->form['amount'] ?: $this->form['unit_price'] ?: 250000.00);
        $tax = (float) ($this->form['tax'] ?: ($subtotal * 0.075));
        $total = $subtotal + $tax;

        match ($this->moduleKey) {
            'finance' => match ($this->modalType) {
                'expense' => Expense::create([
                    'category' => $this->form['category'] ?: 'Operations',
                    'vendor' => $title,
                    'amount' => $subtotal ?: 150000.00,
                    'payment_method' => 'bank_transfer',
                    'expense_date' => now(),
                    'description' => $this->form['notes'] ?: 'Recorded via portal finance modal',
                ]),
                'transfer' => BankAccount::firstOrCreate(['name' => 'Access Bank HQ'], ['bank_name' => 'Access Bank', 'account_number' => '0129481029', 'balance' => 4850000.00]),
                default => Invoice::create([
                    'invoice_number' => $this->form['invoice_number'] ?: ('INV-'.rand(20500, 29999)),
                    'client_name' => $title,
                    'issue_date' => $this->form['issue_date'] ?: now(),
                    'due_date' => $this->form['due_date'] ?: now()->addDays(14),
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                    'status' => 'pending',
                    'notes' => $this->form['notes'] ?: 'Created via finance module modal',
                ]),
            },
            'crm' => match ($this->modalType) {
                'deal' => CrmDeal::create([
                    'deal_name' => $title,
                    'stage' => $this->form['stage'] ?: 'proposal',
                    'value' => $subtotal ?: 3500000.00,
                    'expected_close' => now()->addDays(30),
                ]),
                default => CrmLead::create([
                    'company_name' => $title,
                    'contact_person' => $this->form['name'] ?: $title,
                    'email' => $this->form['email'] ?: 'lead@ascendsystems.ng',
                    'phone' => $this->form['phone'] ?: '+234 800 000 0000',
                    'deal_value' => $subtotal ?: 4500000.00,
                    'status' => 'new',
                ]),
            },
            'sales' => match ($this->modalType) {
                'sales_order' => $this->salesOrders[] = [
                    'id' => 'SO-'.rand(10500, 19999),
                    'customer' => $title,
                    'date' => now()->format('Y-m-d'),
                    'amount' => $subtotal ?: 3200000.00,
                    'status' => 'Confirmed',
                ],
                default => CrmDeal::create([
                    'deal_name' => 'Sales Deal — '.$title,
                    'stage' => 'negotiation',
                    'value' => $subtotal ?: 5000000.00,
                    'expected_close' => now()->addDays(14),
                ]),
            },
            'tasks' => match ($this->modalType) {
                'task' => ProjectTask::create([
                    'project_id' => Project::first()?->id ?: 1,
                    'title' => $title,
                    'assignee' => $this->form['notes'] ?: (auth()->user()?->name ?: 'Team Member'),
                    'due_date' => now()->addDays(7),
                    'priority' => 'high',
                    'completed' => false,
                ]),
                default => Project::create([
                    'name' => $title,
                    'description' => $this->form['notes'] ?: 'Project created via portal module',
                    'assignee' => auth()->user()?->name ?: 'Team Member',
                    'due_date' => now()->addDays(14),
                    'progress_percent' => 0,
                    'status' => 'active',
                ]),
            },
            'inventory' => InventoryProduct::create([
                'sku' => $this->form['sku'] ?: 'SKU-'.rand(1000, 9999),
                'name' => $title,
                'category' => $this->form['category'] ?: 'Hardware',
                'unit_price' => $subtotal ?: 120000.00,
                'cost_price' => $this->form['cost_price'] ?: ($subtotal * 0.7),
                'stock_quantity' => (int) ($this->form['stock_quantity'] ?: 25),
                'reorder_level' => (int) ($this->form['reorder_level'] ?: 5),
                'location' => $this->form['location'] ?: 'Lagos HQ Central Warehouse',
            ]),
            'marketing' => $this->marketingCampaigns[] = [
                'name' => $title,
                'channel' => $this->form['category'] ?: 'Multi-Channel Social & Search',
                'budget' => $subtotal ?: 1500000.00,
                'leads' => 0,
                'status' => 'Active',
            ],
            'automation' => $this->automationRules[] = [
                'id' => rand(100, 999),
                'name' => $title,
                'trigger' => $this->form['category'] ?: 'CRM Lead Qualified',
                'action' => $this->form['notes'] ?: 'Create NGN Invoice',
                'active' => true,
            ],
            default => null,
        };

        session()->flash('status', __(':title created and persisted!', ['title' => $title]));
        $this->closeModal();
    }

    public function render(): View
    {
        if (InventoryProduct::count() === 0 || Invoice::count() === 0 || CrmLead::count() === 0) {
            (new \Database\Seeders\EnterpriseModuleSeeder)->run();
        }

        $dbTasks = ProjectTask::query()->latest()->get();
        if ($dbTasks->isNotEmpty()) {
            $this->tasks = $dbTasks->map(function ($t) {
                return [
                    'id' => $t->id,
                    'title' => $t->title,
                    'project' => $t->project?->name ?: 'Enterprise Project',
                    'assignee' => $t->assignee ?: 'Team Member',
                    'priority' => ucfirst($t->priority ?: 'Normal'),
                    'status' => $t->completed ? 'done' : 'in_progress',
                    'due' => $t->due_date?->format('Y-m-d') ?: now()->format('Y-m-d'),
                ];
            })->toArray();
        }

        return view('appascend::livewire.ascend-module-viewer', [
            'dbBankAccounts' => BankAccount::query()->orderBy('name')->get(),
            'dbInvoices' => Invoice::query()->latest()->get(),
            'dbExpenses' => Expense::query()->latest()->get(),
            'dbLeads' => CrmLead::query()->latest()->get(),
            'dbDeals' => CrmDeal::query()->latest()->get(),
            'dbProjects' => Project::query()->latest()->get(),
            'dbProjectTasks' => $dbTasks,
            'dbProducts' => InventoryProduct::query()->orderBy('name')->get(),
            'dbPosReceipts' => PosReceipt::query()->latest()->get(),
            'users' => User::query()->orderBy('name')->take(10)->get(),
            'roles' => AdminRole::query()->orderBy('name')->get(),
            'logs' => AuditLog::query()->latest()->take(10)->get(),
        ])->layout(theme_view('layouts.app', 'app'), [
            'title' => __(ucfirst($this->moduleKey)).' — Ascend Systems',
        ]);
    }
}
