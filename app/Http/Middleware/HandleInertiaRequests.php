<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    #[\Override]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        $user = $request->user();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $user,
                'userProperties' => $user ? [
                    'isManagerOrAbove' => $user->isManagerOrAbove(),
                    'isAdministrator' => $user->isAdministrator(),
                ] : [],
            ],
            /*'flash' => [
                'success' => fn(): mixed => $request->session()->get('success'),
                'error' => fn(): mixed => $request->session()->get('error'),
                'info' => fn(): mixed => $request->session()->get('info'),
                'warning' => fn(): mixed => $request->session()->get('warning'),
            ],*/ // No longer needed with Inertia 2.3.3+, now can use Inertia::flash() directly. Also adjust use-flash-toast.tsx accordingly.
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
