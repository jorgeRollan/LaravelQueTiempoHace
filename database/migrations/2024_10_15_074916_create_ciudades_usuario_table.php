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
        Schema::create('ciudadesUsuario', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->index('idUsuario');
            $table->foreignId('idUsuario')->constrained()->references('id')->on('usuarios');
            $table->string('nombreCiudad');
            $table->string('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciudadesUsuario');
    }
};
