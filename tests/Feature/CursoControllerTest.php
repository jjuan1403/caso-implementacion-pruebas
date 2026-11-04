<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Curso;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CursoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_un_nuevo_curso(): void
    {
        // Datos del nuevo curso a registrar
        $datosCurso = [
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ];

        // Petición POST al endpoint correspondiente
        $response = $this->postJson('/cursos', $datosCurso);

        // Aserción de respuesta exitosa (201 Created o 200 OK)
        $response->assertStatus(201);

        // Aserción de que el registro existe en la base de datos
        $this->assertDatabaseHas('cursos', [
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);
    }
}