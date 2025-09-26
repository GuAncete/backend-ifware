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
        Schema::create('cliente_membros', function (Blueprint $table) {
            // --- Correção #1: Chave estrangeira para CLIENTES ---
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')
                  ->references('id_cliente') // Aponta para a coluna correta
                  ->on('clientes')
                  ->cascadeOnDelete();

            // --- Correção #2: Chave estrangeira para USERS ---
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id_user') // Aponta para a coluna correta
                  ->on('users')
                  ->cascadeOnDelete();

            $table->string('role')->default('membro'); // Ex: 'admin', 'membro'

            // Chave primária composta para evitar entradas duplicadas
            $table->primary(['cliente_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_membros');
    }
};