@extends('layouts.app')

@section('content')
    <!-- Personalized Welcome Message -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-center text-2xl font-bold mb-4">
            Welcome, {{ auth()->user()->name }}!
        </h1>
        <p class="text-center text-gray-400 text-xs">
            You are logged in as a <span class="font-semibold">{{ auth()->user()->userrole }}</span>.
        </p>
    </div>
@endsection
