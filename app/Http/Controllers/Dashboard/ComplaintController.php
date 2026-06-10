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
use App\Models\ProjectType;
use Illuminate\Support\Facades\Http;
use App\Models\ComplaintResponse;

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
    // public function index(ComplaintDataTable $dataTable)
    // {
    //     return $dataTable->render('dashboard.complaints.index');
    // }

    public function index(ComplaintDataTable $dataTable)
    {
        $genders   = [['id' => 'ذكر', 'name' => 'ذكر'], ['id' => 'أنثى', 'name' => 'أنثى']];
        $govs      = \App\Models\Gov::select('govsid as id', 'govname as name')->get();
        $statuses  = \App\Models\CompStatus::select('statusID as id', 'statusText as name')->get();
        $reqTypes  = \App\Models\RequestType::select('requesttypeid as id', 'requesttypename as name')->get();

        return $dataTable->with([
            'gender_filter'   => request('gender_filter'),
            'gov_filter'      => request('gov_filter'),
            'status_filter'   => request('status_filter'),
            'reqtype_filter'  => request('reqtype_filter'),
        ])->render('dashboard.complaints.index', compact('genders', 'govs', 'statuses', 'reqTypes'));
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
        $projectTypes = ProjectType::orderBy('projecttypename')->get();
        $comsources = Comsource::all();
        $offices = Office::all();

        return view('dashboard.complaints.create_edit', compact(
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices',
            'sectors'
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
            'ComplainerEmail' => 'nullable|email',
            'ComplainerPhone' => [
                'required',
                'regex:/^01[0-2,5]{1}[0-9]{8}$/'
            ],

            'ComplaintGovernorate' => 'required|integer',
            'ComplaintDate' => 'required|date|before_or_equal:today',
            'sector_id' => 'required|integer',
            'office' => 'required|integer',
            'comsource_id' => 'required|integer',
            'ComplainerGender' => 'required|string|max:10',

            'ComplaintNationalID' => 'nullable|required_if:requesttypeid,2|digits:14',
            'ComplaintText'    => 'required|string',

        ], [

            'requesttypeid.required' => 'يرجى اختيار نوع الطلب',
            'ComplainerName.required' => 'يرجى إدخال اسم العميل',

            'ComplainerEmail.email' => 'البريد الإلكتروني غير صحيح',

            'ComplainerPhone.required' => 'يرجى إدخال رقم الهاتف المحمول',
            'ComplainerPhone.regex' => 'رقم الهاتف المحمول غير صحيح',

            'ComplaintNationalID.required_if' => 'الرقم القومي مطلوب',
            'ComplaintNationalID.digits' => 'الرقم القومي يجب أن يكون 14 رقم',

            'ComplaintDate.required' => 'يرجى إدخال تاريخ الشكوى',
            'ComplaintDate.before_or_equal' => 'لا يمكن إدخال تاريخ مستقبلي',

            'ComplaintGovernorate.required' => 'يرجى اختيار المحافظة',

            'sector_id.required' => 'يرجى اختيار القطاع',

            'office.required' => 'يرجى اختيار المكتب',

            'comsource_id.required' => 'يرجى اختيار مصدر الشكوى',
            'ComplaintText.required' => 'يرجى إدخال نص الشكوى',


        ]);


        Complaint::create([
            'RequestType' => $data['requesttypeid'],
            'ComplainerName' => $data['ComplainerName'],
            'ComplainerEmail' => $data['ComplainerEmail'],
            'ComplainerPhone' => $data['ComplainerPhone'],
            'ComplaintGovernorate' => $data['ComplaintGovernorate'],
            'ComplaintDate' => $data['ComplaintDate'],
             'department' => $data['sector_id'],
            // 'ComplaintProjectType' => $data['sector_id'],
            'ComplaintText' => $data['ComplaintText'],
            'office' => $data['office'],
            'ComplaintSources' => $data['comsource_id'],
            'ComplaintNationalID' => $data['ComplaintNationalID'] ?? null,
            'ComplainerGender' => $data['ComplainerGender'] ?? null,
            'username' => auth()->user()->userID,
            
        ]);


        alert()->success('تم بنجاح', 'تم إضافة الشكوى بنجاح');

        return redirect()->route('complaints.index');
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
        $projectTypes = ProjectType::orderBy('projecttypename')->get();
        $offices = Office::all();

        return view('dashboard.complaints.create_edit', compact(
            'complaint',
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices',
            'projectTypes'
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
            'ComplainerEmail' => 'nullable|email',
            'ComplainerPhone' => [
                'required',
                'regex:/^01[0-2,5]{1}[0-9]{8}$/'
            ],

            'ComplaintGovernorate' => 'required|integer',
            'ComplaintDate' => 'required|date|before_or_equal:today',
            'office' => 'required|integer',
            'comsource_id' => 'required|integer',
            'ComplaintNationalID' => 'nullable|required_if:requesttypeid,2|digits:14',
            'ComplainerGender' => 'required|string|max:10',
            'sector_id' => 'required|integer',
            'ComplaintText'    => 'required|string',

        ], [

            'requesttypeid.required' => 'يرجى اختيار نوع الطلب',
            'ComplainerName.required' => 'يرجى إدخال اسم العميل',

            'ComplainerEmail.email' => 'البريد الإلكتروني غير صحيح',

            'ComplainerPhone.required' => 'يرجى إدخال رقم الهاتف المحمول',
            'ComplainerPhone.regex' => 'رقم الهاتف المحمول غير صحيح',

            'ComplaintNationalID.required_if' => 'الرقم القومي مطلوب',
            'ComplaintNationalID.digits' => 'الرقم القومي يجب أن يكون 14 رقم',

            'ComplaintDate.required' => 'يرجى إدخال تاريخ الشكوى',
            'ComplaintDate.before_or_equal' => 'لا يمكن إدخال تاريخ مستقبلي',

            'ComplaintGovernorate.required' => 'يرجى اختيار المحافظة',

            'sector_id.required' => 'يرجى اختيار القطاع',

            'office.required' => 'يرجى اختيار المكتب',

            'comsource_id.required' => 'يرجى اختيار مصدر الشكوى',
            'ComplaintText.required' => 'يرجى إدخال نص الشكوى',

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
            'ComplaintText' => $data['ComplaintText'],

            'ComplaintSources' => $data['comsource_id'],
            'ComplaintNationalID' => $data['ComplaintNationalID'] ?? null,
            'ComplainerGender' => $data['ComplainerGender'],
           
            'UpdateUser' => auth()->user()->userID,
        ]);


        alert()->success('تم بنجاح', 'تم تعديل الشكوى بنجاح');

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
