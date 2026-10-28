<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $primaryKey = 'codEmpleado';
    protected $fillable = [
        'codEmpleado',
        'nombreEmpleado',
        'apellidosEmpleado',
    ];

    public function estudiantesAconsejados()
    {
        return $this->hasMany(Estudiante::class, 'consejero_id', 'codEmpleado');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'docente_grupo', 'docente_id', 'grupo_id');
    }
}