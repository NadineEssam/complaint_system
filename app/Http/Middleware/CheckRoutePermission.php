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
               "reports.view-report-central-report" ,
                "reports.view-report-complaint-percentage-report",
                "reports.view-report-complaint-saved-reasons-report" ,
                "reports.view-report-compare-request-type-between-years" ,
                "reports.view-report-complaints-inquiries-summary-by-source" ,
                "reports.view-report-offices-complaints-and-inquiries-summary-report",
                "reports.view-report-offices-saved-complaints-count-report" ,
                "reports.view-report-annual-sources-comparison" ,
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