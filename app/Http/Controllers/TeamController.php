<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeamController extends Controller
{
    /**
     * Display a listing of the user's teams.
     */
    public function index(): View
    {
        // Cache the dashboard query for 60 seconds
        $teams = Cache::remember('user_teams_' . auth()->id(), 60, function () {
            return auth()->user()->teams()
                ->withCount(['players', 'matches'])
                ->get();
        });

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new team.
     */
    public function create(): View
    {
        $this->authorize('create', Team::class);

        return view('teams.create');
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $this->authorize('create', Team::class);

        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('team-logos', 'public');
        }

        $team = Team::create($validated);

        // Invalidate the cache
        Cache::forget('user_teams_' . auth()->id());

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified team.
     */
    public function show(Team $team): View
    {
        $this->authorize('view', $team);

        // Eager load relationships to prevent N+1 queries
        $team->load(['players' => function ($query) {
            if (request('player_search')) {
                $query->where('name', 'like', '%' . request('player_search') . '%');
            }
        }, 'matches' => function ($query) {
            $query->latest('match_date');
        }]);

        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified team.
     */
    public function edit(Team $team): View
    {
        $this->authorize('update', $team);

        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified team in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $this->authorize('update', $team);

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($team->logo) {
                Storage::disk('public')->delete($team->logo);
            }

            $validated['logo'] = $request->file('logo')->store('team-logos', 'public');
        }

        $team->update($validated);

        // Invalidate the cache
        Cache::forget('user_teams_' . auth()->id());

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $this->authorize('delete', $team);

        // Delete logo if it exists
        if ($team->logo) {
            Storage::disk('public')->delete($team->logo);
        }

        $team->delete();

        // Invalidate the cache
        Cache::forget('user_teams_' . auth()->id());

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }
}
