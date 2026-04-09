<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Gov;
use App\Models\RequestType;
use App\Models\Sector;
use App\Models\Comsource;
use App\Models\Office;
use App\Models\ComplaintSource;
use Illuminate\Http\Request;
use App\Models\CompStatus;
use App\Models\ServiceType;
use App\Models\CompCloseReason;
use App\Models\CompCloseReasonClassify;
use App\Models\ComplaintResponse;

use Yajra\DataTables\Facades\DataTables;

class ComplaintController extends Controller
{
    /**
     * Display the main complaints list page.
     */
    public function index()
    {
        return view('complaints.index');
    }

    /**
     * Return complaints data for DataTables.
     */
    public function data()
    {
        $complaints = Complaint::query();

        return DataTables::of($complaints)
            ->addColumn('action', function ($row) {
                return '
                        <a href="' . route('complaints.responses', $row->ComplaintID) . '" 
                        class="btn btn-sm btn-success">
                        الرد
                        </a>
                        ';
            })
            ->editColumn('ComplaintStatus', function ($row) {
                return match ($row->ComplaintStatus) {
                    1 => 'Pending',
                    2 => 'In Progress',
                    3 => 'Closed',
                    default => 'Unknown',
                };
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function responses($id)
    {
        $complaint = Complaint::findOrFail($id);
        $statuses = CompStatus::all();
        $serviceTypes = ServiceType::all();
        $closeReasons = CompCloseReason::all();
        $classifications = CompCloseReasonClassify::all();

        $responses = $complaint->responses;
        return view('complaints.responses', compact(
            'complaint',
            'statuses',
            'serviceTypes',
            'closeReasons',
            'classifications',
            'responses'
        ));
    }
    public function storeResponse(Request $request)
    {
        $data = $request->validate([
            'complaint_id' => 'required|exists:sfdcomplaints,ComplaintID',
            'ComplaintStatus' => 'required|integer',
            'ComplaintText' => 'nullable|string',
            'ComplaintService' => 'nullable|integer',
            'fk_close_reason_id' => 'nullable|integer',
            'fk_close_reason_classify_id' => 'nullable|integer',
        ]);

        // save response
        ComplaintResponse::create($data);

        // update main complaint status
        Complaint::where('ComplaintID', $data['complaint_id'])
            ->update(['ComplaintStatus' => $data['ComplaintStatus']]);

        return back()->with('success', 'تم إضافة الرد بنجاح');
    }

    public function showResponse($id)
{
    $response = ComplaintResponse::with(['closeReason', 'classification', 'serviceType'])
        ->findOrFail($id);

    return response()->json([
        'complaint_id' => $response->complaint_id,
        'ComplaintText' => $response->ComplaintText,
        'status_text' => match($response->ComplaintStatus) {
            1 => 'Pending',
            2 => 'In Progress',
            3 => 'Closed',
            default => 'Unknown'
        },
        'service_name' => $response->serviceType->srevicetyptname ?? '-',
        'reason_name' => $response->closeReason->close_reason_Name ?? '-',
        'classify_name' => $response->classification->close_reason_classify_Name ?? '-',
    ]);
}

    public function responsesData($id)
    {
        $responses = ComplaintResponse::with('serviceType')
            ->where('complaint_id', $id)
            ->latest();

        return DataTables::of($responses)
            ->addIndexColumn() // for the serial number column

            // 👇 إضافة عمود id علشان نستخدمه في JS
            ->addColumn('id', function ($row) {
                return $row->id; // أو $row->ComplaintResponseID لو ده primary key عندك
            })

            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary view-btn" data-id="' . $row->id . '">عرض</button>';
            })
            ->editColumn('ComplaintStatus', function ($row) {
                return match ($row->ComplaintStatus) {
                    1 => 'Pending',
                    2 => 'In Progress',
                    3 => 'Closed',
                    default => 'Unknown',
                };
            })
            ->editColumn('ComplaintService', function ($row) {
                return $row->ComplaintServiceName;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('Y-m-d H:i')
                    : '-';
            })
            ->rawColumns(['action']) // مهم للـ HTML في action
            ->make(true);
    }
    /**
     * Show the complaint details form with dropdowns.
     */
    public function details()
    {
        $requestTypes = RequestType::select('requesttypeid', 'requesttypename')->get();
        $govs = Gov::select('govsid', 'govname')->get();
        $sectors = Sector::select('sector_id', 'sector_name')->get();
        $comsources = Comsource::select('comsourcesid', 'comsourcesname')->get();
        $offices = Office::select('ID', 'REG_OFFIC_NAMA', 'FK_GOVT_CODE')->get();

        $statuses = CompStatus::select('statusID', 'statusText')->get();
        $serviceTypes = ServiceType::select('srevicetyptid', 'srevicetyptname')->get();



        return view('complaints.details', compact(
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices',
            'statuses',
            'serviceTypes'
        ));
    }
    /**
     * Store a new complaint.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'requesttypeid' => 'required|integer',
            'ComplainerName' => 'required|string',
            'ComplainerEmail' => 'required|email',
            'ComplainerPhone' => 'required|string',
            'ComplaintGovernorate' => 'required|integer',
            'ComplaintNationalID' => 'required_if:requesttypeid,2|nullable|string',
            'ComplainerGender' => 'required_if:requesttypeid,2|nullable|string',
            'ComplaintDate' => 'required|date',
            'sector_id' => 'required|integer',  // changed to match your form
            'office' => 'required|integer',
            'comsources' => 'required|array|min:1',
            'comsources.*' => 'integer|exists:comsources,comsourcesid',
            'ComplaintStatus' => 'nullable|integer',
            'ComplaintText' => 'nullable|string',
            'ComplaintService' => 'nullable|integer',

        ]);

        $complaint = Complaint::create([
            'RequestType' => $data['requesttypeid'],
            'ComplainerName' => $data['ComplainerName'],
            'ComplainerEmail' => $data['ComplainerEmail'],
            'ComplainerPhone' => $data['ComplainerPhone'],
            'ComplaintGovernorate' => $data['ComplaintGovernorate'],
            'ComplaintNationalID' => $data['ComplaintNationalID'] ?? null,
            'ComplainerGender' => $data['ComplainerGender'] ?? null,
            'ComplaintDate' => $data['ComplaintDate'],
            'department' => $data['sector_id'],
            'office' => $data['office'],
            'ComplaintStatus' => $data['ComplaintStatus'] ?? 3,
            'ComplaintText' => $data['ComplaintText'] ?? null,
            'ComplaintService' => $data['ComplaintService'] ?? null,

        ]);

        $complaint->sources()->sync($data['comsources']);

        return redirect()->route('complaints.index')
            ->with('success', 'تم حفظ الشكوى بنجاح');
    }
}
