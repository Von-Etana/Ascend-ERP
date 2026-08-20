<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\CsvExportController;
use App\Http\Controllers\FieldOperationsController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\PublicQuoteController;
use App\Livewire\Portal\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('portal.dashboard')
        : redirect()->route('login');
})->name('home');

// Production Health Check & Monitoring Endpoint
Route::get('api/health', function () {
    $dbOk = false;
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbOk = true;
    } catch (\Throwable) {}

    $cacheOk = false;
    try {
        \Illuminate\Support\Facades\Cache::put('health_check', 1, 10);
        $cacheOk = \Illuminate\Support\Facades\Cache::get('health_check') === 1;
    } catch (\Throwable) {}

    $storageOk = is_writable(storage_path('framework/views')) && is_writable(storage_path('logs'));
    $allOk = $dbOk && $cacheOk && $storageOk;

    return response()->json([
        'status' => $allOk ? 'healthy' : 'degraded',
        'timestamp' => now()->toIso8601String(),
        'app_env' => app()->environment(),
        'database' => $dbOk ? 'connected' : 'error',
        'cache' => $cacheOk ? 'operational' : 'error',
        'storage' => $storageOk ? 'writable' : 'error',
        'version' => '2026.1',
    ], $allOk ? 200 : 503);
})->name('api.health');

// Public Lead Intake & Webhook Endpoint (www.ascendsystems.ng & Embed Widgets)
Route::post('api/leads/capture', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'client_name' => 'required|string|max:255',
        'phone' => 'required|string|max:50',
        'email' => 'nullable|email|max:255',
        'company_name' => 'nullable|string|max:255',
        'city_location' => 'nullable|string|max:100',
        'property_type' => 'nullable|string|max:100',
        'installation_address' => 'nullable|string|max:255',
        'system_interest' => 'nullable|string|max:255',
        'estimated_budget_ngn' => 'nullable|numeric',
        'purchasing_timeline' => 'nullable|string|max:50',
        'financing_preference' => 'nullable|string|max:50',
        'referral_code' => 'nullable|string|max:50',
        'special_notes' => 'nullable|string',
    ]);

    $budget = (float) ($validated['estimated_budget_ngn'] ?? 2000000.00);
    $timeline = (string) ($validated['purchasing_timeline'] ?? 'immediate');
    $system = (string) ($validated['system_interest'] ?? 'Ascend 5.5kVA Hybrid Solar Inverter');
    $city = (string) ($validated['city_location'] ?? 'Abuja');
    $company = (string) ($validated['company_name'] ?? ($validated['client_name'] . ' (' . $city . ' Web Lead)'));

    $aiScore = 75;
    if ($budget >= 5000000) $aiScore += 18;
    elseif ($budget >= 2000000) $aiScore += 10;
    if ($timeline === 'immediate') $aiScore += 10;
    $aiScore = min(99, $aiScore);

    $lead = \App\Models\WebLeadCapture::create([
        'client_name' => $validated['client_name'],
        'company_name' => $company,
        'phone' => $validated['phone'],
        'email' => $validated['email'] ?? 'client@ascendsystems.ng',
        'city_location' => $city,
        'property_type' => $validated['property_type'] ?? 'Residential Villa / Duplex',
        'installation_address' => $validated['installation_address'] ?? ($city . ' Site'),
        'system_interest' => $system,
        'estimated_budget_ngn' => $budget,
        'purchasing_timeline' => $timeline,
        'financing_preference' => $validated['financing_preference'] ?? 'outright',
        'referral_code' => $validated['referral_code'] ?? null,
        'ai_lead_score' => $aiScore,
        'special_notes' => $validated['special_notes'] ?? null,
        'source_url' => $request->header('referer', 'https://www.ascendsystems.ng'),
        'status' => 'new',
    ]);

    $crmLead = \App\Models\CrmLead::create([
        'company_name' => $company,
        'contact_person' => $validated['client_name'],
        'email' => $validated['email'] ?? 'client@ascendsystems.ng',
        'phone' => $validated['phone'],
        'city_location' => $city,
        'system_interest' => $system,
        'deal_value' => $budget,
        'ai_lead_score' => $aiScore,
        'purchasing_timeline' => $timeline,
        'status' => 'new',
        'notes' => "Captured via Public API. System: {$system}, Budget: ₦" . number_format($budget, 2),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Lead captured and queued for instant sales consultation.',
        'lead_id' => $lead->id,
        'ai_lead_score' => $aiScore,
    ], 201);
})->name('api.leads.capture');

