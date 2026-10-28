<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $primaryKey = 'sigla';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['sigla', 'nombreCurso', 'creditos'];

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'curso_sigla', 'sigla');
    }
}
