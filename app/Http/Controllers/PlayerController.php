<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PlayerController extends Controller
{
    /**
     * Display a listing of players.
     * Shows all players from teams owned by the authenticated user.
     */
    public function index(): View
    {
        // Get all players from the user's teams
        // Use eager loading to prevent N+1 queries
        $players = Player::whereHas('team', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->with('team') // Eager load the team relationship
            ->latest()
            ->get();

        return view('players.index', compact('players'));
    }

    /**
     * Show the form for creating a new player.
     */
    public function create(): View
    {
        $this->authorize('create', Player::class);

        // Get only the teams owned by the authenticated user
        $teams = auth()->user()->teams;

        return view('players.create', compact('teams'));
    }

    /**
     * Store a newly created player in storage.
     */
    public function store(StorePlayerRequest $request): RedirectResponse
    {
        $this->authorize('create', Player::class);

        $validated = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('player-photos', 'public');
        }

        $player = Player::create($validated);

        return redirect()->route('players.show', $player)
            ->with('success', 'Player created successfully.');
    }

    /**
     * Display the specified player.
     */
    public function show(Player $player): View
    {
        $this->authorize('view', $player);

        // Eager load relationships to prevent N+1 queries
        $player->load(['team', 'matches' => function ($query) {
            $query->latest('match_date')->withPivot('goals', 'minutes_played');
        }]);

        return view('players.show', compact('player'));
    }

    /**
     * Show the form for editing the specified player.
     */
    public function edit(Player $player): View
    {
        $this->authorize('update', $player);

        // Get only the teams owned by the authenticated user
        $teams = auth()->user()->teams;

        return view('players.edit', compact('player', 'teams'));
    }

    /**
     * Update the specified player in storage.
     */
    public function update(UpdatePlayerRequest $request, Player $player): RedirectResponse
    {
        $this->authorize('update', $player);

        $validated = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($player->photo) {
                Storage::disk('public')->delete($player->photo);
            }

            $validated['photo'] = $request->file('photo')->store('player-photos', 'public');
        }

        $player->update($validated);

        return redirect()->route('players.show', $player)
            ->with('success', 'Player updated successfully.');
    }

    /**
     * Remove the specified player from storage.
     */
    public function destroy(Player $player): RedirectResponse
    {
        $this->authorize('delete', $player);

        // Delete photo if it exists
        if ($player->photo) {
            Storage::disk('public')->delete($player->photo);
        }

        $player->delete();

        return redirect()->route('players.index')
            ->with('success', 'Player deleted successfully.');
    }
}
