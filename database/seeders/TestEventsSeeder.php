<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;

class TestEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get or Create Manager
        $manager = User::role('event_manager')->first();
        if (!$manager) {
            $manager = User::factory()->create([
                'name' => 'Gestor de Prueba',
                'email' => 'gestor_' . uniqid() . '@webathoon.com',
                'password' => Hash::make('password'),
            ]);
            $manager->assignRole('event_manager');
            $this->command->info("Gestor de prueba creado: {$manager->email}");
        }

        // 2. Get or Create Category
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Tecnología',
                'description' => 'Eventos relacionados con tecnología e innovación.'
            ]);
            $this->command->info("Categoría creada.");
        }

        // 3. Create Events
        $events = [
            [
                'name' => 'Hackathon Global 2025',
                'description' => 'Un desafío de 48 horas para resolver problemas globales usando tecnología.',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(12),
                'location' => 'Centro de Convenciones Virtual',
                'status' => 'registration',
            ],
            [
                'name' => 'Feria de Ciencias: Innova',
                'description' => 'Presentación de proyectos científicos y prototipos de ingeniería.',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(21),
                'location' => 'Campus Central',
                'status' => 'registration',
            ]
        ];

        foreach ($events as $data) {
            if (!Event::where('name', $data['name'])->exists()) {
                Event::create(array_merge($data, [
                    'manager_id' => $manager->id,
                    'category_id' => $category->id
                ]));
                $this->command->info("Evento creado: {$data['name']}");
            } else {
                $this->command->info("Evento ya existe: {$data['name']}");
            }
        }
    }
}
