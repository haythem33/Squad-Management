<?php

use App\Models\Team;
use App\Models\User;

test('redirects unauthenticated users to login page', function () {
    $response = $this->get(route('teams.index'));
    $response->assertRedirect(route('login'));
});

test('displays teams belonging to the authenticated user', function () {
    $user = User::factory()->create();
    $teams = Team::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('teams.index'));

    $response->assertStatus(200);
    foreach ($teams as $team) {
        $response->assertSee($team->name);
    }
});

test('does not display teams belonging to other users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->get(route('teams.index'));

    $response->assertStatus(200);
    $response->assertDontSee($otherTeam->name);
});

test('allows authenticated user to create a team', function () {
    $user = User::factory()->create();

    $teamData = [
        'name' => 'Test FC',
        'category' => 'U-18',
        'description' => 'A test team description',
    ];

    $response = $this->actingAs($user)->post(route('teams.store'), $teamData);

    // Get the created team
    $team = Team::where('name', 'Test FC')->first();

    $response->assertRedirect(route('teams.show', $team));
    $this->assertDatabaseHas('teams', [
        'name' => 'Test FC',
        'user_id' => $user->id,
    ]);
});

test('validates team creation input', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('teams.store'), [
        'name' => '', // Required
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('allows authenticated user to update their team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    $updatedData = [
        'name' => 'Updated FC',
        'category' => 'Senior',
        'description' => 'Updated description',
    ];

    $response = $this->actingAs($user)->put(route('teams.update', $team), $updatedData);

    $response->assertRedirect(route('teams.show', $team));
    $this->assertDatabaseHas('teams', [
        'id' => $team->id,
        'name' => 'Updated FC',
    ]);
});

test('prevents user from updating others team', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);

    $updatedData = [
        'name' => 'Hacked FC',
        'category' => 'Senior',
    ];

    $response = $this->actingAs($user)->put(route('teams.update', $otherTeam), $updatedData);

    // Assuming policy returns 403 Forbidden
    $response->assertStatus(403);
});

test('allows authenticated user to delete their team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('teams.destroy', $team));

    $response->assertRedirect(route('teams.index'));
    $this->assertDatabaseMissing('teams', ['id' => $team->id]);
});

test('prevents user from deleting others team', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)->delete(route('teams.destroy', $otherTeam));

    $response->assertStatus(403);
    $this->assertDatabaseHas('teams', ['id' => $otherTeam->id]);
});
