<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo: 'gasto' ou 'ganho'
            $table->string('category'); // Categoria: Ex: 'alimentação', 'salário'
            $table->decimal('amount', 10, 2); // Valor da transação
            $table->text('description')->nullable(); // Descrição (opcional)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacionamento com o usuário
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
