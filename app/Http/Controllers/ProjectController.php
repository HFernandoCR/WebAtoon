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
    /**
     * Lista los proyectos DEL estudiante logueado (como autor o miembro).
     */
    public function index(Request $request)
    {
        $query = Project::where(function($q) {
            $q->where('user_id', Auth::id())
              ->orWhereHas('members', function($m) {
                  $m->where('user_id', Auth::id());
              });
        })->with('event', 'judges');

        if ($request->get('status') === 'active') {
            $query->whereHas('event', function($q) {
                $q->active();
            });
        } elseif ($request->get('status') === 'finished') {
            $query->whereHas('event', function($q) {
                $q->finished();
            });
        }

        $projects = $query->latest()->paginate(5)->appends($request->query());

        return view('Student.projects.index', compact('projects'));
    }

    /**
     * Formulario para inscribir nuevo proyecto.
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        // Check for existing active projects (as author or member)
        $existingActiveProject = Project::where(function($q) {
            $q->where('user_id', Auth::id())
              ->orWhereHas('members', function($m) {
                  $m->where('user_id', Auth::id())->where('status', 'accepted');
              });
        })
        ->whereHas('event', function ($query) {
            $query->active();
        })
        ->first();

        if ($existingActiveProject) {
            return redirect()->route('projects.index')->with('error', 'Ya tienes un proyecto activo (' . $existingActiveProject->title . '). No puedes registrar otro hasta que el evento finalice.');
        }

        $activeEvents = Event::active()
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

        // Check for existing active projects (as author or member)
        $existingActiveProject = Project::where(function($q) {
            $q->where('user_id', Auth::id())
              ->orWhereHas('members', function($m) {
                  $m->where('user_id', Auth::id())->where('status', 'accepted');
              });
        })
        ->whereHas('event', function ($query) {
             $query->active();
        })
        ->first();

        if ($existingActiveProject) {
            return back()->withErrors(['error' => 'Ya tienes un proyecto activo en curso. Solo puedes registrar un nuevo proyecto cuando el evento actual haya finalizado.']);
        }

        if ($event->status !== Event::STATUS_REGISTRATION) {
            return back()->withErrors(['event_id' => 'El evento no está en periodo de inscripciones.'])->withInput();
        }

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

        // AUTO-ASSIGN EVENT JUDGES TO NEW PROJECT
        foreach ($event->judges as $judge) {
            $project->judges()->attach($judge->id);
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


        $activeEvents = Event::where('status', Event::STATUS_REGISTRATION)
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
        // Prioritize ACTIVE project.
        // If the student has a finished project but no active one, we show NOTHING (or handle in view).
        // The user specifically requested: "haz que cuando un equipo de un proyecto finalizado ya no salga"
        $project = Project::where(function($q) {
            $q->where('user_id', Auth::id())
              ->orWhereHas('members', function($m) {
                  $m->where('user_id', Auth::id())->where('status', 'accepted');
              });
        })
        ->whereHas('event', function ($query) {
            $query->active();
        })
        ->first();

        return view('Student.team', compact('project'));
    }

    public function certificates()
    {
        // Fetch ALL projects where the user is an author or member
        // And where criteria is met (Approved OR Graded)
        // User asked: "se le este agregando las nuevas cuando se le califique su proyecto"
        // So we filter based on status 'approved' OR having a score.
        // Actually, let's just fetch all and let the view filter/show locked states if we want, 
        // OR filtering here is cleaner.
        // Let's filter here: Projects that are Approved OR have a score.
        
        $projects = Project::where(function($q) {
                $q->where('user_id', Auth::id())
                  ->orWhereHas('members', function($m) {
                      $m->where('user_id', Auth::id());
                  });
            })
            ->with(['event', 'judges'])
            ->get();
            
        // We will filter visible certificates in the view or here.
        // Let's pass all projects so we can show "Pending" or "Locked" states if we want, 
        // but user just said "show all".
        
        return view('Student.certificates', compact('projects'));
    }



}