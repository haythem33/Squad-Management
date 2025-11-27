@extends('layouts.main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-5">
            <!-- Team Avatar -->
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                <span class="text-white font-bold text-3xl">{{ strtoupper(substr($team->name, 0, 2)) }}</span>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $team->name }}</h1>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span class="flex items-center">
                        <span class="font-semibold text-gray-900 mr-1">{{ $team->players->count() }}</span> Players
                    </span>
                    <span class="flex items-center">
                        <span class="font-semibold text-gray-900 mr-1">{{ $team->matches->count() }}</span> Matches
                    </span>
                    @if($team->matches->count() > 0)
                        @php
                            $wins = 0;
                            $total = $team->matches->count();
                            $winRate = $total > 0 ? round(($wins / $total) * 100) : 0;
                        @endphp
                        <span class="flex items-center">
                            Win Rate: <span class="font-semibold text-gray-900 ml-1">{{ $winRate }}%</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <button onclick="window.location='{{ route('teams.edit', $team) }}'" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-6 rounded-lg transition shadow-sm hover:shadow-md flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Team
        </button>
    </div>

    <!-- 2-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- LEFT COLUMN: Current Squad / Roster -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-7">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Roster</h2>
                
                <div class="flex items-center gap-3">
                    <form action="{{ route('teams.show', $team) }}" method="GET" class="relative">
                        <input type="text" name="player_search" value="{{ request('player_search') }}" placeholder="Search players..." class="pl-3 pr-8 py-1.5 text-sm border border-slate-300 rounded-md focus:outline-none focus:ring-1 focus:ring-slate-500 w-40">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>

                    <button onclick="window.location='{{ route('players.create', ['team_id' => $team->id]) }}'" class="text-primary-600 hover:text-primary-700 font-semibold text-sm flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Player
                    </button>
                </div>
            </div>

            @if($team->players->count() > 0)
                <div class="space-y-3">
                    @foreach($team->players as $player)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 hover:shadow-sm transition-all duration-200 border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <!-- Player Avatar -->
                                @if($player->photo)
                                    <img src="{{ asset('storage/' . $player->photo) }}" 
                                         alt="{{ $player->name }}" 
                                         class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-md">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md">
                                        <span class="text-white font-bold">{{ strtoupper(substr($player->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900">{{ $player->name }}</div>
                                    <div class="text-sm text-gray-500 font-medium">{{ $player->position ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <button onclick="window.location='{{ route('players.edit', $player) }}'" class="text-gray-400 hover:text-primary-600 hover:bg-primary-50 p-2 rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-gray-500 mb-4">No players in this squad yet</p>
                    <button onclick="window.location='{{ route('players.create', ['team_id' => $team->id]) }}'" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Add Your First Player
                    </button>
                </div>
            @endif
        </div>

        <!-- RIGHT COLUMN: Match Schedule / Fixtures -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-7">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Fixtures</h2>
                <button onclick="window.location='{{ route('matches.create', ['team_id' => $team->id]) }}'" class="text-primary-600 hover:text-primary-700 font-semibold text-sm flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Schedule Match
                </button>
            </div>

            @if($team->matches->count() > 0)
                <div class="space-y-3">
                    @foreach($team->matches as $match)
                        @php
                            $matchDate = \Carbon\Carbon::parse($match->match_date);
                            $isUpcoming = $matchDate->isFuture();
                        @endphp
                        <div class="p-5 bg-gray-50 rounded-xl hover:bg-gray-100 hover:shadow-sm transition-all duration-200 border border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-4">
                                    <div class="text-center bg-white rounded-lg p-3 shadow-sm border border-gray-200">
                                        <div class="text-2xl font-bold text-primary-600">{{ $matchDate->format('d') }}</div>
                                        <div class="text-xs font-bold text-gray-500 uppercase">{{ $matchDate->format('M') }}</div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">vs {{ $match->opponent }}</h3>
                                        <p class="text-sm text-gray-500 font-medium">{{ $matchDate->format('g:i A') }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $isUpcoming ? 'bg-primary-100 text-primary-700' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $isUpcoming ? 'Upcoming' : 'Played' }}
                                </span>
                            </div>
                            
                            @if($match->players->count() > 0)
                                <div class="flex items-center gap-5 text-sm text-gray-600 mb-4 bg-white p-3 rounded-lg">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="font-bold text-gray-900 text-base">{{ $match->players->count() }}</span>
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-medium text-gray-700">Goals:</span> <span class="font-bold text-gray-900 ml-1.5">{{ $match->players->sum('pivot.goals') }}</span>
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-medium text-gray-700">Minutes:</span> <span class="font-bold text-gray-900 ml-1.5">{{ $match->players->sum('pivot.minutes_played') }}</span>
                                    </span>
                                </div>
                            @endif
                            
                            <button onclick="window.location='{{ route('matches.show', $match) }}'" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2.5 px-4 rounded-lg transition shadow-sm hover:shadow-md">
                                View Match Details
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500 mb-4">No matches scheduled yet</p>
                    <button onclick="window.location='{{ route('matches.create', ['team_id' => $team->id]) }}'" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-6 rounded-lg transition">
                        Schedule Your First Match
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
