<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Group;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group_name' => $this->faker->randomElement([
                'Action', 'Adventure', 'Comedy', 'Drama',
                'Fantasy', 'Horror', 'Kids', 'Mystery',
                'Romance', 'School', 'Sci-Fi', '-'
            ]),
        ];
    }
}
