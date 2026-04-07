<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Reports\ReportRegistry;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(protected ReportRegistry $registry) {}

    public function index()
    {
        return view('dashboard.reports.index', [
            'reports' => $this->registry->available(),
        ]);
    }

    public function filters(string $key)
    {
        return response()->json($this->registry->find($key)->filters());
    }

    public function generate(Request $request, string $key)
    {
        $report    = $this->registry->find($key);
        $validated = $this->validateFilters($request, $report);

        // Plain HTML view
        if ($request->input('format') === 'view') {
            $results = collect($report->generate($validated));
            return view('dashboard.reports.results', compact('report', 'results', 'validated'));
        }

        return $this->export($report, $validated, $request->input('format', 'xlsx'));
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function validateFilters(Request $request, $report): array
    {
        $rules = collect($report->filters())
            ->mapWithKeys(fn ($f) => [
                $f['name'] => $f['required'] ? 'required' : 'nullable',
            ])
            ->toArray();

        return $request->validate($rules);
    }

    private function export($report, array $filters, string $format)
    {
        $filename = str($report->label())->slug() . '-' . now()->format('Y-m-d');

        return match ($format) {

            'xlsx' => Excel::download(
                new ReportExport($report, $filters),
                "{$filename}.xlsx"
            ),

            'csv' => Excel::download(
                new ReportExport($report, $filters),
                "{$filename}.csv",
                \Maatwebsite\Excel\Excel::CSV,
                ['Content-Type' => 'text/csv']
            ),

            // 'pdf' => Pdf::loadView('dashboard.exports.report-pdf', [
            //         'report'  => $report,
            //         'results' => collect($report->generate($filters)),
            //         'filters' => $filters,
            //     ])
            //     ->setPaper('a4', 'landscape')
            //     ->download("{$filename}.pdf"),
            

            default => abort(422, 'Unsupported format'),
        };
    }


    private function exportPdf($report, array $filters, string $filename)
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode'           => 'utf-8',
            'format'         => 'A4-L',
            'directionality' => 'rtl',
            'default_font'   => 'dejavusans',
        ]);

        $html = view('dashboard.exports.report-pdf', [
            'report'  => $report,
            'results' => collect($report->generate($filters)),
            'filters' => $filters,
        ])->render();

        $mpdf->WriteHTML($html);

        return response()->streamDownload(
            fn () => print($mpdf->Output('', 'S')),
            "{$filename}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

}