<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::paginate(10);

        return view('Admin.events.index', compact('events'));
    }

    public function create()
    {
        $this->authorize('create', Event::class);
        $managers = User::role('event_manager')->get();
        $categories = Category::all();

        return view('Admin.events.create', compact('managers', 'categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Event::class);
        $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:' . Event::STATUS_REGISTRATION . ',' . Event::STATUS_IN_PROGRESS . ',' . Event::STATUS_FINISHED
        ]);

        $manager = User::find($request->input('manager_id'));
        if (!$manager || !$manager->hasRole('event_manager')) {
            return back()->withErrors(['manager_id' => 'El usuario seleccionado no tiene el rol de Gestor de Eventos.'])->withInput();
        }

        $existingEvent = Event::where('manager_id', $manager->id)->active()->first();
        if ($existingEvent) {
            return back()->withErrors(['manager_id' => 'Este gestor ya tiene un evento activo asignado: "' . $existingEvent->name . '". Un gestor solo puede administrar un evento activo a la vez.'])->withInput();
        }

        $event = Event::create($request->all());

        Notification::create([
            'user_id' => $manager->id,
            'type' => 'event_assigned',
            'title' => 'Evento asignado',
            'message' => 'Se te ha asignado como gestor del evento "' . $event->name . '". Ingresa a tu panel para administrarlo.',
            'data' => ['event_id' => $event->id],
            'url' => route('manager.dashboard'),
        ]);

        return redirect()->route('events.index')->with('success', 'Evento creado correctamente.');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $managers = User::role('event_manager')->get();
        $categories = Category::all();

        return view('Admin.events.edit', compact('event', 'managers', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        $request->validate([
            'name' => 'required|string|max:255',
            'manager_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $manager = User::find($request->input('manager_id'));
        if (!$manager || !$manager->hasRole('event_manager')) {
            return back()->withErrors(['manager_id' => 'El usuario seleccionado no tiene el rol de Gestor de Eventos.'])->withInput();
        }

        $oldManagerId = $event->manager_id;

        if ($oldManagerId != $manager->id) {
            $existingEvent = Event::where('manager_id', $manager->id)->active()->first();
            if ($existingEvent) {
                return back()->withErrors(['manager_id' => 'Este gestor ya tiene un evento activo asignado: "' . $existingEvent->name . '". Un gestor solo puede administrar un evento activo a la vez.'])->withInput();
            }
        }

        $event->update($request->all());

        if ($oldManagerId != $manager->id) {
            Notification::create([
                'user_id' => $manager->id,
                'type' => 'event_assigned',
                'title' => 'Evento asignado',
                'message' => 'Se te ha asignado como gestor del evento "' . $event->name . '". Ingresa a tu panel para administrarlo.',
                'data' => ['event_id' => $event->id],
                'url' => route('manager.dashboard'),
            ]);
        }

        return redirect()->route('events.index')->with('success', 'Evento actualizado.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Evento eliminado.');
    }
}