<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Transações</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Cabeçalho */
        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 32px;
            color: #4CAF50;
            margin: 0;
        }

        header p {
            font-size: 18px;
            color: #555;
            margin: 5px 0;
        }

        /* Estilo da Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Rodapé */
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
        }

        .total span {
            color: #4CAF50;
        }
    </style>
</head>
<body>

    <!-- Cabeçalho -->
    <header>
        <h1>Relatório de Transações</h1>
        <p>Período: {{ \Carbon\Carbon::now()->format('F Y') }}</p>
    </header>

    <!-- Tabela de Transações -->
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Categoria</th>
                <th>Valor</th>
                <th>Descrição</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->type == 'gasto' ? 'Gasto' : 'Ganho' }}</td>
                    <td>{{ $transaction->category }}</td>
                    <td>R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                    <td>{{ $transaction->description ?? 'Sem descrição' }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total de Gastos e Ganhos -->
    <div class="total">
        <p>Total de Gastos: <span>R$ {{ number_format($totalGasto, 2, ',', '.') }}</span></p>
        <p>Total de Ganhos: <span>R$ {{ number_format($totalGanho, 2, ',', '.') }}</span></p>
        <p>Saldo Restante: <span>R$ {{ number_format($totalGanho - $totalGasto, 2, ',', '.') }}</span></p>
    </div>

    <!-- Rodapé -->
    <footer>
        <p>Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </footer>

</body>
</html>
