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
        // Change the column to string to support new status values without enum restrictions
        // DB::statement("ALTER TABLE events MODIFY COLUMN status VARCHAR(255) DEFAULT 'registration'");
        // Since we are using Laravel, we can try the Schema builder, but modifying enum is tricky.
        // Let's use raw SQL for sqlite/mysql compatibility if possible, or just standard Schema allow changes.
        // Assuming MySQL:
        Schema::table('events', function (Blueprint $table) {
            // First drop the default
            $table->string('status')->default('registration')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('events', function (Blueprint $table) {
            // Revert to enum (this might lose data if not careful, but for dev it is ok)
            // $table->enum('status', ['active', 'inactive', 'finished'])->default('active')->change();
             $table->string('status')->default('active')->change(); // Keep as string to avoid issues, or restore enum if really needed.
        });
    }
};
