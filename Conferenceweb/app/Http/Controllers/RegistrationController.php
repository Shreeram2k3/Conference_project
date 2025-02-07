<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Event;

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
        ]);

        Registration::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'institution' => $request->institution,
            'designation' => $request->designation,
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('events.index')->with('success', 'Registration successful!');
    }
}
