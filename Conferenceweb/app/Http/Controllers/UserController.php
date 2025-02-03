<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class UserController extends Controller
{
     // Show all events to users
     public function index()
     {
         $events = Event::all();
         return view('user.dashboard', compact('events'));
     }
 
     // Register the user for a specific event
     public function register(Event $event)
     {
         // Check if the user is already registered for the event
         if ($event->registrations()->where('user_id', auth()->id())->exists()) {
             return redirect()->back()->with('error', 'You are already registered for this event.');
         }
 
         // Register the user for the event
         $event->registrations()->create(['user_id' => auth()->id()]);
         return redirect()->back()->with('success', 'You have successfully registered for the event.');
     }
}
