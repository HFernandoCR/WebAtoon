<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Lista los proyectos DEL estudiante logueado.
     */
    public function index()
    {
        $projects = Project::where('user_id', Auth::id())
            ->with('event', 'judges')
            ->paginate(5);

        return view('Student.projects.index', compact('projects'));
    }

    /**
     * Formulario para inscribir nuevo proyecto.
     */
    public function create()
    {
        $this->authorize('create', Project::class);
        $activeEvents = Event::where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();
        $categories = \App\Models\Category::all();

        $advisors = \App\Models\User::role('advisor')->get();

        return view('Student.projects.create', compact('activeEvents', 'advisors', 'categories'));
    }

    /**
     * Guardar el proyecto.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);
        $request->validate([
            'title' => 'required|max:255',
            'event_id' => 'required|exists:events,id',
            'advisor_id' => 'nullable|exists:users,id',
            'category' => 'required|exists:categories,code',
            'description' => 'required',
            'repository_url' => 'nullable|url'
        ]);

        $event = Event::findOrFail($request->event_id);
        $now = now();

        if ($now < $event->start_date || $now > $event->end_date) {
            return back()->withErrors(['event_id' => 'El evento no está activo en este momento.'])->withInput();
        }

        $project = Project::create([
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
            'advisor_id' => $request->advisor_id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'repository_url' => $request->repository_url,
            'status' => 'pending'
        ]);

        $event = Event::find($request->event_id);
        if ($event && $event->manager_id) {
            Notification::create([
                'user_id' => $event->manager_id,
                'type' => 'new_project_registered',
                'title' => 'Nuevo proyecto inscrito',
                'message' => 'El estudiante ' . Auth::user()->name . ' ha inscrito el proyecto "' . $project->title . '" en tu evento.',
                'data' => [
                    'project_id' => $project->id,
                    'event_id' => $event->id,
                ],
                'url' => route('manager.dashboard'),
            ]);
        }

        if ($request->advisor_id) {
            Notification::create([
                'user_id' => $request->advisor_id,
                'type' => 'advisor_assigned',
                'title' => 'Proyecto asignado como asesor',
                'message' => 'Has sido asignado como asesor del proyecto "' . $project->title . '" por el estudiante ' . Auth::user()->name . '.',
                'data' => ['project_id' => $project->id],
                'url' => route('advisor.dashboard'),
            ]);
        }

        // Notify Admins
        $admins = \App\Models\User::role('admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'new_project',
                'title' => 'Nuevo Proyecto Inscrito',
                'message' => "El proyecto \"{$project->title}\" ha sido inscrito en el evento \"{$event->name}\".",
                'data' => ['project_id' => $project->id],
                'url' => route('projects.show', $project->id), // Assuming admin can view project details
            ]);
        }

        return redirect()->route('projects.index')->with('success', '¡Proyecto inscrito exitosamente!');
    }

    /**
     * Mostrar detalles 
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return view('Student.projects.show', compact('project'));
    }

    /**
     * Edición (Solo si el proyecto está pendiente)
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);


        $activeEvents = Event::where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();
        $categories = \App\Models\Category::all();
        return view('Student.projects.edit', compact('project', 'activeEvents', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required|exists:categories,code',
            'description' => 'required',
        ]);

        $event = $project->event;
        $now = now();

        if ($now < $event->start_date || $now > $event->end_date) {
            return back()->withErrors(['error' => 'No puedes editar el proyecto fuera de las fechas del evento.']);
        }

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Proyecto actualizado.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $event = $project->event;
        $now = now();

        if ($now < $event->start_date || $now > $event->end_date) {
            return back()->with('error', 'No puedes eliminar el proyecto fuera de las fechas del evento.');
        }

        $projectTitle = $project->title;
        $studentName = $project->user->name;

        $project->delete();

        // Notify Admins
        $admins = \App\Models\User::role('admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'project_deleted',
                'title' => 'Proyecto Eliminado',
                'message' => "El proyecto \"{$projectTitle}\" del estudiante {$studentName} ha sido eliminado.",
                'data' => ['project_title' => $projectTitle],
                'url' => route('dashboard'), // No project link since it's deleted
            ]);
        }
        return redirect()->route('projects.index')->with('success', 'Inscripción cancelada.');
    }


    public function myTeam()
    {
        $project = Project::where('user_id', Auth::id())->with('event')->first();
        return view('Student.team', compact('project'));
    }

    public function certificates()
    {
        $project = Project::where('user_id', Auth::id())->first();

        return view('Student.certificates', compact('project'));
    }



}