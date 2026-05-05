<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ComplaintDataTable;
use App\DataTables\RolesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\Gov;
use App\Models\RequestType;
use App\Models\Sector;
use App\Models\Comsource;
use App\Models\Office;
use App\Models\ComplaintSource;
use App\Models\CompStatus;
use App\Models\ServiceType;
use App\Models\CompCloseReason;
use App\Models\CompCloseReasonClassify;
use App\Models\ComplaintResponse;
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
    public function index(ComplaintDataTable $dataTable)
    {
        return $dataTable->render('dashboard.complaints.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $requestTypes = RequestType::all();
        $govs = Gov::all();
        $sectors = Sector::all();
        $comsources = Comsource::all();
        $offices = Office::all();

        return view('dashboard.complaints.create_edit', compact(
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
{
    $data = $request->validate([
        'requesttypeid' => 'required|integer',
        'ComplainerName' => 'required|string',
        'ComplainerEmail' => 'required|email',
        'ComplainerPhone' => 'required|string',
        'ComplaintGovernorate' => 'required|integer',
        'ComplaintDate' => 'required|date',
        'sector_id' => 'required|integer',
        'office' => 'required|integer',
        'comsource_id' => 'required|integer',
        'ComplaintNationalID' => 'nullable',
        'ComplainerGender' => 'nullable',
    ]);

    Complaint::create([
        'RequestType' => $data['requesttypeid'],
        'ComplainerName' => $data['ComplainerName'],
        'ComplainerEmail' => $data['ComplainerEmail'],
        'ComplainerPhone' => $data['ComplainerPhone'],
        'ComplaintGovernorate' => $data['ComplaintGovernorate'],
        'ComplaintDate' => $data['ComplaintDate'],
        'department' => $data['sector_id'],
        'office' => $data['office'],
        'comsources' => $data['comsource_id'],
        'ComplaintNationalID' => $data['ComplaintNationalID'] ?? null,
        'ComplainerGender' => $data['ComplainerGender'] ?? null,
    ]);

    return redirect()->route('complaints.index')
        ->with('success', 'Saved successfully');
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
        $requestTypes = RequestType::all();
        $govs = Gov::all();
        $sectors = Sector::all();
        $comsources = Comsource::all();
        $offices = Office::all();

        return view('dashboard.complaints.create_edit', compact(
            'complaint',
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices'
        ));
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
    $data = $request->validate([
        'requesttypeid' => 'required|integer',
        'ComplainerName' => 'required|string',
        'ComplainerEmail' => 'required|email',
        'ComplainerPhone' => 'required|string',
        'ComplaintGovernorate' => 'required|integer',
        'ComplaintDate' => 'required|date',
        'sector_id' => 'required|integer',
        'office' => 'required|integer',
    ]);

    $complaint->update([
        'RequestType' => $data['requesttypeid'],
        'ComplainerName' => $data['ComplainerName'],
        'ComplainerEmail' => $data['ComplainerEmail'],
        'ComplainerPhone' => $data['ComplainerPhone'],
        'ComplaintGovernorate' => $data['ComplaintGovernorate'],
        'ComplaintDate' => $data['ComplaintDate'],
        'department' => $data['sector_id'],
        'office' => $data['office'],
    ]);

    return redirect()->route('complaints.index');
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

        return response()->json(['success' => true, 'message' => __('Delete Successful')]);
    }

    public function validateRoles($request)
    {
        $valid = [];
        if ($request->complaint) {
            $valid['name'] = ['required', Rule::unique('roles', 'name')->ignore($request->complaint->id, 'id')];
        } else {
            $valid['name'] = ['required', Rule::unique('roles', 'name')];
        }
        return $request->validate($valid);
    }
}
