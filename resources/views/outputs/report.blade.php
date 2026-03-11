<!-- resources/views/outputs/report.blade.php -->
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
            font-size: 11px;
            width: 62mm;
            margin: 0 auto;
            padding: 10px;
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
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .header h1 {
            font-size: 14px;
            margin: 0;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        .total-row {
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .red-text {
            color: #000;
            text-decoration: underline;
        }

        /* Em impressora térmica cor única, usamos estilo */
    </style>
</head>

<body>
    <div class="header text-center">
        <h1 class="font-bold" style="font-size: 14px;">RESUMO DE CAIXA</h1>
        <p class="font-bold">{{ $data['date'] }}</p>
    </div>

    <p class="font-bold" style="font-size: 9px; text-decoration: underline;">ENTRADAS POR MÉDOTO</p>
    <table class="table">
        <tr>
            <td>DINHEIRO:</td>
            <td align="right">R$ {{ number_format($data['money_in'], 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>CARTÃO DÉBITO:</td>
            <td align="right">R$ {{ number_format($data['debit'], 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>CARTÃO CRÉDITO:</td>
            <td align="right">R$ {{ number_format($data['credit'], 2, ',', '.') }}</td>
        </tr>
        <tr class="font-bold total-row">
            <td>FATURAMENTO BRUTO:</td>
            <td align="right">R$ {{ number_format($data['total_gross'], 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <p class="font-bold" style="font-size: 9px; text-decoration: underline;">FLUXO DE ESPÉCIE (GAVETA)</p>
    <table class="table">
        <tr>
            <td>VENDAS DINHEIRO (+):</td>
            <td align="right">R$ {{ number_format($data['money_in'], 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>SANGRIAS (-):</td>
            <td align="right">R$ {{ number_format($data['withdrawals'], 2, ',', '.') }}</td>
        </tr>
        <tr class="font-bold" style="font-size: 13px; border-top: 1px double #000;">
            <td>SALDO EM MÃOS:</td>
            <td align="right">R$ {{ number_format($data['net_cash_balance'], 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div style="font-size: 10px; line-height: 1.5;">
        <p>OPERAÇÕES REALIZADAS: {{ $data['count'] }}</p>
        <p>TICKET MÉDIO: R$ {{ number_format($data['total_gross'] / max($data['count'], 1), 2, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Emitido em: {{ now()->format('d/m/Y H:i:s') }}</p>
        <br><br>
        <p>___________________________________</p>
        <p>CONFERIDO POR: {{ auth()->user()->name }}</p>
    </div>
</body>

</html>