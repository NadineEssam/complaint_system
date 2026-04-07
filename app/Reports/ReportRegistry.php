<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;

class ReportRegistry
{
    /** @var ReportInterface[] */
    protected array $reports = [];

    public function register(ReportInterface ...$reports): void
    {
        foreach ($reports as $report) {
            $this->reports[$report->key()] = $report;
        }
    }

    /** Returns only the reports the current user can access */
    public function available(): array
    {
        return array_filter(
            $this->reports,
            fn (ReportInterface $r) => PerUser($r->permission())  // auth()->user()->can($r->permission())
        );
    }

    public function find(string $key): ?ReportInterface
    {
        $report = $this->reports[$key] ?? null;

        // Guard: user must have permission even if they know the key  
        // auth()->user()->cannot($report->permission())
        if ($report && !PerUser($report->permission())   ) {
            abort(403);
        }

        return $report;
    }
}