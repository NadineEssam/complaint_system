<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\RequestType;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {

            $total = Complaint::count();


            $requestTypesStats = RequestType::withCount('complaints')->get();

            foreach ($requestTypesStats as $type) {
                $type->percentage = $total > 0
                    ? round(($type->complaints_count / $total) * 100, 2)
                    : 0;
            }


            $statusStats = Complaint::select('ComplaintStatus', DB::raw('COUNT(*) as total'))
                ->groupBy('ComplaintStatus')
                ->with('status')
                ->get();


            $status24Total = Complaint::whereIn('ComplaintStatus', [2, 4])->count();


            $closeReasonStats = Complaint::select(
                'fk_close_reason_id',
                DB::raw('COUNT(*) as total')
            )
                ->whereIn('ComplaintStatus', [2, 4])
                ->whereNotNull('fk_close_reason_id')
                ->groupBy('fk_close_reason_id')
                ->with('closeReason')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->closeReason->close_reason_Name ?? 'غير معروف',
                        'total' => $item->total
                    ];
                });


            $departmentsStats = Department::select(
                'department.department_id',
                'department.department_name',
                DB::raw('COUNT(sfdcomplaints.ComplaintID) as complaints_count')
            )
                ->leftJoin('sfdcomplaints', 'sfdcomplaints.department', '=', 'department.department_id')
                ->groupBy('department.department_id', 'department.department_name')
                ->get()
                ->filter(function ($dept) {
                    return $dept->complaints_count > 0;
                })
                ->values();

            foreach ($departmentsStats as $dept) {

                $dept->complaints_count = $dept->complaints_count ?? 0;

                $dept->percentage = $total > 0
                    ? round(($dept->complaints_count / $total) * 100, 2)
                    : 0;
            }

            $govStats = Complaint::select(
                'ComplaintGovernorate',
                DB::raw('COUNT(*) as total')
            )
                ->groupBy('ComplaintGovernorate')
                ->get()
                ->map(function ($item) {
                    $gov = \App\Models\Gov::where('govsid', $item->ComplaintGovernorate)->first();

                    return [
                        'name'  => $gov->govname ?? 'غير معروف',
                        'total' => $item->total
                    ];
                });
            return view('home', compact(
                'total',
                'requestTypesStats',
                'statusStats',
                'status24Total',
                'closeReasonStats',
                'departmentsStats',
                'govStats'
            ));
        } catch (\Exception $e) {

            Log::error('Dashboard Error: ' . $e->getMessage());

            return view('home', [
                'total' => 0,
                'requestTypesStats' => collect(),
                'statusStats' => collect(),
                'status24Total' => 0,
                'closeReasonStats' => collect(),
                'departmentsStats' => collect(),
                'govStats' => collect(),
            ]);
        }
    }
}
