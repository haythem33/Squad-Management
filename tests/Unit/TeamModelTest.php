<?php

use App\Models\GameMatch;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;

test('team belongs to a user', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    expect($team->user)->toBeInstanceOf(User::class);
    expect($team->user->id)->toBe($user->id);
});

test('team has many players', function () {
    $team = Team::factory()->create();
    $player = Player::factory()->create(['team_id' => $team->id]);

    expect($team->players)->toHaveCount(1);
    expect($team->players->first())->toBeInstanceOf(Player::class);
});

test('team has many matches', function () {
    $team = Team::factory()->create();
    $match = GameMatch::factory()->create(['team_id' => $team->id]);

    expect($team->matches)->toHaveCount(1);
    expect($team->matches->first())->toBeInstanceOf(GameMatch::class);
});
