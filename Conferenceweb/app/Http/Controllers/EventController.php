<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;

class EventController extends Controller
{
        // Display a listing of events
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    // Show a specific event
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    // Create a new event (this method is for organizer use, will not be shown to users)
    public function create()
    {
        return view('events.create');
    }

    // Store a newly created event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);

        auth()->user()->events()->create($validated);
        return redirect()->route('organizer.dashboard')->with('success', 'Event created successfully.');
    }

    // Edit event (for organizers)
    public function edit(Event $event)
    {
        return view('organizer.events.edit', compact('event'));
    }

    // Update event (for organizers)
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);

        $event->update($validated);
        return redirect()->route('organizer.dashboard')->with('success', 'Event updated successfully.');
    }

    // Delete event (admin functionality)
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Event deleted successfully.');
    }

    public function register(Event $event)
    {
        $user = auth()->user(); // Get the authenticated user

        // Check if the user is already registered for this event
        if ($event->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('events.index')->with('error', 'You are already registered for this event.');
        }

        // Attach the user to the event
        $event->users()->attach($user);

        return redirect()->route('events.index')->with('success', 'You have successfully registered for the event!');
    }
}
