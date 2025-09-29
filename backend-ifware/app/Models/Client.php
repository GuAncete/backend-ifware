<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomeCliente',
        'emailContato',
        'telefoneContato',
    ];

    /**
     * Um cliente tem muitos projetos.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'clienteId');
    }
}