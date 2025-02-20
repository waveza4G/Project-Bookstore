<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Book;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\Category;
use App\Models\Group;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Payment::factory(70)->create();

    }
}

