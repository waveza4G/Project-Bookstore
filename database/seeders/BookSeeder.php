<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Book;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\Typebook;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::factory(40)->create();
        Customer::factory(30)->create();
        Payment::factory(20)->create();
        Rental::factory(30)->create();
        Typebook::factory(10)->create();
    }
}
