<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\Customer;
use App\Models\Rental;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),  // ใช้ factory ของ Customer แทน
            'book_id' => Book::factory(),  // ใช้ factory ของ Book แทน
            'rental_date' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'return_date' => $this->faker->optional()->date(), // return_date สามารถเป็น null ได้
            'status' => $this->faker->randomElement(['borrowed', '-']),
            'amount' => $this->faker->randomFloat(2, 100, 500),  // สุ่มจำนวนเงินที่ต้องชำระ (ช่วง 100 - 500 บาท)

        ];
    }
}
