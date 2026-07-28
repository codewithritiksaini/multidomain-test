<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            if ($role === 'admin' && app()->bound('currentTenant')) {
                $tenant = app('currentTenant');
                return redirect()->guest(route('tenant.admin.login', ['subdomain' => $tenant->subdomain]));
            }
            return redirect()->guest(route('login'));
        }

        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized action for role: ' . $role);
        }

        return $next($request);
    }
}
