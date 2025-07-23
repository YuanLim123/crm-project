<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Spatie\Permission\Models\Permission;

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
        $data =  [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'can' => $request->user()?->getPermissionsViaRoles()
                    ->map(function (Permission $permission): array {
                        return [
                            $permission['name'] => auth()->user()->can($permission['name']),
                        ];
                    })
                    ->collapse()
                    ->all(),
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
        ];

        return $data;
    }
}
