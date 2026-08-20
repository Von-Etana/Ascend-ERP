<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Technical Job Card {{ $quote['id'] ?? 'Draft' }} — Ascend Systems</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif !important; }
        body { font-family: 'DejaVu Sans', sans-serif !important; color: #1e293b; font-size: 11px; line-height: 1.4; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 15px; }
        .company-logo-img { max-height: 40px; width: auto; max-width: 200px; display: block; margin-bottom: 6px; }
        .company-logo-text { font-size: 18px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px; text-transform: uppercase; }
        .title { font-size: 20px; font-weight: 900; color: #0f172a; text-align: right; letter-spacing: 0.5px; text-transform: uppercase; }
        .details-box { width: 100%; margin-top: 10px; margin-bottom: 15px; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; background-color: #f8fafc; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 4px 6px; vertical-align: top; font-size: 10px; }
        .label { font-weight: bold; color: #475569; width: 25%; }
        .value { color: #0f172a; font-weight: 600; width: 75%; }
        .checklist-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; }
        .checklist-table th { background-color: #0f172a; color: #ffffff; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; font-weight: 800; }
        .checklist-table td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .check-box { display: inline-block; width: 12px; height: 12px; border: 1px solid #0f172a; margin-right: 6px; vertical-align: middle; }
        .metric-input { border-bottom: 1px dotted #94a3b8; display: inline-block; width: 120px; height: 14px; }
        .signoff-table { width: 100%; margin-top: 25px; }
        .signoff-table td { width: 48%; vertical-align: top; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; background-color: #fafafa; }
        .sig-line { border-top: 1px solid #0f172a; margin-top: 35px; padding-top: 4px; font-size: 10px; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 9px; text-align: center; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    @php
        $rawItems = $quote['items'] ?? ($quote['line_items'] ?? []);
        $lineItems = isset($rawItems['line_items']) && is_array($rawItems['line_items']) ? $rawItems['line_items'] : (is_array($rawItems) ? $rawItems : []);
        $clientName = $quote['client_name'] ?? 'Client Installation Site';
        $clientPhone = $quote['phone'] ?? ($quote['client_phone'] ?? '+234 800 000 0000');
        $clientAddress = $quote['address'] ?? ($quote['client_address'] ?? 'Customer Premises');
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
                    <div style="font-size: 10px; color: #475569;">Engineering & Field Operations Department</div>
                </td>
                <td style="width: 45%; text-align: right; vertical-align: top;">
                    <div class="title">SOLAR FIELD JOB CARD</div>
                    <div style="font-size: 11px; font-weight: bold; color: #2563eb;">WO #{{ $quoteId }}-JC</div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Deployment Date: {{ date('F d, Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Client and Site Coordinates -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td class="label">Customer / Client:</td>
                <td class="value">{{ $clientName }}</td>
                <td class="label">Contact Phone:</td>
                <td class="value">{{ $clientPhone }}</td>
            </tr>
            <tr>
                <td class="label">Site Location / Address:</td>
                <td class="value" colspan="3">{{ $clientAddress }}</td>
            </tr>
            <tr>
                <td class="label">Lead Field Engineer:</td>
                <td class="value"><span class="metric-input" style="width: 160px;"></span></td>
                <td class="label">Crew Technicians:</td>
                <td class="value"><span class="metric-input" style="width: 160px;"></span></td>
            </tr>
        </table>
    </div>

    <!-- Bill of Materials (BOM) for Site -->
    <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; color: #0f172a; margin-bottom: 4px;">Hardware Bill of Materials (BOM) to Mount & Commission:</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th style="width: 15%;">SKU</th>
                <th style="width: 50%;">Hardware Specification</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 25%;">Serial Number Scanned</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($lineItems) && count($lineItems) > 0)
                @foreach ($lineItems as $item)
                    @php
                        $qty = (int) ($item['qty'] ?? ($item['quantity'] ?? 1));
                        $desc = !empty($item['description']) ? $item['description'] : (!empty($item['name']) ? $item['name'] : 'Hardware Unit');
                        $sku = !empty($item['sku']) ? $item['sku'] : 'SOL-EQP';
                    @endphp
                    <tr>
                        <td style="font-family: monospace; font-size: 9px; font-weight: bold; color: #475569;">{{ $sku }}</td>
                        <td>{{ $desc }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $qty }}</td>
                        <td><span class="metric-input" style="width: 130px;"></span></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="font-family: monospace; font-size: 9px;">SLR-INV-55KW</td>
                    <td>5.5kVA Hybrid Inverter & 10.2kWh LiFePO4 Battery Suite</td>
                    <td style="text-align: center; font-weight: bold;">1</td>
                    <td><span class="metric-input" style="width: 130px;"></span></td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Commissioning & Safety Checklist -->
    <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; color: #0f172a; margin-bottom: 4px;">Commissioning Quality Assurance Checklist:</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th style="width: 45%;">Pre-Power On Safety Inspection</th>
                <th style="width: 20%; text-align: center;">Verified</th>
                <th style="width: 35%;">Measured Electrical Metric</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Solar Array Voc & DC Polarity Verified Correct</td>
                <td style="text-align: center;"><span class="check-box"></span> PASS</td>
                <td>Array String Voc: <span class="metric-input" style="width: 80px;"></span> VDC</td>
            </tr>
            <tr>
                <td>LiFePO4 Battery Terminal Torque & BMS Comm Cable OK</td>
                <td style="text-align: center;"><span class="check-box"></span> PASS</td>
                <td>Resting DC Voltage: <span class="metric-input" style="width: 80px;"></span> VDC</td>
            </tr>
            <tr>
                <td>DC SPD / Surge Arrestor & 125A DC Breaker Installed</td>
                <td style="text-align: center;"><span class="check-box"></span> PASS</td>
                <td>Earth Resistance: <span class="metric-input" style="width: 80px;"></span> Ohms</td>
            </tr>
            <tr>
                <td>AC Output Phase-Neutral Voltage & Frequency Tested</td>
                <td style="text-align: center;"><span class="check-box"></span> PASS</td>
                <td>Grid Switchover Delay: <span class="metric-input" style="width: 60px;"></span> ms</td>
            </tr>
            <tr>
                <td>Client Mobile App / WiFi Monitoring Inverter Registered</td>
                <td style="text-align: center;"><span class="check-box"></span> PASS</td>
                <td>Datalogger ID: <span class="metric-input" style="width: 90px;"></span></td>
            </tr>
        </tbody>
    </table>

    <!-- Dual Sign-off Section -->
    <table class="signoff-table">
        <tr>
            <td>
                <div style="font-weight: bold; color: #0f172a;">Lead Field Engineer Handover:</div>
                <div style="color: #64748b; font-size: 9px; margin-top: 2px;">I confirm all DC/AC connections adhere strictly to Nigerian Electrical Standards (NEMSA/SON) and the system was energized safely.</div>
                <div class="sig-line">Engineer Name & Signature</div>
                <div style="font-size: 9px; margin-top: 4px; color: #64748b;">Date: ________________________</div>
            </td>
            <td style="margin-left: 4%;">
                <div style="font-weight: bold; color: #0f172a;">Customer Handover & Acceptance:</div>
                <div style="color: #64748b; font-size: 9px; margin-top: 2px;">I confirm the solar/inverter installation is complete, operational, and all appliances were tested under load to full satisfaction.</div>
                <div class="sig-line">Client / Representative Signature</div>
                <div style="font-size: 9px; margin-top: 4px; color: #64748b;">Date: ________________________</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Ascend Systems &nbsp;|&nbsp; Official Technical Field Operations Record &nbsp;|&nbsp; Support: +234 811 763 3020 &nbsp;|&nbsp; info@ascendsystems.ng
    </div>
</body>
</html>
