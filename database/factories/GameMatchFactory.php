<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameMatch>
 */
class GameMatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameMatch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['scheduled', 'completed', 'cancelled', 'postponed'];
        $venues = ['home', 'away'];
        
        return [
            'team_id' => Team::factory(),
            'opponent' => fake()->words(2, true) . ' FC',
            'match_date' => fake()->dateTimeBetween('-1 month', '+2 months'),
            'location' => fake()->city(),
            'venue' => fake()->randomElement($venues),
            'team_score' => fake()->optional(0.7)->numberBetween(0, 5),
            'opponent_score' => fake()->optional(0.7)->numberBetween(0, 5),
            'status' => fake()->randomElement($statuses),
        ];
    }

    /**
     * Indicate that the match is scheduled (future match).
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'match_date' => fake()->dateTimeBetween('now', '+2 months'),
            'team_score' => null,
            'opponent_score' => null,
        ]);
    }

    /**
     * Indicate that the match is completed (past match with scores).
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'match_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'team_score' => fake()->numberBetween(0, 5),
            'opponent_score' => fake()->numberBetween(0, 5),
        ]);
    }
}
