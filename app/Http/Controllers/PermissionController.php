<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        $matrix = [];
        foreach ($roles as $role) {
            $matrix[$role->name] = [];
            foreach ($permissions as $permission) {
                $matrix[$role->name][$permission->name] = $role->hasPermissionTo($permission->name);
            }
        }

        return view('Admin.permissions.index', compact('roles', 'permissions', 'matrix'));
    }
}
