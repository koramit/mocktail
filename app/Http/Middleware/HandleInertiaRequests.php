<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'app' => [
                'baseUrl' => url(''),
            ],
            'flash' => [
                'title' => fn () => $request->session()->get('page-title', 'MISSING'),
                'messages' => fn () => $request->session()->get('messages'),
                'mainMenuLinks' => fn () => $request->session()->get('main-menu-links', []),
                'actionMenu' => fn () => $request->session()->get('action-menu', []),
            ],
            'user' => fn () => $request->user()
                ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    // 'avatar' => $request->user()->profile['social']['avatar'],
                    'abilities' => $request->user()->abilities->toArray(),
                    'center' => $request->user()->center_name,
                ] : null,
        ]);
    }
}
