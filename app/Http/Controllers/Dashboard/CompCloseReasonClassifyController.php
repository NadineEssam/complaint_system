<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompCloseReasonClassify;
use App\Models\CompCloseReason;
use App\DataTables\CompCloseReasonClassifyDataTable;

class CompCloseReasonClassifyController extends Controller
{
    public function index(CompCloseReasonClassifyDataTable $dataTable)
    {
        return $dataTable->render('dashboard.close-reason-classify.index');
    }

    public function create()
    {
        $closeReasons = CompCloseReason::where('validity', 1)->get();

        return view('dashboard.close-reason-classify.create_edit', [
            'closeReasons' => $closeReasons
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'close_reason_classify_Name' => 'required|string|max:255',
                'fk_close_reason_id' => 'required|integer|exists:comp_close_reason,close_reason_ID',
                'validity'                   => 'nullable|in:0,1', 
            ], [
                'close_reason_classify_Name.required' => 'اسم التصنيف مطلوب',
                'fk_close_reason_id.required' => 'السبب الرئيسي مطلوب',
                'fk_close_reason_id.exists' => 'السبب المختار غير موجود',
            ]);

            $data['created_by'] = auth()->id();
            CompCloseReasonClassify::create($data);

            alert()->success('تم بنجاح', 'تم إضافة التصنيف بنجاح');

            return redirect()->route('close-reason-classify.index');
        } catch (\Exception $e) {
            return back()->withInput()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        $classification = CompCloseReasonClassify::with([
            'closeReason',
            'createdBy',
            'updatedBy'
        ])->findOrFail($id);

        return view('dashboard.close-reason-classify.show', [
            'classification' => $classification
        ]);
    }

    public function edit($id)
    {
        $classification = CompCloseReasonClassify::findOrFail($id);
        $closeReasons = CompCloseReason::where('validity', 1)->get();

        return view('dashboard.close-reason-classify.create_edit', [
            'classification' => $classification,
            'closeReasons' => $closeReasons
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $classification = CompCloseReasonClassify::findOrFail($id);

            $data = $request->validate([
                'close_reason_classify_Name' => 'required|string|max:255',
                'fk_close_reason_id' => 'required|integer|exists:comp_close_reason,close_reason_ID',
                'validity'                   => 'nullable|in:0,1', 
            ], [
                'close_reason_classify_Name.required' => 'اسم التصنيف مطلوب',
                'fk_close_reason_id.required' => 'السبب الرئيسي مطلوب',
                'fk_close_reason_id.exists' => 'السبب المختار غير موجود',
            ]);

            $data['updated_by'] = auth()->id();
            $classification->update($data);

            alert()->success('تم بنجاح', 'تم تحديث التصنيف بنجاح');

            return redirect()->route('close-reason-classify.index');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'فشل تحديث التصنيف');
        }
    }

    public function destroy($id)
{
    try {
        $classification = CompCloseReasonClassify::findOrFail($id);

        // Block deletion if used in complaints or responses
        $isUsed = \App\Models\Complaint::where('fk_close_reason_classify_id', $id)->exists()
               || \App\Models\ComplaintResponse::where('fk_close_reason_classify_id', $id)->exists();

        if ($isUsed) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف هذا التصنيف لأنه مستخدم في بيانات موجودة'
            ]);
        }

        $classification->delete();

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