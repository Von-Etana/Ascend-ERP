<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Site Inspection Report {{ $quote['id'] ?? 'Audit' }} — Ascend Systems</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif !important; }
        body { font-family: 'DejaVu Sans', sans-serif !important; color: #1e293b; font-size: 11px; line-height: 1.4; margin: 0; padding: 20px; }
        .header { border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 15px; }
        .company-logo-img { max-height: 40px; width: auto; max-width: 200px; display: block; margin-bottom: 6px; }
        .company-logo-text { font-size: 18px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px; text-transform: uppercase; }
        .title { font-size: 18px; font-weight: 900; color: #0f172a; text-align: right; letter-spacing: 0.5px; text-transform: uppercase; }
        .details-box { width: 100%; margin-top: 10px; margin-bottom: 12px; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; background-color: #f8fafc; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 4px 6px; vertical-align: top; font-size: 10px; }
        .label { font-weight: bold; color: #475569; width: 25%; }
        .value { color: #0f172a; font-weight: 600; width: 75%; }
        .audit-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 12px; }
        .audit-table th { background-color: #0f172a; color: #ffffff; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; font-weight: 800; }
        .audit-table td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .check-box { display: inline-block; width: 12px; height: 12px; border: 1px solid #0f172a; margin-right: 6px; vertical-align: middle; }
        .badge-ready { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 10px; font-weight: bold; font-size: 9px; }
        .signoff-table { width: 100%; margin-top: 20px; }
        .signoff-table td { width: 48%; vertical-align: top; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px; background-color: #fafafa; }
        .sig-line { border-top: 1px solid #0f172a; margin-top: 30px; padding-top: 4px; font-size: 10px; font-weight: bold; }
        .footer { margin-top: 15px; font-size: 9px; text-align: center; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>
    @php
        $clientName = $quote['client_name'] ?? 'Client Premises';
        $clientPhone = $quote['phone'] ?? ($quote['client_phone'] ?? '+234 800 000 0000');
        $clientAddress = $quote['address'] ?? ($quote['client_address'] ?? 'Installation Site Address');
        $quoteId = $quote['id'] ?? ($quote['invoice_number'] ?? 'QT-2026');
        $inspectionData = $quote['inspection'] ?? [];
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
                    <div style="font-size: 10px; color: #475569;">Engineering Field Survey & Audit Unit</div>
                </td>
                <td style="width: 45%; text-align: right; vertical-align: top;">
                    <div class="title">PRE-INSTALLATION SITE INSPECTION</div>
                    <div style="font-size: 11px; font-weight: bold; color: #2563eb;">AUDIT REF: SIR-{{ $quoteId }}</div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Survey Date: {{ date('F d, Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Client and Site Coordinates -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td class="label">Client / Facility:</td>
                <td class="value">{{ $clientName }}</td>
                <td class="label">Contact Phone:</td>
                <td class="value">{{ $clientPhone }}</td>
            </tr>
            <tr>
                <td class="label">Site Location:</td>
                <td class="value" colspan="3">{{ $clientAddress }}</td>
            </tr>
            <tr>
                <td class="label">Inspecting Engineer:</td>
                <td class="value">{{ $inspectionData['inspector'] ?? 'Lead Field Systems Engineer' }}</td>
                <td class="label">Site Readiness:</td>
                <td class="value"><span class="badge-ready">APPROVED FOR STAGING</span></td>
            </tr>
        </table>
    </div>

    <!-- Section 1: Roof & Solar PV Mounting Assessment -->
    <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; color: #0f172a; margin-bottom: 3px;">1. Roof Structure & Solar PV Mounting Assessment:</div>
    <table class="audit-table">
        <thead>
            <tr>
                <th style="width: 35%;">Roof Parameter</th>
                <th style="width: 30%;">Site Finding</th>
                <th style="width: 35%;">Technical Recommendation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Roof Covering Material</td>
                <td><strong>{{ $inspectionData['roof_type'] ?? 'Aluminium Longspan / Stone-Coated Metal' }}</strong></td>
                <td>Use L-Feet anodized mounting rails with EPDM weather seal.</td>
            </tr>
            <tr>
                <td>Roof Orientation & Azimuth</td>
                <td>{{ $inspectionData['orientation'] ?? 'South / South-West Facing (Optimal)' }}</td>
                <td>12° - 15° pitch angle for self-cleaning & maximum irradiation.</td>
            </tr>
            <tr>
                <td>Tree / Building Shading</td>
                <td>{{ $inspectionData['shading'] ?? 'Zero Shade (9:00 AM - 5:00 PM Sun Window)' }}</td>
                <td>Full unobstructed solar string performance.</td>
            </tr>
            <tr>
                <td>Available Usable Roof Area</td>
                <td>{{ $inspectionData['roof_area'] ?? 'Approx. 85 m² suitable surface' }}</td>
                <td>Adequate for 12 - 24 High-Efficiency 550W Monocrystalline PVs.</td>
            </tr>
        </tbody>
    </table>

    <!-- Section 2: Electrical Infrastructure & DB Board -->
    <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; color: #0f172a; margin-bottom: 3px;">2. Electrical Distribution (DB) & Cabling Pathway:</div>
    <table class="audit-table">
        <thead>
            <tr>
                <th style="width: 35%;">Electrical Audit Dimension</th>
                <th style="width: 30%;">Measured Site Parameter</th>
                <th style="width: 35%;">Engineering Provision Required</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Mains Power Phase Type</td>
                <td><strong>{{ $inspectionData['phase'] ?? 'Single-Phase (230V, 50Hz)' }}</strong></td>
                <td>Pre-configured 1-Phase Inverter Output with neutral bonding.</td>
            </tr>
            <tr>
                <td>Cable Run (Roof to Inverter)</td>
                <td>{{ $inspectionData['dc_cable_distance'] ?? '18 Meters' }}</td>
                <td>6.0mm² Solar DC PV UV-Resistant Twin Core Cable.</td>
            </tr>
            <tr>
                <td>Cable Run (Inverter to DB)</td>
                <td>{{ $inspectionData['ac_cable_distance'] ?? '6 Meters' }}</td>
                <td>10.0mm² Pure Copper AC Flex Armoured Cable.</td>
            </tr>
            <tr>
                <td>Existing Earth Ground Pit</td>
                <td>{{ $inspectionData['earthing'] ?? 'Present (< 5.0 Ohms Resistance)' }}</td>
                <td>Connect DC SPD & Lightning Arrestor to dedicated copper rod.</td>
            </tr>
            <tr>
                <td>Generator / Grid Changeover</td>
                <td>{{ $inspectionData['ats'] ?? 'Manual 63A Changeover Switch' }}</td>
                <td>Install 63A Dual Automatic Transfer Switch (ATS) module.</td>
            </tr>
        </tbody>
    </table>

    <!-- Section 3: Inverter & Battery Staging Location -->
    <div style="font-weight: bold; font-size: 10px; text-transform: uppercase; color: #0f172a; margin-bottom: 3px;">3. Inverter Room & Battery Staging Location:</div>
    <div class="details-box" style="font-size: 10px; line-height: 1.5;">
        • <strong>Designated Location:</strong> {{ $inspectionData['location'] ?? 'Dedicated Utility Room / Ventilated Inverter Enclosure (Ground Floor)' }}<br>
        • <strong>Ventilation & Environment:</strong> Clean, dry, cross-ventilated room with zero direct rainfall or excessive moisture.<br>
        • <strong>Wall Mounting Capacity:</strong> Solid brick/concrete wall capable of supporting > 90kg total inverter and battery weight.
    </div>

    <!-- Dual Sign-off Section -->
    <table class="signoff-table">
        <tr>
            <td>
                <div style="font-weight: bold; color: #0f172a;">Lead Field Inspector Certification:</div>
                <div style="color: #64748b; font-size: 9px; margin-top: 2px;">I confirm that the physical site audit was performed thoroughly and the facility is prepared for solar hardware deployment.</div>
                <div class="sig-line">Inspecting Engineer Signature</div>
                <div style="font-size: 9px; margin-top: 4px; color: #64748b;">Date: {{ date('F d, Y') }}</div>
            </td>
            <td style="margin-left: 4%;">
                <div style="font-weight: bold; color: #0f172a;">Client Site Contact Acknowledgment:</div>
                <div style="color: #64748b; font-size: 9px; margin-top: 2px;">I acknowledge the pre-installation inspection findings and confirm authorization for scheduled deployment staging.</div>
                <div class="sig-line">Client / Facility Manager Signature</div>
                <div style="font-size: 9px; margin-top: 4px; color: #64748b;">Date: {{ date('F d, Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Ascend Systems &nbsp;|&nbsp; Official Pre-Installation Site Inspection Report &nbsp;|&nbsp; Call: +234 811 763 3020 &nbsp;|&nbsp; info@ascendsystems.ng
    </div>
</body>
</html>
