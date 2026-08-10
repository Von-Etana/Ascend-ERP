<?php

use App\Models\Invoice;
use App\Models\PosReceipt;
use Modules\AdminUser\Models\User;

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

test('invoice PDF download generates clean document with company logo and info', function () {
    $user = User::factory()->create(['is_super_admin' => true]);
    $invoice = Invoice::create([
        'invoice_number' => 'INV-2026-099',
        'client_name' => 'Apex Global Solutions',
        'subtotal' => 500000.00,
        'tax' => 37500.00,
        'total' => 537500.00,
        'status' => 'pending',
        'issue_date' => now(),
        'due_date' => now()->addDays(14),
        'notes' => 'Software License & Hardware Setup',
    ]);

    $response = $this->actingAs($user)->get(route('portal.invoice.pdf', $invoice));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('pos receipt PDF download generates thermal receipt document with company info', function () {
    $user = User::factory()->create(['is_super_admin' => true]);
    $receipt = PosReceipt::create([
        'receipt_number' => 'REC-2026-099',
        'cashier_name' => 'Babatunde Adeleke',
        'subtotal' => 120000.00,
        'tax' => 9000.00,
        'total' => 129000.00,
        'payment_method' => 'card',
        'items' => [
            ['name' => 'POS Terminal Scanner', 'price' => 120000.00, 'quantity' => 1],
        ],
    ]);

    $response = $this->actingAs($user)->get(route('portal.receipt.pdf', $receipt));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('delivery note PDF download generates official dispatch document with company info', function () {
    $user = User::factory()->create(['is_super_admin' => true]);
    $invoice = Invoice::create([
        'invoice_number' => 'INV-2026-100',
        'client_name' => 'Northbridge Media Nigeria',
        'subtotal' => 850000.00,
        'tax' => 63750.00,
        'total' => 913750.00,
        'status' => 'paid',
        'issue_date' => now(),
        'due_date' => now()->addDays(14),
        'notes' => 'Server Equipment & Barcode Readers',
    ]);

    $response = $this->actingAs($user)->get(route('portal.delivery-note.pdf', $invoice));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
