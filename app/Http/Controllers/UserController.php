<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('Admin.users.index', compact('users'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        $roles = Role::with('permissions')->get();
        return view('Admin.users.create', compact('roles'));
    }

    /**
     * Guarda el nuevo usuario en la BD.
     */
    public function store(Request $request)
    {
        // 1. Validaciones
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name', // Validar contra la tabla roles
            'institution' => 'nullable|string|max:255',
        ]);

        // 2. Crear Usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encriptamos la contraseña
            'institution' => $request->institution,
        ]);

        // 3. Asignar Rol
        $user->assignRole($request->role);

        // 4. Redireccionar (La ruta sigue llamándose 'users.index')
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(User $user)
    {
        $roles = Role::with('permissions')->get();
        return view('Admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualiza el usuario existente.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,name',
            'institution' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'email', 'institution']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina el usuario.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->id == $user->id) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}