<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Quote {{ $quote['id'] ?? ($quote['invoice_number'] ?? 'Official') }} — Ascend Systems</title>
    <style>
        @page {
            margin: 10mm 12mm 18mm 12mm;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
            font-family: 'DejaVu Sans', sans-serif !important;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif !important;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .company-logo-img {
            max-height: 40px;
            width: auto;
            max-width: 190px;
            display: block;
            margin-bottom: 3px;
        }
        .company-logo-text {
            font-size: 18px;
            font-weight: 900;
            color: #2563eb;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .contact-info {
            font-size: 10px;
            color: #475569;
            margin-top: 2px;
            font-weight: 500;
        }
        .quote-title {
            font-size: 24px;
            font-weight: 900;
            text-align: right;
            color: #0f172a;
            letter-spacing: 1px;
        }
        .quote-num {
            font-size: 12px;
            font-weight: bold;
            text-align: right;
            color: #2563eb;
            margin-top: 2px;
        }
        .details-box {
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 7px 10px;
            background-color: #f8fafc;
        }
        .details-table {
            width: 100%;
        }
        .details-table td {
            vertical-align: top;
        }
        .section-label {
            font-size: 9px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .customer-name {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
        }
        .customer-detail {
            font-size: 10px;
            color: #475569;
            margin-top: 1px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .items-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .items-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .badge-pending {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        /* Structured Bottom Layout */
        .bottom-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        .bottom-table td {
            vertical-align: top;
        }
        .terms-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background-color: #f8fafc;
            padding: 7px 9px;
            margin-right: 12px;
        }
        .terms-title {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin-bottom: 4px;
        }
        .terms-content {
            font-size: 8.5px;
            line-height: 1.35;
            color: #334155;
            white-space: pre-wrap;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 4px 6px;
            font-size: 10px;
        }
        .grand-total-row td {
            font-size: 12.5px;
            font-weight: bold;
            color: #1d4ed8;
            background-color: #eff6ff;
            border-top: 2px solid #2563eb;
            padding: 5px 6px;
        }

        /* Fixed Footnote sticking cleanly to Page 1 */
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 34px;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
            font-size: 8.5px;
            text-align: center;
            color: #64748b;
            line-height: 1.3;
        }
    </style>
</head>
<body>
    @php
        $rawItems = $quote['items'] ?? ($quote['line_items'] ?? []);
        if (isset($rawItems['line_items']) && is_array($rawItems['line_items'])) {
            $lineItems = $rawItems['line_items'];
        } else {
            $lineItems = is_array($rawItems) ? $rawItems : [];
        }

        $clientPhone = $quote['phone'] ?? ($quote['client_phone'] ?? '');
        $clientEmail = $quote['email'] ?? ($quote['client_email'] ?? '');
        $clientAddress = $quote['address'] ?? ($quote['client_address'] ?? '');
        $subtotal = (float) ($quote['subtotal'] ?? 0);
        $discountAmount = (float) ($quote['discount_amount'] ?? 0);
        $tax = (float) ($quote['tax'] ?? 0);
        $total = (float) ($quote['total'] ?? 0);
        $quoteId = $quote['id'] ?? ($quote['invoice_number'] ?? 'QT-2026');
        $status = strtolower($quote['status'] ?? '');
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
                    <div class="contact-info"><strong>Call:</strong> {{ $companyPhone }} &nbsp;|&nbsp; <strong>Email:</strong> {{ $companyEmail }}</div>
                </td>
                <td style="width: 45%; text-align: right; vertical-align: top;">
                    <div class="quote-title">QUOTE</div>
                    <div class="quote-num">#{{ $quoteId }}</div>
                    @if (!empty($status) && $status !== 'draft')
                        <div style="margin-top: 3px;">
                            <span class="badge badge-pending">
                                STATUS: {{ strtoupper($quote['status']) }}
                            </span>
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Customer Information Card -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td style="width: 55%;">
                    <div class="section-label">Prepared For:</div>
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
                    <div class="customer-detail"><strong>Quote Date:</strong> {{ isset($quote['created_at']) ? date('F d, Y', strtotime($quote['created_at'])) : date('F d, Y') }}</div>
                    <div class="customer-detail"><strong>Valid Until:</strong> {{ isset($quote['valid_until']) ? date('F d, Y', strtotime($quote['valid_until'])) : date('F d, Y', strtotime('+14 days')) }}</div>
                    <div class="customer-detail"><strong>Currency:</strong> Nigerian Naira (&#8358;)</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 22%;">SERIAL NUMBER</th>
                <th style="width: 40%;">ITEM DESCRIPTION</th>
                <th style="text-align: center; width: 8%;">QTY</th>
                <th style="text-align: right; width: 15%;">UNIT PRICE (&#8358;)</th>
                <th style="text-align: right; width: 15%;">AMOUNT (&#8358;)</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($lineItems) && count($lineItems) > 0)
                @foreach ($lineItems as $item)
                    @php
                        $qty = (int) ($item['qty'] ?? ($item['quantity'] ?? 1));
                        $price = (float) ($item['unit_price'] ?? 0);
                        $amt = (float) ($item['amount'] ?? ($qty * $price));
                        $desc = !empty($item['description']) ? $item['description'] : (!empty($item['name']) ? $item['name'] : 'Line Item');
                        $serialNumber = !empty($item['serial_number']) 
                            ? $item['serial_number'] 
                            : (!empty($item['serial_no']) 
                                ? $item['serial_no'] 
                                : (!empty($item['sku']) ? $item['sku'] : 'SN-'.strtoupper(substr(md5($desc), 0, 8))));
                    @endphp
                    <tr>
                        <td style="font-family: 'DejaVu Sans', monospace; font-size: 9px; font-weight: bold; color: #475569;">{{ $serialNumber }}</td>
                        <td>
                            <div style="font-weight: bold; color: #0f172a;">{{ $desc }}</div>
                        </td>
                        <td style="text-align: center; font-weight: bold;">{{ $qty }}</td>
                        <td style="text-align: right;">&#8358;{{ number_format($price, 2) }}</td>
                        <td style="text-align: right; font-weight: bold; color: #0f172a;">&#8358;{{ number_format($amt, 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="font-family: 'DejaVu Sans', monospace; font-size: 9px; font-weight: bold; color: #475569;">SN-SLR-55KVA-01</td>
                    <td>
                        <div style="font-weight: bold; color: #0f172a;">Solar Power Solution & Systems Package</div>
                    </td>
                    <td style="text-align: center; font-weight: bold;">1</td>
                    <td style="text-align: right;">&#8358;{{ number_format($total, 2) }}</td>
                    <td style="text-align: right; font-weight: bold; color: #0f172a;">&#8358;{{ number_format($total, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Commercial Terms & Summary Box (Structured 2-Column Table) -->
    <table class="bottom-table">
        <tr>
            <td style="width: 55%;">
                <div class="terms-card">
                    <div class="terms-title">TERMS & COMMERCIAL CONDITIONS</div>
                    <div class="terms-content">{{ $quote['notes'] ?? "• Quote validity: 14 calendar days from date of issue.\n• Turnkey delivery, setup, and commissioning included.\n• Payment Terms: Bank Transfer to Ascend Systems Official Accounts.\n• Standard manufacturer warranty applies to all specified hardware." }}</div>
                </div>
            </td>
            <td style="width: 45%;">
                <table class="totals-table">
                    <tr>
                        <td style="color: #64748b;">Gross Line Subtotal:</td>
                        <td style="text-align: right; font-weight: 600;">&#8358;{{ number_format($subtotal, 2) }}</td>
                    </tr>
                    @if ($discountAmount > 0)
                        <tr style="color: #2563eb;">
                            <td>Discount Applied:</td>
                            <td style="text-align: right; font-weight: 600;">- &#8358;{{ number_format($discountAmount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="color: #64748b;">VAT (7.5%):</td>
                        <td style="text-align: right; font-weight: 600;">&#8358;{{ number_format($tax, 2) }}</td>
                    </tr>
                    <tr class="grand-total-row">
                        <td>Total Amount Due:</td>
                        <td style="text-align: right;">&#8358;{{ number_format($total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footnote: Fixed to stick to first page bottom -->
    <div class="footer">
        <strong>{{ $companyName }}</strong> &nbsp;|&nbsp; Official Commercial Quotation<br>
        Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT. &nbsp;|&nbsp; Phone: {{ $companyPhone }} &nbsp;|&nbsp; Email: {{ $companyEmail }} &nbsp;|&nbsp; Web: www.ascendsystems.ng
    </div>
</body>
</html>
