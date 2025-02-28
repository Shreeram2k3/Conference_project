<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrationController extends Controller
{
    public function create(Event $event)
    {
        return view('registrations.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'institution' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'abstract' => 'required|file|mimes:doc,docx|max:2048',
            
         ]);

        // Handle file upload
        $abstractPath = null;
        if ($request->hasFile('abstract')) {
            $abstractPath = $request->file('abstract')->store('abstracts', 'public');
        }


        $registration= Registration::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'institution' => $request->institution,
            'designation' => $request->designation,
            'event_id' => $event->id,
            // 'user_id' => auth()->id(),
            'abstract' => $abstractPath,
            'mode' => $request->mode,
            
           
        ]);

        $registration->notify(new \App\Notifications\confirmation_mail());


        return redirect()->route('events.index')->with('success', 'Registration successful!');
    }

    public function update(Request $request, $id)
{
    $registration = Registration::findOrFail($id);

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'institution' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'event_id' => 'required|exists:events,id',
        'mode' => 'required|in:Online,Offline',
    ]);

    $registration->update($validatedData);

    return redirect()->route('admin.registration')->with('success', 'Registration updated successfully.');
}

    

    
    public function destroy(Registration $registration)
    {
        $registration->delete();
        return redirect()->back()->with('success', 'Registration deleted successfully.');
    }


    public function showExportedRegistrations(Request $request)
    {
        $eventId = $request->input('event_id');
        $mode = $request->input('mode', 'online');
        $slotSize = (int) $request->input('slot_size', 10);
    
        // Fetch the event details
        $event = Event::findOrFail($eventId);  // This ensures the event exists
    
        // Get registrations filtered by mode
        $registrations = Registration::where('event_id', $eventId)
            ->where('mode', $mode)
            ->get();
    
        // Divide into slots
        $slots = $registrations->chunk($slotSize);
    
        // Pass event details to the view
        return view('admin.export', [
            'eventId' => $eventId,
            'eventName' => $event->event_name, // Fix: Properly pass event name
            'slots' => $slots,
            'mode' => $mode,
        ]);
    }

    public function export(Request $request)
{
    $eventId = $request->input('event_id');
    $mode = $request->input('mode', 'online');
    $slotSize = (int) $request->input('slot_size', 10);

    // Ensure event exists
    $event = Event::findOrFail($eventId);

    // Fetch registrations filtered by mode
    $registrations = Registration::where('event_id', $eventId)
        ->where('mode', $mode)
        ->get();

    // Divide into slots
    $slots = $registrations->chunk($slotSize);

    // Load PDF view
    $pdf = Pdf::loadView('admin.export', [
        'eventId' => $eventId,
        'eventName' => $event->event_name,
        'slots' => $slots,
        'mode' => $mode,
    ]);

    // Download the PDF
    return $pdf->download('registrations.pdf');
}


//    regitration without authentication 

public function storeUser(Request $request, Event $event)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'institution' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'abstract' => 'nullable|file|mimes:doc,docx|max:2048',
        'mode' => 'required|in:Online,Offline',
    ]);

    // Handle file upload
    $abstractPath = null;
    if ($request->hasFile('abstract')) {
        $abstractPath = $request->file('abstract')->store('abstracts', 'public');
    }

    // Create a new registration without user_id
    Registration::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'email' => $request->email,
        'institution' => $request->institution,
        'designation' => $request->designation,
        'event_id' => $event->id,
        'abstract' => $abstractPath,
        'mode' => $request->mode,
    ]);

    // Redirect to event details page
    // return redirect()->route('eventinfo', $event->id)->with('success', 'Registration successful!');
    return back()->with('success', 'Registration successful!');

    
}

    
}
