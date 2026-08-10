<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Receipt {{ $receipt->receipt_number }} — Ascend Systems</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #000; font-size: 11px; margin: 0; padding: 12px; width: 280px; line-height: 1.4; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .table { width: 100%; font-size: 11px; }
        .text-right { text-align: right; }
        .logo-badge { background-color: #000; color: #fff; padding: 3px 6px; font-weight: bold; font-size: 12px; display: inline-block; margin-bottom: 4px; }
        .small-text { font-size: 10px; }
    </style>
</head>
<body>
    <div class="text-center">
        <div class="logo-badge">▲ ASCEND AI</div>
        <div class="bold" style="font-size: 13px;">{{ $companyName }}</div>
        <div class="small-text">{{ $companyAddress }}</div>
        <div class="small-text">Call: {{ $companyPhone }}</div>
        <div class="small-text">Mail: {{ $companyEmail }}</div>
    </div>
    
    <div class="divider"></div>

    <div><strong>Receipt #:</strong> {{ $receipt->receipt_number }}</div>
    <div><strong>Date:</strong> {{ $receipt->created_at?->format('Y-m-d H:i') ?: date('Y-m-d H:i') }}</div>
    <div><strong>Cashier:</strong> {{ $receipt->cashier_name }}</div>

    <div class="divider"></div>

    <table class="table">
        <tr>
            <td class="bold">Item Subtotal</td>
            <td class="text-right bold">₦{{ number_format($receipt->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>VAT (7.5%)</td>
            <td class="text-right">₦{{ number_format($receipt->tax, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="table" style="font-size: 13px;">
        <tr class="bold">
            <td>TOTAL PAID</td>
            <td class="text-right">₦{{ number_format($receipt->total, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <div class="text-center bold">Paid via {{ strtoupper($receipt->payment_method) }}</div>
    <div class="text-center small-text" style="margin-top: 6px;">Thank you for shopping with {{ $companyName }}!</div>
    <div class="text-center small-text">info@ascendsystems.ng</div>
</body>
</html>
