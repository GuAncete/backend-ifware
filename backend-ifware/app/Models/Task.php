<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'tituloTask',
        'descricaoTask',
        'status',
        'ordem',
        'projetoId',
    ];

    /**
     * Uma tarefa pertence a um projeto.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'projetoId');
    }

    /**
     * Uma tarefa pode ser atribuída a muitos usuários.
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user', 'taskId', 'userId');
    }
}