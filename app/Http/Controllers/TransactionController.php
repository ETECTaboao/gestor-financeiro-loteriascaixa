<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
<<<<<<< HEAD
use Illuminate\Http\Request;

=======
>>>>>>> d2187f2a6e63054ca44c639dca86d1ca0b2131e5

class TransactionController extends Controller
{
    // Exibe a lista de transações
    public function index(Request $request)
    {
<<<<<<< HEAD
        // Definindo o mês atual
        $currentMonth = Carbon::now()->month;

        // Obter os parâmetros de filtro, caso existam
        $type = $request->get('type'); // tipo de transação (gasto/ganho)
        $category = $request->get('category'); // categoria da transação
        $startDate = $request->get('start_date'); // data de início
        $endDate = $request->get('end_date'); // data de fim

        // Query base para transações do usuário logado
        $query = Transaction::where('user_id', Auth::id());

        // Aplicando os filtros
        if ($type) {
            $query->where('type', $type);
        }

        if ($category) {
            $query->where('category', 'like', "%$category%");
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate));
        }

        // Obter as transações filtradas
        $transactions = $query->get();

        // Calcular os totais de gasto e ganho com os filtros aplicados
        $totalGasto = $transactions->where('type', 'gasto')->sum('amount');
        $totalGanho = $transactions->where('type', 'ganho')->sum('amount');
        $dinheiroRestante = $totalGanho - $totalGasto;

        // Passar os dados para a view
=======
       // Obter o mês atual
        $currentMonth = Carbon::now()->month;

        // Calcular o total gasto no mês
        $totalGasto = Transaction::where('user_id', Auth::id())
            ->where('type', 'gasto')
            ->whereMonth('created_at', $currentMonth)
            ->sum('amount');

        // Calcular o total de ganhos no mês
        $totalGanho = Transaction::where('user_id', Auth::id())
            ->where('type', 'ganho')
            ->whereMonth('created_at', $currentMonth)
            ->sum('amount');

        // Calcular o dinheiro restante (ganhos - gastos)
        $dinheiroRestante = $totalGanho - $totalGasto;

        // Buscar as transações do usuário
        $transactions = Transaction::where('user_id', Auth::id())->get();

>>>>>>> d2187f2a6e63054ca44c639dca86d1ca0b2131e5
        return view('transactions.index', compact('transactions', 'totalGasto', 'totalGanho', 'dinheiroRestante'));
    }

    // Formulário de criação de transação
    public function create()
    {
        return view('transactions.create');
    }

    // Armazena uma nova transação
    public function store(StoreTransactionRequest $request)
    {
        // A request já está validada pela StoreTransactionRequest
        Transaction::create([
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('transactions.index');
    }

    // Exibe o formulário para editar uma transação
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.edit', compact('transaction'));
    }

    // Atualiza uma transação
    public function update(UpdateTransactionRequest $request, $id)
    {
        // A request já está validada pela UpdateTransactionRequest
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'type' => $request->type,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('transactions.index');
    }

    // Deleta uma transação
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index');
    }

// Função para gerar o PDF com as transações
public function generatePdf()
{
    // Verifique se o usuário está autenticado
    if (Auth::check()) {
        // Pega todas as transações do usuário logado
        $transactions = Transaction::where('user_id', Auth::id())->get();

        // Calcular o total gasto no mês
        $totalGasto = Transaction::where('user_id', Auth::id())
            ->where('type', 'gasto')
            ->sum('amount');

        // Calcular o total de ganhos no mês
        $totalGanho = Transaction::where('user_id', Auth::id())
            ->where('type', 'ganho')
            ->sum('amount');

        // Instancia o DomPDF
        $dompdf = new Dompdf();

        // Configurações de opções
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);

        // Carrega a view para gerar o conteúdo do PDF
        $html = view('transactions.pdf', compact('transactions', 'totalGasto', 'totalGanho'))->render();

        // Carrega o HTML no DomPDF
        $dompdf->loadHtml($html);

        // (Opcional) Define o tamanho da página
        $dompdf->setPaper('A4', 'landscape'); // Pode ser 'portrait' ou 'landscape'

        // Renderiza o PDF (no formato de renderização automático)
        $dompdf->render();

        // Gera o PDF para download
        return $dompdf->stream('relatorio_transacoes.pdf');
    } else {
        // Se o usuário não estiver autenticado, redireciona para o login
        return redirect()->route('login')->with('error', 'Você precisa estar logado para gerar o PDF.');
    }
}
}
