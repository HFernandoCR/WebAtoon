<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('average_score', 5, 2)->nullable()->default(0)->after('status')->index();
            $table->unsignedInteger('ranking_position')->nullable()->after('average_score');

            // Índice compuesto para facilitar búsquedas de ranking por evento
            $table->index(['event_id', 'ranking_position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['event_id', 'ranking_position']);
            $table->dropColumn(['average_score', 'ranking_position']);
        });
    }
};
