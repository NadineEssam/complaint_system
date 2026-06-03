<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoutePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        

        if (!$user) {
            abort(401);
        }

        // current route name
        $routeName = $request->route()->getName();

        // routes allowed without permission
        $exceptRoutes = [
            'dashboard',
            'home',
            'logout',
              
                "reports.filters"   ,
                "reports.generate"   ,
        ];

        if (in_array($routeName, $exceptRoutes)) {
            return $next($request);
        }

        // check permission using Spatie
        if (!$user->can($routeName)) {

            abort(403, 'ليس لديك صلاحية للوصول');
        }

        return $next($request);
    }
}