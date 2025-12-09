<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Roles y Permisos
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            TestEventsSeeder::class,
        ]);

        // 2. Crear Usuario Administrador por defecto
        $admin = User::firstOrCreate(
            ['email' => 'admin@webatoon.com'],
            [
                'name' => 'Administrador WebAtoon',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');
    }
}
