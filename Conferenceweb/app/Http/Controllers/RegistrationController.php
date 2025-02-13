<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

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
            'user_id' => auth()->id(),
            'abstract' => $abstractPath,
           
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
    ]);

    $registration->update($validatedData);

    return redirect()->route('admin.registration')->with('success', 'Registration updated successfully.');
}

    

    
    public function destroy(Registration $registration)
    {
        $registration->delete();
        return redirect()->back()->with('success', 'Registration deleted successfully.');
    }

   
    
}
