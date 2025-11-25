<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['U-18', 'U-21', 'First Team', 'Reserve Team', 'Youth Academy'];
        
        return [
            'user_id' => User::factory(),
            'name' => fake()->unique()->words(2, true) . ' FC',
            'category' => fake()->randomElement($categories),
            'description' => fake()->optional()->paragraph(),
            'logo' => fake()->optional()->imageUrl(200, 200, 'sports', true, 'logo'),
        ];
    }
}
