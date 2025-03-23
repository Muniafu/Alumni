<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Existing calendar method
    public function calendar()
    {
        return view('events.calendar', [
            'events' => Event::with('attendees')->get()
        ]);
    }

    public function index()
    {
        return view('events.index', [
            'events' => Event::with('attendees')->latest()->paginate(10),
        ]);
    }

    // Dashboard method to display all relevant event information
    public function dashboard()
    {
        return view('dashboard', [
            'upcomingEvents' => Event::upcoming()->take(5)->get(),
            'myEvents' => Event::whereHas('attendees', function($query) {
                $query->where('user_id', Auth::id());
            })->get(),
            'todayEvents' => Event::today()->get(),
            'totalEvents' => Event::count(),
        ]);
    }

    public function attend(Event $event)
    {
        $event->attendees()->attach(Auth::id());
        return redirect('dashboard')->with('success', 'Successfully registered for event: ' . $event->title);
    }

    public function unattend(Event $event)
    {
        $event->attendees()->detach(Auth::id());
        return redirect('dashboard')->with('info', 'Removed registration from event: ' . $event->title);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $event = Event::create($request->all());
        return redirect('dashboard')->with('success', 'Event "' . $event->title . '" created successfully!');
    }

    // Add new dashboard-specific search
    public function dashboardSearch(Request $request)
    {
        $query = $request->get('query');
        $events = Event::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(5)
            ->get();
            
        return response()->json([
            'events' => $events,
            'count' => $events->count()
        ]);
    }
}
