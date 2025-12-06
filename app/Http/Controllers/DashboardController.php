<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        $data = [];

        if ($role === 'admin') {
            // Estadísticas generales
            $data['totalUsers'] = User::count();
            $data['totalEvents'] = Event::count();
            $data['activeEvents'] = Event::where('status', 'active')->count();
            $data['finishedEvents'] = Event::where('status', 'finished')->count();
            $data['inactiveEvents'] = Event::where('status', 'inactive')->count();

            // Estadísticas de proyectos
            $data['totalProjects'] = Project::count();
            $data['pendingProjects'] = Project::where('status', 'pending')->count();
            $data['approvedProjects'] = Project::where('status', 'approved')->count();
            $data['rejectedProjects'] = Project::where('status', 'rejected')->count();

            // Estadísticas de usuarios por rol
            $data['totalAdmins'] = User::role('admin')->count();
            $data['totalManagers'] = User::role('event_manager')->count();
            $data['totalJudges'] = User::role('judge')->count();
            $data['totalAdvisors'] = User::role('advisor')->count();
            $data['totalStudents'] = User::role('student')->count();

            // Proyectos recientes (últimos 5)
            $data['recentProjects'] = Project::with(['author', 'event'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Eventos próximos (próximos 3)
            $data['upcomingEvents'] = Event::where('start_date', '>', now())
                ->orderBy('start_date', 'asc')
                ->take(3)
                ->get();
        } 
        elseif ($role === 'event_manager') {
            $event = Event::where('manager_id', $user->id)->first();
            $data['event'] = $event;
            
            if ($event) {
                $allProjects = Project::where('event_id', $event->id)->with('judges')->get();
                $data['allProjects'] = $allProjects;
                $data['projectsWithoutJudges'] = $allProjects->filter(function($p) {
                    return $p->judges->count() === 0;
                })->count();
            } else {
                $data['allProjects'] = collect();
                $data['projectsWithoutJudges'] = 0;
            }
        }
        elseif ($role === 'judge') {
            $data['judgedProjects'] = $user->judgedProjects()->with('event')->get();
        }
        elseif ($role === 'advisor') {
            $data['advisedProjects'] = $user->advisedProjects()->with(['members', 'event'])->get();
        }
        elseif ($role === 'student') {
            $data['myProject'] = Project::where('user_id', $user->id)->with('event')->first();
        }

        return view('dashboard', $data);
    }
}
