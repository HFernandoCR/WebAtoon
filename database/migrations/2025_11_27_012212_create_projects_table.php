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
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        // Relaciones
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El estudiante líder
        $table->foreignId('event_id')->constrained()->onDelete('cascade'); // El evento

        // Datos del Proyecto
        $table->string('title');
        $table->text('description');
        $table->enum('category', ['software', 'hardware', 'innovation', 'research'])->default('software');
        $table->string('repository_url')->nullable(); 
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Estado de aprobación
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
