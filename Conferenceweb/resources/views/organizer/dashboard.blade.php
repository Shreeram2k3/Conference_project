@extends('layouts.app')

@section('content')
<!-- <h1> Organizer</h1> -->
<p class="text-center text-gray-400 text-xs">
Welcome to the <span class="font-semibold">{{ auth()->user()->userrole }} Dashboard!</span>.
</p>

  <div class="mt-10 ">
        <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center justify-center border-2 border-dashed border-gray-400 cursor-pointer hover:bg-gray-100 transition">
    <div class="text-8xl text-gray-600">+</div>
    <p class="mt-20 text-gray-600">Create Event</p>
</div>



  

@endsection