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
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.3;
            background: #f5f5f5;
            padding: 20px;
        }

        .page-container {
            max-width: 210mm;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Main Layout Table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table td {
            vertical-align: top;
        }

        /* Black borders for all boxes */
        .box {
            border: 2px solid #000;
            padding: 10px;
        }

        .box-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }

        /* Header Section */
        .header-box {
            border: 3px solid #000;
            padding: 15px;
            margin-bottom: 15px;
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
            font-size: 32pt;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .meta-table {
            border-collapse: collapse;
            margin-top: 10px;
        }

        .meta-table td {
            padding: 4px 8px;
            border: 1px solid #000;
            font-size: 9pt;
        }

        .meta-label {
            background: #000;
            color: #fff;
            font-weight: bold;
        }

        /* Info Boxes - BILL TO / SHIP TO */
        .info-row-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .info-row-table td {
            width: 50%;
            padding: 5px;
            vertical-align: top;
        }

        .info-box {
            border: 2px solid #000;
            padding: 12px;
            min-height: 180px;
            background: #fff;
        }

        .info-label {
            font-size: 7.5pt;
            color: #000;
            margin: 0;
            padding: 0;
            display: block;
        }

        .info-value {
            font-size: 10pt;
            font-weight: bold;
            margin: 0 0 10px 0;
            padding: 2px 0;
            display: block;
            border-bottom: 1px solid #ccc;
        }

        .address-box {
            border: 1px solid #000;
            padding: 8px;
            min-height: 80px;
            margin-top: 5px;
            font-size: 9pt;
        }

        .address-rtl {
            direction: rtl;
            text-align: right;
        }

        .address-ltr {
            direction: ltr;
            text-align: left;
        }

        /* QR Code in Header */
        .qr-code-wrapper {
            text-align: center;
            vertical-align: middle;
        }

        #qrcode {
            display: inline-block;
        }

        #qrcode img {
            width: 140px !important;
            height: 140px !important;
            margin: 0 auto;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .items-table th {
            background: #000;
            color: #fff;
            font-size: 9pt;
            font-weight: bold;
            padding: 10px 5px;
            border: 1px solid #000;
            text-align: left;
        }

        .items-table td {
            padding: 8px 5px;
            border: 1px solid #000;
            font-size: 9pt;
            vertical-align: top;
        }

        .items-table .text-center {
            text-align: center;
        }

        .items-table .text-right {
            text-align: right;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .product-sku {
            font-size: 8pt;
            color: #333;
        }

        .attr-badge {
            border: 1px solid #000;
            padding: 2px 6px;
            margin: 2px;
            font-size: 8pt;
            display: inline-block;
        }

        /* Totals Section */
        .totals-wrapper {
            width: 100%;
            margin-top: 15px;
        }

        .totals-container {
            width: 350px;
            float: right;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
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
            background: #000;
            color: #fff;
        }

        .grand-total-row td {
            font-size: 12pt;
            font-weight: bold;
            padding: 12px 10px;
        }

        /* Footer */
        .footer-box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
            font-size: 9pt;
        }

        .footer-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .thank-you {
            border: 2px solid #000;
            padding: 12px;
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-top: 10px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Edit Controls */
        .edit-controls {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .edit-controls h3 {
            margin-bottom: 10px;
            color: #856404;
        }

        .edit-controls p {
            margin: 5px 0;
            font-size: 11pt;
        }
    </style>
</head>
<body>
    @php
        // Check if address contains Arabic characters
        function hasArabic($text) {
            return preg_match('/[\x{0600}-\x{06FF}]/u', $text);
        }
        $isAddressArabic = hasArabic($order->shipping_address);
    @endphp

    <!-- HEADER -->
    <div class="header-box">
        <table class="main-table">
            <tr>
                <td style="width: 35%; vertical-align: top;">
                    <img src="{{ public_path('assets/images/rakazLogo.png') }}" alt="RAKAZ" class="logo">
                    <div class="company-info">
                        Your Company Address<br>
                        City, Country<br>
                        Phone: +123 456 789<br>
                        Email: info@rakaz.com
                    </div>
                </td>
                <td style="width: 30%; text-align: center; vertical-align: middle;">
                    <div class="qr-code-wrapper">
                        {!! $qrCode !!}
                    </div>
                </td>
                <td style="width: 35%; text-align: right; vertical-align: top;">
                    <div class="invoice-title">INVOICE</div>
                    <table class="meta-table" style="margin-left: auto; margin-right: 0;">
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

    <!-- BILL TO / SHIP TO -->
    <table class="info-row-table">
        <tr>
            <td>
                <div class="info-box">
                    <div class="box-title">BILL TO</div>
                    <table>
                        <tr>
                            <td>
                                <div class="info-label">Customer Name</div>
                                <div class="info-value">{{ $order->customer_name }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-label">Phone</div>
                                <div class="info-value">{{ $order->customer_phone }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $order->customer_email }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-label">Payment Method</div>
                                <div class="info-value">
                                    @if($order->payment_method == 'cash')
                                        Cash on Delivery
                                    @else
                                        {{ ucfirst($order->payment_method) }}
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="info-box">
                    <div class="box-title">SHIP TO</div>
                    <table>
                        <tr>
                            <td>
                                <div class="info-label">Shipping Address</div>
                                <div class="address-box {{ $isAddressArabic ? 'address-rtl' : 'address-ltr' }}">
                                    {{ $order->shipping_address }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
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
                    $productName = $product ? (is_array($product->name) ? ($product->name['en'] ?? $product->name['ar'] ?? 'Product') : $product->name) : 'Deleted Product';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $productName }}</div>
                        @if($product)
                            <div class="product-sku">SKU: {{ $product->sku }}</div>
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
                    <td class="text-right">{{ number_format($item->price, 2) }} OMR</td>
                    <td class="text-right"><strong>{{ number_format($item->price * $item->quantity, 2) }} OMR</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <div class="totals-wrapper clearfix">
        <div class="totals-container">
            <table class="totals-table">
                <tr>
                    <td class="totals-label">Subtotal:</td>
                    <td class="totals-value">{{ number_format($order->subtotal, 2) }} OMR</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td class="totals-label">Discount:</td>
                    <td class="totals-value">-{{ number_format($order->discount, 2) }} OMR</td>
                </tr>
                @endif
                <tr>
                    <td class="totals-label">Shipping:</td>
                    <td class="totals-value">{{ number_format($order->shipping_cost, 2) }} OMR</td>
                </tr>
                <tr class="grand-total-row">
                    <td>GRAND TOTAL:</td>
                    <td class="totals-value">{{ number_format($order->total, 2) }} OMR</td>
                </tr>
            </table>
        </div>
    </div>

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
