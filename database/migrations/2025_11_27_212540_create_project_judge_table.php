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
    Schema::create('project_judge', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El Juez

        // Campos para la evaluación futura
        $table->decimal('score', 5, 2)->nullable(); // Calificación (ej. 9.5)
        $table->text('feedback')->nullable();       // Retroalimentación

        $table->timestamps();

        // Evitar asignar el mismo juez dos veces al mismo proyecto
        $table->unique(['project_id', 'user_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_judge');
    }
};
