<?php

namespace App\Services;

use App\Mail\MatchReport;
use App\Models\GameMatch;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class MatchService
{
    /**
     * Update the lineup for a specific match.
     * Handles security checks, data processing, syncing, and notification.
     *
     * @param GameMatch $match
     * @param array $data Input data containing players selection and stats
     * @param User $user The user performing the action (for email notification)
     * @return void
     * @throws ValidationException
     */
    public function updateLineup(GameMatch $match, array $data, User $user): void
    {
        $playersData = $data['players'] ?? [];

        // Filter only players where checkbox was selected
        $selectedPlayers = collect($playersData)
            ->filter(function ($playerData) {
                return isset($playerData['selected']) && $playerData['selected'];
            });

        // Security Check: Verify all selected players belong to the match's team
        if ($selectedPlayers->isNotEmpty()) {
            $playerIds = $selectedPlayers->keys()->toArray();
            $validPlayers = Player::whereIn('id', $playerIds)
                ->where('team_id', $match->team_id)
                ->pluck('id')
                ->toArray();

            // If any player doesn't belong to the team, throw validation exception
            if (count($validPlayers) !== count($playerIds)) {
                throw ValidationException::withMessages([
                    'players' => 'One or more selected players do not belong to this team.'
                ]);
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

        // Send Match Report Email
        Mail::to($user)->send(new MatchReport($match));
    }
}
