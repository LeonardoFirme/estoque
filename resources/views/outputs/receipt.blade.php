<!-- resources/views/outputs/receipt.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier', monospace;
            font-size: 10px;
            margin: 0;
            padding: 5mm;
            line-height: 1.2;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .header {
            margin-bottom: 10px;
            text-align: center;
        }

        .header h1 {
            font-size: 14px;
            margin: 0 0 5px 0;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
            width: 100%;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            font-size: 10px;
        }

        .table td {
            vertical-align: top;
            padding: 5px 0;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="font-bold">ERP ESTOQUE</h1>
        <p>VENDA PRESENCIAL - PDV 01</p>
        <p>{{ $saleDate }}</p>
        <p>CUPOM: {{ $saleId }}</p>
    </div>

    <div class="divider"></div>

    <table class="table">
        <thead>
            <tr class="font-bold">
                <th align="left">DESCRIÇÃO</th>
                <th align="center">QTD</th>
                <th align="right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <span class="uppercase">{{ $item->product->name }}</span><br>
                        <small>REF: {{ $item->product->sku }}</small>
                    </td>
                    <td align="center">{{ $item->quantity }}</td>
                    <td align="right">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table style="width: 100%;" class="font-bold">
        <tr>
            <td>TOTAL BRUTO</td>
            <td align="right">R$ {{ number_format($totalGross, 2, ',', '.') }}</td>
        </tr>
        @if($totalDiscount > 0)
            <tr>
                <td>TOTAL DESCONTOS</td>
                <td align="right">- R$ {{ number_format($totalDiscount, 2, ',', '.') }}</td>
            </tr>
        @endif
        <tr style="font-size: 12px;">
            <td>TOTAL LÍQUIDO</td>
            <td align="right">R$ {{ number_format($totalNet, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="payment-info">
        <p class="font-bold">PAGAMENTO: <span class="uppercase">{{ $paymentMethod }}</span></p>
        @if($installments > 1)
            <p>PARCELAMENTO: {{ $installments }}x de R$ {{ number_format($totalNet / $installments, 2, ',', '.') }}</p>
        @endif
        <p>VENDEDOR: <span class="uppercase">{{ $employeeName }}</span></p>
    </div>

    <div class="footer">
        <p class="font-bold uppercase">Obrigado pela preferência!</p>
        <p>SISTEMA ERP PROFISSIONAL</p>
    </div>
</body>

</html>