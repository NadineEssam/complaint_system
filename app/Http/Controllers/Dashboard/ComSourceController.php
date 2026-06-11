<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComSource;
use App\DataTables\ComSourceDataTable;

class ComSourceController extends Controller
{
    public function index(ComSourceDataTable $dataTable)
    {
        return $dataTable->render('dashboard.sources.index');
    }

    public function create()
    {
        return view('dashboard.sources.create_edit');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'comsourcesname' => 'required|string|max:255|unique:comsources,comsourcesname',
            ], [
                'comsourcesname.required' => 'اسم المصدر مطلوب',
                'comsourcesname.unique' => 'هذا الاسم موجود بالفعل',
            ]);

            $data['created_by'] = auth()->id();
            ComSource::create($data);

            alert()->success('تم بنجاح', 'تم إضافة مصدر الشكوى بنجاح');

            return redirect()->route('sources.index');
        } catch (\Exception $e) {
            return back()->withInput()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        $source = ComSource::with(['createdBy', 'updatedBy'])->findOrFail($id);

        return view('dashboard.sources.show', [
            'source' => $source
        ]);
    }

    public function edit($id)
    {
        $source = ComSource::findOrFail($id);

        return view('dashboard.sources.create_edit', [
            'source' => $source
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $source = ComSource::findOrFail($id);

            $data = $request->validate([
                'comsourcesname' => 'required|string|max:255|unique:comsources,comsourcesname,' . $id . ',comsourcesid',
            ], [
                'comsourcesname.required' => 'اسم المصدر مطلوب',
                'comsourcesname.unique' => 'هذا الاسم موجود بالفعل',
            ]);

            $data['updated_by'] = auth()->id();
            $source->update($data);

            alert()->success('تم بنجاح', 'تم تحديث مصدر الشكوى بنجاح');

            return redirect()->route('sources.index');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'فشل تحديث المصدر');
        }
    }

    public function destroy($id)
    {
        try {
            $source = ComSource::findOrFail($id);
            $source->delete();

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