<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PosReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExportController
{
    protected array $companyInfo = [
        'companyName'    => 'Ascend Systems Nigeria Limited',
        'companyAddress' => 'Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.',
        'companyPhone'   => '+234 811 763 3020',
        'companyEmail'   => 'info@ascendsystems.ng',
    ];

    public function downloadInvoice(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('pdf.invoice', array_merge([
            'invoice' => $invoice,
        ], $this->companyInfo));

        return $pdf->download('Invoice-'.$invoice->invoice_number.'.pdf');
    }

    public function downloadReceipt(PosReceipt $receipt): Response
    {
        $pdf = Pdf::loadView('pdf.receipt', array_merge([
            'receipt' => $receipt,
        ], $this->companyInfo));

        return $pdf->download('Receipt-'.$receipt->receipt_number.'.pdf');
    }

    public function downloadDeliveryNote(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('pdf.delivery_note', array_merge([
            'invoice' => $invoice,
        ], $this->companyInfo));

        return $pdf->download('DeliveryNote-'.$invoice->invoice_number.'.pdf');
    }
}
