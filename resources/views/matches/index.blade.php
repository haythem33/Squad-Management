@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Matches</h1>
            <p class="text-gray-600 mt-1">View and manage all your team matches</p>
        </div>
        <a href="{{ route('matches.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-200">
            <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Match
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

    @if($matches->count() > 0)
    <!-- Matches List -->
    <div class="space-y-4">
        @foreach($matches as $match)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <!-- Match Info -->
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full 
                                @if($match->match_type === 'league') bg-blue-100 text-blue-800
                                @elseif($match->match_type === 'cup') bg-purple-100 text-purple-800
                                @elseif($match->match_type === 'friendly') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif mr-3">
                                {{ ucfirst($match->match_type) }}
                            </span>
                            <span class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($match->match_date)->format('D, M j, Y') }}
                                @if($match->match_time)
                                    at {{ \Carbon\Carbon::parse($match->match_time)->format('g:i A') }}
                                @endif
                            </span>
                        </div>

                        <h2 class="text-xl font-bold text-gray-800 mb-1">
                            {{ $match->team->name }} vs {{ $match->opponent }}
                        </h2>

                        @if($match->location)
                        <p class="text-sm text-gray-600">
                            <svg class="inline-block w-4 h-4 mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $match->location }}
                        </p>
                        @endif

                        @if($match->notes)
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $match->notes }}</p>
                        @endif
                    </div>

                    <!-- Match Score/Status -->
                    <div class="text-right ml-6">
                        @if($match->team_score !== null && $match->opponent_score !== null)
                        <div class="text-3xl font-bold 
                            @if($match->team_score > $match->opponent_score) text-green-600
                            @elseif($match->team_score < $match->opponent_score) text-red-600
                            @else text-gray-600
                            @endif">
                            {{ $match->team_score }} - {{ $match->opponent_score }}
                        </div>
                        <p class="text-xs text-gray-600 mt-1 uppercase">
                            @if($match->team_score > $match->opponent_score)
                                Win
                            @elseif($match->team_score < $match->opponent_score)
                                Loss
                            @else
                                Draw
                            @endif
                        </p>
                        @else
                        <div class="text-lg font-semibold text-gray-400">
                            - - -
                        </div>
                        <p class="text-xs text-gray-600 mt-1 uppercase">Scheduled</p>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-2 mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('matches.show', $match) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-2 px-4 rounded transition duration-200">
                        View Details
                    </a>
                    <a href="{{ route('matches.lineup.edit', $match) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition duration-200">
                        <svg class="inline-block w-5 h-5 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Lineup
                    </a>
                    <a href="{{ route('matches.edit', $match) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded transition duration-200">
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
    @if($matches instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-8">
        {{ $matches->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">No Matches Yet</h2>
        <p class="text-gray-600 mb-6">Get started by scheduling your first match for your teams.</p>
        <a href="{{ route('matches.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition duration-200">
            <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Your First Match
        </a>
    </div>
    @endif
</div>
@endsection
