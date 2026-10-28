<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->invoice_number }}</title>
    <style>
        body {
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .header {
            border-bottom: 3px solid #06b6d4;
            margin-bottom: 28px;
            padding-bottom: 18px;
        }

        .brand {
            color: #2563eb;
            font-size: 24px;
            font-weight: bold;
        }

        .muted {
            color: #64748b;
        }

        .invoice-meta {
            margin: 20px 0 28px;
            width: 100%;
        }

        .invoice-meta td {
            vertical-align: top;
            width: 50%;
        }

        table.items {
            border-collapse: collapse;
            margin-top: 20px;
            width: 100%;
        }

        .items th,
        .items td {
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 10px;
            text-align: left;
        }

        .items th {
            background: #f1f5f9;
        }

        .number {
            text-align: right !important;
        }

        .total {
            color: #0f172a;
            font-size: 16px;
            font-weight: bold;
        }

        .status {
            color: #b45309;
            font-weight: bold;
        }

        .footer {
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            margin-top: 48px;
            padding-top: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">SabuMarket</div>
        <div class="muted">Invoice pesanan produk</div>
    </div>

    <table class="invoice-meta">
        <tr>
            <td>
                <strong>Ditagihkan kepada</strong><br>
                {{ $order->user->name }}<br>
                {{ $order->user->email }}
            </td>
            <td class="number">
                <strong>Nomor invoice</strong><br>
                {{ $order->invoice_number }}<br>
                <span class="muted">{{ $order->created_at->format('d/m/Y H:i') }}</span><br>
                Status: <span class="status">Belum dibayar</span>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="number">Harga satuan</th>
                <th class="number">Jumlah</th>
                <th class="number">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->product_name }}</td>
                <td class="number">Rp {{ number_format((float) $order->unit_price, 2, ',', '.') }}</td>
                <td class="number">{{ $order->quantity }}</td>
                <td class="number">Rp {{ number_format((float) $order->total, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="number total">Total</td>
                <td class="number total">Rp {{ number_format((float) $order->total, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Terima kasih telah berbelanja di SabuMarket.
    </div>
</body>
</html>
