<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        return [
            ...parent::share($request),
            'auth' => [
                'customer' => Auth::guard('customer')->user(), // ✅ ข้อมูลของ customer
                'admin' => Auth::guard('admin')->user(), // ✅ ข้อมูลของ admin
            ],

            'categories' => DB::table('categories')->select('id', 'category_name')->get(),
            'groups' => DB::table('groups')->select('id', 'group_name')->get(),
            'rentals' => DB::table('rentals')
            ->select('id', 'customer_id', 'book_id', 'rental_date', 'due_date', 'return_date', 'status','amount')->get(),
            'books' => DB::table('books')
            ->join('categories', 'books.category_id', '=', 'categories.id')  // join กับ categories
            ->join('groups', 'books.group_id', '=', 'groups.id')  // join กับ groups
            ->select(
                'books.id',
                'books.book_name',
                'books.image',
                'books.price',
                'books.author',
                'books.description',
                'books.publisher',
                'books.remaining_quantity',
                'categories.category_name',
                'groups.group_name'
            )
            ->take(10)
            ->get(),
    ];

    }

}
