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
    Schema::create('projetos', function (Blueprint $table) {
        $table->id('id_projeto');
        
        // --- Correção Aplicada ---
        $table->unsignedBigInteger('id_cliente');
        $table->foreign('id_cliente')
              ->references('id_cliente')
              ->on('clientes')
              ->cascadeOnDelete();
        // --- Fim da Correção ---

        $table->string('nome');
        $table->text('descricao')->nullable();
        $table->timestamps();

        $table->unique(['id_cliente', 'nome']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projetos');
    }
};