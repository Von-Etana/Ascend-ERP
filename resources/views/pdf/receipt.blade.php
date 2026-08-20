<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>POS Receipt {{ $receipt->receipt_number }} — Ascend Systems</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif !important; }
        body { font-family: 'DejaVu Sans', sans-serif !important; font-size: 11px; line-height: 1.4; color: #000; margin: 0; padding: 10px; }
        .text-center { text-align: center; }
        .company-logo-img { max-height: 40px; width: auto; margin: 0 auto 6px auto; display: block; }
        .logo-badge { background-color: #000; color: #fff; padding: 3px 6px; font-weight: bold; font-size: 12px; display: inline-block; margin-bottom: 4px; }
        .company-name { font-weight: bold; font-size: 13px; text-transform: uppercase; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { text-align: left; padding: 2px 0; font-size: 10px; }
        .table .right { text-align: right; }
        .table .center { text-align: center; }
        .total-row { font-weight: bold; font-size: 12px; }
        .footer { font-size: 9px; text-align: center; margin-top: 12px; }
    </style>
</head>
<body>
    @php
        $itemsData = is_array($receipt->items) ? $receipt->items : [];
        $cartItems = $itemsData['cart'] ?? (isset($itemsData[0]) ? $itemsData : []);
        $customerName = $itemsData['customer_name'] ?? ($receipt->customer_name ?? 'Walk-in Retail Customer');
        $customerPhone = $itemsData['customer_phone'] ?? '';
        $customerEmail = $itemsData['customer_email'] ?? '';
        $discountAmount = (float) ($itemsData['discount_amount'] ?? 0);
        $promoCode = $itemsData['promo_code'] ?? '';
    @endphp

    <div class="text-center">
        @if (!empty($companyLogo))
            <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="company-logo-img">
        @else
            <div class="logo-badge">▲ ASCEND SYSTEMS</div>
        @endif
        <div class="company-name">{{ $companyName }}</div>
        <div>{{ $companyAddress }}</div>
        <div>TEL: {{ $companyPhone }}</div>
        <div>MAIL: {{ $companyEmail }}</div>
    </div>

    <div class="divider"></div>

    <div>
        <div><strong>RECEIPT #:</strong> {{ $receipt->receipt_number }}</div>
        <div><strong>DATE:</strong> {{ $receipt->created_at?->format('Y-m-d H:i:s') ?: date('Y-m-d H:i:s') }}</div>
        <div><strong>CASHIER:</strong> {{ $receipt->cashier_name }}</div>
        <div><strong>CUSTOMER:</strong> {{ $customerName }}</div>
        @if (!empty($customerPhone))
            <div><strong>TEL:</strong> {{ $customerPhone }}</div>
        @endif
        <div><strong>PAYMENT:</strong> {{ strtoupper($receipt->payment_method ?: 'CASH') }}</div>
    </div>

    <div class="divider"></div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 50%;">ITEM</th>
                <th class="center" style="width: 15%;">QTY</th>
                <th class="right" style="width: 35%;">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($cartItems) && is_array($cartItems))
                @foreach ($cartItems as $item)
                    @php
                        $qty = (float) ($item['quantity'] ?? 1);
                        $price = (float) ($item['unit_price'] ?? ($item['price'] ?? 0));
                        $amt = isset($item['amount']) ? (float) $item['amount'] : ($qty * $price);
                    @endphp
                    <tr>
                        <td>{{ $item['description'] ?? ($item['title'] ?? ($item['name'] ?? 'Product Item')) }}</td>
                        <td class="center">{{ $qty }}</td>
                        <td class="right">&#8358;{{ number_format($amt, 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>POS Sale Item</td>
                    <td class="center">1</td>
                    <td class="right">&#8358;{{ number_format($receipt->subtotal, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="table">
        <tr>
            <td>SUBTOTAL:</td>
            <td class="right">&#8358;{{ number_format($receipt->subtotal, 2) }}</td>
        </tr>
        @if ($discountAmount > 0)
            <tr>
                <td>DISCOUNT @if($promoCode)({{ strtoupper($promoCode) }})@endif:</td>
                <td class="right">- &#8358;{{ number_format($discountAmount, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td>VAT (7.5%):</td>
            <td class="right">&#8358;{{ number_format($receipt->tax, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL PAID:</td>
            <td class="right">&#8358;{{ number_format($receipt->total, 2) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer">
        THANK YOU FOR YOUR BUSINESS!<br>
        GOODS BOUGHT IN GOOD CONDITION ARE NON-REFUNDABLE.<br>
        *** POWERED BY ASCEND AI ERP ***
    </div>
</body>
</html>
