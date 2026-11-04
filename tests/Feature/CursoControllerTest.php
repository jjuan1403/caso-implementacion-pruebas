<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Curso;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CursoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_listar_cursos_desde_la_base_de_datos(): void
    {
        Curso::create([
            'sigla' => 'IF6100',
            'nombreCurso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        // Alterado de '/api/cursos' para '/cursos'
        $response = $this->getJson('/cursos');

        $response->assertStatus(200)
                 ->assertJsonFragment(['sigla' => 'IF6100']);
    }

    public function test_puede_guardar_un_curso_en_base_de_datos(): void
    {
        $data = [
            'sigla' => 'IF3001',
            'nombreCurso' => 'Algoritmos y Estructuras',
            'creditos' => 3,
        ];

        // Alterado de '/api/cursos' para '/cursos'
        $response = $this->postJson('/cursos', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('cursos', ['sigla' => 'IF3001']);
    }
}