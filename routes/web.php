<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\CsvExportController;
use App\Http\Controllers\PdfExportController;
use App\Livewire\Portal\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('portal.dashboard')
        : redirect()->route('login');
})->name('home');

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
    Route::get('portal/finance/export-csv', [CsvExportController::class, 'exportInvoices'])->name('portal.finance.export-csv');
    Route::get('portal/crm/export-csv', [CsvExportController::class, 'exportLeads'])->name('portal.crm.export-csv');
    Route::get('portal/inventory/export-csv', [CsvExportController::class, 'exportInventory'])->name('portal.inventory.export-csv');
});

require __DIR__.'/settings.php';
require __DIR__.'/public-storage.php';
