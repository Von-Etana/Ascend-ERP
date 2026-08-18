<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Warranty Certificate {{ $quote['id'] ?? 'Draft' }} — Ascend Systems</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif !important; }
        body { font-family: 'DejaVu Sans', sans-serif !important; color: #1e293b; font-size: 12px; line-height: 1.5; margin: 0; padding: 25px; }
        .cert-border { border: 4px double #2563eb; padding: 24px; border-radius: 12px; position: relative; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 16px; margin-bottom: 20px; }
        .company-logo-img { max-height: 48px; width: auto; max-width: 220px; margin: 0 auto 8px auto; display: block; }
        .company-logo-text { font-size: 22px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px; text-transform: uppercase; margin-bottom: 4px; }
        .cert-title { font-size: 24px; font-weight: 900; color: #0f172a; letter-spacing: 2px; text-transform: uppercase; margin-top: 6px; }
        .cert-subtitle { font-size: 13px; color: #2563eb; font-weight: bold; margin-top: 2px; letter-spacing: 1px; }
        .details-box { width: 100%; margin-top: 15px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; background-color: #f8fafc; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 6px 8px; vertical-align: top; font-size: 11px; }
        .label { font-weight: bold; color: #475569; width: 30%; }
        .value { color: #0f172a; font-weight: 600; width: 70%; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background-color: #0f172a; color: #ffffff; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; font-weight: 800; }
        .items-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .warranty-badge { display: inline-block; background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; padding: 4px 12px; border-radius: 16px; font-size: 11px; font-weight: bold; }
        .terms-section { margin-top: 20px; font-size: 10px; color: #475569; line-height: 1.6; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 12px; background-color: #fafafa; }
        .signature-table { width: 100%; margin-top: 30px; }
        .signature-box { text-align: center; width: 45%; }
        .sig-line { border-top: 1px solid #0f172a; margin-top: 40px; padding-top: 6px; font-size: 11px; font-weight: bold; color: #0f172a; }
        .footer { margin-top: 30px; font-size: 9px; text-align: center; color: #94a3b8; }
    </style>
</head>
<body>
    @php
        $rawItems = $quote['items'] ?? ($quote['line_items'] ?? []);
        $lineItems = isset($rawItems['line_items']) && is_array($rawItems['line_items']) ? $rawItems['line_items'] : (is_array($rawItems) ? $rawItems : []);
        $clientName = $quote['client_name'] ?? 'Valued Customer';
        $clientAddress = $quote['address'] ?? ($quote['client_address'] ?? 'Customer Installation Site');
        $quoteId = $quote['id'] ?? ($quote['invoice_number'] ?? 'QT-2026');
        $issueDate = isset($quote['created_at']) ? date('F d, Y', strtotime($quote['created_at'])) : date('F d, Y');
        $expiryDate = date('F d, Y', strtotime('+5 years'));
    @endphp

    <div class="cert-border">
        <div class="header">
            @if (!empty($companyLogo))
                <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="company-logo-img">
            @else
                <div class="company-logo-text">{{ $companyName }}</div>
            @endif
            <div class="cert-title">CERTIFICATE OF WARRANTY</div>
            <div class="cert-subtitle">5-YEAR COMPREHENSIVE SOLAR SYSTEM PERFORMANCE & EQUIPMENT GUARANTEE</div>
        </div>

        <div class="details-box">
            <table class="details-table">
                <tr>
                    <td class="label">Certificate Reference:</td>
                    <td class="value font-mono">WNT-{{ $quoteId }}</td>
                </tr>
                <tr>
                    <td class="label">Issued To (Owner / Client):</td>
                    <td class="value">{{ $clientName }}</td>
                </tr>
                <tr>
                    <td class="label">Site / Installation Location:</td>
                    <td class="value">{{ $clientAddress }}</td>
                </tr>
                <tr>
                    <td class="label">Warranty Coverage Period:</td>
                    <td class="value"><span class="warranty-badge">5 Years ({{ $issueDate }} to {{ $expiryDate }})</span></td>
                </tr>
            </table>
        </div>

        <div style="font-weight: bold; font-size: 11px; text-transform: uppercase; color: #0f172a; margin-top: 15px;">Covered Equipment & Installation Scope:</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 20%;">SKU / Code</th>
                    <th style="width: 55%;">Hardware / Scope Description</th>
                    <th style="width: 25%; text-align: center;">Coverage Tier</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($lineItems) && count($lineItems) > 0)
                    @foreach ($lineItems as $item)
                        @php
                            $desc = !empty($item['description']) ? $item['description'] : (!empty($item['name']) ? $item['name'] : 'Solar Hardware Unit');
                            $sku = !empty($item['sku']) ? $item['sku'] : 'SOL-EQP';
                        @endphp
                        <tr>
                            <td style="font-family: 'DejaVu Sans', monospace; font-size: 10px; font-weight: bold; color: #475569;">{{ $sku }}</td>
                            <td style="font-weight: 600; color: #0f172a;">{{ $desc }}</td>
                            <td style="text-align: center; font-weight: bold; color: #16a34a;">5-Yr Full Replacement</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="font-family: 'DejaVu Sans', monospace; font-size: 10px;">SRV-SOLAR</td>
                        <td style="font-weight: 600;">Ascend Turnkey Hybrid Solar Inverter & LiFePO4 Battery System</td>
                        <td style="text-align: center; font-weight: bold; color: #16a34a;">5-Yr Full Replacement</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="terms-section">
            <strong>WARRANTY TERMS & CONDITIONS OF SERVICE:</strong><br>
            1. <strong>LiFePO4 Lithium Batteries:</strong> Guaranteed minimum 80% state of health (SoH) over 6,000 deep cycles or 5 years.<br>
            2. <strong>Hybrid Solar Inverter:</strong> Covers all internal MPPT controllers, power electronic MOSFETs, and logic boards against manufacturing defects.<br>
            3. <strong>Workmanship & Cabling:</strong> All DC/AC cabling, surge arrestors, and distribution connections are guaranteed against degradation under normal operation.<br>
            4. <strong>Rapid Field Service:</strong> On-site technician deployment within 24 hours of reported fault in Abuja, Lagos, and major metropolitan centres.
        </div>

        <table class="signature-table">
            <tr>
                <td class="signature-box">
                    <div class="sig-line">Chief Technical Officer (CTO)<br><span style="font-weight: normal; color: #64748b;">Ascend Systems Nigeria Ltd</span></div>
                </td>
                <td style="width: 10%;"></td>
                <td class="signature-box">
                    <div class="sig-line">Authorized Quality Assurance Seal<br><span style="font-weight: normal; color: #64748b;">Verified & Commissioned</span></div>
                </td>
            </tr>
        </table>

        <div class="footer">
            {{ $companyName }} &nbsp;|&nbsp; Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT. &nbsp;|&nbsp; Call: {{ $companyPhone }} &nbsp;|&nbsp; Email: {{ $companyEmail }}
        </div>
    </div>
</body>
</html>
