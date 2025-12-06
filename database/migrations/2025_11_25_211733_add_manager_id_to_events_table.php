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
    Schema::table('events', function (Blueprint $table) {
        // Creamos la relación. Si borran al usuario, el campo queda NULL (set null)
        $table->foreignId('manager_id')
              ->nullable()
              ->after('name') // Para que quede ordenado visualmente
              ->constrained('users')
              ->onDelete('set null'); 
    });
}

public function down(): void
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropForeign(['manager_id']);
        $table->dropColumn('manager_id');
    });
}
};
