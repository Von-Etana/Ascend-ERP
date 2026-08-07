<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $receipt->receipt_number }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #000; font-size: 12px; margin: 0; padding: 15px; width: 280px; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .table { width: 100%; font-size: 11px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="text-center bold" style="font-size: 16px;">{{ $companyName }}</div>
    <div class="text-center">{{ $companyAddress }}</div>
    <div class="divider"></div>

    <div><strong>Receipt #:</strong> {{ $receipt->receipt_number }}</div>
    <div><strong>Date:</strong> {{ $receipt->created_at?->format('Y-m-d H:i') ?: date('Y-m-d H:i') }}</div>
    <div><strong>Cashier:</strong> {{ $receipt->cashier_name }}</div>

    <div class="divider"></div>

    <table class="table">
        <tr>
            <td class="bold">Item Total</td>
            <td class="text-right bold">₦{{ number_format($receipt->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>VAT (7.5%)</td>
            <td class="text-right">₦{{ number_format($receipt->tax, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="table" style="font-size: 14px;">
        <tr class="bold">
            <td>TOTAL PAID</td>
            <td class="text-right">₦{{ number_format($receipt->total, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <div class="text-center">Paid via {{ strtoupper($receipt->payment_method) }}</div>
    <div class="text-center" style="margin-top: 10px;">*** Thank You! ***</div>
</body>
</html>
