<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class TransactionController extends Controller
{
    // Exibe a lista de transações
    public function index()
    {
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
