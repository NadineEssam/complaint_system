<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ComplaintDataTable;
use App\DataTables\RolesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\Datatables\Facades\Datatables;


class ComplaintController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ComplaintDataTable $dataTable){
        return $dataTable->render('dashboard.complaints.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.complaints.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validateRoles($request);
        $role=Role::create(array_merge($request->only(['name'])));
        $role->givePermissionTo($request->input('permissions'));
        try {
            $response = Http::get( url("optimize-clear") );
        } catch (\Throwable $th) {
            //throw $th;
        }
        alert()->success(__('Success'),__('Create Successfully'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        //
        return view('dashboard.complaints.create_edit',compact('complaint'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complaint $complaint)
    {
        //
        $this->validateRoles($request);
        $role->update(array_merge($request->only('name')));
        Log::channel('permissions')->info('=============START LOG===========');
        Log::channel('permissions')->info('=======Request_URL======'.$request->fullUrl().'===========');
        Log::channel('permissions')->info('=======REQUEST_DATA======'.json_encode($request->input()).'===========');
        Log::channel('permissions')->info('=============Change Role:'.json_encode($role).'===========');
        Log::channel('permissions')->info('Permission changes from '.json_encode($role->permissions));
        $role->syncPermissions($request->input('permissions'));
        Log::channel('permissions')->info('Permission changes to '.json_encode($request->input('permissions')));
        Log::channel('permissions')->info('Using User '.auth()->id());
        Log::channel('permissions')->info('============END LOG============');
        try {
            $response = Http::get( url("optimize-clear") );
        } catch (\Throwable $th) {
            //throw $th;
        }
        alert()->success(__('Success'),__('Update Successfully'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
   
        return response()->json(['success'=>true,'message'=>__('Delete Successful')]);
    }

    public function validateRoles($request){
          $valid=[
        ];
        if($request->complaint){
            $valid['name']=['required',Rule::unique('roles','name')->ignore($request->complaint->id,'id')];
        }else{
            $valid['name']=['required' , Rule::unique('roles','name') ];
        }
        return $request->validate($valid);
    }
}
