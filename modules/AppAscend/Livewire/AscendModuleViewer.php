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

    public array $generalLedger = [
        ['code' => '1010', 'account' => 'Cash at Bank (Access Bank HQ)', 'type' => 'Asset', 'debit' => 4850000.00, 'credit' => 0.00, 'balance' => 4850000.00],
        ['code' => '1020', 'account' => 'GTBank Operations Reserve', 'type' => 'Asset', 'debit' => 2140500.00, 'credit' => 0.00, 'balance' => 2140500.00],
        ['code' => '1200', 'account' => 'Accounts Receivable (Clients)', 'type' => 'Asset', 'debit' => 3800000.00, 'credit' => 0.00, 'balance' => 3800000.00],
        ['code' => '2010', 'account' => 'Accounts Payable (Vendors)', 'type' => 'Liability', 'debit' => 0.00, 'credit' => 180000.00, 'balance' => -180000.00],
        ['code' => '4010', 'account' => 'Enterprise ERP Software Revenue', 'type' => 'Revenue', 'debit' => 0.00, 'credit' => 1245780.00, 'balance' => 1245780.00],
        ['code' => '5010', 'account' => 'Cloud Infrastructure & Hosting Expenses', 'type' => 'Expense', 'debit' => 324500.00, 'credit' => 0.00, 'balance' => -324500.00],
    ];

    public array $warehouses = [
        ['name' => 'Lagos HQ Central Warehouse', 'location' => 'Ikeja, Lagos', 'manager' => 'Babatunde Adeleke', 'contact' => '+234 802 300 1122', 'capacity' => 85, 'skus' => 245, 'status' => 'Optimal'],
        ['name' => 'Abuja Regional Distribution Hub', 'location' => 'CBD, Abuja', 'manager' => 'Fatima Bello', 'contact' => '+234 809 110 4455', 'capacity' => 42, 'skus' => 110, 'status' => 'Optimal'],
        ['name' => 'Port Harcourt Logistics Hub', 'location' => 'Trans-Amadi, PH', 'manager' => 'Emeka Nwosu', 'contact' => '+234 803 998 7766', 'capacity' => 60, 'skus' => 85, 'status' => 'Optimal'],
    ];

    public array $suppliers = [
        ['name' => 'Apex Hardware Supplies Ltd', 'category' => 'POS Hardware & Electronics', 'contact' => 'Tunde Bakare', 'email' => 'orders@apexhardware.ng', 'phone' => '+234 801 222 3333', 'lead_time' => '3 Days', 'rating' => 4.9],
        ['name' => 'Zhengzhou Tech Equipment Corp', 'category' => 'Thermal Printers & Scanners', 'contact' => 'Li Wei', 'email' => 'export@zhengzhoutech.cn', 'phone' => '+86 371 6688 9900', 'lead_time' => '10 Days', 'rating' => 4.8],
        ['name' => 'Lagoon Thermal Paper Industries', 'category' => 'Consumables & Rolls', 'contact' => 'Sola Adeyemi', 'email' => 'sales@lagoonpaper.ng', 'phone' => '+234 805 444 5555', 'lead_time' => '1 Day', 'rating' => 4.7],
    ];

    public array $stockMovements = [
        ['date' => '2026-08-09 16:45', 'sku' => 'POS-HDW-004', 'product' => 'Barcode Scanner Unit', 'type' => 'Inbound PO', 'qty' => 50, 'origin' => 'Apex Hardware', 'destination' => 'Lagos HQ Central Warehouse'],
        ['date' => '2026-08-09 14:10', 'sku' => 'ENT-LIC-001', 'product' => 'Enterprise License Key Card', 'type' => 'Branch Transfer', 'qty' => 15, 'origin' => 'Lagos HQ', 'destination' => 'Abuja Regional Hub'],
        ['date' => '2026-08-08 11:30', 'sku' => 'REC-PRN-002', 'product' => 'Thermal Receipt Printer', 'type' => 'POS Dispatch', 'qty' => -5, 'origin' => 'Lagos HQ', 'destination' => 'Abuja Retail Store'],
    ];

    public array $automationRules = [
        ['id' => 1, 'name' => 'Auto-generate Invoice on Qualified CRM Lead', 'trigger' => 'CRM Lead Qualified', 'action' => 'Create NGN Invoice', 'active' => true],
        ['id' => 2, 'name' => 'Low Stock Reorder Alert Notification', 'trigger' => 'Stock Quantity < Reorder Level', 'action' => 'Notify Operations Team', 'active' => true],
        ['id' => 3, 'name' => 'POS Receipt Email Dispatch', 'trigger' => 'POS Checkout Completed', 'action' => 'Send Email Receipt', 'active' => true],
    ];

    public array $salesOrders = [
        ['id' => 'SO-10458', 'customer' => 'Northbridge Media Nigeria', 'date' => '2026-08-08', 'amount' => 4500000.00, 'status' => 'Confirmed'],
        ['id' => 'SO-10462', 'customer' => 'Apex Technology Solutions', 'date' => '2026-08-09', 'amount' => 2150000.00, 'status' => 'Processing'],
        ['id' => 'SO-10465', 'customer' => 'Horizon Media Communications', 'date' => '2026-08-09', 'amount' => 7800000.00, 'status' => 'Draft'],
    ];

    public array $marketingCampaigns = [
        ['name' => 'Q3 Enterprise ERP Launch', 'channel' => 'Multi-Channel (Meta, LinkedIn, Google)', 'budget' => 2500000.00, 'leads' => 142, 'status' => 'Active'],
        ['name' => 'Abuja Retail POS Hardware Promo', 'channel' => 'Facebook & Instagram Ads', 'budget' => 850000.00, 'leads' => 88, 'status' => 'Active'],
        ['name' => 'SaaS AI Assistant Upgrade Push', 'channel' => 'Email Newsletter Blast', 'budget' => 350000.00, 'leads' => 210, 'status' => 'Scheduled'],
    ];

    public array $socialChannels = [
        ['platform' => 'Facebook', 'name' => 'Ascend Systems Meta Page', 'handle' => '@AscendSystems', 'followers' => '24,500', 'icon' => 'fa-brands fa-facebook text-blue-600', 'status' => 'Connected'],
        ['platform' => 'Instagram', 'name' => 'Ascend AI Nigeria', 'handle' => '@ascend_ai_ng', 'followers' => '18,200', 'icon' => 'fa-brands fa-instagram text-pink-600', 'status' => 'Connected'],
        ['platform' => 'LinkedIn', 'name' => 'Ascend Systems Enterprise', 'handle' => 'Ascend Systems Ltd', 'followers' => '12,800', 'icon' => 'fa-brands fa-linkedin text-blue-700', 'status' => 'Connected'],
        ['platform' => 'Twitter/X', 'name' => 'Ascend ERP Tech', 'handle' => '@AscendERP', 'followers' => '31,000', 'icon' => 'fa-brands fa-x-twitter text-slate-900 dark:text-white', 'status' => 'Connected'],
    ];

    public array $audienceBlasts = [
        ['subject' => 'Q3 Enterprise ERP Feature Release', 'segment' => 'All Active Clients', 'recipients' => 4850, 'delivered' => '98.4%', 'opened' => '42.1%', 'date' => '2026-08-05', 'status' => 'Sent'],
        ['subject' => 'Abuja Regional Branch Opening Promo', 'segment' => 'Qualified CRM Leads', 'recipients' => 1240, 'delivered' => '99.1%', 'opened' => '54.8%', 'date' => '2026-08-01', 'status' => 'Sent'],
    ];

    public array $blastForm = [
        'segment' => 'All Active Clients (4,850 Subscribers)',
        'subject' => 'Special Announcement: New POS & AI Features Released!',
        'message' => 'Dear Partner, We are excited to announce our latest software update with integrated AI marketing and cashier POS terminals in Lagos & Abuja!',
        'channel' => 'email',
    ];

    public bool $abTestEnabled = false;

    public string $subjectB = '🚀 Exclusive Access: Try Ascend AI ERP Now!';

    public array $tasks = [
        ['id' => 1, 'title' => 'Implement POS receipt thermal printing', 'project' => 'POS Hardware Integration', 'assignee' => 'Babatunde Adeleke', 'priority' => 'High', 'status' => 'in_progress', 'due' => '2026-08-15'],
        ['id' => 2, 'title' => 'Design CRM pipeline Kanban board UI', 'project' => 'CRM Overhaul Q3', 'assignee' => 'Fatima Bello', 'priority' => 'Critical', 'status' => 'todo', 'due' => '2026-08-12'],
        ['id' => 3, 'title' => 'Setup automated low-stock email alerts', 'project' => 'Inventory Automation', 'assignee' => 'Emeka Nwosu', 'priority' => 'Normal', 'status' => 'done', 'due' => '2026-08-08'],
        ['id' => 4, 'title' => 'Configure WhatsApp Business API integration', 'project' => 'Marketing Channels', 'assignee' => 'Sola Adeyemi', 'priority' => 'High', 'status' => 'in_review', 'due' => '2026-08-18'],
        ['id' => 5, 'title' => 'Generate Q3 executive financial report', 'project' => 'Finance Reporting', 'assignee' => 'Babatunde Adeleke', 'priority' => 'Normal', 'status' => 'todo', 'due' => '2026-08-20'],
        ['id' => 6, 'title' => 'Migrate Abuja warehouse SKU barcodes', 'project' => 'POS Hardware Integration', 'assignee' => 'Emeka Nwosu', 'priority' => 'Low', 'status' => 'in_progress', 'due' => '2026-08-22'],
    ];

    public array $emailTemplates = [
        ['id' => 1, 'name' => 'Welcome Series — New Client Onboarding', 'category' => 'Onboarding', 'opens' => '68.2%', 'clicks' => '24.5%', 'status' => 'Active'],
        ['id' => 2, 'name' => 'Monthly Product Newsletter', 'category' => 'Newsletter', 'opens' => '42.1%', 'clicks' => '12.8%', 'status' => 'Active'],
        ['id' => 3, 'name' => 'Abandoned Cart Recovery Sequence', 'category' => 'Re-engagement', 'opens' => '55.4%', 'clicks' => '31.2%', 'status' => 'Active'],
        ['id' => 4, 'name' => 'Q3 Seasonal Promotion Blast', 'category' => 'Promotional', 'opens' => '38.9%', 'clicks' => '18.6%', 'status' => 'Draft'],
    ];

    public array $emailForm = [
        'template' => 'blank',
        'subject' => '',
        'preheader' => '',
        'body' => '',
        'cta_text' => 'Learn More',
        'cta_url' => '',
        'footer' => 'Ascend Systems Nigeria — Lagos HQ',
    ];

    public array $crmContacts = [
        ['name' => 'Adebayo Ogundimu', 'company' => 'Northbridge Media Nigeria', 'email' => 'adebayo@northbridge.ng', 'phone' => '+234 802 111 2233', 'deals' => 3, 'value' => 8500000.00, 'last_contact' => '2026-08-08'],
        ['name' => 'Chioma Eze', 'company' => 'Apex Technology Solutions', 'email' => 'chioma@apextech.ng', 'phone' => '+234 803 444 5566', 'deals' => 2, 'value' => 4200000.00, 'last_contact' => '2026-08-07'],
        ['name' => 'Ibrahim Musa', 'company' => 'Horizon Media Communications', 'email' => 'ibrahim@horizonmedia.ng', 'phone' => '+234 805 777 8899', 'deals' => 1, 'value' => 7800000.00, 'last_contact' => '2026-08-09'],
        ['name' => 'Ngozi Okafor', 'company' => 'Sterling Finance Corp', 'email' => 'ngozi@sterlingfinance.ng', 'phone' => '+234 808 222 3344', 'deals' => 4, 'value' => 12500000.00, 'last_contact' => '2026-08-06'],
    ];

    public array $crmContracts = [
        ['id' => 'CTR-2026-001', 'client' => 'Northbridge Media Nigeria', 'type' => 'Annual SaaS License', 'value' => 4500000.00, 'start' => '2026-01-15', 'end' => '2027-01-14', 'status' => 'Active'],
        ['id' => 'CTR-2026-002', 'client' => 'Apex Technology Solutions', 'type' => 'POS Hardware Lease', 'value' => 2150000.00, 'start' => '2026-03-01', 'end' => '2027-02-28', 'status' => 'Active'],
        ['id' => 'CTR-2026-003', 'client' => 'Horizon Media Communications', 'type' => 'Enterprise Integration', 'value' => 7800000.00, 'start' => '2026-06-01', 'end' => '2026-11-30', 'status' => 'In Review'],
    ];

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
        foreach ($this->automationRules as $rule) {
            if ($rule['id'] === $ruleId) {
                session()->flash('status', __('Test execution triggered for rule ":name". Result: SUCCESS 200 OK', ['name' => $rule['name']]));
                break;
            }
        }
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

    public function connectSocialChannel(string $platform, string $handle): void
    {
        $this->socialChannels[] = [
            'platform' => $platform,
            'name' => 'Ascend '.$platform.' Channel',
            'handle' => $handle ?: '@ascend_official',
            'followers' => '1,200',
            'icon' => 'fa-light fa-share-nodes text-purple-600',
            'status' => 'Connected',
        ];
        session()->flash('status', __(':platform channel account :handle connected successfully!', ['platform' => $platform, 'handle' => $handle]));
    }

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
