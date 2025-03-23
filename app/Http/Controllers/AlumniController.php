<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AlumniController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        
        return view('dashboard', [
            'profile' => $user->profile,
            'skills' => $user->skills,
            'experiences' => $user->experiences,
            'events' => Event::where('date', '>=', now())->with(['attendees'])->take(5)->get(),
        ]);
    }
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Event $event)
    {
        return view('events.show', [
            'event' => $event
        ]);
    }

    public function attend(Event $event)
    {
        $event->attendees()->attach(Auth::id());

        return back();
    }

    public function unattend(Event $event)
    {
        $event->attendees()->detach(Auth::id());

        return back();
    }

    public function create()
    {
        return view('events.create');
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

        Event::create($request->all());

        return redirect('dashboard')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('events.edit', [
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $event->update($request->all());

        return redirect('dashboard')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect('dashboard')->with('success', 'Event deleted successfully!');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required',
        ]);

        $events = Event::where('title', 'like', "%{$request->query}%")
            ->orWhere('description', 'like', "%{$request->query}%")
            ->get();

        return view('search', [
            'query' => $request->query,
            'events' => $events,
        ]);
    }

    public function searchAll(Request $request)
    {
        $request->validate([
            'query' => 'required',
        ]);

        $users = User::where('name', 'like', "%{$request->query}%")
            ->orWhere('email', 'like', "%{$request->query}%")
            ->get();

        $events = Event::where('title', 'like', "%{$request->query}%")
            ->orWhere('description', 'like', "%{$request->query}%")
            ->get();

        return view('search', [
            'query' => $request->query,
            'users' => $users,
            'events' => $events,
        ]);
    }

    public function searchEvents(Request $request)
    {
        $request->validate([
            'query' => 'required',
        ]);

        $events = Event::where('title', 'like', "%{$request->query}%")
            ->orWhere('description', 'like', "%{$request->query}%")
            ->get();

        return view('search', [
            'query' => $request->query,
            'events' => $events,
        ]);
    }

    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required',
        ]);

        $users = User::where('name', 'like', "%{$request->query}%")
            ->orWhere('email', 'like', "%{$request->query}%")
            ->get();

        return view('search', [
            'query' => $request->query,
            'users' => $users,
        ]);
    }

}