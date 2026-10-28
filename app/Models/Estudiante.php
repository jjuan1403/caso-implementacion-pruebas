<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $primaryKey = 'carne';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['carne', 'nombre', 'apellidos', 'consejero_id'];

    public function consejero()
    {
        return $this->belongsTo(Docente::class, 'consejero_id', 'codEmpleado');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'estudiante_matriculados', 'estudiante_carne', 'grupo_id')
                    ->withPivot('nota')
                    ->using(EstudianteMatriculado::class);
    }
}
