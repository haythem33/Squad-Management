<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatchRequest;
use App\Http\Requests\UpdateLineupRequest;
use App\Http\Requests\UpdateMatchRequest;
use App\Models\GameMatch;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MatchController extends Controller
{
    /**
     * Display a listing of matches.
     * Shows all matches from teams owned by the authenticated user.
     */
    public function index(): View
    {
        // Get all matches from the user's teams
        // Use eager loading to prevent N+1 queries
        $matches = GameMatch::whereHas('team', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->with('team') // Eager load the team relationship
            ->latest('match_date')
            ->get();

        return view('matches.index', compact('matches'));
    }

    /**
     * Show the form for creating a new match.
     */
    public function create(): View
    {
        $this->authorize('create', GameMatch::class);

        // Get only the teams owned by the authenticated user
        $teams = auth()->user()->teams;

        return view('matches.create', compact('teams'));
    }

    /**
     * Store a newly created match in storage.
     */
    public function store(StoreMatchRequest $request): RedirectResponse
    {
        $this->authorize('create', GameMatch::class);

        $validated = $request->validated();

        $match = GameMatch::create($validated);

        return redirect()->route('matches.show', $match)
            ->with('success', 'Match created successfully.');
    }

    /**
     * Display the specified match.
     * Eager loads players with pivot data (goals, minutes_played).
     */
    public function show(GameMatch $match): View
    {
        $this->authorize('view', $match);

        // Eager load relationships to prevent N+1 queries
        // Include pivot columns for the lineup and all team players
        $match->load([
            'team.players', // Load all players in the team for the lineup form
            'players' => function ($query) {
                $query->withPivot('goals', 'minutes_played')
                      ->orderBy('match_player.id');
            }
        ]);

        $team = $match->team;

        return view('matches.show', compact('match', 'team'));
    }

    /**
     * Show the form for editing the specified match.
     */
    public function edit(GameMatch $match): View
    {
        $this->authorize('update', $match);

        // Get only the teams owned by the authenticated user
        $teams = auth()->user()->teams;

        return view('matches.edit', compact('match', 'teams'));
    }

    /**
     * Update the specified match in storage.
     */
    public function update(UpdateMatchRequest $request, GameMatch $match): RedirectResponse
    {
        $this->authorize('update', $match);

        $validated = $request->validated();

        $match->update($validated);

        return redirect()->route('matches.show', $match)
            ->with('success', 'Match updated successfully.');
    }

    /**
     * Remove the specified match from storage.
     */
    public function destroy(GameMatch $match): RedirectResponse
    {
        $this->authorize('delete', $match);

        $match->delete();

        return redirect()->route('matches.index')
            ->with('success', 'Match deleted successfully.');
    }

    /**
     * Show the form for managing the match lineup.
     */
    public function editLineup(GameMatch $match): View
    {
        $this->authorize('manageLineup', $match);

        // Eager load team and current lineup with pivot data
        $match->load([
            'team.players', // All players available for this team
            'players' => function ($query) {
                $query->withPivot('goals', 'minutes_played');
            }
        ]);

        return view('matches.lineup', compact('match'));
    }

    /**
     * Update the lineup for the match.
     * This is the crucial method that syncs players to the match with pivot data.
     */
    public function updateLineup(UpdateLineupRequest $request, GameMatch $match): RedirectResponse
    {
        $this->authorize('manageLineup', $match);

        $playersData = $request->validated()['players'] ?? [];

        // Filter only players where checkbox was selected
        $selectedPlayers = collect($playersData)
            ->filter(function ($playerData, $playerId) {
                return isset($playerData['selected']) && $playerData['selected'];
            });

        // Security Check: Verify all selected players belong to the match's team
        if ($selectedPlayers->isNotEmpty()) {
            $playerIds = $selectedPlayers->keys()->toArray();
            $validPlayers = Player::whereIn('id', $playerIds)
                ->where('team_id', $match->team_id)
                ->pluck('id')
                ->toArray();

            // If any player doesn't belong to the team, abort
            if (count($validPlayers) !== count($playerIds)) {
                return back()->withErrors([
                    'players' => 'One or more selected players do not belong to this team.'
                ])->withInput();
            }
        }

        // Prepare sync data with pivot columns
        $syncData = [];
        foreach ($selectedPlayers as $playerId => $playerData) {
            $syncData[$playerId] = [
                'goals' => $playerData['goals'] ?? 0,
                'minutes_played' => $playerData['minutes'] ?? 0,
            ];
        }

        // Sync players with their pivot data
        // This will add new players, update existing ones, and remove players not in the array
        $match->players()->sync($syncData);

        return redirect()->route('matches.show', $match)
            ->with('success', 'Lineup updated successfully.');
    }
}
