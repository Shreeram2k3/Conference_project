<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class OrganizerController extends Controller
{
    // public function index()
    // {
    //     return view('organizer.dashboard');
    // }

    public function index()
    {
        // Get events created by the logged-in organizer
        $events = auth()->user()->events;
        return view('organizer.dashboard', compact('events'));
    }

    // Show event creation form
    public function createEvent()
    {
        return view('organizer.event.create');
    }

    // Store new event created by the organizer
    public function storeEvent(Request $request)
    {
        $event = auth()->user()->events()->create($request->all());
        return redirect()->route('organizer.dashboard')->with('success', 'Event created successfully');
    }

    // View registrations for an event (only organizer's events)
    public function showRegistrations(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $registrations = $event->registrations;
        return view('organizer.registrations', compact('event', 'registrations'));
    }
}
