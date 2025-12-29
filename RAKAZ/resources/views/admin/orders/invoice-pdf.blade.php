<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        @page {
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.4;
        }

        /* Main Layout Table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table td {
            vertical-align: top;
        }

        /* Header Section */
        .header-box {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 10px;
        }

        .logo {
            max-width: 100px;
            height: auto;
        }

        .company-info {
            font-size: 9pt;
            line-height: 1.5;
        }

        .invoice-title {
            font-size: 28pt;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .meta-table {
            border-collapse: collapse;
            margin-top: 10px;
        }

        .meta-table td {
            padding: 5px 8px;
            border: 1px solid #000;
            font-size: 9pt;
        }

        .meta-label {
            background-color: #000000;
            color: #ffffff;
            font-weight: bold;
        }

        /* QR Code */
        .qr-code-wrapper {
            text-align: center;
        }

        .qr-code-wrapper svg {
            width: 120px;
            height: 120px;
        }

        /* Info Boxes - BILL TO / SHIP TO */
        .info-section {
            border: 2px solid #000;
            padding: 0;
            margin: 10px 0;
        }

        .info-row-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .info-row-table > tbody > tr > td {
            width: 50%;
            padding: 0;
            vertical-align: top;
            border-right: 2px solid #000;
        }

        .info-row-table > tbody > tr > td:last-child {
            border-right: none;
        }

        .info-box {
            border: none;
            padding: 0;
            background: #fff;
            height: 100%;
        }

        .box-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #000000;
            color: #ffffff;
            padding: 8px;
            text-align: center;
            height: 32px;
            line-height: 16pt;
            display: table-cell;
            vertical-align: middle;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .info-box table tr:last-child td {
            border-bottom: none;
        }

        .info-label {
            font-size: 8pt;
            color: #666;
            display: block;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 10pt;
            font-weight: bold;
            color: #000;
            word-wrap: break-word;
        }

        .address-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 5px;
            font-size: 10pt;
            background: #f9f9f9;
            min-height: 80px;
            font-weight: bold;
        }

        .address-rtl {
            direction: rtl;
            text-align: right;
        }

        .address-ltr {
            direction: ltr;
            text-align: left;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .items-table th {
            background-color: #000000;
            color: #ffffff;
            font-size: 9pt;
            font-weight: bold;
            padding: 8px 5px;
            border: 1px solid #000;
            text-align: left;
        }

        .items-table td {
            padding: 8px 5px;
            border: 1px solid #000;
            font-size: 9pt;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .product-sku {
            font-size: 8pt;
            color: #666;
        }

        .attr-badge {
            border: 1px solid #000;
            padding: 2px 6px;
            margin: 2px;
            font-size: 8pt;
            display: inline-block;
        }

        /* Totals Section */
        .totals-table {
            width: 350px;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-left: auto;
            margin-top: 10px;
        }

        .totals-table td {
            padding: 8px 10px;
            border: 1px solid #000;
            font-size: 10pt;
        }

        .totals-label {
            font-weight: bold;
            width: 60%;
        }

        .totals-value {
            text-align: right;
            width: 40%;
        }

        .grand-total-row {
            background-color: #000000;
        }

        .grand-total-row td {
            font-size: 11pt;
            font-weight: bold;
            padding: 10px;
            color: #ffffff;
        }

        /* Footer */
        .footer-box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 15px;
            font-size: 9pt;
        }

        .footer-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .thank-you {
            border: 2px solid #000;
            padding: 10px;
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    @php
        function hasArabic($text) {
            return preg_match('/[\x{0600}-\x{06FF}]/u', $text);
        }
        $isAddressArabic = hasArabic($order->shipping_address ?? '');
    @endphp

    <!-- HEADER -->
    <div class="header-box">
        <table class="main-table">
            <tr>
                <td style="width: 35%;">
                    <img src="{{ public_path('assets/images/rakazLogo.png') }}" alt="RAKAZ" class="logo">
                    <div class="company-info">
                        RAKAZ Store<br>
                        Riyadh, Saudi Arabia<br>
                        Phone: +966 XXX XXXX<br>
                        Email: info@rakaz.com
                    </div>
                </td>
                <td style="width: 30%; text-align: center;">
                    <div class="qr-code-wrapper">
                        {!! $qrCode !!}
                    </div>
                </td>
                <td style="width: 35%; text-align: right;">
                    <div class="invoice-title">INVOICE</div>
                    <table class="meta-table" style="margin-left: auto;">
                        <tr>
                            <td class="meta-label">Invoice No:</td>
                            <td>{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Date:</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Status:</td>
                            <td>{{ strtoupper($order->status) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- BILL TO / SHIP TO - FORCED ALIGNED STRUCTURE -->
    <table style="width: 100%; border: 2px solid #000; border-collapse: collapse; margin: 10px 0;">
        <!-- HEADER ROW - TITLES ON SAME LINE -->
        <tr>
            <td style="width: 50%; background-color: #000000; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; padding: 10px; border-right: 2px solid #000; border-bottom: 2px solid #000;">
                BILL TO
            </td>
            <td style="width: 50%; background-color: #000000; color: #ffffff; font-weight: bold; font-size: 11pt; text-align: center; padding: 10px; border-bottom: 2px solid #000;">
                SHIP TO
            </td>
        </tr>
        <!-- DATA ROW -->
        <tr>
            <td style="width: 50%; padding: 0; vertical-align: top; border-right: 2px solid #000;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <span style="font-size: 8pt; color: #666; text-transform: uppercase; display: block; margin-bottom: 4px;">Customer Name</span>
                            <span style="font-size: 10pt; font-weight: bold; color: #000;">{{ $order->customer_name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <span style="font-size: 8pt; color: #666; text-transform: uppercase; display: block; margin-bottom: 4px;">Phone Number</span>
                            <span style="font-size: 10pt; font-weight: bold; color: #000;">{{ $order->customer_phone }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <span style="font-size: 8pt; color: #666; text-transform: uppercase; display: block; margin-bottom: 4px;">Email Address</span>
                            <span style="font-size: 10pt; font-weight: bold; color: #000;">{{ $order->customer_email }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">
                            <span style="font-size: 8pt; color: #666; text-transform: uppercase; display: block; margin-bottom: 4px;">Payment Method</span>
                            <span style="font-size: 10pt; font-weight: bold; color: #000;">
                                @if($order->payment_method == 'cash')
                                    Cash on Delivery
                                @elseif($order->payment_method == 'card')
                                    Credit/Debit Card
                                @elseif($order->payment_method == 'bank_transfer')
                                    Bank Transfer
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                @endif
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; padding: 0; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <span style="font-size: 8pt; color: #666; text-transform: uppercase; display: block; margin-bottom: 4px;">Shipping Address</span>
                            <div style="border: 1px solid #ddd; padding: 10px; margin-top: 5px; font-size: 10pt; background-color: #f9f9f9; min-height: 60px; font-weight: bold; {{ $isAddressArabic ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}">
                                {{ $order->shipping_address }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px;">
                            <span style="font-size: 8pt; color: #666; text-transform: uppercase; display: block; margin-bottom: 4px;">City / Country</span>
                            <span style="font-size: 10pt; font-weight: bold; color: #000;">
                                {{ $order->shipping_city ? $order->shipping_city . ', ' : '' }}{{ $order->shipping_country ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ORDER ITEMS -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 35%;">DESCRIPTION</th>
                <th style="width: 20%;">ATTRIBUTES</th>
                <th class="text-center" style="width: 10%;">QTY</th>
                <th class="text-right" style="width: 15%;">UNIT PRICE</th>
                <th class="text-right" style="width: 15%;">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                @php
                    $product = $item->product;
                    // Get English product name
                    if ($product && $product->name) {
                        if (is_array($product->name)) {
                            $productName = $product->name['en'] ?? $product->name['ar'] ?? 'Product';
                        } else {
                            // If it's JSON string
                            $nameArray = json_decode($product->name, true);
                            $productName = $nameArray['en'] ?? $nameArray['ar'] ?? $product->name;
                        }
                    } else {
                        $productName = $item->product_name ?? 'Product';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $productName }}</div>
                        @if($item->product_sku)
                            <div class="product-sku">SKU: {{ $item->product_sku }}</div>
                        @endif
                    </td>
                    <td>
                        @if($item->color)
                            <span class="attr-badge">{{ $item->color }}</span>
                        @endif
                        @if($item->size)
                            <span class="attr-badge">{{ $item->size }}</span>
                        @endif
                        @if(!$item->color && !$item->size)
                            -
                        @endif
                    </td>
                    <td class="text-center"><strong>{{ $item->quantity }}</strong></td>
                    <td class="text-right">{{ number_format($item->price, 2) }} AED</td>
                    <td class="text-right"><strong>{{ number_format($item->price * $item->quantity, 2) }} AED</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <table class="totals-table">
        <tr>
            <td class="totals-label">Subtotal:</td>
            <td class="totals-value">{{ number_format($order->subtotal, 2) }} AED</td>
        </tr>
        @if($order->discount > 0)
        <tr>
            <td class="totals-label">Discount:</td>
            <td class="totals-value">-{{ number_format($order->discount, 2) }} AED</td>
        </tr>
        @endif
        <tr>
            <td class="totals-label">Shipping:</td>
            <td class="totals-value">{{ number_format($order->shipping_cost, 2) }} AED</td>
        </tr>
        <tr class="grand-total-row">
            <td>GRAND TOTAL:</td>
            <td class="totals-value">{{ number_format($order->total, 2) }} AED</td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer-box">
        <div class="footer-title">Terms & Conditions:</div>
        Payment is due within 15 days. Please include invoice number with your payment.<br>
        All sales are final. Returns accepted within 7 days with receipt.
    </div>

    <div class="thank-you">
        THANK YOU FOR YOUR BUSINESS!
    </div>

</body>
</html>
