<?php

use App\Models\Player;
use App\Models\Team;
use App\Models\User;

test('redirects unauthenticated users to login page', function () {
    $response = $this->get(route('players.index'));
    $response->assertRedirect(route('login'));
});

test('displays players belonging to the authenticated user', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $players = Player::factory()->count(3)->create(['team_id' => $team->id]);

    $response = $this->actingAs($user)->get(route('players.index'));

    $response->assertStatus(200);
    foreach ($players as $player) {
        $response->assertSee($player->name);
    }
});

test('does not display players belonging to other users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);
    $otherPlayer = Player::factory()->create(['team_id' => $otherTeam->id]);

    $response = $this->actingAs($user)->get(route('players.index'));

    $response->assertStatus(200);
    $response->assertDontSee($otherPlayer->name);
});

test('allows authenticated user to create a player', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    $playerData = [
        'team_id' => $team->id,
        'name' => 'John Doe',
        'position' => 'Forward',
        'jersey_number' => 10,
        'date_of_birth' => '2000-01-01',
    ];

    $response = $this->actingAs($user)->post(route('players.store'), $playerData);

    $player = Player::where('name', 'John Doe')->first();

    $response->assertRedirect(route('players.show', $player));
    $this->assertDatabaseHas('players', [
        'name' => 'John Doe',
        'team_id' => $team->id,
    ]);
});

test('prevents user from creating player for another users team', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);

    $playerData = [
        'team_id' => $otherTeam->id,
        'name' => 'Intruder',
    ];

    $response = $this->actingAs($user)->post(route('players.store'), $playerData);

    $response->assertStatus(403);
    $this->assertDatabaseMissing('players', ['name' => 'Intruder']);
});

test('validates player creation input', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post(route('players.store'), [
        'team_id' => $team->id,
        'name' => '', // Required
        'jersey_number' => 100, // Max 99
    ]);

    $response->assertSessionHasErrors(['name', 'jersey_number']);
});

test('allows authenticated user to update their player', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $player = Player::factory()->create(['team_id' => $team->id]);

    $updatedData = [
        'team_id' => $team->id,
        'name' => 'Jane Doe',
        'position' => 'Midfielder',
    ];

    $response = $this->actingAs($user)->put(route('players.update', $player), $updatedData);

    // Assuming UpdatePlayerController redirects to show just like Store
    $response->assertRedirect(route('players.show', $player));
    $this->assertDatabaseHas('players', [
        'id' => $player->id,
        'name' => 'Jane Doe',
    ]);
});

test('prevents user from updating others player', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);
    $otherPlayer = Player::factory()->create(['team_id' => $otherTeam->id]);

    $updatedData = [
        'name' => 'Hacked Player',
    ];

    $response = $this->actingAs($user)->put(route('players.update', $otherPlayer), $updatedData);

    $response->assertStatus(403);
    $this->assertDatabaseHas('players', ['id' => $otherPlayer->id, 'name' => $otherPlayer->name]);
});

test('allows authenticated user to delete their player', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $player = Player::factory()->create(['team_id' => $team->id]);

    $response = $this->actingAs($user)->delete(route('players.destroy', $player));

    $response->assertRedirect(route('players.index'));
    $this->assertDatabaseMissing('players', ['id' => $player->id]);
});

test('prevents user from deleting others player', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);
    $otherPlayer = Player::factory()->create(['team_id' => $otherTeam->id]);

    $response = $this->actingAs($user)->delete(route('players.destroy', $otherPlayer));

    $response->assertStatus(403);
    $this->assertDatabaseHas('players', ['id' => $otherPlayer->id]);
});
