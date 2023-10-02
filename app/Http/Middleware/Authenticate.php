<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // dd(str_starts_with($request->route()->getName(), 'admin.'));
        if (str_starts_with($request->route()->getName(), 'admin.')) {
            return $request->expectsJson() ? null : route('admin.auth.login');
        }
        return $request->expectsJson() ? null : route('public.account.login');
    }
}
