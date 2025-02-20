<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\Customer;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'book_id' => Book::factory(),
            'rental_id' => Rental::factory(),
            'payment_amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['on_time', 'late']),
            'payment_date' => $this->faker->date(), // ใช้วันที่ที่เกี่ยวข้อง
        ];
    }
}
