<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['numeroCiclo', 'ano', 'numeroGrupo', 'abierto', 'curso_sigla'];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_sigla', 'sigla');
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_grupo', 'grupo_id', 'docente_id');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_matriculados', 'grupo_id', 'estudiante_carne')
                    ->withPivot('nota')
                    ->using(EstudianteMatriculado::class);
    }
}