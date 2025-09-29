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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('tituloTask');
            $table->text('descricaoTask')->nullable();
            $table->string('status')->default('A Fazer'); // 'A Fazer', 'Em Andamento', 'Concluído'
            $table->integer('ordem')->default(0);
            $table->foreignId('projetoId')->constrained('projects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
