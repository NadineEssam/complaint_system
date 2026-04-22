<?php

namespace App\Providers;

use App\Reports\AnnualSourcesComparisonReport;
use App\Reports\CentralReport;
use App\Reports\CompareByRequestTypeBetweenYearsReport;
use App\Reports\ComplaintPercentageReport;
use App\Reports\ComplaintsAndInquiriesSummaryBySource;
use App\Reports\ComplaintSavedReasonsReport;
use App\Reports\OfficesComplaintsAndInquiriesSummaryReport;
use App\Reports\OfficesSavedComplaintsCountReport;
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
                new CompareByRequestTypeBetweenYearsReport(),
                new ComplaintsAndInquiriesSummaryBySource(),
                new AnnualSourcesComparisonReport(),
                new CentralReport(),
                new OfficesComplaintsAndInquiriesSummaryReport(),
                new OfficesSavedComplaintsCountReport(),
                new ComplaintSavedReasonsReport(),
                new ComplaintPercentageReport(),
            );

            return $registry;
        });
    }
}