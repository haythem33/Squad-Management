@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Professional Match Report Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg rounded-lg p-8 mb-8 text-white">
        <div class="flex justify-between items-start mb-6">
            <div>
                <p class="text-blue-200 text-sm uppercase tracking-wide mb-2">Match Report</p>
                <h1 class="text-4xl font-bold mb-3">
                    {{ $team->name }} <span class="text-blue-200">vs</span> {{ $match->opponent }}
                </h1>
                <div class="flex items-center space-x-6 text-blue-100">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($match->match_date)->format('l, F j, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($match->match_date)->format('g:i A') }}</span>
                    </div>
                    @if($match->location)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-semibold">{{ $match->location }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <a href="{{ route('teams.show', $team) }}" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-2 px-6 rounded-lg backdrop-blur-sm transition">
                ← Back to Team
            </a>
        </div>

        <!-- Quick Stats Bar -->
        @if($match->players->count() > 0)
        <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-blue-500 border-opacity-30">
            <div class="text-center">
                <p class="text-3xl font-bold">{{ $match->players->count() }}</p>
                <p class="text-blue-200 text-sm uppercase tracking-wide">Players Selected</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold">{{ $match->players->sum('pivot.goals') }}</p>
                <p class="text-blue-200 text-sm uppercase tracking-wide">Total Goals</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold">
                    {{ $match->players->count() > 0 ? round($match->players->avg('pivot.minutes_played')) : 0 }}
                </p>
                <p class="text-blue-200 text-sm uppercase tracking-wide">Avg Minutes</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Lineup Management Form -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden" x-data="lineupManager()">
        <div class="bg-gray-50 px-8 py-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Squad Lineup Manager</h2>
                    <p class="text-gray-600 mt-1">Select players and record their performance statistics</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Selected Players</p>
                    <p class="text-3xl font-bold text-blue-600" x-text="selectedCount">0</p>
                </div>
            </div>
        </div>

        <form action="{{ route('matches.lineup.update', $match) }}" method="POST" id="lineupForm">
            @csrf
            @method('PUT')

            @if($team->players->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           @change="toggleAll($event)" 
                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-3">
                                    Select All
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Player Name
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Goals Scored
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Minutes Played
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($team->players as $player)
                            @php
                                $inLineup = $match->players->contains($player->id);
                                $pivotData = $inLineup ? $match->players->find($player->id)->pivot : null;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors player-row" 
                                :class="{ 'bg-green-50 hover:bg-green-100': isSelected{{ $player->id }} }"
                                x-data="{ isSelected{{ $player->id }}: {{ $inLineup ? 'true' : 'false' }} }">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input 
                                        type="checkbox" 
                                        name="players[{{ $player->id }}][selected]" 
                                        value="1"
                                        {{ $inLineup ? 'checked' : '' }}
                                        @change="isSelected{{ $player->id }} = $event.target.checked; updateCount()"
                                        class="h-6 w-6 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($player->photo)
                                            <img src="{{ asset('storage/' . $player->photo) }}" 
                                                 alt="{{ $player->name }}" 
                                                 class="h-12 w-12 rounded-full mr-4 object-cover border-2 border-gray-300 shadow-sm">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-4 shadow-sm">
                                                <span class="text-white font-bold text-lg">{{ strtoupper(substr($player->name, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $player->name }}</div>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="px-2 py-0.5 text-xs font-medium rounded 
                                                    @if($player->position === 'Forward') bg-red-100 text-red-800
                                                    @elseif($player->position === 'Midfielder') bg-blue-100 text-blue-800
                                                    @elseif($player->position === 'Defender') bg-green-100 text-green-800
                                                    @elseif($player->position === 'Goalkeeper') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $player->position ?? 'N/A' }}
                                                </span>
                                                @if($player->jersey_number)
                                                    <span class="text-xs text-gray-500 font-semibold">#{{ $player->jersey_number }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                        <input 
                                            type="number" 
                                            name="players[{{ $player->id }}][goals]" 
                                            value="{{ $pivotData->goals ?? 0 }}"
                                            min="0"
                                            max="20"
                                            class="w-24 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="0"
                                        >
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <input 
                                            type="number" 
                                            name="players[{{ $player->id }}][minutes]" 
                                            value="{{ $pivotData->minutes_played ?? 0 }}"
                                            min="0"
                                            max="120"
                                            class="w-24 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="0"
                                        >
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-16 px-4">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-gray-600 font-semibold text-lg mb-4">No players available in this team</p>
                <p class="text-gray-500 mb-6">Add players to your team before creating a lineup</p>
                <a href="{{ route('players.create', ['team_id' => $team->id]) }}" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md transition">
                    Add Players Now
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Floating Save Button -->
    @if($team->players->count() > 0)
    <div class="fixed bottom-8 right-8 z-50">
        <button type="submit" 
                form="lineupForm"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-full shadow-2xl hover:shadow-xl transition-all transform hover:scale-105 flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-lg">Save Lineup</span>
        </button>
    </div>
    @endif
</div>

<!-- Alpine.js Component for Interactive Features -->
<script>
    function lineupManager() {
        return {
            selectedCount: {{ $match->players->count() }},
            updateCount() {
                this.selectedCount = document.querySelectorAll('input[type="checkbox"][name*="[selected]"]:checked').length;
            },
            toggleAll(event) {
                const checkboxes = document.querySelectorAll('input[type="checkbox"][name*="[selected]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = event.target.checked;
                    checkbox.dispatchEvent(new Event('change'));
                });
            }
        }
    }
</script>
@endsection
