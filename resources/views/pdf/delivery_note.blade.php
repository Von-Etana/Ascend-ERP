<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Delivery Note {{ $invoice->invoice_number }} — Ascend Systems</title>
    <style>
        body { font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 12px; line-height: 1.5; margin: 0; padding: 25px; }
        .header { border-bottom: 2px solid #059669; padding-bottom: 18px; margin-bottom: 20px; }
        .company-logo-img { max-height: 48px; width: auto; max-width: 220px; display: block; margin-bottom: 8px; }
        .company-logo-text { font-size: 20px; font-weight: 900; color: #0284c7; letter-spacing: -0.5px; text-transform: uppercase; margin-bottom: 4px; }
        .contact-info { font-size: 11px; color: #475569; margin-top: 4px; font-weight: 500; }
        .title-main { font-size: 24px; font-weight: 900; text-align: right; color: #0f172a; letter-spacing: 1px; }
        .title-sub { font-size: 13px; font-weight: bold; text-align: right; color: #0284c7; margin-top: 2px; }
        .details-box { width: 100%; margin-top: 15px; margin-bottom: 20px; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px; background-color: #f0f9ff; }
        .details-table { width: 100%; }
        .details-table td { vertical-align: top; }
        .section-label { font-size: 10px; font-weight: 800; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .customer-name { font-size: 15px; font-weight: 800; color: #0f172a; }
        .customer-detail { font-size: 11px; color: #334155; margin-top: 2px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background-color: #0369a1; color: #ffffff; padding: 9px 10px; text-align: left; font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; }
        .items-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .items-table tr:nth-child(even) td { background-color: #f8fafc; }
        .sig-container { margin-top: 45px; width: 100%; }
        .sig-box { width: 45%; float: left; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; font-size: 11px; background-color: #fafafa; }
        .sig-line { border-bottom: 1px solid #000; margin-top: 40px; margin-bottom: 6px; }
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
                    <div style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $companyName }}</div>
                    <div class="contact-info"><strong>Call:</strong> {{ $companyPhone }} &nbsp;|&nbsp; <strong>Email:</strong> {{ $companyEmail }}</div>
                </td>
                <td style="width: 45%; text-align: right; vertical-align: top;">
                    <div class="title-main">DELIVERY NOTE</div>
                    <div class="title-sub">REF #: DN-{{ $invoice->invoice_number }}</div>
                    <div style="font-size: 11px; font-weight: bold; color: #64748b; margin-top: 4px;">Linked Invoice: #{{ $invoice->invoice_number }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Consignee & Shipping Card -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td style="width: 55%;">
                    <div class="section-label">Consignee / Delivery Address:</div>
                    <div class="customer-name">{{ $invoice->client_name }}</div>
                    <div class="customer-detail"><strong>Delivery Site:</strong> {{ $clientAddress ?: 'Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.' }}</div>
                    @if (!empty($clientPhone) || !empty($clientEmail))
                        <div class="customer-detail">
                            @if (!empty($clientPhone)) <strong>Contact Phone:</strong> {{ $clientPhone }} @endif
                            @if (!empty($clientEmail)) &nbsp;|&nbsp; <strong>Email:</strong> {{ $clientEmail }} @endif
                        </div>
                    @endif
                    @if (!empty($clientTin))
                        <div class="customer-detail"><strong>Customer TIN:</strong> {{ $clientTin }}</div>
                    @endif
                </td>
                <td style="width: 45%; text-align: right;">
                    <div class="customer-detail"><strong>Dispatch Date:</strong> {{ date('F d, Y') }}</div>
                    <div class="customer-detail"><strong>Carrier / Delivery Mode:</strong> Logistics Express / Courier</div>
                    <div class="customer-detail"><strong>Status:</strong> Dispatched / Awaiting Confirmation</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Goods Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 15%;">SKU / Code</th>
                <th style="width: 55%;">Goods / Equipment Description</th>
                <th style="text-align: center; width: 15%;">Qty Ordered</th>
                <th style="text-align: center; width: 15%;">Qty Delivered</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($lineItems) && is_array($lineItems))
                @foreach ($lineItems as $item)
                    @php $qty = (float) ($item['quantity'] ?? 1); @endphp
                    <tr>
                        <td style="font-family: monospace; font-size: 10px; font-weight: bold; color: #0369a1;">{{ $item['sku'] ?? 'GEN-ITEM' }}</td>
                        <td>
                            <div style="font-weight: bold; color: #0f172a;">{{ $item['description'] ?? 'Product Line Item' }}</div>
                            <div style="font-size: 10px; color: #64748b;">Inspected & Verified at Dispatch HQ</div>
                        </td>
                        <td style="text-align: center; font-weight: bold;">{{ $qty }}</td>
                        <td style="text-align: center; font-weight: bold; color: #0369a1;">{{ $qty }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="font-family: monospace; font-size: 10px;">SRV-001</td>
                    <td>
                        <div style="font-weight: bold; color: #0f172a;">{{ $invoice->notes ?: 'Enterprise Equipment & Software Package' }}</div>
                    </td>
                    <td style="text-align: center; font-weight: bold;">1</td>
                    <td style="text-align: center; font-weight: bold; color: #0369a1;">1</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 15px; font-size: 10px; color: #64748b; font-style: italic;">
        Notice: Please inspect all delivered packages immediately upon arrival. Any discrepancies or damages must be endorsed on this delivery note before driver departure.
    </div>

    <!-- Dual Signature Acknowledgement Block -->
    <div class="sig-container">
        <div class="sig-box">
            <div style="font-weight: bold; color: #0284c7; text-transform: uppercase;">Dispatched By (Ascend Logistics):</div>
            <div class="sig-line"></div>
            <div>Authorized Logistics Officer Signature & Date</div>
            <div style="margin-top: 4px; color: #64748b; font-size: 10px;">Ascend Systems</div>
        </div>

        <div class="sig-box" style="float: right;">
            <div style="font-weight: bold; color: #15803d; text-transform: uppercase;">Received in Good Condition By:</div>
            <div class="sig-line"></div>
            <div>Consignee / Customer Signature & Date</div>
            <div style="margin-top: 4px; color: #64748b; font-size: 10px;">Full Name / Stamp / Phone Number</div>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <strong>{{ $companyName }}</strong> &nbsp;|&nbsp; Official Dispatch & Delivery Acknowledgement Note<br>
        Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.<br>
        Phone: {{ $companyPhone }} &nbsp;|&nbsp; Email: {{ $companyEmail }} &nbsp;|&nbsp; Web: www.ascendsystems.ng
    </div>
</body>
</html>
