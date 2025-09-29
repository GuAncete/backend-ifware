<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectType extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomeTipoProjeto',
    ];

    /**
     * Um tipo de projeto tem muitos projetos.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'tipoProjetoId');
    }
}