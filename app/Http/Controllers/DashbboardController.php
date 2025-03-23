<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\Activity;
use App\Models\Event;
use App\Models\Connection;
use App\Models\Job;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch data for the dashboard
        $activities = Activity::where('user_id', $user->id)->latest()->take(5)->get();
        $eventCount = Event::count();
        $connectionCount = Connection::where('user_id', $user->id)->count();
        $jobCount = Job::count();
        $upcomingEvents = Event::where('start_date', '>=', now())->orderBy('start_date', 'asc')->take(5)->get();
        
        return view('dashboard', compact('activities', 'eventCount', 'connectionCount', 'jobCount', 'upcomingEvents'));
    }

    public function dashboard()
    {
        $activities = Activity::where('user_id', Auth::id())->latest()->take(10)->get(); // Fetch latest 10 activities for the logged-in user
        
        return view('dashboard', [
        'activities' => $activities,
        ]);
    }

}
