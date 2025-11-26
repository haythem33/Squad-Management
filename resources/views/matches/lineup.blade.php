@extends('layouts.main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Lineup Editor</h1>
        <p class="text-gray-600 text-lg">Select players and track match statistics</p>
    </div>

    <!-- Match Info Card -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl shadow-lg p-7 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white mb-2">{{ $match->team->name }} vs {{ $match->opponent }}</h2>
                <p class="text-primary-100 font-medium">
                    {{ \Carbon\Carbon::parse($match->match_date)->format('F j, Y - g:i A') }}
                </p>
                @if($match->location)
                    <p class="text-primary-100 mt-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $match->location }}
                    </p>
                @endif
            </div>
            <div class="text-right bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <div class="text-white">
                    <div class="text-3xl font-bold" id="selectedCount">{{ $match->players->count() }}</div>
                    <div class="text-primary-100 text-sm font-medium">Players Selected</div>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Lineup Form -->
    <form action="{{ route('matches.lineup.update', $match) }}" method="POST" id="lineupForm">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            @if($match->team->players->count() > 0)
                <!-- Table Header -->
                <div class="bg-gray-800 border-b border-gray-700 px-8 py-5">
                    <div class="grid grid-cols-12 gap-4 items-center font-bold text-sm text-white">
                        <div class="col-span-1">Select</div>
                        <div class="col-span-5">Player Name</div>
                        <div class="col-span-2">Position</div>
                        <div class="col-span-2 text-center">Goals</div>
                        <div class="col-span-2 text-center">Minutes Played</div>
                    </div>
                </div>

                <!-- Player Rows -->
                <div class="divide-y divide-gray-100">
                    @foreach($match->team->players as $player)
                        @php
                            $inLineup = $match->players->contains($player->id);
                            $pivotData = $inLineup ? $match->players->find($player->id)->pivot : null;
                        @endphp
                        <div class="player-row px-8 py-5 hover:bg-gray-50 transition-all duration-200 {{ $inLineup ? 'bg-primary-50 border-l-4 border-primary-500' : '' }}" 
                             data-player-id="{{ $player->id }}">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Checkbox -->
                                <div class="col-span-1">
                                    <input type="checkbox" 
                                           name="players[{{ $player->id }}][selected]" 
                                           value="1"
                                           class="player-checkbox w-6 h-6 text-primary-600 focus:ring-primary-500 border-gray-300 rounded cursor-pointer"
                                           {{ $inLineup ? 'checked' : '' }}>
                                </div>

                                <!-- Player Info -->
                                <div class="col-span-5 flex items-center space-x-4">
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
                                        @if($player->jersey_number)
                                            <div class="text-xs text-gray-500">#{{ $player->jersey_number }}</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Position -->
                                <div class="col-span-2">
                                    <span class="text-sm text-gray-600">{{ $player->position ?? 'N/A' }}</span>
                                </div>

                                <!-- Goals Input -->
                                <div class="col-span-2 text-center">
                                    <input type="number" 
                                           name="players[{{ $player->id }}][goals]" 
                                           min="0" 
                                           max="20"
                                           value="{{ $pivotData->goals ?? 0 }}"
                                           class="goals-input w-24 px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center font-bold text-gray-900 {{ $inLineup ? 'bg-white' : 'bg-gray-100' }}"
                                           {{ $inLineup ? '' : 'readonly' }}>
                                </div>

                                <!-- Minutes Input -->
                                <div class="col-span-2 text-center">
                                    <input type="number" 
                                           name="players[{{ $player->id }}][minutes]" 
                                           min="0" 
                                           max="120"
                                           value="{{ $pivotData->minutes_played ?? 0 }}"
                                           class="minutes-input w-24 px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center font-bold text-gray-900 {{ $inLineup ? 'bg-white' : 'bg-gray-100' }}"
                                           {{ $inLineup ? '' : 'readonly' }}>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Footer -->
                <div class="bg-gray-800 border-t border-gray-700 px-8 py-5">
                    <div class="flex justify-between items-center">
                        <span class="text-white font-medium"><span id="selectedCountFooter" class="font-bold text-2xl text-primary-300">{{ $match->players->count() }}</span> <span class="text-gray-300">players in lineup</span></span>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="window.location='{{ route('matches.show', $match) }}'" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition shadow-sm">
                                Cancel
                            </button>
                            <button type="submit" class="px-8 py-3 bg-primary-500 hover:bg-primary-600 text-white font-bold rounded-lg transition shadow-md hover:shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Lineup
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-16 bg-gray-50">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-gray-600 font-medium mb-4">No players available in this team</p>
                    <button onclick="window.location='{{ route('players.create', ['team_id' => $match->team_id]) }}'" class="bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-8 rounded-lg transition">
                        Add Players to Team
                    </button>
                </div>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const playerCheckboxes = document.querySelectorAll('.player-checkbox');
    const playerRows = document.querySelectorAll('.player-row');
    
    // Individual checkbox change
    playerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateRowState(this);
            updateStats();
        });
    });
    
    // Update row visual state and input states
    function updateRowState(checkbox) {
        const row = checkbox.closest('.player-row');
        const goalsInput = row.querySelector('.goals-input');
        const minutesInput = row.querySelector('.minutes-input');
        
        if (checkbox.checked) {
            row.classList.add('bg-primary-50', 'border-l-4', 'border-primary-500');
            goalsInput.removeAttribute('readonly');
            goalsInput.classList.remove('bg-gray-100');
            goalsInput.classList.add('bg-white');
            minutesInput.removeAttribute('readonly');
            minutesInput.classList.remove('bg-gray-100');
            minutesInput.classList.add('bg-white');
        } else {
            row.classList.remove('bg-primary-50', 'border-l-4', 'border-primary-500');
            goalsInput.setAttribute('readonly', 'readonly');
            goalsInput.classList.add('bg-gray-100');
            goalsInput.classList.remove('bg-white');
            minutesInput.setAttribute('readonly', 'readonly');
            minutesInput.classList.add('bg-gray-100');
            minutesInput.classList.remove('bg-white');
        }
    }
    
    // Update summary statistics
    function updateStats() {
        let selectedCount = 0;
        
        playerCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedCount++;
            }
        });
        
        const selectedCountEl = document.getElementById('selectedCount');
        const selectedCountFooter = document.getElementById('selectedCountFooter');
        
        if (selectedCountEl) selectedCountEl.textContent = selectedCount;
        if (selectedCountFooter) selectedCountFooter.textContent = selectedCount;
    }
    
    // Update stats when goals/minutes change
    const goalsInputs = document.querySelectorAll('.goals-input');
    
    goalsInputs.forEach(input => {
        input.addEventListener('input', updateStats);
    });
});
</script>
@endpush
@endsection
