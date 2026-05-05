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
    $complaint = Complaint::findOrFail($id);

    return $dataTable
        ->withComplaint($id)
        ->render('dashboard.responses.responses', [
            'complaint' => $complaint
        ]);
}

    public function create(Request $request)
{
    $complaintId = request('complaint_id');

    if (!$complaintId) {
        abort(404, 'Complaint ID is required');
    }

    $complaint = Complaint::findOrFail($complaintId);

    return view('dashboard.responses.create_edit_responses', [
        'complaint' => $complaint,
        'statuses' => CompStatus::all(),
        'serviceTypes' => ServiceType::all(),
        'closeReasons' => CompCloseReason::all(),
        'classifications' => CompCloseReasonClassify::all(),
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

        ComplaintResponse::create($data);

        Complaint::where('ComplaintID', $data['complaint_id'])
            ->update([
                'ComplaintStatus' => $data['ComplaintStatus']
            ]);

            // dd('وصل هنا');

   return redirect('/responses?complaint_id=' . $data['complaint_id'])
    ->with('success', 'تم إضافة الرد بنجاح');

    }    catch (\Exception $e) {
    $e->getMessage();
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

    return view('dashboard.responses.create_edit_responses', [ // 👈 ممكن توحد الفورم
        'response' => $response,
        'complaint' => $complaint,
        'statuses' => CompStatus::all(),
        'serviceTypes' => ServiceType::all(),
        'closeReasons' => CompCloseReason::all(),
        'classifications' => CompCloseReasonClassify::all(),
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

            $response->update($data);

            Complaint::where(
                'ComplaintID',
                $response->complaint_id
            )->update([
                'ComplaintStatus' => $data['ComplaintStatus']
            ]);

            return redirect('/responses?complaint_id=' . $response->complaint_id)
                ->with('success', 'تم تعديل الرد بنجاح');

                
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
