<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quote {{ $invoice->invoice_number }} — Ascend Systems</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif !important; }
        body { font-family: 'DejaVu Sans', sans-serif !important; color: #1e293b; font-size: 12px; line-height: 1.5; margin: 0; padding: 25px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 18px; margin-bottom: 20px; }
        .company-logo-img { max-height: 48px; width: auto; max-width: 220px; display: block; margin-bottom: 8px; }
        .company-logo-text { font-size: 20px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px; text-transform: uppercase; margin-bottom: 4px; }
        .address { font-size: 11px; color: #475569; margin-top: 3px; }
        .contact-info { font-size: 11px; color: #475569; margin-top: 2px; font-weight: 500; }
        .invoice-title { font-size: 28px; font-weight: 900; text-align: right; color: #0f172a; letter-spacing: 1px; }
        .invoice-num { font-size: 13px; font-weight: bold; text-align: right; color: #2563eb; margin-top: 2px; }
        .details-box { width: 100%; margin-top: 15px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; background-color: #f8fafc; }
        .details-table { width: 100%; }
        .details-table td { vertical-align: top; }
        .section-label { font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .customer-name { font-size: 15px; font-weight: 800; color: #0f172a; }
        .customer-detail { font-size: 11px; color: #475569; margin-top: 2px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background-color: #0f172a; color: #ffffff; padding: 9px 10px; text-align: left; font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; }
        .items-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .items-table tr:nth-child(even) td { background-color: #f8fafc; }
        .totals-table { width: 48%; float: right; margin-top: 15px; border-collapse: collapse; }
        .totals-table td { padding: 5px 10px; font-size: 11px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .badge-paid { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-pending { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .bank-box { width: 48%; float: left; margin-top: 15px; padding: 10px; border: 1px dashed #cbd5e1; border-radius: 6px; background-color: #fafafa; font-size: 10px; }
        .footer { margin-top: 90px; border-top: 1px solid #e2e8f0; padding-top: 15px; font-size: 10px; text-align: center; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>
    @php
        $itemsData = is_array($invoice->items) ? $invoice->items : [];
        $lineItems = $itemsData['line_items'] ?? (isset($itemsData[0]) ? $itemsData : []);
        $clientPhone = $itemsData['client_phone'] ?? ($invoice->client_phone ?? '');
        $clientEmail = $itemsData['client_email'] ?? ($invoice->client_email ?? '');
        $clientAddress = $itemsData['client_address'] ?? ($invoice->client_address ?? '');
        $clientTin = $itemsData['client_tin'] ?? ($invoice->client_tin ?? '');
        $promoCode = $itemsData['promo_code'] ?? ($invoice->promo_code ?? '');
        $discountAmount = (float) ($itemsData['discount_amount'] ?? ($invoice->discount_amount ?? 0));
    @endphp

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 55%;">
                    @if (!empty($companyLogo))
                        <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="company-logo-img">
                    @else
                        <div class="company-logo-text">{{ $companyName }}</div>
                    @endif
                    <div style="font-size: 12px; font-weight: bold; color: #0f172a;">{{ $companyName }}</div>
                    <div class="address"><strong>HQ Address:</strong> {{ $companyAddress }}</div>
                    <div class="contact-info"><strong>Call:</strong> {{ $companyPhone }} &nbsp;|&nbsp; <strong>Email:</strong> {{ $companyEmail }}</div>
                </td>
                <td style="width: 45%; text-align: right; vertical-align: top;">
                    <div class="invoice-title">QUOTE</div>
                    <div class="invoice-num">#{{ $invoice->invoice_number }}</div>
                    <div style="margin-top: 6px;">
                        <span class="badge {{ $invoice->status === 'paid' ? 'badge-paid' : 'badge-pending' }}">
                            STATUS: {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Customer Information Card -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td style="width: 55%;">
                    <div class="section-label">Billed To (Client / Customer):</div>
                    <div class="customer-name">{{ $invoice->client_name }}</div>
                    @if (!empty($clientAddress))
                        <div class="customer-detail"><strong>Address:</strong> {{ $clientAddress }}</div>
                    @endif
                    @if (!empty($clientPhone) || !empty($clientEmail))
                        <div class="customer-detail">
                            @if (!empty($clientPhone)) <strong>Phone:</strong> {{ $clientPhone }} @endif
                            @if (!empty($clientEmail)) &nbsp;|&nbsp; <strong>Email:</strong> {{ $clientEmail }} @endif
                        </div>
                    @endif
                    @if (!empty($clientTin))
                        <div class="customer-detail"><strong>Tax ID / TIN:</strong> {{ $clientTin }}</div>
                    @endif
                </td>
                <td style="width: 45%; text-align: right;">
                    <div class="customer-detail"><strong>Quote Date:</strong> {{ $invoice->issue_date?->format('F d, Y') ?: date('F d, Y') }}</div>
                    <div class="customer-detail"><strong>Valid Until:</strong> {{ $invoice->due_date?->format('F d, Y') ?: 'Upon Receipt' }}</div>
                    <div class="customer-detail"><strong>Currency:</strong> Nigerian Naira (&#8358;)</div>
                    @if (!empty($promoCode))
                        <div class="customer-detail" style="color: #2563eb; font-weight: bold; margin-top: 4px;">
                            Promo Code Applied: {{ strtoupper($promoCode) }}
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 15%;">SKU / CODE</th>
                <th style="width: 45%;">ITEM DESCRIPTION</th>
                <th style="text-align: center; width: 10%;">QTY</th>
                <th style="text-align: right; width: 15%;">UNIT PRICE (&#8358;)</th>
                <th style="text-align: right; width: 18%;">AMOUNT (&#8358;)</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($lineItems) && is_array($lineItems))
                @foreach ($lineItems as $item)
                    @php
                        $qty = (float) ($item['quantity'] ?? ($item['qty'] ?? 1));
                        $price = (float) ($item['unit_price'] ?? 0);
                        $discPct = (float) ($item['discount_percent'] ?? 0);
                        $amt = isset($item['amount']) ? (float) $item['amount'] : ($qty * $price * (1 - $discPct / 100));
                        $desc = !empty($item['description']) ? $item['description'] : (!empty($item['name']) ? $item['name'] : 'Line Item');
                        $sku = !empty($item['sku']) ? $item['sku'] : 'GEN-ITEM';
                    @endphp
                    <tr>
                        <td style="font-family: 'DejaVu Sans', monospace; font-size: 10px; font-weight: bold; color: #475569;">{{ $sku }}</td>
                        <td>
                            <div style="font-weight: bold; color: #0f172a;">{{ $desc }}</div>
                            @if ($discPct > 0)
                                <div style="font-size: 10px; color: #16a34a;">Line Discount Applied: {{ $discPct }}% off</div>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: bold;">{{ (int) $qty }}</td>
                        <td style="text-align: right;">&#8358;{{ number_format($price, 2) }}</td>
                        <td style="text-align: right; font-weight: bold; color: #0f172a;">&#8358;{{ number_format($amt, 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="font-family: 'DejaVu Sans', monospace; font-size: 10px;">SRV-SOLAR</td>
                    <td>
                        <div style="font-weight: bold; color: #0f172a;">{{ $invoice->notes ?: 'Solar Power Solution & Systems Package' }}</div>
                    </td>
                    <td style="text-align: center; font-weight: bold;">1</td>
                    <td style="text-align: right;">&#8358;{{ number_format($invoice->subtotal, 2) }}</td>
                    <td style="text-align: right; font-weight: bold; color: #0f172a;">&#8358;{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Bank Details & Summary Box -->
    <div style="margin-top: 15px;">
        <div class="bank-box">
            <div style="font-weight: bold; color: #0f172a; text-transform: uppercase; margin-bottom: 4px;">Bank Transfer Payment Details:</div>
            <div><strong>Bank Name:</strong> Access Bank Nigeria</div>
            <div><strong>Account Name:</strong> Ascend Systems Nigeria Ltd</div>
            <div><strong>Account Number:</strong> 0129481029</div>
            <div style="margin-top: 4px; color: #64748b;">Please use Reference <strong>#{{ $invoice->invoice_number }}</strong> in payment description.</div>
        </div>

        <table class="totals-table">
            <tr>
                <td style="color: #64748b;">Gross Line Subtotal:</td>
                <td style="text-align: right; font-weight: 600;">&#8358;{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if ($discountAmount > 0)
                <tr style="color: #2563eb;">
                    <td>Custom Discount / Promo:</td>
                    <td style="text-align: right; font-weight: 600;">- &#8358;{{ number_format($discountAmount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td style="color: #64748b;">VAT (7.5%):</td>
                <td style="text-align: right; font-weight: 600;">&#8358;{{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr style="font-size: 14px; font-weight: bold; border-top: 2px solid #2563eb;">
                <td style="color: #2563eb; padding-top: 6px;">Total Amount Due:</td>
                <td style="text-align: right; color: #2563eb; padding-top: 6px;">&#8358;{{ number_format($invoice->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <strong>{{ $companyName }}</strong> &nbsp;|&nbsp; Official Quote<br>
        Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.<br>
        Phone: {{ $companyPhone }} &nbsp;|&nbsp; Email: {{ $companyEmail }} &nbsp;|&nbsp; Web: www.ascendsystems.ng
    </div>
</body>
</html>
