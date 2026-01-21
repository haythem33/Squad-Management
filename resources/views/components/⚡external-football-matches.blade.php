<?php

use Livewire\Component;
use App\Services\FootballDataService;

new class extends Component
{
    public $matches = [];

    public function mount(FootballDataService $service)
    {
        $this->matches = $service->getUpcomingMatches();
    }
    
    public function refresh()
    {
        $service = app(FootballDataService::class);
        $this->matches = $service->getUpcomingMatches();
    }
};
?>

<div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 h-full flex flex-col">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-900">Live Football</h3>
        <button wire:click="refresh" class="text-xs text-blue-600 font-medium hover:text-blue-800 flex items-center gap-1">
            <svg wire:loading.class="animate-spin" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh
        </button>
    </div>

    <div class="flex-1 overflow-y-auto pr-1 space-y-3 custom-scrollbar" style="max-height: 300px;">
        @forelse($matches as $match)
            <div class="p-3 border border-slate-100 rounded-lg bg-slate-50">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase">{{ $match['competition']['name'] ?? 'Friendly' }}</span>
                    <span class="text-xs text-slate-500 bg-white px-2 py-1 rounded border border-slate-100">
                        {{ \Carbon\Carbon::parse($match['utcDate'] ?? now())->format('d M H:i') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex-1 text-right font-semibold text-sm text-slate-800">
                        {{ $match['homeTeam']['name'] ?? 'Team A' }}
                    </div>
                    <div class="px-3 font-bold text-slate-300 text-xs">VS</div>
                    <div class="flex-1 text-left font-semibold text-sm text-slate-800">
                        {{ $match['awayTeam']['name'] ?? 'Team B' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-center py-8">
                <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <p class="text-sm text-slate-500">No live matches available.</p>
                <p class="text-xs text-slate-400 mt-1">Check back later.</p>
            </div>
        @endforelse
    </div>
</div>