<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public static function getParentDomain(): string
    {
        if (env('APP_PARENT_DOMAIN')) {
            return strtolower(env('APP_PARENT_DOMAIN'));
        }
        $central = strtolower(env('APP_CENTRAL_DOMAIN', 'localhost'));
        $parts = explode('.', $central);
        if (count($parts) >= 3) {
            array_shift($parts);
            return implode('.', $parts);
        }
        return $central;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost()); // e.g. "test1.ritiksaini.in" or "multidomain.ritiksaini.in"
        $hostParts = explode('.', $host);

        $parentDomain = self::getParentDomain();
        $parentParts = explode('.', $parentDomain);

        // If host has more subdomain parts than the parent domain (e.g. test1.ritiksaini.in vs ritiksaini.in)
        if (count($hostParts) > count($parentParts)) {
            $subdomain = $hostParts[0];

            // Reserved subdomains that belong to Central SuperAdmin System
            $reserved = array_filter(array_map('trim', explode(',', env('RESERVED_SUBDOMAINS', 'multidomain,admin,www,app'))));

            if (!in_array($subdomain, $reserved)) {
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
