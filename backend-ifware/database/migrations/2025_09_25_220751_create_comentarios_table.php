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
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id('id_comentario');

            // --- Correção #1: Chave estrangeira para TAREFAS ---
            $table->unsignedBigInteger('tarefa_id');
            $table->foreign('tarefa_id')
                  ->references('id_tarefa') // Aponta para a coluna correta
                  ->on('tarefas')
                  ->cascadeOnDelete();

            // --- Correção #2: Chave estrangeira para USERS ---
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id_user') // Aponta para a coluna correta
                  ->on('users')
                  ->cascadeOnDelete();

            $table->text('conteudo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};