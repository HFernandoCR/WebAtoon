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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // software, hardware, etc.
            $table->string('name'); // Software / Apps
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed initial data
        DB::table('categories')->insert([
            ['code' => 'software', 'name' => 'Software / Apps', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'hardware', 'name' => 'Hardware / Robótica', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'innovation', 'name' => 'Innovación Social', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'research', 'name' => 'Investigación', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
