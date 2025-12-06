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
        Schema::table('project_judge', function (Blueprint $table) {
            $table->decimal('score_document', 5, 2)->nullable()->after('score');
            $table->decimal('score_presentation', 5, 2)->nullable()->after('score_document');
            $table->decimal('score_demo', 5, 2)->nullable()->after('score_presentation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_judge', function (Blueprint $table) {
            $table->dropColumn(['score_document', 'score_presentation', 'score_demo']);
        });
    }
};
