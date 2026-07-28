<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost(); // e.g., "tech.localhost" or "tech.yourdomain.com"
        $hostParts = explode('.', strtolower($host));

        $centralDomain = env('APP_CENTRAL_DOMAIN', 'localhost');
        $centralParts = explode('.', strtolower($centralDomain));

        // If host has extra subdomain prefix (e.g. tech.localhost vs localhost, or tech.domain.com vs domain.com)
        if (count($hostParts) > count($centralParts)) {
            $subdomain = $hostParts[0];

            // Skip tenant check for 'admin' (SuperAdmin reserved domain) or 'www'
            if (!in_array($subdomain, ['admin', 'www'])) {
                $tenant = User::where('subdomain', $subdomain)
                              ->where('role', 'admin')
                              ->where('is_active', true)
                              ->first();

                if (!$tenant) {
                    abort(404, "Tenant subdomain [{$subdomain}] does not exist or is inactive.");
                }

                // Bind the current tenant into Laravel Service Container & Request attributes
                app()->instance('currentTenant', $tenant);
                $request->attributes->set('tenant', $tenant);
            }
        }

        return $next($request);
    }
}
