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
use App\DataTables\ComplaintResponseDataTable;
use Illuminate\Support\Facades\Log;

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
                ->with('error', 'لا يمكن إضافة رد على بيان مغلق');
        }
        $usedStatuses = ComplaintResponse::where('complaint_id', $complaintId)
            ->pluck('ComplaintStatus')
            ->toArray();
        $usedStatuses = array_diff($usedStatuses, [1]);

        $statuses = CompStatus::where('statusID', '!=', 3)
            ->whereNotIn('statusID', $usedStatuses)
            ->get();

        return view('dashboard.responses.create_edit_responses', [
            'complaint' => $complaint,
            'statuses' => $statuses,
            'serviceTypes' => ServiceType::where('validity', 1)->get(),
            'closeReasons'    => CompCloseReason::all(),
            'classifications' => CompCloseReasonClassify::where('validity', 1)->select(
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
            ], [
                'fk_close_reason_id.required_if'          => 'سبب البيان مطلوب عند إغلاق البيان',
                'fk_close_reason_classify_id.required_if' => 'التصنيف مطلوب عند إغلاق البيان',
            ]);

            $data['created_by'] = auth()->id();
            $response = ComplaintResponse::create($data);

            $updateData = ['ComplaintStatus' => $response->ComplaintStatus];

            if (in_array($response->ComplaintStatus, [2, 4])) {
                $updateData['fk_close_reason_id']          = $response->fk_close_reason_id;
                $updateData['fk_close_reason_classify_id'] = $response->fk_close_reason_classify_id;
            }

            $response->complaint()->update($updateData);

            Log::info('ComplaintResponse created', [
                'response_id'  => $response->id,
                'complaint_id' => $data['complaint_id'],
                'status'       => $data['ComplaintStatus'],
                'created_by'   => $data['created_by'],
            ]);

            alert()->success('تم بنجاح', 'تم إضافة الرد على البيان بنجاح');

            return redirect()->route(
                'responses.index',
                ['complaint_id' => $data['complaint_id']]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {

            Log::warning('ComplaintResponse store — validation failed', [
                'complaint_id' => $request->input('complaint_id'),
                'user_id'      => auth()->id(),
                'errors'       => $e->errors(),
            ]);

            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {

            Log::error('ComplaintResponse store — unexpected error', [
                'complaint_id' => $request->input('complaint_id'),
                'user_id'      => auth()->id(),
                'message'      => $e->getMessage(),
                'file'         => $e->getFile(),
                'line'         => $e->getLine(),
                'trace'        => $e->getTraceAsString(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
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

        $complaint = Complaint::findOrFail($response->complaint_id);

        // Check if this is the last response added for this complaint
        $lastResponseId = ComplaintResponse::where('complaint_id', $complaint->ComplaintID)
            ->max('id');

        $isLastResponse = $response->id === $lastResponseId;

        // Block edit only if complaint is closed AND this is NOT the last response
        if (in_array($complaint->ComplaintStatus, [2, 4]) && !$isLastResponse) {
            return redirect()
                ->route('responses.index', [
                    'complaint_id' => $complaint->ComplaintID
                ])
                ->with('error', 'لا يمكن تعديل ردود بيان مغلق');
        }

        $usedStatuses = ComplaintResponse::where('complaint_id', $complaint->ComplaintID)
            ->where('id', '!=', $response->id)
            ->pluck('ComplaintStatus')
            ->toArray();
        $usedStatuses = array_diff($usedStatuses, [1]);

        $statuses = CompStatus::where('statusID', '!=', 3)
            ->whereNotIn('statusID', $usedStatuses)
            ->orWhere('statusID', $response->ComplaintStatus)
            ->get();

        return view('dashboard.responses.create_edit_responses', [
            'response'        => $response,
            'complaint'       => $complaint,
            'statuses'        => $statuses,
            'serviceTypes' => ServiceType::where('validity', 1)->get(),
            'closeReasons'    => CompCloseReason::all(),
            'classifications' => CompCloseReasonClassify::where('validity', 1)->select(
                'close_reason_classify_id',
                'close_reason_classify_Name',
                'fk_close_reason_id'
            )->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        try {

            $response = ComplaintResponse::findOrFail($id);

            $data = $request->validate([
                'ComplaintStatus'            => 'required|integer',
                'ComplaintText'              => 'nullable|string',
                'ComplaintService'           => 'nullable|integer',
                'fk_close_reason_id'         => 'nullable|integer',
                'fk_close_reason_classify_id' => 'nullable|integer',
            ], [
                'fk_close_reason_id.required_if'          => 'سبب البيان مطلوب عند إغلاق البيان',
                'fk_close_reason_classify_id.required_if' => 'التصنيف مطلوب عند إغلاق البيان',
            ]);

            $data['updated_by'] = auth()->id();
            $response->update($data);

            // Check if this is still the last response after update
            $lastResponseId = ComplaintResponse::where('complaint_id', $response->complaint_id)
                ->max('id');

            $updateData = ['ComplaintStatus' => $response->ComplaintStatus];

            if ($response->id === $lastResponseId && in_array($response->ComplaintStatus, [2, 4])) {
                $updateData['fk_close_reason_id']          = $response->fk_close_reason_id;
                $updateData['fk_close_reason_classify_id'] = $response->fk_close_reason_classify_id;
            }

            $response->complaint()->update($updateData);

            Log::info('ComplaintResponse updated', [
                'response_id'  => $response->id,
                'complaint_id' => $response->complaint_id,
                'status'       => $response->ComplaintStatus,
                'updated_by'   => $data['updated_by'],
            ]);

            alert()->success('تم بنجاح', 'تم تعديل الرد على البيان بنجاح');

            return redirect()->route(
                'responses.index',
                ['complaint_id' => $response->complaint_id]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {

            Log::warning('ComplaintResponse update — validation failed', [
                'response_id' => $id,
                'user_id'     => auth()->id(),
                'errors'      => $e->errors(),
            ]);

            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {

            Log::error('ComplaintResponse update — unexpected error', [
                'response_id' => $id,
                'user_id'     => auth()->id(),
                'message'     => $e->getMessage(),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return back()->withInput()->with('error', 'فشل تعديل الرد');
        }
    }

    public function destroy($id)
    {
        try {

            $response = ComplaintResponse::findOrFail($id);
            $complaintId = $response->complaint_id;

            // After deletion, sync from the new last response (if any)
            $newLast = ComplaintResponse::where('complaint_id', $complaintId)
                ->where('id', '!=', $id)
                ->orderByDesc('id')
                ->first();

            $response->complaint()->update([
                'ComplaintStatus'            => $newLast?->ComplaintStatus ?? 3,
                'fk_close_reason_id'         => $newLast?->fk_close_reason_id ?? 0,
                'fk_close_reason_classify_id' => $newLast?->fk_close_reason_classify_id ?? 0,
            ]);

            $response->delete();

            Log::info('ComplaintResponse deleted', [
                'response_id'  => $id,
                'complaint_id' => $complaintId,
                'deleted_by'   => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف بنجاح'
            ]);
        } catch (\Exception $e) {

            Log::error('ComplaintResponse destroy — unexpected error', [
                'response_id' => $id,
                'user_id'     => auth()->id(),
                'message'     => $e->getMessage(),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل الحذف'
            ]);
        }
    }
}
