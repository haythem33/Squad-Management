<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Player Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Player Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Profile Photo -->
                            <div class="flex justify-center mb-4">
                                @if($player->profile_photo)
                                    <img 
                                        src="{{ Storage::url($player->profile_photo) }}" 
                                        alt="{{ $player->name }}" 
                                        class="h-48 w-48 rounded-full object-cover border-4 border-gray-200"
                                    >
                                @else
                                    <div class="h-48 w-48 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border-4 border-gray-200">
                                        <span class="text-white font-bold text-6xl">
                                            {{ strtoupper(substr($player->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Player Name -->
                            <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">
                                {{ $player->name }}
                            </h2>

                            <!-- Position Badge -->
                            <div class="flex justify-center mb-4">
                                <span class="px-4 py-2 text-sm font-semibold rounded-full 
                                    @if($player->position === 'Forward') bg-red-100 text-red-800
                                    @elseif($player->position === 'Midfielder') bg-blue-100 text-blue-800
                                    @elseif($player->position === 'Defender') bg-green-100 text-green-800
                                    @elseif($player->position === 'Goalkeeper') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $player->position ?? 'N/A' }}
                                </span>
                            </div>

                            <!-- Team Info -->
                            <div class="border-t border-gray-200 pt-4 mb-4">
                                <p class="text-sm text-gray-600 mb-1">Team</p>
                                <a href="{{ route('teams.show', $player->team) }}" 
                                   class="text-lg font-semibold text-blue-600 hover:text-blue-800">
                                    {{ $player->team->name }}
                                </a>
                            </div>

                            <!-- Career Stats -->
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Career Stats</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center bg-gray-50 rounded-lg p-3">
                                        <p class="text-2xl font-bold text-blue-600">
                                            {{ $player->matches->count() }}
                                        </p>
                                        <p class="text-xs text-gray-600 uppercase">Appearances</p>
                                    </div>
                                    <div class="text-center bg-gray-50 rounded-lg p-3">
                                        <p class="text-2xl font-bold text-green-600">
                                            {{ $player->matches->sum('pivot.goals') }}
                                        </p>
                                        <p class="text-xs text-gray-600 uppercase">Goals</p>
                                    </div>
                                    <div class="text-center bg-gray-50 rounded-lg p-3 col-span-2">
                                        <p class="text-2xl font-bold text-purple-600">
                                            {{ $player->matches->sum('pivot.minutes_played') }}
                                        </p>
                                        <p class="text-xs text-gray-600 uppercase">Minutes Played</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('players.edit', $player) }}" 
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-2 px-4 rounded">
                                        Edit Player
                                    </a>
                                    <a href="{{ route('teams.show', $player->team) }}" 
                                       class="w-full bg-gray-500 hover:bg-gray-600 text-white text-center font-semibold py-2 px-4 rounded">
                                        Back to Team
                                    </a>
                                    <form action="{{ route('players.destroy', $player) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this player?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                                            Delete Player
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Match History -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Match History</h3>

                            @if($player->matches->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Date
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Opponent
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Goals
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Minutes
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($player->matches as $match)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $match->match_date->format('M j, Y') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $match->match_date->format('g:i A') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4">
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            vs {{ $match->opponent }}
                                                        </div>
                                                        @php
                                                            $isUpcoming = $match->match_date->isFuture();
                                                        @endphp
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                            {{ $isUpcoming ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                            {{ $isUpcoming ? 'Upcoming' : 'Completed' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full 
                                                            {{ $match->pivot->goals > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }} 
                                                            font-bold text-sm">
                                                            {{ $match->pivot->goals }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <span class="text-sm font-semibold text-gray-900">
                                                            {{ $match->pivot->minutes_played }}'
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-right">
                                                        <a href="{{ route('matches.show', $match) }}" 
                                                           class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                            View Match
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-lg">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No Match History</h3>
                                    <p class="text-gray-600 mb-4">This player hasn't been assigned to any matches yet.</p>
                                    <a href="{{ route('matches.create', ['team_id' => $player->team_id]) }}" 
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded">
                                        Schedule a Match
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
