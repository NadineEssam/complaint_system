<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceType;
use App\DataTables\ServiceTypeDataTable;

class ServiceTypeController extends Controller
{
    public function index(ServiceTypeDataTable $dataTable)
    {
        return $dataTable->render('dashboard.services.index');
    }

    public function create()
    {
        return view('dashboard.services.create_edit');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'srevicetyptname' => 'required|string|max:255|unique:srevicetypt,srevicetyptname',
                'validity'        => 'nullable|in:0,1',
            ], [
                'srevicetyptname.required' => 'اسم الخدمة مطلوب',
                'srevicetyptname.unique' => 'هذا الاسم موجود بالفعل',
            ]);

            $data['created_by'] = auth()->id();
            ServiceType::create($data);

            alert()->success('تم بنجاح', 'تم إضافة الخدمة بنجاح');

            return redirect()->route('services.index');
        } catch (\Exception $e) {
            return back()->withInput()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        $service = ServiceType::with(['createdBy', 'updatedBy'])->findOrFail($id);

        return view('dashboard.services.show', [
            'service' => $service
        ]);
    }

    public function edit($id)
    {
        $service = ServiceType::findOrFail($id);

        return view('dashboard.services.create_edit', [
            'service' => $service
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $service = ServiceType::findOrFail($id);

            $data = $request->validate([
                'srevicetyptname' => 'required|string|max:255|unique:srevicetypt,srevicetyptname,' . $id . ',srevicetyptid',
                'validity'        => 'nullable|in:0,1',
            ], [
                'srevicetyptname.required' => 'اسم الخدمة مطلوب',
                'srevicetyptname.unique' => 'هذا الاسم موجود بالفعل',
            ]);

            $data['updated_by'] = auth()->id();
            $service->update($data);

            alert()->success('تم بنجاح', 'تم تحديث الخدمة بنجاح');

            return redirect()->route('services.index');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'فشل تحديث الخدمة');
        }
    }

    public function destroy($id)
{
    try {
        $service = ServiceType::findOrFail($id);

        // Block deletion if used in any complaint
        $isUsed = \App\Models\Complaint::where('ComplaintService', $id)->exists()
               || \App\Models\ComplaintResponse::where('ComplaintService', $id)->exists();

        if ($isUsed) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف هذه الخدمة لأنها مستخدمة في بيانات موجودة'
            ]);
        }

        $service->delete();

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