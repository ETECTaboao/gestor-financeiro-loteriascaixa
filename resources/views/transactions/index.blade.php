<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Minhas Transações</h1>

        <!-- Link para adicionar nova transação -->
        <a href="{{ route('transactions.create') }}" class="btn btn-success mb-3">Adicionar Transação</a>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- Exibir o total gasto, total de ganhos e dinheiro restante no mês -->
        <div class="mb-3">
            <h4>Total Gasto no Mês: <span class="badge bg-danger">R$ {{ number_format($totalGasto, 2, ',', '.') }}</span></h4>
            <h4>Total de Ganhos no Mês: <span class="badge bg-success">R$ {{ number_format($totalGanho, 2, ',', '.') }}</span></h4>
            <h4>Dinheiro Restante: <span class="badge bg-primary">R$ {{ number_format($dinheiroRestante, 2, ',', '.') }}</span></h4>
        </div>

        <!-- Tabela de transações -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->type == 'gasto' ? 'Gasto' : 'Ganho' }}</td>
                        <td>{{ $transaction->category }}</td>
                        <td>R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                        <td>{{ $transaction->description ?? 'Sem descrição' }}</td>
                        <td>
                            <!-- Editar transação -->
                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-warning btn-sm">Editar</a>

                            <!-- Deletar transação -->
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta transação?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Você ainda não tem transações registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Link para gerar o PDF -->
        <a href="{{ route('transactions.generatePdf') }}" class="btn btn-primary mb-3">Gerar Relatório em PDF</a>


        <!-- Botão de logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary mt-3">Sair</button>
        </form>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
