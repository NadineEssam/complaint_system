<?php



namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\CompStatus;
use App\Models\ServiceType;
use App\Models\CompCloseReason;
use App\Models\CompCloseReasonClassify;
use Yajra\DataTables\Facades\DataTables;
use App\DataTables\ComplaintDataTable;
use App\Models\Gov;
use App\Models\RequestType;
use App\Models\Sector;
use App\Models\Comsource;
use App\Models\Office;
use App\Models\ComplaintSource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\DataTables\ComplaintResponseDataTable;

class ComplaintResponseController extends Controller
{
    public function index(ComplaintResponseDataTable $dataTable)
    {
        $id = request('complaint_id');

        $complaint = Complaint::with([
            'responses.status'
        ])->findOrFail($id);

        $lastResponse = $complaint->responses
            ->sortByDesc('id')
            ->first();

        return $dataTable
            ->withComplaint($id)
            ->render('dashboard.responses.responses', [
                'complaint' => $complaint,
                'lastResponse' => $lastResponse
            ]);
    }

    public function create(Request $request)
    {
        $complaintId = request('complaint_id');

        if (!$complaintId) {
            abort(404, 'Complaint ID is required');
        }

        $complaint = Complaint::findOrFail($complaintId);
        if (in_array($complaint->ComplaintStatus, [2, 4])) {

            return redirect()
                ->route('responses.index', [
                    'complaint_id' => $complaint->ComplaintID
                ])
                ->with('error', 'لا يمكن إضافة رد على شكوى مغلقة');
        }
        $usedStatuses = ComplaintResponse::where('complaint_id', $complaintId)
            ->pluck('ComplaintStatus')
            ->toArray();

        $statuses = CompStatus::where('statusID', '!=', 3)
            ->whereNotIn('statusID', $usedStatuses)
            ->get();

        return view('dashboard.responses.create_edit_responses', [
            'complaint' => $complaint,
            'statuses' => $statuses,
            'serviceTypes' => ServiceType::all(),
            'closeReasons' => CompCloseReason::all(),
            'classifications' => CompCloseReasonClassify::select(
            'close_reason_classify_id',
            'close_reason_classify_Name',
            'fk_close_reason_id'
        )->get(),
        ]);
    }

    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'complaint_id' => 'required|integer|exists:sfdcomplaints,ComplaintID',
                'ComplaintStatus' => 'required|integer',
                'ComplaintText' => 'nullable|string',
                'ComplaintService' => 'nullable|integer',
                'fk_close_reason_id' => 'nullable|integer',
                'fk_close_reason_classify_id' => 'nullable|integer',

            ]);
            $data['created_by'] = auth()->id();
            $response = ComplaintResponse::create($data);

            $response->complaint()->update([
                'ComplaintStatus' => $response->ComplaintStatus
            ]);

            alert()->success('تم بنجاح', 'تم إضافة الرد على البيان بنجاح');

            return redirect()->route(
                'responses.index',
                ['complaint_id' => $data['complaint_id']]
            );
        } catch (\Exception $e) {

            return back()->withInput()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        $response = ComplaintResponse::with([
            'status',
            'serviceType',
            'closeReason',
            'classification'
        ])->findOrFail($id);

        return view('dashboard.responses.show_response', [
            'response' => $response
        ]);
    }

    public function edit($id)
    {
        $response = ComplaintResponse::findOrFail($id);

        // جايب الـ complaint المرتبط
        $complaint = Complaint::findOrFail($response->complaint_id);
        if (in_array($complaint->ComplaintStatus, [2, 4])) {

            return redirect()
                ->route('responses.index', [
                    'complaint_id' => $complaint->ComplaintID
                ])
                ->with('error', 'لا يمكن تعديل ردود شكوى مغلقة');
        }
        $usedStatuses = ComplaintResponse::where('complaint_id', $complaint->ComplaintID)
            ->where('id', '!=', $response->id)
            ->pluck('ComplaintStatus')
            ->toArray();

        $statuses = CompStatus::where('statusID', '!=', 3)
            ->whereNotIn('statusID', $usedStatuses)
            ->orWhere('statusID', $response->ComplaintStatus)
            ->get();

        return view('dashboard.responses.create_edit_responses', [
            'response' => $response,
            'complaint' => $complaint,
            'statuses' => $statuses,
            'serviceTypes' => ServiceType::all(),
            'closeReasons' => CompCloseReason::all(),
            'classifications' => CompCloseReasonClassify::select(
            'close_reason_classify_id',
            'close_reason_classify_Name',
            'fk_close_reason_id'
        )->get(),
            // 'classifications' => CompCloseReasonClassify::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        try {

            $response = ComplaintResponse::findOrFail($id);

            $data = $request->validate([
                'ComplaintStatus' => 'required|integer',
                'ComplaintText' => 'nullable|string',
                'ComplaintService' => 'nullable|integer',
                'fk_close_reason_id' => 'nullable|integer',
                'fk_close_reason_classify_id' => 'nullable|integer',
            ]);

            $data['updated_by'] = auth()->id();
            $response->update($data);

            $response->complaint()->update([
                'ComplaintStatus' => $response->ComplaintStatus
            ]);
            alert()->success('تم بنجاح', 'تم تعديل الرد على البيان بنجاح');
            return redirect()->route(
                'responses.index',
                ['complaint_id' => $response->complaint_id]
            );
        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'فشل تعديل الرد');
        }
    }

    public function destroy($id)
    {
        try {

            $response = ComplaintResponse::findOrFail($id);

            $response->complaint()->update([
                'ComplaintStatus' => 3
            ]);

            $response->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف بنجاح'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'فشل الحذف'
            ]);
        }
    }
}
