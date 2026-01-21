<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">🌍 Pro Football Wire</h3>
        <span class="text-xs font-medium bg-indigo-100 text-indigo-800 px-2 py-1 rounded">External API</span>
    </div>
    
    @if(empty($matches))
        <p class="text-gray-500 italic">No matches currently available from the scout feed.</p>
    @else
        <div class="space-y-3">
            @foreach($matches as $match)
                <div class="flex items-center justify-between border-b pb-2 last:border-0 last:pb-0">
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
    @endif
    <div class="mt-4 text-xs text-gray-400 text-center border-t pt-2">
        Data provided by Football-Data.org Service
    </div>
</div>