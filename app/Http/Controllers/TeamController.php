<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{

    public function index()
    {
        $userId = Auth::id();

        // 1. Verificar si SOY LÍDER de un proyecto

        $myProject = Project::where('user_id', $userId)
            ->with('members.user')
            ->first();


        $memberships = ProjectMember::where('user_id', $userId)
            ->with('project.author')
            ->get();

        return view('Student.team', compact('myProject', 'memberships'));
    }


    public function invite(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $project = Project::where('user_id', Auth::id())->first();

        if (!$project) {
            return back()->with('error', 'Debes crear un proyecto antes de invitar a alguien.');
        }

        // Validar límite (Líder + 4 miembros = 5 total)

        $acceptedCount = ProjectMember::where('project_id', $project->id)
            ->where('status', 'accepted')
            ->count();

        if ($acceptedCount >= 4) {
            return back()->with('error', 'El equipo ya está lleno (Máx. 5 integrantes en total).');
        }

        // 3. Buscar usuario invitado
        $userToInvite = User::where('email', $request->email)->role('student')->first();

        if (!$userToInvite) {
            return back()->with('error', 'El correo no corresponde a un estudiante registrado.');
        }

        if ($userToInvite->id == Auth::id()) {
            return back()->with('error', 'No puedes invitarte a ti mismo.');
        }

        $leadsAProject = Project::where('user_id', $userToInvite->id)->exists();

        if ($leadsAProject) {
            return back()->with('error', 'El estudiante ya lidera un proyecto.');
        }

        $acceptedMembership = ProjectMember::where('user_id', $userToInvite->id)
            ->where('status', 'accepted')
            ->exists();

        if ($acceptedMembership) {
            return back()->with('error', 'El estudiante ya pertenece a otro equipo.');
        }

        // 4. Crear invitación evitando duplicados
        $membership = ProjectMember::firstOrCreate(
            ['project_id' => $project->id, 'user_id' => $userToInvite->id],
            ['status' => 'pending']
        );

        if (!$membership->wasRecentlyCreated) {
            if ($membership->status === 'accepted') {
                return back()->with('error', 'El estudiante ya forma parte de tu equipo.');
            }

            if ($membership->status === 'pending') {
                return back()->with('error', 'Ya existe una invitación pendiente para este estudiante.');
            }
        }

        Notification::create([
            'user_id' => $userToInvite->id,
            'type' => 'team_invitation',
            'title' => 'Invitación a equipo',
            'message' => Auth::user()->name . ' te ha invitado a unirte al proyecto "' . $project->title . '"',
            'data' => [
                'project_id' => $project->id,
                'membership_id' => $membership->id,
            ],
            'url' => route('student.team'),
        ]);

        return back()->with('success', 'Invitación enviada a ' . $userToInvite->name);
    }


    public function accept($id)
    {
        $invite = ProjectMember::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $invite->update(['status' => 'accepted']);

        $project = Project::find($invite->project_id);
        Notification::create([
            'user_id' => $project->user_id,
            'type' => 'team_member_accepted',
            'title' => 'Invitación aceptada',
            'message' => Auth::user()->name . ' ha aceptado unirse a tu proyecto "' . $project->title . '".',
            'data' => ['project_id' => $project->id],
            'url' => route('student.team'),
        ]);

        return back()->with('success', '¡Te has unido al equipo!');
    }

    /**
     * Rechazar Invitación
     */
    public function reject($id)
    {
        $invite = ProjectMember::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $project = Project::find($invite->project_id);
        Notification::create([
            'user_id' => $project->user_id,
            'type' => 'team_member_rejected',
            'title' => 'Invitación rechazada',
            'message' => Auth::user()->name . ' ha rechazado la invitación a tu proyecto "' . $project->title . '".',
            'data' => ['project_id' => $project->id],
            'url' => route('student.team'),
        ]);

        $invite->delete();

        return back()->with('success', 'Invitación rechazada.');
    }

    /**
     * Expulsar miembro o Cancelar invitación (Solo líder)
     */
    public function remove($id)
    {
        $memberEntry = ProjectMember::findOrFail($id);

        $project = Project::where('id', $memberEntry->project_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$project) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        $memberEntry->delete();

        return back()->with('success', 'Miembro eliminado del equipo');
    }
}