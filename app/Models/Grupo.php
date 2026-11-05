<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Docente;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'numeroCiclo',
        'ano',
        'numeroGrupo',
        'abierto',
        'curso_sigla', // o curso_id según tu migración
    ];

    protected $casts = [
        'abierto' => 'boolean',
    ];

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

    public function asignarDocente($docente): void
    {
        $docenteId = $docente instanceof Docente ? $docente->codEmpleado : $docente;

        if ($this->docentes()->count() >= 2) {
            throw new \DomainException('Un grupo no puede tener más de dos docentes.');
        }

        $this->docentes()->attach($docenteId);
    }

    public function puedeAbrirse(): bool
    {
        return $this->docentes()->count() >= 1 && $this->estudiantes()->count() >= 15;
    }

    public function abrir(): void
    {
        if (!$this->puedeAbrirse()) {
            throw new \DomainException('El grupo no cumple los requisitos para abrirse.');
        }

        $this->update(['abierto' => true]);
    }
}