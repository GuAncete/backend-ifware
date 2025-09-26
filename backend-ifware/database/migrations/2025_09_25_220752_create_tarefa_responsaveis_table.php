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
        Schema::create('tarefa_responsaveis', function (Blueprint $table) {
            // Correctly references the 'id_tarefa' column on the 'tarefas' table
            $table->foreignId('tarefa_id')->constrained(
                table: 'tarefas', column: 'id_tarefa'
            )->cascadeOnDelete();

            // This line is correct as the 'users' table's primary key is 'id'
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // The composite primary key is perfect for a pivot table
            $table->primary(['tarefa_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefa_responsaveis');
    }
};