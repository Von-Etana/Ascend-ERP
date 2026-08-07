<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PosReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExportController
{
    public function downloadInvoice(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'companyName' => 'Ascend Systems Ltd',
            'companyAddress' => 'Plot 1042, Constitution Avenue, Central Business District, Abuja HQ, Nigeria',
            'companyPhone' => '+234 9 876 5432 / +234 803 000 1122',
            'companyEmail' => 'admin@ascendsystems.ng',
        ]);

        return $pdf->download('Invoice-'.$invoice->invoice_number.'.pdf');
    }

    public function downloadReceipt(PosReceipt $receipt): Response
    {
        $pdf = Pdf::loadView('pdf.receipt', [
            'receipt' => $receipt,
            'companyName' => 'Ascend Systems Ltd',
            'companyAddress' => 'Abuja HQ Terminal #01 · CBD, Abuja',
        ]);

        return $pdf->download('Receipt-'.$receipt->receipt_number.'.pdf');
    }
}
