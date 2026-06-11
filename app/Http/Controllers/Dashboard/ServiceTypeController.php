<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use App\DataTables\ServiceTypeDataTable;
use Illuminate\Http\Request;

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
            ], [
                'srevicetyptname.required' => 'اسم الخدمة مطلوب',
                'srevicetyptname.unique' => 'هذا الاسم موجود بالفعل',
            ]);

            $data['created_by'] = auth()->id();
            ServiceType::create($data);

            alert()->success('تم بنجاح', 'تم إضافة نوع الخدمة بنجاح');
            return redirect()->route('service-types.index');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $serviceType = ServiceType::with('createdBy', 'updatedBy')->findOrFail($id);
        return view('dashboard.reference-data.service-types.show', compact('serviceType'));
    }

    public function edit($id)
    {
        $serviceType = ServiceType::findOrFail($id);
        return view('dashboard.reference-data.service-types.edit', compact('serviceType'));
    }

    public function update(Request $request, $id)
    {
        try {
            $serviceType = ServiceType::findOrFail($id);

            $data = $request->validate([
                'srevicetyptname' => 'required|string|max:255|unique:srevicetypt,srevicetyptname,' . $id . ',srevicetyptid',
            ], [
                'srevicetyptname.required' => 'اسم الخدمة مطلوب',
                'srevicetyptname.unique' => 'هذا الاسم موجود بالفعل',
            ]);

            $data['updated_by'] = auth()->id();
            $serviceType->update($data);

            alert()->success('تم بنجاح', 'تم تحديث نوع الخدمة بنجاح');
            return redirect()->route('service-types.index');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $serviceType = ServiceType::findOrFail($id);
            $serviceType->delete();

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