@extends('layouts.main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Welcome Banner -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-slate-500 mt-1">Manage your teams and track performance</p>
        </div>
        <button onclick="window.location='{{ route('teams.create') }}'" class="bg-white text-slate-700 border border-slate-300 hover:bg-slate-50 font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Team
        </button>
    </div>

    <!-- Section Title & Search -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Your Teams</h2>
        
        <form action="{{ route('teams.index') }}" method="GET" class="flex items-center">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search teams..." class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent w-full md:w-64">
            </div>
            <button type="submit" class="ml-2 bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition font-medium">Search</button>
            
            @if(request('search'))
                <a href="{{ route('teams.index') }}" class="ml-3 text-sm text-slate-500 hover:text-slate-700 underline whitespace-nowrap">Clear Search</a>
            @endif
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif

    @if($teams->count() > 0)
    <!-- Teams Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($teams as $team)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Card Content -->
            <div class="p-6">
                <!-- Team Header with Delete Icon -->
                <div class="flex justify-between items-start mb-5">
                    <div class="flex items-center space-x-4">
                        <!-- Team Avatar -->
                        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 border border-slate-200">
                            <span class="text-slate-700 font-bold text-xl">{{ strtoupper(substr($team->name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">{{ $team->name }}</h3>
                        </div>
                    </div>
                    <!-- Delete Button -->
                    <form action="{{ route('teams.destroy', $team) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this team?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-red-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
                
                <!-- Team Stats -->
                <div class="flex items-center gap-6 mb-6 pb-5 border-b border-slate-100">
                    <div class="flex items-center text-slate-500">
                        <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="font-bold text-slate-900 text-lg mr-1">{{ $team->players_count ?? $team->players->count() }}</span>
                        <span class="text-sm">Players</span>
                    </div>
                    <div class="flex items-center text-slate-500">
                        <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-bold text-slate-900 text-lg mr-1">{{ $team->matches_count ?? $team->matches->count() }}</span>
                        <span class="text-sm">Matches</span>
                    </div>
                </div>

                <!-- Manage Team Button -->
                <button onclick="window.location='{{ route('teams.show', $team) }}'" class="w-full bg-slate-900 text-white hover:bg-slate-800 font-semibold py-3 px-4 rounded-lg transition-all duration-200">
                    Manage Team
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($teams instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-8">
        {{ $teams->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">No Teams Yet</h2>
        <p class="text-gray-600 mb-6">Get started by creating your first team to manage players and matches.</p>
        <a href="{{ route('teams.create') }}" class="inline-block bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200">
            <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Your First Team
        </a>
    </div>
    @endif
</div>
@endsection
