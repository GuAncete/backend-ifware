<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id('id_tarefa');

            // --- Correção #1: Chave estrangeira para PROJETOS ---
            // Desmontamos o atalho 'constrained' para especificar a coluna correta.
            $table->unsignedBigInteger('projeto_id');
            $table->foreign('projeto_id')
                ->references('id_projeto') // Aponta para a coluna correta
                ->on('projetos')
                ->cascadeOnDelete();

            // --- Correção #2: Chave estrangeira para USERS ---
            // Removemos o 'constrained()' redundante.
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')
                ->references('id_user') // Aponta para a coluna correta
                ->on('users')
                ->nullOnDelete();

            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('prioridade', ['Baixa', 'Média', 'Alta', 'Urgente'])->default('Média');
            $table->enum('status', ['Pendente', 'Em Andamento', 'Bloqueada', 'Concluída'])->default('Pendente');
            $table->date('data_vencimento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
