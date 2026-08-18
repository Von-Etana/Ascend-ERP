<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PosReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExportController
{
    protected function getCompanyInfo(): array
    {
        $logoBase64 = null;

        if (extension_loaded('gd')) {
            $candidatePaths = [
                public_path('img/logo-brand-dark.png'),
                public_path('img/logo-dark.png'),
                public_path('img/logo-brand-light.png'),
                public_path('img/logo-light.png'),
            ];

            if (function_exists('get_option') && get_option('website_logo_brand_dark')) {
                array_unshift($candidatePaths, public_path(get_option('website_logo_brand_dark')));
            }

            foreach ($candidatePaths as $path) {
                if (file_exists($path) && is_readable($path)) {
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $mime = match (strtolower($ext)) {
                        'png' => 'image/png',
                        'jpg', 'jpeg' => 'image/jpeg',
                        'svg' => 'image/svg+xml',
                        default => 'image/png',
                    };
                    $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
                    break;
                }
            }
        }

        return [
            'companyName'    => 'Ascend Systems Nigeria Limited',
            'companyAddress' => 'Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.',
            'companyPhone'   => '+234 811 763 3020',
            'companyEmail'   => 'info@ascendsystems.ng',
            'companyLogo'    => $logoBase64,
        ];
    }

    public function downloadInvoice(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('pdf.invoice', array_merge([
            'invoice' => $invoice,
        ], $this->getCompanyInfo()));

        return $pdf->download('Invoice-'.$invoice->invoice_number.'.pdf');
    }

    public function downloadReceipt(PosReceipt $receipt): Response
    {
        $pdf = Pdf::loadView('pdf.receipt', array_merge([
            'receipt' => $receipt,
        ], $this->getCompanyInfo()));

        return $pdf->download('Receipt-'.$receipt->receipt_number.'.pdf');
    }

    public function downloadDeliveryNote(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('pdf.delivery_note', array_merge([
            'invoice' => $invoice,
        ], $this->getCompanyInfo()));

        return $pdf->download('DeliveryNote-'.$invoice->invoice_number.'.pdf');
    }

    public function downloadPayslip(int $id): Response
    {
        $salaryRecord = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('salary_records')) {
            $record = \Illuminate\Support\Facades\DB::table('salary_records')->where('id', $id)->first();
            if ($record) {
                $salaryRecord = (array) $record;
            }
        }

        if (! $salaryRecord) {
            $salaryRecord = [
                'id' => $id,
                'staff_name' => 'Babatunde Adeleke',
                'role' => 'Senior Software Engineer',
                'department' => 'Engineering & Operations',
                'payroll_period' => date('F Y'),
                'base_salary' => 650000.00,
                'housing' => 162500.00,
                'transport' => 97500.00,
                'allowances' => 50000.00,
                'paye_tax' => 115200.00,
                'pension_employee' => 76800.00,
                'nhf' => 16250.00,
                'bank_name' => 'Access Bank Nigeria',
                'account_number' => '0129481029',
            ];
        }

        $pdf = Pdf::loadView('pdf.payslip', array_merge([
            'salaryRecord' => $salaryRecord,
        ], $this->getCompanyInfo()));

        return $pdf->download('Payslip-'.$id.'-'.date('Ym').'.pdf');
    }

    public function downloadExecutiveReport(): Response
    {
        $pdf = Pdf::loadView('pdf.executive_report', $this->getCompanyInfo());

        return $pdf->download('Executive-Financial-Report-'.date('Y-Q').'.pdf');
    }

    public function downloadQuote(): Response
    {
        $dataStr = request()->query('data');
        if (!$dataStr) {
            abort(400, 'Missing quote data');
        }
        $quote = json_decode(base64_decode($dataStr), true);
        if (!$quote) {
            abort(400, 'Invalid quote data');
        }

        $pdf = Pdf::loadView('pdf.quote', array_merge([
            'quote' => $quote,
        ], $this->getCompanyInfo()));

        return $pdf->download('Quote-'.($quote['id'] ?? 'Draft').'.pdf');
    }

    public function downloadWarrantyCertificate(): Response
    {
        $dataStr = request()->query('data');
        if (!$dataStr) {
            abort(400, 'Missing quote data');
        }
        $quote = json_decode(base64_decode($dataStr), true);
        if (!$quote) {
            abort(400, 'Invalid quote data');
        }

        $pdf = Pdf::loadView('pdf.warranty_certificate', array_merge([
            'quote' => $quote,
        ], $this->getCompanyInfo()));

        return $pdf->download('Warranty-Certificate-'.($quote['id'] ?? 'Solar').'.pdf');
    }

    public function downloadJobCard(): Response
    {
        $dataStr = request()->query('data');
        if (!$dataStr) {
            abort(400, 'Missing quote data');
        }
        $quote = json_decode(base64_decode($dataStr), true);
        if (!$quote) {
            abort(400, 'Invalid quote data');
        }

        $pdf = Pdf::loadView('pdf.job_card', array_merge([
            'quote' => $quote,
        ], $this->getCompanyInfo()));

        return $pdf->download('Field-JobCard-'.($quote['id'] ?? 'Site').'.pdf');
    }
}
