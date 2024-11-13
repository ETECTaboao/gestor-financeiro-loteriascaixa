<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;

class TransactionController extends Controller
{
    // Exibe a lista de transações
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())->get();
        return view('transactions.index', compact('transactions'));
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
}
