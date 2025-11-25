<?php

namespace Database\Seeders;

use App\Models\GameMatch;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the specific user (Coach) for login
        $coach = User::factory()->create([
            'name' => 'Haythem',
            'email' => 'haythembenjbara@gmail.com',
            'password' => Hash::make('Souleter15963&'),
        ]);

        // Create 3 teams for this coach
        $teamNames = [
            ['name' => 'First Team', 'category' => 'Senior'],
            ['name' => 'U-21 Squad', 'category' => 'U-21'],
            ['name' => 'U-18 Academy', 'category' => 'U-18'],
        ];

        foreach ($teamNames as $teamData) {
            // Create a team
            $team = Team::factory()->create([
                'user_id' => $coach->id,
                'name' => $teamData['name'],
                'category' => $teamData['category'],
            ]);

            // Create 15 players for this team
            $players = Player::factory()->count(15)->create([
                'team_id' => $team->id,
            ]);

            // Create 5 matches for this team
            $matches = GameMatch::factory()->count(5)->create([
                'team_id' => $team->id,
            ]);

            // For each match, attach 11 random players with pivot data
            foreach ($matches as $match) {
                // Get 11 random players from this team
                $selectedPlayers = $players->random(11);

                // Attach each player with random pivot data
                foreach ($selectedPlayers as $player) {
                    $match->players()->attach($player->id, [
                        'goals' => fake()->numberBetween(0, 3),
                        'minutes_played' => fake()->numberBetween(0, 90),
                    ]);
                }
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: haythembenjbara@gmail.com');
        $this->command->info('Password: Souleter15963&');
    }
}
