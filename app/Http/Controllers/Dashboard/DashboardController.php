<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\RequestType;
use App\Models\Department;
use App\Models\ComSource;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        try {
            $from = $request->from;
            $to   = $request->to;

            $today = Carbon::today()->toDateString();

            // 1) منع المستقبل
            if (
                ($from && $from > $today) ||
                ($to && $to > $today)
            ) {
                return redirect()->route('home')
                    ->with('error', 'لا يمكن اختيار تاريخ أكبر من تاريخ اليوم');
            }

            // 2) منع range غلط
            if ($from && $to && $from > $to) {
                return redirect()->route('home')
                    ->with('error', 'تاريخ البداية لا يمكن أن يكون بعد تاريخ النهاية');
            }

            $complaintsQuery = Complaint::query()
                ->where('valid', 1) // <-- only active complaints in every stat below
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('ComplaintDate', [$from, $to]);
                })
                ->when($from && !$to, function ($q) use ($from) {
                    $q->whereDate('ComplaintDate', '>=', $from);
                })
                ->when(!$from && $to, function ($q) use ($to) {
                    $q->whereDate('ComplaintDate', '<=', $to);
                });


            $total = (clone $complaintsQuery)->count();

            $statuses = (clone $complaintsQuery)
                ->select('ComplaintStatus', DB::raw('COUNT(*) as total'))
                ->groupBy('ComplaintStatus')
                ->pluck('total', 'ComplaintStatus');

            $statusSolved = $statuses[2] ?? 0;
            $statusProcessing = $statuses[1] ?? 0;
            $statusNew = $statuses[3] ?? 0;
            $statusSaved = $statuses[4] ?? 0;


            $requestTypesStats = RequestType::withCount([
                'complaints' => function ($q) use ($from, $to) {
                    $q->where('valid', 1) // <-- only active complaints
                        ->when(
                            $from && $to,
                            fn($q) =>
                            $q->whereBetween('ComplaintDate', [$from, $to])
                        )
                        ->when(
                            $from && !$to,
                            fn($q) =>
                            $q->whereDate('ComplaintDate', '>=', $from)
                        )
                        ->when(
                            !$from && $to,
                            fn($q) =>
                            $q->whereDate('ComplaintDate', '<=', $to)
                        );
                }
            ])->get();

            foreach ($requestTypesStats as $type) {
                $type->percentage = $total > 0
                    ? round(($type->complaints_count / $total) * 100, 2)
                    : 0;
            }


            $statusStats = (clone $complaintsQuery)
                ->select('ComplaintStatus', DB::raw('COUNT(*) as total'))
                ->groupBy('ComplaintStatus')
                ->with('status')
                ->get();

            $status24Total = (clone $complaintsQuery)
                ->whereIn('ComplaintStatus', [2, 4])
                ->count();



            $closeReasonStats = (clone $complaintsQuery)
                ->select('fk_close_reason_id', DB::raw('COUNT(*) as total'))
                ->whereIn('ComplaintStatus', [2, 4])
                ->whereNotNull('fk_close_reason_id')
                ->groupBy('fk_close_reason_id')
                ->with('closeReason')
                ->get()
                ->map(fn($item) => [
                    'name' => $item->closeReason->close_reason_Name ?? 'غير معروف',
                    'total' => $item->total
                ]);

            $sectorStats = (clone $complaintsQuery)
                ->select('sector_id', DB::raw('COUNT(*) as total'))
                ->groupBy('sector_id')
                ->get()
                ->map(function ($item) {
                    $sector = \App\Models\Sector::where('sec_id', $item->sector_id)->first();
                    return [
                        'name'  => $sector->sector_ar ?? 'غير معروف',
                        'total' => $item->total,
                    ];
                })
                // merge every bucket that resolved to the same name (e.g. all the
                // unmatched/غير معروف ones) into a single row instead of many
                ->groupBy('name')
                ->map(function ($group, $name) {
                    return [
                        'name'  => $name,
                        'total' => $group->sum('total'),
                    ];
                })
                ->values();

            // This one builds its own query from scratch via joins rather
            // than cloning $complaintsQuery, so the valid filter has to be
            // applied on the joined sfdcomplaints table explicitly.
            $sourceStats = ComSource::select(
                'comsources.comsourcesid',
                'comsources.comsourcesname',
                DB::raw('COUNT(complaint_sources.complaint_id) as total')
            )
                ->leftJoin('complaint_sources', 'complaint_sources.comsource_id', '=', 'comsources.comsourcesid')
                ->leftJoin('sfdcomplaints', 'sfdcomplaints.ComplaintID', '=', 'complaint_sources.complaint_id')
                ->where(function ($q) {
                    // keep sources with zero matching complaints (left join nulls)
                    // but exclude any complaint row that's soft-deleted
                    $q->whereNull('sfdcomplaints.ComplaintID')
                        ->orWhere('sfdcomplaints.valid', 1);
                })
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('sfdcomplaints.ComplaintDate', [$from, $to]);
                })
                ->when($from && !$to, function ($q) use ($from) {
                    $q->whereDate('sfdcomplaints.ComplaintDate', '>=', $from);
                })
                ->when(!$from && $to, function ($q) use ($to) {
                    $q->whereDate('sfdcomplaints.ComplaintDate', '<=', $to);
                })
                ->groupBy('comsources.comsourcesid', 'comsources.comsourcesname')
                ->orderByDesc('total')
                ->get()
                ->filter(fn($i) => $i->total > 0)
                ->values();

            $projectTypeStats = (clone $complaintsQuery)
    ->select('ComplaintProjectType', DB::raw('COUNT(*) as total'))
    ->groupBy('ComplaintProjectType')
    ->get()
    ->map(function ($item) {
        $projectType = \App\Models\ProjectType::where('ID', $item->ComplaintProjectType)->first();
        return [
            'name'  => $projectType->sector_nama ?? 'غير معروف',
            'total' => $item->total,
        ];
    })
    // merge every bucket that resolved to the same name (covers all
    // unmatched/null ComplaintProjectType values collapsing into one bar)
    ->groupBy('name')
    ->map(fn($group, $name) => [
        'name'  => $name,
        'total' => $group->sum('total'),
    ])
    ->values();


            $officeStats = (clone $complaintsQuery)
                ->select('office', DB::raw('COUNT(*) as total'))
                ->groupBy('office')
                ->get()
                ->map(function ($item) {
                    $office = \App\Models\Office::where('ID', $item->office)->first();

                    return [
                        'name' => $office->REG_OFFIC_NAMA ?? 'غير معروف',
                        'total' => $item->total
                    ];
                });
            return view('home', compact(
                'total',
                'requestTypesStats',
                'statusStats',
                'statusSolved',
                'statusProcessing',
                'statusNew',
                'statusSaved',
                'status24Total',
                'closeReasonStats',
                'sectorStats',
                'projectTypeStats',
                // 'govStats',
                'officeStats',
                'sourceStats'

            ));
        } catch (\Exception $e) {

            Log::error('Dashboard Error: ' . $e->getMessage());
            return view('home', [
                'total' => 0,
                'requestTypesStats' => collect(),
                'statusStats' => collect(),

                'statusSolved' => collect(),
                'statusProcessing' => collect(),
                'statusNew' => collect(),
                'statusSaved' => collect(),
                'sectorStats' => collect(),
                'status24Total' => collect(),
                'closeReasonStats' => collect(),
                'projectTypeStats' => collect(),
                'sourceStats' => collect(),
                'officeStats' => collect(),
            ]);
        }
    }
}
