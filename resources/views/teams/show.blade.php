@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $team->name }}</h1>
                @if($team->description)
                    <p class="text-gray-600">{{ $team->description }}</p>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('teams.edit', $team) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Edit Team
                </a>
                <a href="{{ route('teams.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Back to Teams
                </a>
            </div>
        </div>
    </div>

    <!-- 2-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- LEFT COLUMN: Current Squad / Roster -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Current Squad</h2>
                <a href="{{ route('players.create', ['team_id' => $team->id]) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Player
                </a>
            </div>

            @if($team->players->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Player
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Position
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($team->players as $player)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            @if($player->photo)
                                                <img src="{{ asset('storage/' . $player->photo) }}" 
                                                     alt="{{ $player->name }}" 
                                                     class="h-10 w-10 rounded-full mr-3 object-cover border-2 border-gray-200">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($player->name, 0, 2)) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $player->name }}</div>
                                                @if($player->jersey_number)
                                                    <div class="text-xs text-gray-500">#{{ $player->jersey_number }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            @if($player->position === 'Forward') bg-red-100 text-red-800
                                            @elseif($player->position === 'Midfielder') bg-blue-100 text-blue-800
                                            @elseif($player->position === 'Defender') bg-green-100 text-green-800
                                            @elseif($player->position === 'Goalkeeper') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $player->position ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('players.edit', $player) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Squad Statistics -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ $team->players->count() }}</p>
                            <p class="text-xs text-gray-600 uppercase">Total Players</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $team->players->where('position', 'Forward')->count() }}
                            </p>
                            <p class="text-xs text-gray-600 uppercase">Forwards</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-purple-600">
                                {{ $team->players->where('position', 'Defender')->count() }}
                            </p>
                            <p class="text-xs text-gray-600 uppercase">Defenders</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-gray-600 font-medium mb-4">No players in this squad yet</p>
                    <a href="{{ route('players.create', ['team_id' => $team->id]) }}" 
                       class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded">
                        Add Your First Player
                    </a>
                </div>
            @endif
        </div>

        <!-- RIGHT COLUMN: Match Schedule / Fixtures -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Fixtures</h2>
                <a href="{{ route('matches.create', ['team_id' => $team->id]) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Schedule Match
                </a>
            </div>

            @if($team->matches->count() > 0)
                <div class="space-y-4">
                    @foreach($team->matches as $match)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow bg-gray-50">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <h3 class="text-lg font-bold text-gray-800">
                                            vs {{ $match->opponent }}
                                        </h3>
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600 mb-1">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($match->match_date)->format('F j, Y - g:i A') }}
                                    </div>

                                    @if($match->location)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $match->location }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Match Status Badge -->
                                @php
                                    $matchDate = \Carbon\Carbon::parse($match->match_date);
                                    $isUpcoming = $matchDate->isFuture();
                                    $isPast = $matchDate->isPast();
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $isUpcoming ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $isUpcoming ? 'Upcoming' : 'Completed' }}
                                </span>
                            </div>

                            <!-- Lineup Summary -->
                            @if($match->players->count() > 0)
                                <div class="flex items-center justify-between bg-white rounded p-3 mb-3 text-sm">
                                    <div class="flex items-center space-x-4">
                                        <div>
                                            <span class="text-gray-600">Players:</span>
                                            <span class="font-semibold text-gray-900">{{ $match->players->count() }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Goals:</span>
                                            <span class="font-semibold text-green-600">{{ $match->players->sum('pivot.goals') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('matches.show', $match) }}" 
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-2 px-4 rounded text-sm">
                                    Manage Lineup
                                </a>
                                <a href="{{ route('matches.edit', $match) }}" 
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Match Statistics -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ $team->matches->count() }}</p>
                            <p class="text-xs text-gray-600 uppercase">Total Matches</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $team->matches->filter(fn($m) => \Carbon\Carbon::parse($m->match_date)->isFuture())->count() }}
                            </p>
                            <p class="text-xs text-gray-600 uppercase">Upcoming</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-600 font-medium mb-4">No matches scheduled yet</p>
                    <a href="{{ route('matches.create', ['team_id' => $team->id]) }}" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded">
                        Schedule Your First Match
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
