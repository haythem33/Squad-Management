<?php

use App\Mail\MatchReport;
use App\Models\GameMatch;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('redirects unauthenticated users to login page', function () {
    $response = $this->get(route('matches.index'));
    $response->assertRedirect(route('login'));
});

test('displays matches belonging to the authenticated user', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $matches = GameMatch::factory()->count(3)->create(['team_id' => $team->id]);

    $response = $this->actingAs($user)->get(route('matches.index'));

    $response->assertStatus(200);
    foreach ($matches as $match) {
        $response->assertSee($match->opponent); // Assuming opponent name helps identify the match
    }
});

test('allows authenticated user to create a match', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    $matchData = [
        'team_id' => $team->id,
        'opponent' => 'Rival FC',
        'match_date' => now()->addDays(7)->toDateTimeString(),
        'location' => 'Home',
        'venue' => 'Stadium X',
        'status' => 'scheduled',
    ];

    $response = $this->actingAs($user)->post(route('matches.store'), $matchData);

    $match = GameMatch::where('opponent', 'Rival FC')->first();

    $response->assertRedirect(route('matches.show', $match));
    $this->assertDatabaseHas('matches', [
        'opponent' => 'Rival FC',
        'team_id' => $team->id,
    ]);
});

test('allows authenticated user to manage lineup and stats', function () {
    Mail::fake();
    
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $match = GameMatch::factory()->create(['team_id' => $team->id]);
    $player1 = Player::factory()->create(['team_id' => $team->id]);
    $player2 = Player::factory()->create(['team_id' => $team->id]);

    // Data structure as expected by UpdateLineupRequest and Controller
    $lineupData = [
        'players' => [
            $player1->id => [
                'selected' => true,
                'goals' => 1,
                'minutes' => 90,
            ],
            $player2->id => [
                'selected' => false, // Not selected
                'goals' => 0,
                'minutes' => 0,
            ],
        ],
    ];

    $response = $this->actingAs($user)->put(route('matches.lineup.update', $match), $lineupData);

    $response->assertRedirect(route('matches.show', $match));
    
    // Check Pivot Data
    $this->assertDatabaseHas('match_player', [
        'match_id' => $match->id,
        'player_id' => $player1->id,
        'goals' => 1,
        'minutes_played' => 90,
    ]);
    
    $this->assertDatabaseMissing('match_player', [
        'match_id' => $match->id,
        'player_id' => $player2->id,
    ]);

    // Check that email was sent
    Mail::assertSent(MatchReport::class, function ($mail) use ($match) {
        return $mail->match->id === $match->id;
    });
});

test('prevents adding player from another team to lineup', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $match = GameMatch::factory()->create(['team_id' => $team->id]);
    
    $otherTeam = Team::factory()->create(); // Different team
    $otherPlayer = Player::factory()->create(['team_id' => $otherTeam->id]);

    $lineupData = [
        'players' => [
            $otherPlayer->id => [
                'selected' => true,
                'goals' => 0,
                'minutes' => 90,
            ],
        ],
    ];

    $response = $this->actingAs($user)->put(route('matches.lineup.update', $match), $lineupData);

    // Controller validation for team ownership
    $response->assertSessionHasErrors(['players']);
    
    $this->assertDatabaseMissing('match_player', [
        'match_id' => $match->id,
        'player_id' => $otherPlayer->id,
    ]);
});

test('prevents user from updating others match', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);
    $otherMatch = GameMatch::factory()->create(['team_id' => $otherTeam->id]);

    $updatedData = [
        'opponent' => 'Modified FC',
    ];

    $response = $this->actingAs($user)->put(route('matches.update', $otherMatch), $updatedData);

    $response->assertStatus(403);
    $this->assertDatabaseHas('matches', ['id' => $otherMatch->id, 'opponent' => $otherMatch->opponent]);
});

test('prevents user from managing lineup for others match', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherTeam = Team::factory()->create(['user_id' => $otherUser->id]);
    $otherMatch = GameMatch::factory()->create(['team_id' => $otherTeam->id]);

    $response = $this->actingAs($user)->get(route('matches.lineup.edit', $otherMatch));
    
    $response->assertStatus(403);
});
