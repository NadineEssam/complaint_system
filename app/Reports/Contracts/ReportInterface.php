<?php

namespace App\Reports\Contracts;

interface ReportInterface
{
    public function permission(): string;
    public function label(): string;
    public function key(): string;
    public function filters(): array;
    public function generate(array $filters): mixed;

    /** Column headers for Excel/PDF export */
    public function headings(): array;

    /** Maps a result row to an ordered array matching headings() */
    public function map(mixed $row): array;
}