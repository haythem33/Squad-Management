<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Player::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $positions = [
            'Goalkeeper',
            'Right Back',
            'Left Back',
            'Center Back',
            'Defensive Midfielder',
            'Central Midfielder',
            'Attacking Midfielder',
            'Right Winger',
            'Left Winger',
            'Striker',
            'Forward',
        ];
        
        return [
            'team_id' => Team::factory(),
            'name' => fake()->name(),
            'position' => fake()->randomElement($positions),
            'jersey_number' => fake()->unique()->numberBetween(1, 99),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'photo' => fake()->optional()->imageUrl(300, 400, 'people', true, 'player'),
        ];
    }
}
