<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Definir los usuarios a crear con sus roles y correos en español
$users = [
    'admin' => [
        'name' => 'Administrador',
        'email' => 'admin@prueba.com',
        'password' => 'password',
    ],
    'event_manager' => [
        'name' => 'Gestor de Eventos',
        'email' => 'gestor_eventos@prueba.com',
        'password' => 'password',
    ],
    'judge' => [
        'name' => 'Juez',
        'email' => 'juez@prueba.com',
        'password' => 'password',
    ],
    'advisor' => [
        'name' => 'Asesor',
        'email' => 'asesor@prueba.com',
        'password' => 'password',
    ],
    'student' => [
        'name' => 'Estudiante',
        'email' => 'estudiante@prueba.com',
        'password' => 'password',
    ],
];

foreach ($users as $role => $userData) {
    // Crear el usuario o recuperarlo si ya existe
    $user = User::firstOrCreate(
        ['email' => $userData['email']],
        [
            'name' => $userData['name'],
            'password' => Hash::make($userData['password']),
        ]
    );

    // Asignar el rol
    $user->syncRoles([$role]);

    echo "Usuario creado/actualizado: {$userData['name']} ({$userData['email']}) - Rol: {$role}\n";
}
