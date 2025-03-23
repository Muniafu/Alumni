<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Event;

class NetworkController extends Controller
{
    public function index()
    {
        return view('networking.index', [
            'users' => User::latest()->paginate(10),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get();

        return view('search', [
            'query' => $query,
            'users' => $users,
        ]);
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get();

        return view('search', [
            'query' => $query,
            'users' => $users,
        ]);
    }

    public function searchEvents(Request $request)
    {
        $query = $request->input('query');

        $events = Event::where('title', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->get();

        return view('search', [
            'query' => $query,
            'events' => $events,
        ]);
    }

    public function searchAll(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get();

        $events = Event::where('title', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->get();

        return view('search', [
            'query' => $query,
            'users' => $users,
            'events' => $events,
        ]);
    }
}
