<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">🌍 Pro Football Wire</h3>
        <span class="text-xs font-medium bg-indigo-100 text-indigo-800 px-2 py-1 rounded">External API</span>
    </div>
    
    @if(empty($matches))
        <p class="text-gray-500 italic">No matches currently available from the scout feed.</p>
    @else
        <div x-data="{ 
            page: 1, 
            perPage: 5, 
            total: {{ count($matches) }},
            get totalPages() { return Math.ceil(this.total / this.perPage) },
            get start() { return (this.page - 1) * this.perPage },
            get end() { return this.start + this.perPage }
        }" class="space-y-4">
            
            <div class="space-y-3 min-h-[300px]">
                @foreach($matches as $match)
                    <div x-show="$el.dataset.index >= start && $el.dataset.index < end" 
                         data-index="{{ $loop->index }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="flex items-center justify-between border-b pb-2 last:border-0 last:pb-0"
                         style="display: none;"> <!-- Default hidden to prevent flash -->
                        <div class="flex-1">
                            <div class="text-[10px] text-gray-500 uppercase font-tracking-wider mb-1">
                                {{ $match['competition']['name'] ?? 'Competition' }} &bull; 
                                {{ \Carbon\Carbon::parse($match['utcDate'])->format('D M d, H:i') }}
                            </div>
                            <div class="flex flex-col text-sm">
                                <div class="font-medium text-gray-800 truncate">{{ $match['homeTeam']['name'] }}</div>
                                <div class="font-medium text-gray-800 truncate">{{ $match['awayTeam']['name'] }}</div>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-400 pl-2">
                            VS
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Controls -->
            <div class="flex items-center justify-between pt-2 border-t border-gray-100" x-show="total > perPage">
                <button 
                    @click="page > 1 ? page-- : null" 
                    :class="{ 'opacity-50 cursor-not-allowed': page === 1, 'hover:bg-gray-100': page > 1 }"
                    class="text-xs font-semibold text-gray-600 px-3 py-1 rounded transition-colors"
                    :disabled="page === 1">
                    &larr; Prev
                </button>
                
                <span class="text-xs text-gray-400">
                    Page <span x-text="page"></span> of <span x-text="totalPages"></span>
                </span>

                <button 
                    @click="page < totalPages ? page++ : null" 
                    :class="{ 'opacity-50 cursor-not-allowed': page === totalPages, 'hover:bg-gray-100': page < totalPages }"
                    class="text-xs font-semibold text-gray-600 px-3 py-1 rounded transition-colors"
                    :disabled="page === totalPages">
                    Next &rarr;
                </button>
            </div>
        </div>
    @endif
    <div class="mt-4 text-xs text-gray-400 text-center border-t pt-2">
        Data provided by Football-Data.org Service
    </div>
</div>