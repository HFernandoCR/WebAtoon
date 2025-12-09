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
            $data['totalUsers'] = User::count();
            $data['activeEvents'] = Event::where('status', 'active')->count();
            $data['totalJudges'] = User::role('judge')->count();
        } 
        elseif ($role === 'event_manager') {
            // Priority 1: Active event managed by user
            $event = Event::where('manager_id', $user->id)
                ->active()
                ->first();

            // Priority 2: Latest event managed by user (fallback)
            if (!$event) {
                $event = Event::where('manager_id', $user->id)
                    ->latest()
                    ->first();
            }

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
            $data['judgedProjects'] = $user->judgedProjects()
                ->whereHas('event', function($q) {
                    $q->active();
                })
                ->with('event')
                ->get();
        }
        elseif ($role === 'advisor') {
            $data['advisedProjects'] = $user->advisedProjects()->with(['members', 'event'])->get();
        }
        elseif ($role === 'student') {
            // Priority 1: Project in an active event (Registration or In Progress)
            $activeProject = Project::where('user_id', $user->id)
                ->whereHas('event', function($q) {
                    $q->active();
                })
                ->with('event')
                ->first();

            if ($activeProject) {
                $data['myProject'] = $activeProject;
            } else {
                // Priority 2: Most recent project if no active one exists
                $data['myProject'] = Project::where('user_id', $user->id)
                    ->with('event')
                    ->latest()
                    ->first();
            }
        }

        return view('dashboard', $data);
    }
}
