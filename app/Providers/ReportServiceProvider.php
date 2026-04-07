<?php

namespace App\Providers;

use App\Reports\CompareByRequestTypeBetweenYearsReport;
use App\Reports\ReportRegistry;
use App\Reports\SalesReport;
use App\Reports\UserActivityReport;
use App\Reports\InventoryReport;
use Illuminate\Support\ServiceProvider;

class ReportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ReportRegistry::class, function () {
            $registry = new ReportRegistry();

            $registry->register(
                new SalesReport(),
                new CompareByRequestTypeBetweenYearsReport(),
            );

            return $registry;
        });
    }
}