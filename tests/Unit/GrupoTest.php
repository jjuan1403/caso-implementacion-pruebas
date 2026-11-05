<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Curso;
use App\Models\Grupo;
use App\Models\Docente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GrupoTest extends TestCase
{
    use RefreshDatabase;

    public function test_cp01_registrar_un_grupo_con_toda_su_informacion_y_su_curso(): void
    {
        $curso = Curso::create([
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        $grupoCreado = Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 1,
            'abierto' => true,
            'curso_sigla' => $curso->sigla,
        ]);

        $grupo = Grupo::find($grupoCreado->id);

        $this->assertNotNull($grupo);
        $this->assertSame(2, $grupo->numeroCiclo);
        $this->assertSame(2026, $grupo->ano);
        $this->assertSame(1, $grupo->numeroGrupo);
        $this->assertTrue($grupo->abierto);
        $this->assertIsBool($grupo->abierto);
        $this->assertSame('IF6100', $grupo->curso->sigla);
        $this->assertSame(4, $grupo->curso->creditos);
        $this->assertDatabaseCount('grupos', 1);
    }

    public function test_cp02_un_curso_tiene_varios_grupos_y_se_distinguen_los_abiertos(): void
    {
        $curso = Curso::create([
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 1,
            'abierto' => true,
            'curso_sigla' => $curso->sigla,
        ]);

        Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 2,
            'abierto' => true,
            'curso_sigla' => $curso->sigla,
        ]);

        Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 3,
            'abierto' => false,
            'curso_sigla' => $curso->sigla,
        ]);

        $gruposDelCurso = $curso->grupos;
        $this->assertCount(3, $gruposDelCurso);

        $gruposAbiertos = $gruposDelCurso->filter(function ($g) {
            return $g->abierto === true;
        });

        $this->assertCount(2, $gruposAbiertos);
        $this->assertTrue($gruposAbiertos->contains('numeroGrupo', 1));
        $this->assertTrue($gruposAbiertos->contains('numeroGrupo', 2));
        $this->assertFalse($gruposAbiertos->contains('numeroGrupo', 3));
    }

    public function test_cp03_un_grupo_acepta_dos_docentes(): void
    {
        $curso = Curso::create([
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        $grupo = Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 1,
            'abierto' => false,
            'curso_sigla' => $curso->sigla,
        ]);

        $docente1 = Docente::create([
            'codEmpleado' => 1001,
            'nombreEmpleado' => 'Ana',
            'apellidosEmpleado' => 'Mora Vargas',
        ]);

        $docente2 = Docente::create([
            'codEmpleado' => 1002,
            'nombreEmpleado' => 'Luis',
            'apellidosEmpleado' => 'Rojas Solano',
        ]);

        $grupo->asignarDocente(1001);
        $grupo->asignarDocente(1002);

        $docentesDelGrupo = $grupo->fresh()->docentes;
        $this->assertCount(2, $docentesDelGrupo);
        $this->assertTrue($docentesDelGrupo->contains('codEmpleado', 1001));
        $this->assertTrue($docentesDelGrupo->contains('codEmpleado', 1002));

        $gruposDocente1 = $docente1->fresh()->grupos;
        $this->assertTrue($gruposDocente1->contains('id', $grupo->id));
    }

    public function test_cp04_un_grupo_rechaza_un_tercer_docente(): void
    {
        $curso = Curso::create([
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        $grupo = Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 1,
            'abierto' => false,
            'curso_sigla' => $curso->sigla,
        ]);

        Docente::create([
            'codEmpleado' => 1001,
            'nombreEmpleado' => 'Ana',
            'apellidosEmpleado' => 'Mora Vargas',
        ]);

        Docente::create([
            'codEmpleado' => 1002,
            'nombreEmpleado' => 'Luis',
            'apellidosEmpleado' => 'Rojas Solano',
        ]);

        Docente::create([
            'codEmpleado' => 1003,
            'nombreEmpleado' => 'Carla',
            'apellidosEmpleado' => 'Jiménez Araya',
        ]);

        $grupo->asignarDocente(1001);
        $grupo->asignarDocente(1002);

        $this->expectException(\DomainException::class);

        try {
            $grupo->asignarDocente(1003);
        } finally {
            $docentesActuales = $grupo->fresh()->docentes;
            $this->assertCount(2, $docentesActuales);
            $this->assertFalse($docentesActuales->contains('codEmpleado', 1003));
        }
    }


    public function test_cp05_un_grupo_con_14_estudiantes_no_puede_abrirse(): void
    {
        $curso = Curso::create([
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        $grupo = Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 1,
            'abierto' => false,
            'curso_sigla' => $curso->sigla,
        ]);

        $docente = Docente::create([
            'codEmpleado' => 1001,
            'nombreEmpleado' => 'Ana',
            'apellidosEmpleado' => 'Mora Vargas',
        ]);
        $grupo->asignarDocente($docente->codEmpleado);

        for ($i = 1; $i <= 14; $i++) {
            $carne = 'C' . str_pad((string)$i, 5, '0', STR_PAD_LEFT);
            $estudiante = \App\Models\Estudiante::create([
                'carne' => $carne,
                'nombre' => 'Estudiante ' . $i,
                'apellidos' => 'Prueba ' . $i,
                'consejero_id' => $docente->codEmpleado,
            ]);
            $grupo->estudiantes()->attach($estudiante->carne);
        }

        $this->assertFalse($grupo->puedeAbrirse());

        try {
            $grupo->abrir();
            $this->fail('Se esperaba DomainException');
        } catch (\DomainException $e) {
            $this->assertFalse((bool)$grupo->fresh()->abierto);
        }
    }

    public function test_cp06_un_grupo_con_15_estudiantes_y_un_docente_se_abre(): void
    {
        $curso = Curso::create([
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        $grupo = Grupo::create([
            'numeroCiclo' => 2,
            'ano' => 2026,
            'numeroGrupo' => 1,
            'abierto' => false,
            'curso_sigla' => $curso->sigla,
        ]);

        $docente = Docente::create([
            'codEmpleado' => 1001,
            'nombreEmpleado' => 'Ana',
            'apellidosEmpleado' => 'Mora Vargas',
        ]);
        $grupo->asignarDocente($docente->codEmpleado);

        for ($i = 1; $i <= 15; $i++) {
            $carne = 'B' . str_pad((string)$i, 5, '0', STR_PAD_LEFT);
            $estudiante = \App\Models\Estudiante::create([
                'carne' => $carne,
                'nombre' => 'Estudiante ' . $i,
                'apellidos' => 'Prueba ' . $i,
                'consejero_id' => $docente->codEmpleado,
            ]);
            $grupo->estudiantes()->attach($estudiante->carne);
        }

        $this->assertTrue($grupo->puedeAbrirse());
        $grupo->abrir();

        $this->assertTrue($grupo->fresh()->abierto);
    }
}