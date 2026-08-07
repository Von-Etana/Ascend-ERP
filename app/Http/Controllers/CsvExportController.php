<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\InventoryProduct;
use App\Models\Invoice;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportController
{
    public function exportInvoices(): StreamedResponse
    {
        $fileName = 'Ascend_Invoices_Report_'.date('Y-m-d').'.csv';
        $invoices = Invoice::query()->orderBy('id', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return response()->stream(function () use ($invoices): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Invoice Number', 'Client Name', 'Issue Date', 'Due Date', 'Subtotal (NGN)', 'VAT Tax (NGN)', 'Total Amount (NGN)', 'Status', 'Branch / Location']);

            foreach ($invoices as $inv) {
                fputcsv($handle, [
                    $inv->invoice_number,
                    $inv->client_name,
                    $inv->issue_date?->format('Y-m-d') ?: date('Y-m-d'),
                    $inv->due_date?->format('Y-m-d') ?: '',
                    number_format($inv->subtotal, 2, '.', ''),
                    number_format($inv->tax, 2, '.', ''),
                    number_format($inv->total, 2, '.', ''),
                    ucfirst($inv->status),
                    'Abuja HQ',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function exportLeads(): StreamedResponse
    {
        $fileName = 'Ascend_CRM_Leads_'.date('Y-m-d').'.csv';
        $leads = CrmLead::query()->orderBy('id', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return response()->stream(function () use ($leads): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Company Name', 'Contact Person', 'Email', 'Phone', 'Estimated Value (NGN)', 'Status', 'Notes']);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->company_name,
                    $lead->contact_person,
                    $lead->email,
                    $lead->phone,
                    number_format($lead->deal_value, 2, '.', ''),
                    ucfirst($lead->status),
                    $lead->notes,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function exportInventory(): StreamedResponse
    {
        $fileName = 'Ascend_Inventory_Stock_'.date('Y-m-d').'.csv';
        $products = InventoryProduct::query()->orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return response()->stream(function () use ($products): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['SKU', 'Product Name', 'Category', 'Unit Price (NGN)', 'Cost Price (NGN)', 'Stock Qty', 'Reorder Point', 'Warehouse Location']);

            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->sku,
                    $p->name,
                    $p->category,
                    number_format($p->unit_price, 2, '.', ''),
                    number_format($p->cost_price, 2, '.', ''),
                    $p->stock_quantity,
                    $p->reorder_level,
                    $p->location ?: 'Abuja HQ',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
