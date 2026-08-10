<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }} — Ascend Systems</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 13px; line-height: 1.5; margin: 0; padding: 25px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 18px; margin-bottom: 25px; }
        .company-logo { font-size: 22px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px; text-transform: uppercase; }
        .company-sub { font-size: 11px; font-weight: bold; color: #64748b; margin-top: 2px; }
        .address { font-size: 11px; color: #475569; margin-top: 4px; }
        .contact-info { font-size: 11px; color: #475569; margin-top: 2px; font-weight: 500; }
        .invoice-title { font-size: 24px; font-weight: 900; text-align: right; color: #0f172a; tracking: 1px; }
        .invoice-num { font-size: 13px; font-weight: bold; text-align: right; color: #2563eb; margin-top: 4px; }
        .details-table { width: 100%; margin-top: 20px; margin-bottom: 25px; }
        .details-table td { vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background-color: #f8fafc; border-bottom: 2px solid #cbd5e1; padding: 10px 12px; text-align: left; font-size: 10px; text-transform: uppercase; font-weight: 800; color: #475569; letter-spacing: 0.5px; }
        .items-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
        .totals-table { width: 45%; float: right; margin-top: 20px; border-collapse: collapse; }
        .totals-table td { padding: 6px 12px; font-size: 12px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .badge-paid { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-pending { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .footer { margin-top: 90px; border-top: 1px solid #e2e8f0; padding-top: 15px; font-size: 10px; text-align: center; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    <div style="display: inline-block; background-color: #2563eb; color: #ffffff; padding: 6px 12px; border-radius: 8px; font-weight: 900; font-size: 14px; margin-bottom: 6px;">
                        ▲ ASCEND AI SYSTEMS
                    </div>
                    <div class="company-logo">{{ $companyName }}</div>
                    <div class="address"><strong>Location:</strong> {{ $companyAddress }}</div>
                    <div class="contact-info"><strong>Call:</strong> {{ $companyPhone }} &nbsp;|&nbsp; <strong>Mail:</strong> {{ $companyEmail }}</div>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: top;">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-num">#{{ $invoice->invoice_number }}</div>
                    <div style="margin-top: 8px;">
                        <span class="badge {{ $invoice->status === 'paid' ? 'badge-paid' : 'badge-pending' }}">
                            STATUS: {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="details-table">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Billed To (Customer):</div>
                <div style="font-size: 15px; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $invoice->client_name }}</div>
                <div style="font-size: 11px; color: #475569; margin-top: 2px;">Authorized Business Partner / Account</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div><strong>Issue Date:</strong> {{ $invoice->issue_date?->format('F d, Y') ?: date('F d, Y') }}</div>
                <div><strong>Due Date:</strong> {{ $invoice->due_date?->format('F d, Y') ?: 'Upon Receipt' }}</div>
                <div><strong>Payment Currency:</strong> NGN (₦)</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Description / Line Item</th>
                <th style="text-align: center; width: 15%;">Qty</th>
                <th style="text-align: right; width: 17%;">Unit Price</th>
                <th style="text-align: right; width: 18%;">Amount (NGN)</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($invoice->items) && is_array($invoice->items))
                @foreach ($invoice->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight: bold; color: #0f172a; font-size: 12px;">{{ $item['description'] ?? 'Line Item' }}</div>
                        </td>
                        <td style="text-align: center; font-weight: bold; font-size: 12px;">{{ $item['quantity'] ?? 1 }}</td>
                        <td style="text-align: right; font-weight: 500; font-size: 12px;">₦{{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                        <td style="text-align: right; font-weight: bold; font-size: 12px; color: #0f172a;">₦{{ number_format($item['amount'] ?? (($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0)), 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        <div style="font-weight: bold; color: #0f172a; font-size: 13px;">{{ $invoice->notes ?: 'Enterprise Software & Services Package' }}</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Official Billing Invoice from Ascend Systems HQ — Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.</div>
                    </td>
                    <td style="text-align: center; font-weight: bold;">1</td>
                    <td style="text-align: right;">₦{{ number_format($invoice->subtotal, 2) }}</td>
                    <td style="text-align: right; font-weight: bold; font-size: 13px; color: #0f172a;">₦{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td style="color: #64748b;">Subtotal:</td>
            <td style="text-align: right; font-weight: 600;">₦{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td style="color: #64748b;">VAT (7.5%):</td>
            <td style="text-align: right; font-weight: 600;">₦{{ number_format($invoice->tax, 2) }}</td>
        </tr>
        <tr style="font-size: 15px; font-weight: bold; border-top: 2px solid #2563eb;">
            <td style="color: #2563eb; padding-top: 8px;">Total Due:</td>
            <td style="text-align: right; color: #2563eb; padding-top: 8px;">₦{{ number_format($invoice->total, 2) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <div class="footer">
        <strong>{{ $companyName }}</strong><br>
        Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.<br>
        Call: {{ $companyPhone }} &nbsp;|&nbsp; Mail: {{ $companyEmail }}
    </div>
</body>
</html>