// Public B2B Partner, Wholesaler & Distributor Intake Endpoint
Route::post('api/leads/partner-capture', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'client_name' => 'required|string|max:255',
        'job_title' => 'nullable|string|max:255',
        'company_name' => 'required|string|max:255',
        'country' => 'nullable|string|max:100',
        'website' => 'nullable|string|max:255',
        'phone' => 'required|string|max:50',
        'email' => 'required|email|max:255',
        'monthly_sales_volume' => 'nullable|string|max:255',
        'product_interest' => 'nullable|string|max:255',
        'customer_type' => 'nullable|string|max:255',
        'estimated_budget_ngn' => 'nullable|numeric',
        'special_notes' => 'nullable|string',
    ]);

    $monthlyVolume = (string) ($validated['monthly_sales_volume'] ?? '₦20,000,000 - ₦50,000,000 / Month');
    $product = (string) ($validated['product_interest'] ?? 'Ascend Pure Sine Wave Hybrid Solar Inverters');
    $customerType = (string) ($validated['customer_type'] ?? 'Renewable Energy Installer / EPC Contractor');
    $country = (string) ($validated['country'] ?? 'Nigeria');
    $budget = (float) ($validated['estimated_budget_ngn'] ?? 15000000.00);

    $aiScore = 80;
    if (str_contains($monthlyVolume, '50,000,000') || str_contains($monthlyVolume, '100,000,000')) $aiScore += 15;
    elseif (str_contains($monthlyVolume, '20,000,000')) $aiScore += 10;
    if (str_contains(strtolower($customerType), 'distributor') || str_contains(strtolower($customerType), 'epc')) $aiScore += 5;
    $aiScore = min(99, $aiScore);

    $lead = \App\Models\WebLeadCapture::create([
        'lead_type' => 'partner',
        'client_name' => $validated['client_name'],
        'job_title' => $validated['job_title'] ?? 'Procurement / Managing Director',
        'company_name' => $validated['company_name'],
        'country' => $country,
        'website' => $validated['website'] ?? null,
        'phone' => $validated['phone'],
        'email' => $validated['email'],
        'city_location' => $country,
        'property_type' => $customerType,
        'product_interest' => $product,
        'system_interest' => $product,
        'monthly_sales_volume' => $monthlyVolume,
        'customer_type' => $customerType,
        'estimated_budget_ngn' => $budget,
        'purchasing_timeline' => 'immediate',
        'ai_lead_score' => $aiScore,
        'special_notes' => $validated['special_notes'] ?? "Partner Application: {$customerType} in {$country}. Monthly Volume: {$monthlyVolume}",
        'source_url' => $request->header('referer', 'https://www.ascendsystems.ng/partners'),
        'status' => 'new',
    ]);

    $crmLead = \App\Models\CrmLead::create([
        'lead_type' => 'partner',
        'company_name' => $validated['company_name'],
        'contact_person' => $validated['client_name'],
        'job_title' => $validated['job_title'] ?? 'Procurement / Managing Director',
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'website' => $validated['website'] ?? null,
        'city_location' => $country,
        'country' => $country,
        'system_interest' => $product,
        'product_interest' => $product,
        'deal_value' => $budget,
        'monthly_sales_volume' => $monthlyVolume,
        'customer_type' => $customerType,
        'ai_lead_score' => $aiScore,
        'purchasing_timeline' => 'immediate',
        'status' => 'new',
        'notes' => "B2B Partner Intake: {$customerType} ({$country}). Monthly Volume: {$monthlyVolume}. Product Interest: {$product}.",
    ]);

    // High scoring partner leads auto-generate high-priority wholesale distribution deal
    if ($aiScore >= 80) {
        \App\Models\CrmDeal::create([
            'crm_lead_id' => $crmLead->id,
            'deal_name' => 'Wholesale Partnership — ' . $validated['company_name'],
            'stage' => 'proposal',
            'value' => $budget,
            'expected_close' => now()->addDays(14),
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Partner application received and assigned to Wholesale Accounts Director.',
        'lead_id' => $lead->id,
        'ai_lead_score' => $aiScore,
    ], 201);
})->name('api.leads.partner-capture');

// Public Client Quotation Review & E-Signature Portal
Route::get('portal/quote/view', [PublicQuoteController::class, 'showQuote'])->name('portal.quote.public-view');
Route::get('portal/quote/warranty/pdf', [PdfExportController::class, 'downloadWarrantyCertificate'])->name('portal.quote.warranty.pdf');
Route::get('portal/quote/job-card/pdf', [PdfExportController::class, 'downloadJobCard'])->name('portal.quote.job-card.pdf');
Route::get('portal/quote/inspection/pdf', [PdfExportController::class, 'downloadSiteInspection'])->name('portal.quote.inspection.pdf');
Route::get('portal/quote/waybill/pdf', [PdfExportController::class, 'downloadWaybill'])->name('portal.quote.waybill.pdf');
Route::get('portal/field/inspection-commissioning', [FieldOperationsController::class, 'showPortal'])->name('portal.field.portal');

Route::middleware('guest')->prefix('auth/login')->group(function (): void {
    Route::get('/{provider}', [SocialLoginController::class, 'redirect'])
        ->whereIn('provider', ['google', 'facebook', 'x'])
        ->name('auth.social.redirect');
    Route::get('/{provider}/callback', [SocialLoginController::class, 'callback'])
        ->whereIn('provider', ['google', 'facebook', 'x'])
        ->name('auth.social.callback');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('portal/dashboard', Dashboard::class)->name('portal.dashboard');
    Route::get('portal/invoice/{invoice}/pdf', [PdfExportController::class, 'downloadInvoice'])->name('portal.invoice.pdf');
    Route::get('portal/receipt/{receipt}/pdf', [PdfExportController::class, 'downloadReceipt'])->name('portal.receipt.pdf');
    Route::get('portal/delivery-note/{invoice}/pdf', [PdfExportController::class, 'downloadDeliveryNote'])->name('portal.delivery-note.pdf');
    Route::get('portal/payslip/{id}/pdf', [PdfExportController::class, 'downloadPayslip'])->name('portal.payslip.pdf');
    Route::get('portal/reports/executive/pdf', [PdfExportController::class, 'downloadExecutiveReport'])->name('portal.reports.executive.pdf');
    Route::get('portal/quote/pdf', [PdfExportController::class, 'downloadQuote'])->name('portal.quote.pdf');
    Route::get('portal/finance/export-csv', [CsvExportController::class, 'exportInvoices'])->name('portal.finance.export-csv');
    Route::get('portal/crm/export-csv', [CsvExportController::class, 'exportLeads'])->name('portal.crm.export-csv');
    Route::get('portal/inventory/export-csv', [CsvExportController::class, 'exportInventory'])->name('portal.inventory.export-csv');
});

require __DIR__.'/settings.php';
require __DIR__.'/public-storage.php';
