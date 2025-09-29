<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomeProjeto',
        'descricaoProjeto',
        'clienteId',
        'tipoProjetoId',
    ];

    /**
     * Um projeto pertence a um cliente.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clienteId');
    }

    /**
     * Um projeto pertence a um tipo.
     */
    public function projectType(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class, 'tipoProjetoId');
    }

    /**
     * Um projeto tem muitas tarefas.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'projetoId');
    }
}