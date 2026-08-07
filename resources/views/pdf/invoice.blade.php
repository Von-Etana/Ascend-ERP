<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 13px; line-height: 1.5; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .company-title { font-size: 24px; font-weight: bold; color: #2563eb; }
        .address { font-size: 11px; color: #64748b; margin-top: 5px; }
        .invoice-title { font-size: 20px; font-weight: bold; text-align: right; color: #0f172a; }
        .details-table { width: 100%; margin-top: 20px; margin-bottom: 30px; }
        .details-table td { vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th { background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 10px; text-align: left; font-size: 11px; text-transform: uppercase; color: #475569; }
        .items-table td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; }
        .totals-table { width: 40%; float: right; margin-top: 20px; }
        .totals-table td { padding: 6px 10px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-paid { background-color: #dcfce7; color: #15803d; }
        .badge-pending { background-color: #fef3c7; color: #b45309; }
        .footer { margin-top: 80px; border-top: 1px solid #e2e8f0; padding-top: 15px; font-size: 10px; text-align: center; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="company-title">{{ $companyName }}</div>
                    <div class="address">{{ $companyAddress }}</div>
                    <div class="address">Email: {{ $companyEmail }} · Phone: {{ $companyPhone }}</div>
                </td>
                <td style="text-align: right;">
                    <div class="invoice-title">INVOICE</div>
                    <div style="font-weight: bold; margin-top: 5px;">#{{ $invoice->invoice_number }}</div>
                    <div style="margin-top: 5px;">
                        <span class="badge {{ $invoice->status === 'paid' ? 'badge-paid' : 'badge-pending' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="details-table">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase;">Billed To:</div>
                <div style="font-size: 15px; font-weight: bold; margin-top: 4px;">{{ $invoice->client_name }}</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div><strong>Issue Date:</strong> {{ $invoice->issue_date?->format('F d, Y') ?: date('F d, Y') }}</div>
                <div><strong>Due Date:</strong> {{ $invoice->due_date?->format('F d, Y') ?: 'Upon Receipt' }}</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount (NGN)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-weight: bold;">{{ $invoice->notes ?: 'Enterprise Software & Services Package' }}</div>
                    <div style="font-size: 11px; color: #64748b;">Provided by Ascend Systems Headquarters, Abuja</div>
                </td>
                <td style="text-align: right; font-weight: bold;">₦{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td style="text-align: right;">₦{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>VAT (7.5%):</td>
            <td style="text-align: right;">₦{{ number_format($invoice->tax, 2) }}</td>
        </tr>
        <tr style="font-size: 15px; font-weight: bold; border-top: 2px solid #2563eb;">
            <td style="color: #2563eb;">Total Due:</td>
            <td style="text-align: right; color: #2563eb;">₦{{ number_format($invoice->total, 2) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <div class="footer">
        Thank you for doing business with Ascend Systems Ltd · Head Office: Abuja HQ, Nigeria.
    </div>
</body>
</html>
