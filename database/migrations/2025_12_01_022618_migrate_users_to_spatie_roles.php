<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Assign Spatie roles based on the 'role' column
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                // Ensure the role exists (it should from the seeder)
                // We use the string value directly as it matches our seeder
                $user->assignRole($user->role);
            }
        }

        // 2. Drop the old 'role' column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Re-add the 'role' column
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'event_manager', 'judge', 'advisor', 'student'])
                ->default('student')
                ->after('password'); // Try to place it back where it was roughly
        });

        // 2. Populate the 'role' column from Spatie roles
        $users = User::all();
        foreach ($users as $user) {
            // Get the first role name
            $roleName = $user->getRoleNames()->first();
            if ($roleName) {
                $user->role = $roleName;
                $user->save();
            }
        }
    }
};
