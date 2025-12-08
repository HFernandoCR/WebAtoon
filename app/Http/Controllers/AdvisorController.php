<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvisorController extends Controller
{
    public function index()
    {
        // Obtener proyectos donde yo soy el advisor_id
        $projects = Auth::user()->advisedProjects()
            ->with('event', 'author') // Traer datos del evento y del alumno líder
            ->paginate(10);

        return view('Advisor.index', compact('projects'));
    }

    public function certificates()
    {
        // Obtener proyectos aprobados del asesor (solo aprobados generan constancia)
        $projects = Auth::user()->advisedProjects()
            ->where('status', 'approved')
            ->with('event')
            ->get();

        return view('Advisor.certificates', compact('projects'));
    }
}