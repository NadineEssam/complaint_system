<?php

namespace App\Providers;

use App\Reports\CompareByRequestTypeBetweenYearsReport;
use App\Reports\ComplaintsAndInquiriesSummaryBySource;
use App\Reports\ReportRegistry;
use App\Reports\SalesReport;

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
                new ComplaintsAndInquiriesSummaryBySource()
            );

            return $registry;
        });
    }
}