<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quote['id'] ?? 'Draft' }} — Ascend Systems</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 12px; line-height: 1.5; margin: 0; padding: 25px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 18px; margin-bottom: 20px; }
        .company-logo-img { max-height: 48px; width: auto; max-width: 220px; display: block; margin-bottom: 8px; }
        .company-logo-text { font-size: 20px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px; text-transform: uppercase; margin-bottom: 4px; }
        .address { font-size: 11px; color: #475569; margin-top: 3px; }
        .contact-info { font-size: 11px; color: #475569; margin-top: 2px; font-weight: 500; }
        .invoice-title { font-size: 22px; font-weight: 900; text-align: right; color: #0f172a; letter-spacing: 0.5px; }
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
        .badge-pending { background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .terms-box { width: 48%; float: left; margin-top: 15px; padding: 12px; border: 1px dashed #cbd5e1; border-radius: 6px; background-color: #fafafa; font-size: 10px; }
        .footer { margin-top: 90px; border-top: 1px solid #e2e8f0; padding-top: 15px; font-size: 10px; text-align: center; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>
    @php
        $lineItems = $quote['items'] ?? [];
        $clientPhone = $quote['phone'] ?? '';
        $clientEmail = $quote['email'] ?? '';
        $clientAddress = $quote['address'] ?? '';
        $subtotal = (float) ($quote['subtotal'] ?? 0);
        $discountAmount = (float) ($quote['discount_amount'] ?? 0);
        $tax = (float) ($quote['tax'] ?? 0);
        $total = (float) ($quote['total'] ?? 0);
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
                    <div class="invoice-title">PRICE QUOTATION & PROPOSAL</div>
                    <div class="invoice-num">#{{ $quote['id'] ?? 'Draft' }}</div>
                    <div style="margin-top: 6px;">
                        <span class="badge badge-pending">
                            STATUS: {{ strtoupper($quote['status'] ?? 'Draft') }}
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
                    <div class="section-label">Prepared For (Client):</div>
                    <div class="customer-name">{{ $quote['client_name'] ?? 'Prospective Client' }}</div>
                    @if (!empty($clientAddress))
                        <div class="customer-detail"><strong>Address:</strong> {{ $clientAddress }}</div>
                    @endif
                    @if (!empty($clientPhone) || !empty($clientEmail))
                        <div class="customer-detail">
                            @if (!empty($clientPhone)) <strong>Phone:</strong> {{ $clientPhone }} @endif
                            @if (!empty($clientEmail)) &nbsp;|&nbsp; <strong>Email:</strong> {{ $clientEmail }} @endif
                        </div>
                    @endif
                </td>
                <td style="width: 45%; text-align: right;">
                    <div class="customer-detail"><strong>Quotation Date:</strong> {{ isset($quote['created_at']) ? date('F d, Y', strtotime($quote['created_at'])) : date('F d, Y') }}</div>
                    <div class="customer-detail"><strong>Proposal Valid Until:</strong> {{ isset($quote['valid_until']) ? date('F d, Y', strtotime($quote['valid_until'])) : date('F d, Y', strtotime('+14 days')) }}</div>
                    <div class="customer-detail"><strong>Currency:</strong> Nigerian Naira (NGN ₦)</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 15%;">SKU / Code</th>
                <th style="width: 45%;">Item & Scope Description</th>
                <th style="text-align: center; width: 10%;">Qty</th>
                <th style="text-align: right; width: 15%;">Unit Price</th>
                <th style="text-align: right; width: 18%;">Amount (NGN)</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($lineItems) && is_array($lineItems))
                @foreach ($lineItems as $item)
                    @php
                        $qty = (int) ($item['qty'] ?? ($item['quantity'] ?? 1));
                        $price = (float) ($item['unit_price'] ?? 0);
                        $amt = (float) ($item['amount'] ?? ($qty * $price));
                    @endphp
                    <tr>
                        <td style="font-family: monospace; font-size: 10px; font-weight: bold; color: #475569;">{{ $item['sku'] ?? 'GEN-ITEM' }}</td>
                        <td>
                            <div style="font-weight: bold; color: #0f172a;">{{ $item['description'] ?? 'Line Item' }}</div>
                        </td>
                        <td style="text-align: center; font-weight: bold;">{{ $qty }}</td>
                        <td style="text-align: right;">₦{{ number_format($price, 2) }}</td>
                        <td style="text-align: right; font-weight: bold; color: #0f172a;">₦{{ number_format($amt, 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="font-family: monospace; font-size: 10px;">SRV-SOLAR</td>
                    <td>
                        <div style="font-weight: bold; color: #0f172a;">Turnkey Solar Solution Bundle Sizing</div>
                    </td>
                    <td style="text-align: center; font-weight: bold;">1</td>
                    <td style="text-align: right;">₦{{ number_format($total, 2) }}</td>
                    <td style="text-align: right; font-weight: bold; color: #0f172a;">₦{{ number_format($total, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Commercial Terms & Summary Box -->
    <div style="margin-top: 15px;">
        <div class="terms-box">
            <div style="font-weight: bold; color: #0f172a; text-transform: uppercase; margin-bottom: 4px;">Commercial Terms & Execution Guidelines:</div>
            <div style="white-space: pre-wrap; line-height: 1.4;">{{ $quote['notes'] ?? "• Proposal valid for 14 calendar days from date of issue.\n• Turnkey delivery, setup, and engineering commissioning included." }}</div>
        </div>

        <table class="totals-table">
            <tr>
                <td style="color: #64748b;">Gross Proposal Subtotal:</td>
                <td style="text-align: right; font-weight: 600;">₦{{ number_format($subtotal, 2) }}</td>
            </tr>
            @if ($discountAmount > 0)
                <tr style="color: #2563eb;">
                    <td>Commercial Discount Applied:</td>
                    <td style="text-align: right; font-weight: 600;">- ₦{{ number_format($discountAmount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td style="color: #64748b;">VAT (7.5% Standard):</td>
                <td style="text-align: right; font-weight: 600;">₦{{ number_format($tax, 2) }}</td>
            </tr>
            <tr style="font-size: 14px; font-weight: bold; border-top: 2px solid #2563eb;">
                <td style="color: #2563eb; padding-top: 6px;">Grand Quoted Total:</td>
                <td style="text-align: right; color: #2563eb; padding-top: 6px;">₦{{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <strong>{{ $companyName }}</strong> &nbsp;|&nbsp; Commercial Price Quotation & Project Proposal<br>
        Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.<br>
        Phone: {{ $companyPhone }} &nbsp;|&nbsp; Email: {{ $companyEmail }} &nbsp;|&nbsp; Web: www.ascendsystems.ng
    </div>
</body>
</html>
