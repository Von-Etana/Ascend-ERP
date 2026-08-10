<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\AdminUser\Models\User;
use Tests\TestCase;

class HrPayrollPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_payslip_pdf_download_generates_successful_response(): void
    {
        $user = User::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($user)->get(route('portal.payslip.pdf', ['id' => 1]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
