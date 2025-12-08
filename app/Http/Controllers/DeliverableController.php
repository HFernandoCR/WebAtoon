<?php

namespace App\Http\Controllers;

use App\Models\Deliverable;
use App\Models\Project;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliverableController extends Controller
{
    public function index()
    {
        // 1. Buscamos el proyecto del estudiante actual
        $project = Project::where('user_id', Auth::id())->first();

        if (!$project) {
            return redirect()->route('projects.create')->with('error', 'Primero debes inscribir un proyecto.');
        }

        // 2. Buscamos los entregables de ese proyecto
        $deliverables = Deliverable::where('project_id', $project->id)->get();

        return view('Student.deliverables.index', compact('project', 'deliverables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255|min:3',
            'file' => [
                'required',
                'file',
                'mimes:pdf,zip,doc,docx,ppt,pptx,xlsx',
                'max:20480',
                'mimetypes:application/pdf,application/zip,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ],
            'comments' => 'nullable|string|max:1000'
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'file.required' => 'Debes seleccionar un archivo.',
            'file.mimes' => 'El archivo debe ser PDF, ZIP, DOC, DOCX, PPT, PPTX o XLSX.',
            'file.max' => 'El archivo no debe superar los 20MB.',
            'comments.max' => 'Los comentarios no deben exceder 1000 caracteres.'
        ]);

        $project = Project::where('id', $request->input('project_id'))
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $event = $project->event;
        $now = now();

        if ($event->status !== Event::STATUS_IN_PROGRESS) {
            return redirect()->back()->with('error', 'El evento no está en curso. No se pueden subir entregables.');
        }

        if ($now < $event->start_date || $now > $event->end_date) {
            return redirect()->back()->with('error', 'No puedes subir entregables fuera de las fechas del evento.');
        }

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $originalName = $request->file('file')->getClientOriginalName();
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
            $path = $request->file('file')->storeAs('deliverables', $filename, 'public');

            Deliverable::create([
                'project_id' => $project->id,
                'title' => $request->input('title'),
                'file_path' => $path,
                'comments' => $request->input('comments')
            ]);

            return redirect()->back()->with('success', 'Entregable subido correctamente.');
        }

        return redirect()->back()->with('error', 'Error al subir el archivo.');
    }
    
    // Método para descargar (opcional pero útil)
    public function download(Deliverable $deliverable)
    {
        // Seguridad: Verificar que el entregable pertenezca a un proyecto del usuario
        if($deliverable->project->user_id !== Auth::id()) abort(403);
        
        return Storage::disk('public')->download($deliverable->file_path);
    }
}