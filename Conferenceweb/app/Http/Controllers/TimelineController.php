<?php

namespace App\Http\Controllers;

use App\Models\Timeline;
use App\Models\Event;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    /**
     * Store a newly created timeline entry.
     */
    public function store(Request $request, $eventId)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:' . Event::findOrFail($eventId)->start_date . '|before_or_equal:' . Event::findOrFail($eventId)->end_date,
        ]);

        // Store timeline
        Timeline::create([
            'event_id' => $eventId,
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Timeline added successfully.');
    }

    /**
     * Destroy the specified timeline.
     */
    public function destroy(Timeline $timeline)
    {
        // Delete the timeline
        $timeline->delete();

        // Redirect back to the event details page with a success message
        return redirect()->route('events.show', $timeline->event_id)->with('success', 'Timeline deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        $timeline = Timeline::findOrFail($id);
    
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:' . $timeline->event->start_date . '|before_or_equal:' . $timeline->event->end_date,
        ]);
    
        $timeline->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
        ]);
    
        return redirect()->back()->with('success', 'Timeline updated successfully!');
    }
    
}
