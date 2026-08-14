<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Financial & Performance Report — {{ $companyName }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 12px; line-height: 1.5; margin: 0; padding: 25px; }
        .header { border-bottom: 2px solid #0d9488; padding-bottom: 18px; margin-bottom: 20px; }
        .company-logo-img { max-height: 48px; width: auto; max-width: 220px; display: block; margin-bottom: 8px; }
        .company-logo-text { font-size: 20px; font-weight: 900; color: #0d9488; letter-spacing: -0.5px; text-transform: uppercase; margin-bottom: 4px; }
        .address { font-size: 11px; color: #475569; margin-top: 3px; }
        .contact-info { font-size: 11px; color: #475569; margin-top: 2px; font-weight: 500; }
        .report-title { font-size: 24px; font-weight: 900; text-align: right; color: #0f172a; letter-spacing: 1px; }
        .report-num { font-size: 13px; font-weight: bold; text-align: right; color: #0d9488; margin-top: 2px; }
        .details-box { width: 100%; margin-top: 15px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; background-color: #f8fafc; }
        .details-table { width: 100%; }
        .details-table td { vertical-align: top; }
        .section-label { font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background-color: #0f172a; color: #ffffff; padding: 9px 10px; text-align: left; font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; }
        .items-table td { padding: 9px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .items-table tr:nth-child(even) td { background-color: #f8fafc; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .badge-verified { background-color: #ccfbf1; color: #0f766e; border: 1px solid #99f6e4; }
        .sign-box { width: 48%; float: left; margin-top: 25px; padding: 10px; border: 1px dashed #cbd5e1; border-radius: 6px; background-color: #fafafa; font-size: 10px; }
        .totals-table { width: 48%; float: right; margin-top: 15px; border-collapse: collapse; }
        .totals-table td { padding: 5px 10px; font-size: 11px; }
        .footer { margin-top: 90px; border-top: 1px solid #e2e8f0; padding-top: 15px; font-size: 10px; text-align: center; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>
    @php
        $grossRevenue = 28450000.00;
        $cogs = 9850000.00;
        $grossProfit = $grossRevenue - $cogs;
        $opex = 5200000.00;
        $payroll = 2250000.00;
        $netOperatingIncome = $grossProfit - ($opex + $payroll);
        $taxProvision = $netOperatingIncome * 0.15;
        $netProfit = $netOperatingIncome - $taxProvision;
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
                    <div class="address"><strong>Corporate Headquarters:</strong> {{ $companyAddress }}</div>
                    <div class="contact-info"><strong>Call:</strong> {{ $companyPhone }} &nbsp;|&nbsp; <strong>Email:</strong> {{ $companyEmail }}</div>
                </td>
                <td style="width: 45%; text-align: right; vertical-align: top;">
                    <div class="report-title">EXECUTIVE P&L REPORT</div>
                    <div class="report-num">Reporting Period: Q3 2026</div>
                    <div style="margin-top: 8px;">
                        <span class="badge badge-verified">AUDITED EXECUTIVE STATEMENT</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Corporate Metadata Box -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <div class="section-label">Organization Overview</div>
                    <div style="font-size: 14px; font-weight: bold; color: #0f172a;">{{ $companyName }}</div>
                    <div class="customer-detail"><strong>Corporate HQ:</strong> Abuja HQ &bull; {{ $companyAddress }}</div>
                    <div class="customer-detail"><strong>Regional Operations:</strong> Lagos, Port Harcourt, Kano</div>
                </td>
                <td style="width: 50%; padding-left: 20px;">
                    <div class="section-label">Audit & Governance</div>
                    <div class="customer-detail"><strong>Audit Status:</strong> Verified & Signed</div>
                    <div class="customer-detail"><strong>Generated Date:</strong> {{ date('F d, Y') }}</div>
                    <div class="customer-detail"><strong>Currency:</strong> Nigerian Naira (NGN / ₦)</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Master Profit & Loss Table -->
    <div class="section-label" style="font-size: 11px; margin-top: 15px;">Master Profit & Loss (P&L) Financial Statement</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 55%;">Financial Item / Category</th>
                <th style="width: 45%; text-align: right;">Amount (NGN)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Gross Operating Revenue (Sales, POS & Subscriptions)</strong></td>
                <td style="text-align: right; font-weight: bold; color: #0f172a;">₦{{ number_format($grossRevenue, 2) }}</td>
            </tr>
            <tr>
                <td>Cost of Goods Sold (COGS & Hardware Procurement)</td>
                <td style="text-align: right; color: #b91c1c;">-₦{{ number_format($cogs, 2) }}</td>
            </tr>
            <tr style="background-color: #f0fdf4; font-weight: bold;">
                <td><strong>GROSS OPERATING PROFIT</strong></td>
                <td style="text-align: right; color: #15803d;"><strong>₦{{ number_format($grossProfit, 2) }}</strong></td>
            </tr>
            <tr>
                <td>Operating Expenses (Marketing, Utilities, Cloud Infrastructure)</td>
                <td style="text-align: right; color: #b91c1c;">-₦{{ number_format($opex, 2) }}</td>
            </tr>
            <tr>
                <td>Personnel & Staff Payroll Disbursement</td>
                <td style="text-align: right; color: #b91c1c;">-₦{{ number_format($payroll, 2) }}</td>
            </tr>
            <tr style="background-color: #f0fdfa; font-weight: bold;">
                <td><strong>NET OPERATING INCOME (EBITDA)</strong></td>
                <td style="text-align: right; color: #0d9488;"><strong>₦{{ number_format($netOperatingIncome, 2) }}</strong></td>
            </tr>
            <tr>
                <td>Tax Provision & Statutory Reserve (15%)</td>
                <td style="text-align: right; color: #b91c1c;">-₦{{ number_format($taxProvision, 2) }}</td>
            </tr>
            <tr style="background-color: #e6fffa; font-weight: 900; font-size: 12px;">
                <td style="color: #0f766e;"><strong>NET PROFIT AFTER TAX (NPAT)</strong></td>
                <td style="text-align: right; color: #0f766e;"><strong>₦{{ number_format($netProfit, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Regional Branch Breakdown Table -->
    <div class="section-label" style="font-size: 11px; margin-top: 20px;">Regional Branch Revenue Contribution Matrix</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Branch Location</th>
                <th>Role / Status</th>
                <th>Monthly Sales</th>
                <th>Revenue Share</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Abuja HQ (Neighborhood Centre, Area 3, Garki)</strong></td>
                <td><strong>Corporate Headquarters</strong></td>
                <td style="font-weight: bold;">₦12,450,000.00</td>
                <td style="font-weight: bold; color: #0d9488;">43.8%</td>
            </tr>
            <tr>
                <td>Lagos Commercial Branch (Victoria Island)</td>
                <td>Regional Office</td>
                <td>₦8,920,000.00</td>
                <td>31.3%</td>
            </tr>
            <tr>
                <td>Port Harcourt Depot & Logistics Hub</td>
                <td>Logistics Hub</td>
                <td>₦4,680,000.00</td>
                <td>16.5%</td>
            </tr>
            <tr>
                <td>Kano Regional Branch</td>
                <td>Regional Outlet</td>
                <td>₦2,400,000.00</td>
                <td>8.4%</td>
            </tr>
        </tbody>
    </table>

    <!-- Executive Sign-Off & Financial Ratios -->
    <div style="width: 100%; margin-top: 20px;">
        <div class="sign-box">
            <strong>FINANCIAL PERFORMANCE RATIOS</strong><br>
            Gross Profit Margin: <strong>65.4%</strong><br>
            Operating Margin: <strong>39.2%</strong><br>
            Net Profit Margin: <strong>33.3%</strong><br><br>
            <em>Authorized Executive Signatory:</em><br>
            <strong style="color: #0f172a;">Managing Director & CEO — Ascend Systems Ltd</strong>
        </div>

        <table class="totals-table">
            <tr>
                <td style="font-weight: bold; color: #64748b;">Total Gross Revenue:</td>
                <td style="text-align: right; font-weight: bold;">₦{{ number_format($grossRevenue, 2) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #b91c1c;">Total Expenses & Tax:</td>
                <td style="text-align: right; font-weight: bold; color: #b91c1c;">-₦{{ number_format($cogs + $opex + $payroll + $taxProvision, 2) }}</td>
            </tr>
            <tr style="border-top: 2px solid #0d9488; border-bottom: 2px solid #0d9488; background-color: #f0fdfa;">
                <td style="font-size: 14px; font-weight: 900; color: #0d9488; padding: 10px;">NET PROFIT AFTER TAX:</td>
                <td style="font-size: 15px; font-weight: 900; text-align: right; color: #0d9488; padding: 10px;">₦{{ number_format($netProfit, 2) }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <!-- Official Footer -->
    <div class="footer">
        <strong>Ascend Systems Nigeria Limited</strong> — Enterprise Resource Planning (ERP) Platform<br>
        Corporate HQ: {{ $companyAddress }} | Call: {{ $companyPhone }} | Mail: {{ $companyEmail }}<br>
        <em>This Executive Financial Report is an official system-generated document. Confidential for board and management review.</em>
    </div>
</body>
</html>
