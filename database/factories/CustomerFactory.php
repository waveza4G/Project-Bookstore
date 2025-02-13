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
            'customer_name' => $this->faker->name(),
            'customer_code' => $this->faker->unique()->randomNumber(5),
            'age' => $this->faker->numberBetween(18, 70),
            'address' => $this->faker->address(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // สุ่มรหัสผ่านและเข้ารหัส
            'status' => $this->faker->randomElement(['borrowed', 'returned']),
            'penalty' => $this->faker->randomFloat(2, 0, 100), // ถูกต้อง
        ];
    }
}
