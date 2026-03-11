<!-- resources/views/outputs/receipt-withdrawal.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Sangria - {{ $output->id }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            padding: 5mm;
            margin: 0;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .border-top {
            border-top: 1px dashed #000;
            margin-top: 5mm;
            padding-top: 5mm;
        }

        .border-bottom {
            border-bottom: 1px dashed #000;
            margin-bottom: 5mm;
            padding-bottom: 5mm;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .m-0 {
            margin: 0;
        }

        .mb-2 {
            margin-bottom: 2mm;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        .signature-line {
            margin-top: 15mm;
            border-top: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div class="text-center mb-2">
        <h2 class="m-0 uppercase font-bold">ERP Estoque Pro</h2>
        <p class="m-0">Comprovante de Sangria de Caixa</p>
    </div>

    <div class="border-top border-bottom">
        <div class="flex">
            <span class="font-bold">ID OPERACAO:</span>
            <span>{{ substr($output->id, 0, 8) }}</span>
        </div>
        <div class="flex">
            <span class="font-bold">DATA/HORA:</span>
            <span>{{ $output->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex">
            <span class="font-bold">OPERADOR:</span>
            <span class="uppercase">{{ auth()->user()->name }}</span>
        </div>
        <div class="flex">
            <span class="font-bold">RESPONSAVEL:</span>
            <span class="uppercase">{{ $output->employee->name }}</span>
        </div>
    </div>

    <div class="text-center mb-2">
        <p class="m-0 uppercase font-bold" style="font-size: 16px;">Valor Retirado</p>
        <h1 class="m-0">R$ {{ number_format($output->total_price, 2, ',', '.') }}</h1>
    </div>

    <div class="mb-2">
        <p class="font-bold m-0 uppercase">Motivo/Notas:</p>
        <p class="m-0 italic">{{ $output->notes ?? 'Sangria manual de valores' }}</p>
    </div>

    <div class="border-top text-center" style="margin-top: 10mm;">
        <div class="signature-line"></div>
        <p class="m-0 uppercase text-center" style="font-size: 9px;">Assinatura do Gerente</p>

        <div class="signature-line" style="margin-top: 10mm;"></div>
        <p class="m-0 uppercase text-center" style="font-size: 9px;">Assinatura do Operador</p>
    </div>

    <div class="text-center" style="margin-top: 8mm; font-size: 9px;">
        <p class="m-0 uppercase">Ambiente Seguro - Verificado</p>
        <p class="m-0">Via Unica do Estabelecimento</p>
    </div>
</body>

</html>