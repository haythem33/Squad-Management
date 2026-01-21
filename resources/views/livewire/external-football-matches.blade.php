<?php

use Livewire\Component;
use App\Services\FootballDataService;

new class extends Component
{
    public $matches = [];
    public $filter = '';
    public $loading = false;

    public function mount(FootballDataService $service)
    {
        $this->matches = $service->getUpcomingMatches();
    }
    
    public function refresh()
    {
        $service = app(FootballDataService::class);
        $this->matches = $service->getUpcomingMatches();
    }

    public function with()
    {
        $displayMatches = collect($this->matches);

        if ($this->filter) {
            $displayMatches = $displayMatches->filter(function ($match) {
                $home = $match['homeTeam']['name'] ?? '';
                $away = $match['awayTeam']['name'] ?? '';
                $comp = $match['competition']['name'] ?? '';
                $term = strtolower($this->filter);
                
                return str_contains(strtolower($home), $term) || 
                       str_contains(strtolower($away), $term) ||
                       str_contains(strtolower($comp), $term);
            });
        }

        return [
            'displayMatches' => $displayMatches
        ];
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

    <div class="mb-3">
        <input 
            wire:model.live.debounce.300ms="filter" 
            type="text" 
            placeholder="Filter teams..." 
            class="w-full text-xs border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
    </div>

    <div class="flex-1 overflow-y-auto pr-1 space-y-3 custom-scrollbar" style="max-height: 250px;">
        @forelse($displayMatches as $match)
            <div class="p-3 border border-slate-100 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase">{{ $match['competition']['name'] ?? 'Friendly' }}</span>
                    <span class="text-xs text-slate-500 bg-white px-2 py-1 rounded border border-slate-100">
                        {{ \Carbon\Carbon::parse($match['utcDate'] ?? now())->format('d M H:i') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex-1 text-right font-semibold text-sm text-slate-800 truncate">
                        {{ $match['homeTeam']['name'] ?? 'Team A' }}
                    </div>
                    <div class="px-2 font-bold text-slate-300 text-xs">VS</div>
                    <div class="flex-1 text-left font-semibold text-sm text-slate-800 truncate">
                        {{ $match['awayTeam']['name'] ?? 'Team B' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-center py-6">
                <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-xs text-slate-500">No matches found.</p>
            </div>
        @endforelse
    </div>
</div>