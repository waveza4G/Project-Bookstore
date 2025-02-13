<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Typebook;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Typebook>
 */
class TypebookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'typebook_name' => $this->faker->word(),
        ];
    }
}
