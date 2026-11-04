<?php


use App\Http\Controllers\CursoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('cursos', CursoController::class);