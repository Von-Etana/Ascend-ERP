<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Delivery Waybill {{ $quote['id'] ?? 'Dispatch' }} — Ascend Systems</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif !important; }
        body { font-family: 'DejaVu Sans', sans-serif !important; color: #1e293b; font-size: 11px; line-height: 1.4; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 15px; }
        .company-logo-img { max-height: 40px; width: auto; max-width: 200px; display: block; margin-bottom: 6px; }
        .company-logo-text { font-size: 18px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px; text-transform: uppercase; }
        .title { font-size: 18px; font-weight: 900; color: #0f172a; text-align: right; letter-spacing: 0.5px; text-transform: uppercase; }
        .details-box { width: 100%; margin-top: 10px; margin-bottom: 15px; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; background-color: #f8fafc; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 4px 6px; vertical-align: top; font-size: 10px; }
        .label { font-weight: bold; color: #475569; width: 25%; }
        .value { color: #0f172a; font-weight: 600; width: 75%; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; }
        .items-table th { background-color: #0f172a; color: #ffffff; padding: 7px 8px; text-align: left; font-size: 9px; text-transform: uppercase; font-weight: 800; }
        .items-table td { padding: 7px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .signoff-table { width: 100%; margin-top: 20px; }
        .signoff-table td { width: 32%; vertical-align: top; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px; background-color: #fafafa; font-size: 9px; }
        .sig-line { border-top: 1px solid #0f172a; margin-top: 30px; padding-top: 4px; font-size: 10px; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 9px; text-align: center; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        .badge-gate { display: inline-block; background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding: 2px 8px; border-radius: 10px; font-weight: bold; font-size: 9px; }
    </style>
</head>
<body>
    @php
        $rawItems = $quote['items'] ?? ($quote['line_items'] ?? []);
        $lineItems = isset($rawItems['line_items']) && is_array($rawItems['line_items']) ? $rawItems['line_items'] : (is_array($rawItems) ? $rawItems : []);
        $clientName = $quote['client_name'] ?? 'Client Installation Site';
        $clientPhone = $quote['phone'] ?? ($quote['client_phone'] ?? '+234 800 000 0000');
        $clientAddress = $quote['address'] ?? ($quote['client_address'] ?? 'Customer Delivery Site Address');
        $quoteId = $quote['id'] ?? ($quote['invoice_number'] ?? 'QT-2026');
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
                    <div style="font-size: 10px; color: #475569;">Central Warehouse & Logistics Logistics Hub</div>
                </td>
                <td style="width: 45%; text-align: right; vertical-align: top;">
                    <div class="title">DELIVERY WAYBILL & GATE PASS</div>
                    <div style="font-size: 11px; font-weight: bold; color: #2563eb;">WAYBILL #: WB-{{ $quoteId }}</div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Dispatch Date: {{ date('F d, Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Logistics Delivery & Destination Coordinates -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td class="label">Consignee / Client:</td>
                <td class="value">{{ $clientName }}</td>
                <td class="label">Contact Phone:</td>
                <td class="value">{{ $clientPhone }}</td>
            </tr>
            <tr>
                <td class="label">Delivery Destination:</td>
                <td class="value" colspan="3">{{ $clientAddress }}</td>
            </tr>
            <tr>
                <td class="label">Dispatched From:</td>
                <td class="value">Ascend Central Warehouse (Lagos / Abuja Staging Depot)</td>
                <td class="label">Gate Status:</td>
                <td class="value"><span class="badge-gate">CLEARED FOR TRANSIT</span></td>
            </tr>
        </table>
    </div>

    <!-- Transporter Details -->
    <div class="details-box" style="background-color: #f1f5f9; border-color: #cbd5e1;">
        <table class="details-table">
            <tr>
                <td class="label">Assigned Driver / Tech:</td>
                <td class="value font-bold">Ibrahim Aliyu (Field Logistics Lead)</td>
                <td class="label">Driver Phone:</td>
                <td class="value">+234 812 345 6789</td>
            </tr>
            <tr>
                <td class="label">Vehicle Reg. Number:</td>
                <td class="value font-mono font-bold">ABC-784-XY (Toyota Hilux Logistics Van)</td>
                <td class="label">Dispatch Time:</td>
                <td class="value font-mono">{{ date('H:i A') }}</td>
            </tr>
        </table>
    </div>

    <!-- Loaded Hardware Consignment Table -->
    <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; color: #0f172a; margin-bottom: 4px;">Loaded Consignment Hardware & Accessories Checklist:</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 15%;">SKU Code</th>
                <th style="width: 45%;">Hardware Description</th>
                <th style="width: 10%; text-align: center;">Qty Dispatched</th>
                <th style="width: 30%;">Serial Numbers / Batch Codes</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($lineItems) && count($lineItems) > 0)
                @foreach ($lineItems as $item)
                    @php
                        $qty = (int) ($item['qty'] ?? ($item['quantity'] ?? 1));
                        $desc = !empty($item['description']) ? $item['description'] : (!empty($item['name']) ? $item['name'] : 'Solar Hardware Package');
                        $sku = !empty($item['sku']) ? $item['sku'] : 'SOL-EQP';
                    @endphp
                    <tr>
                        <td style="font-family: monospace; font-size: 9px; font-weight: bold; color: #475569;">{{ $sku }}</td>
                        <td style="font-weight: 600;">{{ $desc }}</td>
                        <td style="text-align: center; font-weight: bold; font-size: 11px;">{{ $qty }}</td>
                        <td style="font-family: monospace; font-size: 9px; color: #64748b;">[ Verified & Scanned ]</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="font-family: monospace; font-size: 9px;">SLR-INV-55KW</td>
                    <td>5.5kVA Hybrid Solar Inverter + 10.2kWh LiFePO4 Battery Suite</td>
                    <td style="text-align: center; font-weight: bold;">1</td>
                    <td style="font-family: monospace; font-size: 9px; color: #64748b;">[ Verified & Scanned ]</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Triple Sign-off Section -->
    <table class="signoff-table">
        <tr>
            <td>
                <div style="font-weight: bold; color: #0f172a;">1. Warehouse Manager:</div>
                <div style="color: #64748b; font-size: 8.5px; margin-top: 2px;">Released items in good condition & deducted from inventory stock.</div>
                <div class="sig-line">Warehouse Lead Signature</div>
                <div style="font-size: 8.5px; margin-top: 3px; color: #64748b;">Date: {{ date('F d, Y') }}</div>
            </td>
            <td style="margin-left: 2%;">
                <div style="font-weight: bold; color: #0f172a;">2. Security Gate Clearance:</div>
                <div style="color: #64748b; font-size: 8.5px; margin-top: 2px;">Physical count matches waybill. Cleared to leave premises.</div>
                <div class="sig-line">Gate Security Stamp / Sig</div>
                <div style="font-size: 8.5px; margin-top: 3px; color: #64748b;">Date: {{ date('F d, Y') }}</div>
            </td>
            <td style="margin-left: 2%;">
                <div style="font-weight: bold; color: #0f172a;">3. Consignee / Customer Receipt:</div>
                <div style="color: #64748b; font-size: 8.5px; margin-top: 2px;">Received all listed consignment items intact at destination site.</div>
                <div class="sig-line">Client Receiving Signature</div>
                <div style="font-size: 8.5px; margin-top: 3px; color: #64748b;">Date: ____________________</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Ascend Systems &nbsp;|&nbsp; Official Warehouse Delivery Waybill & Gate Pass &nbsp;|&nbsp; Logistics Hotline: +234 811 763 3020
    </div>
</body>
</html>
