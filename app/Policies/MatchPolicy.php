<?php

namespace App\Policies;

use App\Models\GameMatch;
use App\Models\User;

class MatchPolicy
{
    /**
     * Determine whether the user can view any matches.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the match.
     * User must own the team that the match belongs to.
     */
    public function view(User $user, GameMatch $match): bool
    {
        return $user->id === $match->team->user_id;
    }

    /**
     * Determine whether the user can create matches.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the match.
     * User must own the team that the match belongs to.
     */
    public function update(User $user, GameMatch $match): bool
    {
        return $user->id === $match->team->user_id;
    }

    /**
     * Determine whether the user can delete the match.
     * User must own the team that the match belongs to.
     */
    public function delete(User $user, GameMatch $match): bool
    {
        return $user->id === $match->team->user_id;
    }

    /**
     * Determine whether the user can manage the lineup for the match.
     * User must own the team that the match belongs to.
     */
    public function manageLineup(User $user, GameMatch $match): bool
    {
        return $user->id === $match->team->user_id;
    }
}
