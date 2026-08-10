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
}
