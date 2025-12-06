<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'event_manager', 'advisor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Admin y Event Manager ven todo
        if ($user->hasAnyRole(['admin', 'event_manager'])) {
            return true;
        }

        // El dueño ve su proyecto
        if ($user->id === $project->user_id) {
            return true;
        }

        // El asesor asignado ve el proyecto
        if ($user->id === $project->advisor_id) {
            return true;
        }

        // Los jueces asignados ven el proyecto
        if ($project->judges->contains($user->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-own-projects');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Admin puede editar siempre
        if ($user->hasRole('admin')) {
            return true;
        }

        // El dueño solo puede editar si está en borrador (pending)
        return $user->id === $project->user_id && $project->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Admin puede eliminar siempre
        if ($user->hasRole('admin')) {
            return true;
        }

        // El dueño solo puede eliminar si está en borrador (pending)
        return $user->id === $project->user_id && $project->status === 'pending';
    }

    /**
     * Determine whether the user can submit the project.
     */
    public function submit(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $project->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->hasRole('admin');
    }
}
