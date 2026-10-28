<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EstudianteMatriculado extends Pivot
{
    protected $table = 'estudiante_matriculados';

    protected $fillable = [
        'estudiante_carne',
        'grupo_id',
        'nota',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_carne', 'carne');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id', 'id');
    }
}