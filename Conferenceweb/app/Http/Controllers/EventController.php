<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Registration; 
use Illuminate\Support\Facades\Storage;
use App\Models\CommitteeMember;


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
        
        // Fetch registrations only for this event
        $registrations = Registration::where('event_id', $event->id)->get();
        $events = Event::all();
        $committee_members = $event->committeeMembers; // Fetch committee members related to this event

    
        return view('events.show', compact('event', 'timelines', 'registrations', 'events','committee_members'));
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
        if ($event->sample_paper) {
            Storage::disk('public')->delete($event->sample_paper);
        }
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
            'sample_paper' => 'nullable|file|mimes:doc,docx|max:2048',
            'category' => 'required|in:National,International', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('sample_paper')) {
            $validated['sample_paper'] = $request->file('sample_paper')->store('sample_papers', 'public');
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('event_images', 'public');
        }
    
        Event::create($validated); 
    
        return redirect()->route('admin.dashboard')->with('success', 'Event created successfully.');
    }


    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category' => 'required|in:National,International',
            'sample_paper' => 'nullable|file|mimes:doc,docx|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle sample paper update
        if ($request->hasFile('sample_paper')) {
            // Delete old file if exists
            if ($event->sample_paper) {
                Storage::disk('public')->delete($event->sample_paper);
            }
            $validated['sample_paper'] = $request->file('sample_paper')->store('sample_papers', 'public');
        }

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('event_images', 'public');
        }

        // Update event details
        $event->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Event updated successfully.');
    }


public function registration(Request $request)
{
    $query = Registration::with('event'); // Only eager load event, not users

    // Case-insensitive search within the registrations table
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = strtolower($request->search); // Convert to lowercase for case-insensitive search

        $query->where(function ($q) use ($searchTerm) {
            $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
              ->orWhereRaw('LOWER(email) LIKE ?', ["%{$searchTerm}%"])
              ->orWhereRaw('LOWER(phone) LIKE ?', ["%{$searchTerm}%"])
              ->orWhereRaw('LOWER(institution) LIKE ?', ["%{$searchTerm}%"])
              ->orWhereRaw('LOWER(designation) LIKE ?', ["%{$searchTerm}%"]);
        });
    }

    // Filter by event
    if ($request->has('event_id') && !empty($request->event_id)) {
        $query->where('event_id', $request->event_id);
    }

    $registrations = $query->get(); // Fetch filtered registrations
    $events = Event::all(); // Fetch all events for dropdown

    return view('admin.registration', compact('registrations', 'events'));
}

    
public function downloadSample($id)
{
    // Find event by ID
    $event = Event::findOrFail($id);

    // Check if the event has a sample paper file
    if (!$event->sample_paper || !Storage::exists('public/' . $event->sample_paper)) {
        abort(404, 'File not found.');
    }

    // Return the file for download
    return Storage::download('public/' . $event->sample_paper);
}

public function storeCommitteeMember(Request $request, $eventId)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'role' => 'nullable|string|max:255',
    ]);

    CommitteeMember::create([
        'event_id' => $eventId,
        'name' => $request->name,
        'designation' => $request->designation,
        'role' => $request->role,
    ]);

    return back()->with('success', 'Committee member added successfully!');
}

public function updateCommitteeMember(Request $request, $eventId, $memberId)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'role' => 'nullable|string|max:255',
    ]);

    $member = CommitteeMember::where('event_id', $eventId)->findOrFail($memberId);
    $member->update($request->all());

    return back()->with('success', 'Committee member updated successfully!');
}

public function destroyCommitteeMember(Event $event, CommitteeMember $member)
{
    $member->delete();

    return redirect()->back()->with('success', 'Committee member deleted successfully.');
}




    
}
