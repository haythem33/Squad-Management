<?php

use Livewire\Component;
use App\Models\Player;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $position = '';

    // Reset pagination when searching
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function with()
    {
        return [
            'players' => Player::query()
                ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                ->when($this->position, fn($q) => $q->where('position', $this->position))
                ->with('team') // Optimisation
                ->latest()
                ->paginate(5)
        ];
    }
};
?>

<div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 h-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-900">Player Search</h3>
        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Livewire</span>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search..." 
                class="pl-10 w-full border-slate-200 rounded-lg text-sm focus:ring-slate-900 focus:border-slate-900"
            >
        </div>
        <select wire:model.live="position" class="border-slate-200 rounded-lg text-sm focus:ring-slate-900 focus:border-slate-900">
            <option value="">All</option>
            <option value="Forward">Forward</option>
            <option value="Midfielder">Midfielder</option>
            <option value="Defender">Defender</option>
            <option value="Goalkeeper">Goalkeeper</option>
        </select>
    </div>

    <div class="space-y-3">
        @forelse($players as $player)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <div class="flex items-center gap-3">
                    @if($player->photo)
                        <img src="{{ asset('storage/' . $player->photo) }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                            {{ substr($player->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $player->name }}</p>
                        <p class="text-xs text-slate-500">{{ $player->team->name ?? 'No Team' }}</p>
                    </div>
                </div>
                <span class="text-xs font-medium px-2 py-1 bg-white border border-slate-200 rounded text-slate-600">
                    {{ $player->position }}
                </span>
            </div>
        @empty
            <div class="text-center py-6 text-slate-400 text-sm">
                No players found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $players->links(data: ['scrollTo' => false]) }}
    </div>
</div>