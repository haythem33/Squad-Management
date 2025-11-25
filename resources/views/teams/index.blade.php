@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Teams</h1>
            <p class="text-gray-600 mt-1">Manage your squads and team rosters</p>
        </div>
        <a href="{{ route('teams.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Team
        </a>
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
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
            <!-- Team Image/Banner -->
            @if($team->logo)
            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $team->logo) }}');">
            </div>
            @else
            <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            @endif

            <!-- Team Info -->
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $team->name }}</h2>
                
                @if($team->description)
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $team->description }}</p>
                @endif

                <!-- Team Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4 pt-4 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $team->players_count ?? $team->players->count() }}</p>
                        <p class="text-xs text-gray-600 uppercase">Players</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $team->matches_count ?? $team->matches->count() }}</p>
                        <p class="text-xs text-gray-600 uppercase">Matches</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('teams.show', $team) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-2 px-4 rounded transition duration-200">
                        Manage Team
                    </a>
                    <a href="{{ route('teams.edit', $team) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                </div>
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
        <a href="{{ route('teams.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200">
            <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Your First Team
        </a>
    </div>
    @endif
</div>
@endsection
