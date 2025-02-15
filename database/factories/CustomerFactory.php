<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'username' => $this->faker->unique()->name(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // สุ่มรหัสผ่านและเข้ารหัส
            'book_count' => $this->faker->numberBetween(0, 10),
            'status' => $this->faker->randomElement(['borrowed', '-']),
            'penalty' => $this->faker->randomFloat(2, 0, 100), // ถูกต้อง
        ];
    }
}
