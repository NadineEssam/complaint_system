<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Models\Order;
use App\Models\User;

class SalesReport implements ReportInterface
{
    public function permission(): string
    {
        return 'view-report-sales';
    }

    public function label(): string
    {
        return 'Sales Report';
    }

    public function key(): string
    {
        return 'sales';
    }

    public function filters(): array
    {
        return [
            [
                'name'        => 'date_from',
                'label'       => 'Date From',
                'type'        => 'date',
                'required'    => true,
            ],
            [
                'name'        => 'date_to',
                'label'       => 'Date To',
                'type'        => 'date',
                'required'    => true,
            ],
            [
                'name'        => 'region',
                'label'       => 'Region',
                'type'        => 'select',
                'required'    => false,
                'options'     => ['north' => 'North', 'south' => 'South', 'all' => 'All'],
                'default'     => 'all',
            ],
        ];
    }

    public function generate(array $filters): mixed
    {
        return User::query()
            // ->whereBetween('created_at', [$filters['date_from'], $filters['date_to']])
            // ->when($filters['region'] !== 'all', fn ($q) => $q->where('region', $filters['region']))
            ->get();
    }



    public function headings(): array
    {
        return ['Order ID', 'Customer', 'Region', 'Total', 'Date'];
    }

    public function map(mixed $row): array
    {
        return [
            $row->userEmail,
            $row->userEmail,
            $row->userEmail,
            $row->userEmail,
            $row->userEmail,
            
            
        ];
    }

}