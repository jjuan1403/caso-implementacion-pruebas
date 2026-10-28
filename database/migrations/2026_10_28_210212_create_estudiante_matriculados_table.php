<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estudiante_matriculados', function (Blueprint $table) {
            $table->id();
            $table->string('estudiante_carne');
            $table->unsignedBigInteger('grupo_id');
            $table->float('nota')->nullable();
            $table->foreign('estudiante_carne')->references('carne')->on('estudiantes')->onDelete('cascade');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiante_matriculados');
    }
};
