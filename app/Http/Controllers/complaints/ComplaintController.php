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

    public function show($id)
    {
        $complaint = Complaint::with('status')->findOrFail($id);

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Return complaints data for DataTables.
     */
    public function data()
    {
        $complaints = Complaint::query();

        return DataTables::of($complaints)
            ->addColumn('action', function ($row) {

                $viewUrl = route('complaints.show', $row->ComplaintID);
                $editUrl = route('complaints.edit', $row->ComplaintID);
                $responseUrl = route('complaints.responses', $row->ComplaintID);


                return '
                        <a href="' . $viewUrl . '" class="btn btn-sm btn-info">عرض</a>

                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning">تعديل</a>

                        <a href="' . $responseUrl . '" class="btn btn-sm btn-success">الرد</a>
                        <button class="btn btn-sm btn-danger delete-btn"
                            data-id="' . $row->ComplaintID . '">
                            حذف
                        </button>

                    
                        ';
            })
            ->editColumn('ComplaintStatus', function ($row) {
                return $row->status->statusText ?? '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

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

    public function edit($id)
    {
        $complaint = Complaint::with('sources')
            ->findOrFail($id);

        $requestTypes = RequestType::all();
        $govs = Gov::all();
        $sectors = Sector::all();
        $comsources = Comsource::all();
        $offices = Office::all();

        return view('complaints.edit', compact(
            'complaint',
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices'
        ));
    }
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $data = $request->validate([
            'requesttypeid' => 'required|integer',
            'ComplainerName' => 'required|string',
            'ComplainerEmail' => 'required|email',
            'ComplainerPhone' => 'required|string',
            'ComplaintGovernorate' => 'required|integer',
            'ComplaintNationalID' => 'nullable|string',
            'ComplainerGender' => 'nullable|string',
            'ComplaintDate' => 'required|date',
            'sector_id' => 'required|integer',
            'office' => 'required|integer',
            'comsources' => 'array',
            'comsources.*' => 'integer',
            'ComplaintText' => 'nullable|string',
        ]);

        $complaint->update([
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
            'ComplaintText' => $data['ComplaintText'] ?? null,
        ]);

        $complaint->sources()->sync($data['comsources'] ?? []);

        return redirect()->route('complaints.index')
            ->with('success', 'تم تحديث الشكوى بنجاح');
    }

    public function destroy($id)
{
    $complaint = Complaint::findOrFail($id);

    $complaint->sources()->detach();
    $complaint->responses()->delete();
    $complaint->delete();

    return response()->json([
        'status' => true,
        'message' => 'تم حذف الشكوى بنجاح'
    ]);
}
}
