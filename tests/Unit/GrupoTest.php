<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Grupo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GrupoTest extends TestCase
{
    use RefreshDatabase;

    public function test_instancia_grupo_con_informacion_real_asociada(): void
    {
       
        $curso = Curso::create([
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        
        $docente = Docente::create([
            'codEmpleado' => 101,
            'nombreEmpleado' => 'Mario',
            'apellidosEmpleado' => 'Gómez',
        ]);

        
        $grupo = Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 1,
            'abierto' => true,
            'curso_sigla' => $curso->sigla,
        ]);

        
        $grupo->docentes()->attach($docente->codEmpleado);

        
        $estudiante = Estudiante::create([
            'carne' => 'C01234',
            'nombre' => 'Ana',
            'apellidos' => 'Rojas',
            'consejero_id' => $docente->codEmpleado,
        ]);

        $grupo->estudiantes()->attach($estudiante->carne, ['nota' => 95.0]);

        //verificaciones
        $this->assertEquals('IF6100', $grupo->curso->sigla);
        $this->assertTrue($grupo->abierto);
        $this->assertCount(1, $grupo->docentes);
        $this->assertEquals('Mario', $grupo->docentes->first()->nombreEmpleado);
        $this->assertCount(1, $grupo->estudiantes);
        $this->assertEquals('Ana', $grupo->estudiantes->first()->nombre);
        $this->assertEquals(95.0, $grupo->estudiantes->first()->pivot->nota);
    }
}