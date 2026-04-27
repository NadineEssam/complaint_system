<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\CompStatus;
use App\Models\ServiceType;
use App\Models\CompCloseReason;
use App\Models\CompCloseReasonClassify;
use Yajra\DataTables\Facades\DataTables;

class ComplaintResponseController extends Controller
{
    public function index($id)
    {
        $complaint = Complaint::findOrFail($id);

        return view('responses.responses', [
            'complaint' => $complaint,
            'statuses' => CompStatus::all(),
            'serviceTypes' => ServiceType::all(),
            'closeReasons' => CompCloseReason::all(),
            'classifications' => CompCloseReasonClassify::all(),
        ]);
    }

    public function create($id)
    {
        $complaint = Complaint::findOrFail($id);

        return view('responses.create_responses', [
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
            'complaint_id' => 'required|exists:sfdcomplaints,ComplaintID',
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

        return redirect()
            ->route('complaints.responses', $data['complaint_id'])
            ->with('success', 'تم إضافة الرد بنجاح');

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with('error', 'فشل إضافة الرد');
    }
}
    public function data($id)
    {
        $responses = ComplaintResponse::with([
            'serviceType',
            'status'
        ])
            ->where('complaint_id', $id)
            ->latest();

        return DataTables::of($responses)

            ->addColumn('action', function ($row) {
                return '
                    <a href="/complaints/' . $row->complaint_id . '/responses/create"
                       class="btn btn-success btn-sm">إضافة</a>

                    <a href="/responses/' . $row->id . '"
                        class="btn btn-primary btn-sm">
                        عرض
                        </a>

                    <a href="/responses/' . $row->id . '/edit"
                       class="btn btn-warning btn-sm">تعديل</a>

                    <button class="btn btn-danger btn-sm delete-btn"
                        data-id="' . $row->id . '">حذف</button>
                ';
            })

            ->editColumn('ComplaintStatus', function ($row) {
                return $row->status->statusText ?? '-';
            })

            ->editColumn('ComplaintService', function ($row) {
                return $row->serviceType->srevicetyptname ?? '-';
            })

            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? $row->created_at->format('Y-m-d H:i')
                    : '-';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $response = ComplaintResponse::with([
            'status',
            'serviceType',
            'closeReason',
            'classification'
        ])->findOrFail($id);

        return view('responses.show_response', [
            'response' => $response
        ]);
    }

    public function edit($id)
    {
        $response = ComplaintResponse::findOrFail($id);

        $complaint = Complaint::findOrFail(
            $response->complaint_id
        );

        return view('responses.edit_responses', [
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

            return redirect()
                ->route(
                    'complaints.responses',
                    $response->complaint_id
                )
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
