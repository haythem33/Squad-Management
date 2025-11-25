<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\User;

class PlayerPolicy
{
    /**
     * Determine whether the user can view any players.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the player.
     * User must own the team that the player belongs to.
     */
    public function view(User $user, Player $player): bool
    {
        return $user->id === $player->team->user_id;
    }

    /**
     * Determine whether the user can create players.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the player.
     * User must own the team that the player belongs to.
     */
    public function update(User $user, Player $player): bool
    {
        return $user->id === $player->team->user_id;
    }

    /**
     * Determine whether the user can delete the player.
     * User must own the team that the player belongs to.
     */
    public function delete(User $user, Player $player): bool
    {
        return $user->id === $player->team->user_id;
    }
}
