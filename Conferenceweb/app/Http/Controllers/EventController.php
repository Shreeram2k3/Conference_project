<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Registration; 

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
        $timelines = $event->timelines()->orderBy('date', 'asc')->get();

          return view('events.show', compact('event', 'timelines'));
    }

    // // Create a new event (this method is for organizer use, will not be shown to users)
    // public function create()
    // {
    //     return view('events.create');
    // }

    // // Store a newly created event
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'date' => 'required|date',
    //     ]);

    //     auth()->user()->events()->create($validated);
    //     return redirect()->route('organizer.dashboard')->with('success', 'Event created successfully.');
    // }

    // // Edit event (for organizers)
    // public function edit(Event $event)
    // {
    //     return view('organizer.events.edit', compact('event'));
    // }

    // // Update event (for organizers)
    // public function update(Request $request, Event $event)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'date' => 'required|date',
    //     ]);

    //     $event->update($validated);
    //     return redirect()->route('organizer.dashboard')->with('success', 'Event updated successfully.');
    // }

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
        ]);
    
        Event::create($validated); 
    
        return redirect()->route('admin.dashboard')->with('success', 'Event created successfully.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'event_name' => 'required|string|max:255',
        'description' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $event = Event::findOrFail($id);
    $event->update([
        'event_name' => $request->event_name,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Event updated successfully.');
}


public function registration(Request $request)
{
    $query = Registration::with('user', 'event');

    // Search by user name, email, or phone
    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;
        $query->whereHas('user', function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('email', 'LIKE', "%{$searchTerm}%");
        })->orWhere('phone', 'LIKE', "%{$searchTerm}%");
    }

    // Filter by event
    if ($request->has('event_id') && $request->event_id != '') {
        $query->where('event_id', $request->event_id);
    }

    $registrations = $query->get();
    $events = Event::all(); // Fetch all events for dropdown

    return view('admin.registration', compact('registrations', 'events'));
}



    
}
