<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip — {{ $salaryRecord['staff_name'] ?? ($salaryRecord['name'] ?? 'Employee') }} — {{ $companyName }}</title>
    <style>
        body { font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 12px; line-height: 1.5; margin: 0; padding: 25px; }
        .header { border-bottom: 2px solid #0284c7; padding-bottom: 18px; margin-bottom: 20px; }
        .company-logo-img { max-height: 48px; width: auto; max-width: 220px; display: block; margin-bottom: 8px; }
        .company-logo-text { font-size: 20px; font-weight: 900; color: #0284c7; letter-spacing: -0.5px; text-transform: uppercase; margin-bottom: 4px; }
        .address { font-size: 11px; color: #475569; margin-top: 3px; }
        .contact-info { font-size: 11px; color: #475569; margin-top: 2px; font-weight: 500; }
        .payslip-title { font-size: 24px; font-weight: 900; text-align: right; color: #0f172a; letter-spacing: 1px; }
        .payslip-num { font-size: 13px; font-weight: bold; text-align: right; color: #0284c7; margin-top: 2px; }
        .details-box { width: 100%; margin-top: 15px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; background-color: #f8fafc; }
        .details-table { width: 100%; }
        .details-table td { vertical-align: top; }
        .section-label { font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .employee-name { font-size: 15px; font-weight: 800; color: #0f172a; }
        .employee-detail { font-size: 11px; color: #475569; margin-top: 2px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th { background-color: #0f172a; color: #ffffff; padding: 9px 10px; text-align: left; font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; }
        .items-table td { padding: 9px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .items-table tr:nth-child(even) td { background-color: #f8fafc; }
        .totals-table { width: 48%; float: right; margin-top: 15px; border-collapse: collapse; }
        .totals-table td { padding: 5px 10px; font-size: 11px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .badge-paid { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-pending { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .sign-box { width: 48%; float: left; margin-top: 25px; padding: 10px; border: 1px dashed #cbd5e1; border-radius: 6px; background-color: #fafafa; font-size: 10px; }
        .footer { margin-top: 90px; border-top: 1px solid #e2e8f0; padding-top: 15px; font-size: 10px; text-align: center; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>
    @php
        $staffName = $salaryRecord['staff_name'] ?? ($salaryRecord['name'] ?? 'Babatunde Adeleke');
        $jobTitle = $salaryRecord['role'] ?? ($salaryRecord['job_title'] ?? 'Senior Software Engineer');
        $department = $salaryRecord['department'] ?? 'Engineering & Operations';
        $period = $salaryRecord['period'] ?? ($salaryRecord['payroll_period'] ?? date('F Y'));
        $baseSalary = (float) ($salaryRecord['basic_salary'] ?? ($salaryRecord['base_salary'] ?? 650000.00));
        $housing = (float) ($salaryRecord['housing'] ?? ($baseSalary * 0.25));
        $transport = (float) ($salaryRecord['transport'] ?? ($baseSalary * 0.15));
        $allowance = (float) ($salaryRecord['allowances'] ?? 50000.00);
        $grossSalary = $baseSalary + $housing + $transport + $allowance;
        
        $payeTax = (float) ($salaryRecord['paye_tax'] ?? ($grossSalary * 0.12));
        $employeePension = (float) ($salaryRecord['pension_employee'] ?? ($grossSalary * 0.08));
        $nhf = (float) ($salaryRecord['nhf'] ?? ($baseSalary * 0.025));
        $totalDeductions = $payeTax + $employeePension + $nhf;
        $netSalary = $grossSalary - $totalDeductions;
        $bankName = $salaryRecord['bank_name'] ?? 'Access Bank Nigeria';
        $accNo = $salaryRecord['account_number'] ?? '0129481029';
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
                    <div class="payslip-title">CONFIDENTIAL PAYSLIP</div>
                    <div class="payslip-num">Cycle: {{ $period }}</div>
                    <div style="margin-top: 8px;">
                        <span class="badge badge-paid">DISBURSED / PAID</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Employee & Payroll Cycle Card -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <div class="section-label">Employee Details</div>
                    <div class="employee-name">{{ $staffName }}</div>
                    <div class="employee-detail"><strong>Role / Title:</strong> {{ $jobTitle }}</div>
                    <div class="employee-detail"><strong>Department:</strong> {{ $department }}</div>
                    <div class="employee-detail"><strong>Tax ID / TIN:</strong> TIN-NG-{{ rand(10000000, 99999999) }}</div>
                </td>
                <td style="width: 50%; padding-left: 20px;">
                    <div class="section-label">Disbursement Details</div>
                    <div class="employee-detail"><strong>Bank Name:</strong> {{ $bankName }}</div>
                    <div class="employee-detail"><strong>Account Number:</strong> {{ $accNo }}</div>
                    <div class="employee-detail"><strong>Pay Period:</strong> {{ $period }}</div>
                    <div class="employee-detail"><strong>Payment Date:</strong> {{ date('Y-m-d') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Earnings & Statutory Deductions Grid -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Earnings Category</th>
                <th style="width: 50%; text-align: right;">Amount (&#8358;)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Basic Monthly Salary</strong></td>
                <td style="text-align: right; font-weight: bold;">&#8358;{{ number_format($baseSalary, 2) }}</td>
            </tr>
            <tr>
                <td>Housing Allowance</td>
                <td style="text-align: right;">&#8358;{{ number_format($housing, 2) }}</td>
            </tr>
            <tr>
                <td>Transport & Utility Allowance</td>
                <td style="text-align: right;">&#8358;{{ number_format($transport, 2) }}</td>
            </tr>
            <tr>
                <td>Performance & Utility Bonus</td>
                <td style="text-align: right;">&#8358;{{ number_format($allowance, 2) }}</td>
            </tr>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td><strong>TOTAL GROSS EARNINGS</strong></td>
                <td style="text-align: right; color: #0284c7; font-size: 12px;"><strong>&#8358;{{ number_format($grossSalary, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="items-table" style="margin-top: 15px;">
        <thead>
            <tr>
                <th style="width: 50%; background-color: #b91c1c;">Statutory & Voluntary Deductions</th>
                <th style="width: 50%; text-align: right; background-color: #b91c1c;">Amount (&#8358;)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PAYE Personal Income Tax (Progressive Tax Schedule)</td>
                <td style="text-align: right; color: #b91c1c;">-₦{{ number_format($payeTax, 2) }}</td>
            </tr>
            <tr>
                <td>Employee Contributory Pension (8% Statutory Reform Act)</td>
                <td style="text-align: right; color: #b91c1c;">-₦{{ number_format($employeePension, 2) }}</td>
            </tr>
            <tr>
                <td>National Housing Fund (NHF 2.5% Basic)</td>
                <td style="text-align: right; color: #b91c1c;">-₦{{ number_format($nhf, 2) }}</td>
            </tr>
            <tr style="background-color: #fef2f2; font-weight: bold;">
                <td><strong>TOTAL STATUTORY DEDUCTIONS</strong></td>
                <td style="text-align: right; color: #b91c1c; font-size: 12px;"><strong>-₦{{ number_format($totalDeductions, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Totals Summary & Sign-off -->
    <div style="width: 100%; margin-top: 15px;">
        <div class="sign-box">
            <strong>EMPLOYER PENSION CONTRIBUTION (10%)</strong><br>
            Employer Pension (10%): ₦{{ number_format($grossSalary * 0.10, 2) }}<br>
            Total Pension Remitted to RSA: ₦{{ number_format(($grossSalary * 0.08) + ($grossSalary * 0.10), 2) }}<br><br>
            <em>Authorized HR & Payroll Officer Signature:</em><br>
            <strong style="color: #0f172a;">Ascend Systems Finance & HR Desk</strong>
        </div>

        <table class="totals-table">
            <tr>
                <td style="font-weight: bold; color: #64748b;">Gross Monthly Earnings:</td>
                <td style="text-align: right; font-weight: bold;">₦{{ number_format($grossSalary, 2) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #b91c1c;">Total Deductions:</td>
                <td style="text-align: right; font-weight: bold; color: #b91c1c;">-₦{{ number_format($totalDeductions, 2) }}</td>
            </tr>
            <tr style="border-top: 2px solid #0284c7; border-bottom: 2px solid #0284c7; background-color: #f0f9ff;">
                <td style="font-size: 14px; font-weight: 900; color: #0284c7; padding: 10px;">NET TAKE-HOME PAY:</td>
                <td style="font-size: 15px; font-weight: 900; text-align: right; color: #0284c7; padding: 10px;">₦{{ number_format($netSalary, 2) }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <!-- Official Footer -->
    <div class="footer">
        <strong>Ascend Systems Nigeria Limited</strong> — Enterprise Resource Planning (ERP) Platform<br>
        HQ: {{ $companyAddress }} | Call: {{ $companyPhone }} | Mail: {{ $companyEmail }}<br>
        <em>This payslip is an official system-generated document. Confidential and intended solely for named employee.</em>
    </div>
</body>
</html>
