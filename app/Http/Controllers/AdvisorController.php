<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvisorController extends Controller
{
    public function index()
    {
        // Obtener proyectos donde yo soy el advisor_id   :3:3:3
        $projects = Auth::user()->advisedProjects()
                        ->with('event', 'author') // Traer datos del evento y del alumno líder
                        ->paginate(10);

        return view('Advisor.index', compact('projects'));
    }
}