<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CurrencyController;


// Rota para a página de login
Route::get('login', function () {
    return view('auth.login');
})->name('login');

// Rota para o processamento do login
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

// Rota para o registro
Route::get('register', function () {
    return view('auth.register');
})->name('register');

// Rota para o processamento do registro
Route::post('register', [AuthController::class, 'register'])->name('register.submit');

// Rota para o dashboard (transações) - somente para usuários autenticados
Route::middleware('auth')->group(function () {
    // Página de transações (CRUD)
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    // Formulário para criar uma nova transação
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    
    // Processar a criação de uma transação
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
    
    // Formulário para editar uma transação
    Route::get('transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    
    // Processar a atualização de uma transação
    Route::put('transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    
    // Deletar uma transação
    Route::delete('transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    
    // Rota para logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

<<<<<<< HEAD
Route::get('/transacoes/relatorio', [TransactionController::class, 'generatePdf'])->name('transactions.generatePdf');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

=======
Route::get('/transacoes/relatorio', [TransactionController::class, 'generatePdf'])->name('transactions.generatePdf');
>>>>>>> d2187f2a6e63054ca44c639dca86d1ca0b2131e5
