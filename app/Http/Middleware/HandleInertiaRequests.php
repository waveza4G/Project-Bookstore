<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
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
        // ดึง token จาก session
        $token = $request->session()->get('token');

        return [
            ...parent::share($request),
            'auth' => [
                'customer' => Auth::guard('customer')->user(), // ✅ ข้อมูลของ customer
                'admin' => Auth::guard('admin')->user(), // ✅ ข้อมูลของ admin
            ],
            'flash' => [
                'success' => $request->session()->get('success') ?? null,
                'error' => $request->session()->get('error') ?? null,
            ],
        ];
    }

}
