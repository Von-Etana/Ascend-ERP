<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Note {{ $invoice->invoice_number }} — Ascend Systems</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 13px; line-height: 1.5; margin: 0; padding: 25px; }
        .header { border-bottom: 2px solid #0284c7; padding-bottom: 18px; margin-bottom: 25px; }
        .company-logo { font-size: 22px; font-weight: 900; color: #0284c7; letter-spacing: -0.5px; text-transform: uppercase; }
        .address { font-size: 11px; color: #475569; margin-top: 4px; }
        .contact-info { font-size: 11px; color: #475569; margin-top: 2px; font-weight: 500; }
        .doc-title { font-size: 24px; font-weight: 900; text-align: right; color: #0f172a; tracking: 1px; }
        .doc-num { font-size: 13px; font-weight: bold; text-align: right; color: #0284c7; margin-top: 4px; }
        .details-table { width: 100%; margin-top: 20px; margin-bottom: 25px; }
        .details-table td { vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background-color: #f0f9ff; border-bottom: 2px solid #bae6fd; padding: 10px 12px; text-align: left; font-size: 10px; text-transform: uppercase; font-weight: 800; color: #0369a1; letter-spacing: 0.5px; }
        .items-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
        .signature-table { width: 100%; margin-top: 60px; border-collapse: collapse; }
        .signature-box { border-top: 1.5px solid #64748b; padding-top: 6px; font-size: 11px; font-weight: bold; color: #334155; }
        .footer { margin-top: 60px; border-top: 1px solid #e2e8f0; padding-top: 15px; font-size: 10px; text-align: center; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    <div style="display: inline-block; background-color: #0284c7; color: #ffffff; padding: 6px 12px; border-radius: 8px; font-weight: 900; font-size: 14px; margin-bottom: 6px;">
                        ▲ ASCEND AI LOGISTICS
                    </div>
                    <div class="company-logo">{{ $companyName }}</div>
                    <div class="address"><strong>Location:</strong> {{ $companyAddress }}</div>
                    <div class="contact-info"><strong>Call:</strong> {{ $companyPhone }} &nbsp;|&nbsp; <strong>Mail:</strong> {{ $companyEmail }}</div>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: top;">
                    <div class="doc-title">DELIVERY NOTE</div>
                    <div class="doc-num">#DN-{{ $invoice->invoice_number }}</div>
                    <div style="font-size: 11px; font-weight: bold; color: #0284c7; margin-top: 6px;">
                        OFFICIAL DISPATCH SLIP
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="details-table">
        <tr>
            <td style="width: 50%;">
                <div style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Deliver To (Recipient):</div>
                <div style="font-size: 15px; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $invoice->client_name }}</div>
                <div style="font-size: 11px; color: #475569; margin-top: 2px;">Abuja / Regional Client Delivery Destination</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div><strong>Dispatch Date:</strong> {{ date('F d, Y') }}</div>
                <div><strong>Order / Invoice Ref:</strong> #{{ $invoice->invoice_number }}</div>
                <div><strong>Dispatch Warehouse:</strong> Lagos HQ Central Warehouse</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 15%;">SKU / Code</th>
                <th style="width: 55%;">Item Description</th>
                <th style="text-align: center; width: 15%;">Qty Dispatched</th>
                <th style="text-align: center; width: 15%;">Condition</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-family: monospace; font-weight: bold; color: #0284c7;">HDW-ASC-01</td>
                <td>
                    <div style="font-weight: bold; color: #0f172a; font-size: 13px;">{{ $invoice->notes ?: 'Enterprise Software & Hardware Package' }}</div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Dispatched from Ascend Systems HQ — Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.</div>
                </td>
                <td style="text-align: center; font-weight: bold; font-size: 13px;">1 Lot</td>
                <td style="text-align: center; font-weight: bold; color: #16a34a;">Good / Sealed</td>
            </tr>
        </tbody>
    </table>

    <!-- Receiver & Dispatch Signatures -->
    <table class="signature-table">
        <tr>
            <td style="width: 45%;">
                <div style="height: 40px;"></div>
                <div class="signature-box">
                    Dispatched By (Driver / Logistics Lead)<br>
                    <span style="font-weight: normal; font-size: 10px; color: #64748b;">Ascend Logistics Team · Abuja</span>
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                <div style="height: 40px;"></div>
                <div class="signature-box">
                    Received In Good Condition By (Client Signature)<br>
                    <span style="font-weight: normal; font-size: 10px; color: #64748b;">Date & Stamp: ________________________</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <strong>{{ $companyName }}</strong><br>
        Suite FF002, Neighborhood Centre, Area 3, Garki. Abuja. FCT.<br>
        Call: {{ $companyPhone }} &nbsp;|&nbsp; Mail: {{ $companyEmail }}
    </div>
</body>
</html>
