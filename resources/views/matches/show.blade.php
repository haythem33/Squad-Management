@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Match Details Card -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Match Details</h1>
                <p class="text-gray-600">{{ $team->name }}</p>
            </div>
            <a href="{{ route('teams.show', $team) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                Back to Team
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="bg-gray-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-1">Opponent</p>
                <p class="text-xl font-semibold text-gray-800">{{ $match->opponent }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-1">Match Date</p>
                <p class="text-xl font-semibold text-gray-800">{{ \Carbon\Carbon::parse($match->match_date)->format('F j, Y - g:i A') }}</p>
            </div>
            @if($match->location)
            <div class="bg-gray-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-1">Location</p>
                <p class="text-xl font-semibold text-gray-800">{{ $match->location }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Lineup Form -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Manage Lineup</h2>

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('matches.lineup.update', $match) }}" method="POST">
            @csrf
            @method('PUT')

            @if($team->players->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Select
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Player Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Position
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Goals
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Minutes Played
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($team->players as $player)
                            @php
                                // Check if this player is already in the match lineup
                                $inLineup = $match->players->contains($player->id);
                                $pivotData = $inLineup ? $match->players->find($player->id)->pivot : null;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input 
                                        type="checkbox" 
                                        name="players[{{ $player->id }}][selected]" 
                                        value="1"
                                        {{ $inLineup ? 'checked' : '' }}
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($player->photo)
                                            <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->name }}" class="h-10 w-10 rounded-full mr-3 object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                                <span class="text-gray-600 font-semibold">{{ substr($player->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div class="text-sm font-medium text-gray-900">{{ $player->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $player->position ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input 
                                        type="number" 
                                        name="players[{{ $player->id }}][goals]" 
                                        value="{{ $pivotData->goals ?? 0 }}"
                                        min="0"
                                        max="20"
                                        class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input 
                                        type="number" 
                                        name="players[{{ $player->id }}][minutes]" 
                                        value="{{ $pivotData->minutes_played ?? 0 }}"
                                        min="0"
                                        max="120"
                                        class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('teams.show', $team) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded">
                    Save Lineup
                </button>
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-gray-600 mb-4">No players available in this team.</p>
                <a href="{{ route('players.create', ['team_id' => $team->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded inline-block">
                    Add Players
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Current Lineup Summary -->
    @if($match->players->count() > 0)
    <div class="bg-white shadow-md rounded-lg p-6 mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Current Lineup Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-1">Players Selected</p>
                <p class="text-3xl font-bold text-blue-600">{{ $match->players->count() }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-1">Total Goals</p>
                <p class="text-3xl font-bold text-green-600">{{ $match->players->sum('pivot.goals') }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded">
                <p class="text-sm text-gray-600 mb-1">Avg Minutes</p>
                <p class="text-3xl font-bold text-purple-600">
                    {{ $match->players->count() > 0 ? round($match->players->avg('pivot.minutes_played')) : 0 }}
                </p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
