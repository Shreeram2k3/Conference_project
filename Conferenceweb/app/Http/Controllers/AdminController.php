<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;

class AdminController extends Controller
{
    // public function index()
    // {
    //     return view('admin.dashboard'); // Return the page from admin folder
    // }

    public function index()
    {
        // View all users and events
        $users = User::all();
        $events = Event::all();
        return view('admin.dashboard', compact('users', 'events'));
    }

    // Method to show a specific event details (admin can view)
    public function showEvent(Event $event)
    {
        return view('admin.event.show', compact('event'));
    }

    // Method to delete an event (admin can delete)
    public function destroyEvent(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Event deleted successfully');
    }
}
