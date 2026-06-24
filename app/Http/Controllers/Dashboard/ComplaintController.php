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
use App\Models\Department;
use App\Models\ProjectType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;



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
        $govs      = \App\Models\Gov::select('GOVT_CODE as id', 'GOVT_NAMA as name')->get();
        $offices = \App\Models\Office::select('ID as id', 'REG_OFFIC_NAMA as name')->get(); // adjust column name
        $statuses  = \App\Models\CompStatus::select('statusID as id', 'statusText as name')->get();
        $reqTypes  = \App\Models\RequestType::select('requesttypeid as id', 'requesttypename as name')->get();

        return $dataTable->with([
            'gender_filter'   => request('gender_filter'),
            'gov_filter'      => request('gov_filter'),
            'status_filter'   => request('status_filter'),
            'reqtype_filter'  => request('reqtype_filter'),
        ])->render('dashboard.complaints.index', compact('genders', 'govs', 'statuses','offices', 'reqTypes'));
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
        $departments = Department::all();
        // $projectTypes = ProjectType::orderBy('projecttypename')->get();
        $comsources = Comsource::all();
        $offices = Office::all();
        $projectTypes = ProjectType::all();

        return view('dashboard.complaints.create_edit', compact(
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices',
            'sectors',
            'departments',
            'projectTypes',
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
            'ComplainerEmail' => 'nullable|required_if:requesttypeid,4|email',
            'ComplainerPhone' => [
                'required',
                'regex:/^01[0-2,5]{1}[0-9]{8}$/'
            ],

            'ComplaintGovernorate' => 'nullable|integer',
            'ComplainerGovernorate' => 'required|integer',
            'ComplaintDate' => 'required|date|before_or_equal:today',
            'sec_id' => 'nullable|integer',
            'department' => 'nullable|integer|exists:new_po.departments,dep_id',
            'office' => 'nullable|integer',
            'comsource_ids' => 'required|array|min:1',
            'comsource_ids.*' => 'integer|exists:comsources,comsourcesid',
            'ComplainerGender' => 'required|string|max:10',
            'complaint_type' => 'required|in:internal,external',
            'ComplaintNationalID' => 'nullable|required_if:requesttypeid,2,3|digits:14',
            'ComplaintText'    => 'required|string',
            'ComplaintProjectType' => 'required|exists:ben.sectors,ID',

        ], [

            'requesttypeid.required' => 'يرجى اختيار نوع الطلب',
            'ComplainerName.required' => 'يرجى إدخال اسم العميل',

            'ComplainerEmail.email' => 'البريد الإلكتروني غير صحيح',
            'ComplainerEmail.required_if' => 'البريد الإلكتروني مطلوب',

            'ComplainerPhone.required' => 'يرجى إدخال رقم الهاتف المحمول',
            'ComplainerPhone.regex' => 'رقم الهاتف المحمول غير صحيح',

            'ComplaintNationalID.required_if' => 'الرقم القومي مطلوب',
            'ComplaintNationalID.digits' => 'الرقم القومي يجب أن يكون 14 رقم',

            'ComplaintDate.required' => 'يرجى إدخال تاريخ الشكوى',
            'ComplaintDate.before_or_equal' => 'لا يمكن إدخال تاريخ مستقبلي',

            'ComplaintGovernorate.required' => 'يرجى اختيار المحافظة',

            'sec_id.required' => 'يرجى اختيار القطاع',
            'department.required' => 'يرجى اختيار الإدارة',
            'office.required' => 'يرجى اختيار المكتب',

            'comsource_ids.required' => 'يرجى اختيار مصدر الشكوى',
            'comsource_ids.min' => 'يرجى اختيار مصدر شكوى واحد على الأقل',
            'ComplaintText.required' => 'يرجى إدخال نص البيان',
            'complaint_type.required' => 'يرجى اختيار نوعية وتوجيه البيان',
            'complaint_type.in'       => 'نوعية البيان غير صحيحة',
            'ComplaintProjectType.required' => 'يرجى اختيار نوع النشاط',


        ]);


        $complaint = Complaint::create([
            'RequestType' => $data['requesttypeid'],
            'ComplainerName' => $data['ComplainerName'],
            'ComplainerEmail' => $data['ComplainerEmail'],
            'ComplainerPhone' => $data['ComplainerPhone'],
            'ComplaintGovernorate' => $data['ComplaintGovernorate'],
            'ComplaintDate' => $data['ComplaintDate'],
            'sector_id' => $data['sec_id'] ?? 0,
            'department'  => $data['department'] ?? 0,
            'ComplainerGovernorate' => $data['ComplainerGovernorate'] ?? 0,
            // 'ComplaintProjectType' => $data['sec_id'],
            'ComplaintText' => $data['ComplaintText'],
            'office' => $data['office'] ?? 0,
            'complaint_type' => $data['complaint_type'],
            'ComplaintProjectType' => $data['ComplaintProjectType'],
            // 'ComplaintSources' => $data['comsource_id'],
            'ComplaintNationalID' => $data['ComplaintNationalID'] ?? null,
            'ComplainerGender' => $data['ComplainerGender'] ?? null,
            'username' => auth()->user()->userID,

        ]);
        $complaint->sources()->sync($data['comsource_ids']);


        alert()->success('تم بنجاح', 'تم إضافة الشكوى بنجاح');

        return redirect()->route('complaints.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Complaint $complaint)
    {
        return view('dashboard.complaints.show', compact('complaint'));
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
        $departments = Department::all();
        $comsources = Comsource::all();
        // $projectTypes = ProjectType::orderBy('projecttypename')->get();
        $offices = Office::all();
        $projectTypes = ProjectType::all();

        return view('dashboard.complaints.create_edit', compact(
            'complaint',
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices',
            // 'projectTypes',
            'departments',
            'projectTypes',
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
            'ComplainerEmail' => 'nullable|required_if:requesttypeid,4|email',
            'ComplainerPhone' => [
                'required',
                'regex:/^01[0-2,5]{1}[0-9]{8}$/'
            ],

            'ComplaintGovernorate' => 'nullable|integer',
            'ComplainerGovernorate' => 'required|integer',
            'ComplaintDate' => 'required|date|before_or_equal:today',
            'office' => 'nullable|integer',
            'comsource_ids' => 'required|array|min:1',
            'comsource_ids.*' => 'integer|exists:comsources,comsourcesid',
            'ComplaintNationalID' => 'nullable|required_if:requesttypeid,2,3|digits:14',
            'ComplainerGender' => 'required|string|max:10',
            'sec_id' => 'nullable|integer',
            'department' => 'nullable|integer|exists:new_po.departments,dep_id',
            'complaint_type' => 'required|in:internal,external',
            'ComplaintText'    => 'required|string',
            'ComplaintProjectType' => 'required|exists:ben.sectors,ID',

        ], [

            'requesttypeid.required' => 'يرجى اختيار نوع الطلب',
            'ComplainerName.required' => 'يرجى إدخال اسم العميل',

            'ComplainerEmail.email' => 'البريد الإلكتروني غير صحيح',
            'ComplainerEmail.required_if' => 'البريد الإلكتروني مطلوب',

            'ComplainerPhone.required' => 'يرجى إدخال رقم الهاتف المحمول',
            'ComplainerPhone.regex' => 'رقم الهاتف المحمول غير صحيح',

            'ComplaintNationalID.required_if' => 'الرقم القومي مطلوب',
            'ComplaintNationalID.digits' => 'الرقم القومي يجب أن يكون 14 رقم',

            'ComplaintDate.required' => 'يرجى إدخال تاريخ الشكوى',
            'ComplaintDate.before_or_equal' => 'لا يمكن إدخال تاريخ مستقبلي',

            'ComplaintGovernorate.required' => 'يرجى اختيار المحافظة',

            'sec_id.required' => 'يرجى اختيار القطاع',
            'department.required' => 'يرجى اختيار الإدارة',
            'office.required' => 'يرجى اختيار المكتب',

            'comsource_ids.required' => 'يرجى اختيار مصدر الشكوى',
            'comsource_ids.min' => 'يرجى اختيار مصدر شكوى واحد على الأقل',
            'ComplaintText.required' => 'يرجى إدخال نص البيان',
            'complaint_type.required' => 'يرجى اختيار نوعية وتوجيه البيان',
            'complaint_type.in'       => 'نوعية البيان غير صحيحة',
            'ComplaintProjectType.required' => 'يرجى اختيار نوع النشاط',

        ]);



        $complaint->update([
            'RequestType' => $data['requesttypeid'],
            'ComplainerName' => $data['ComplainerName'],
            'ComplainerEmail' => $data['ComplainerEmail'],
            'ComplainerPhone' => $data['ComplainerPhone'],
            'ComplaintGovernorate' => $data['ComplaintGovernorate'],
            'ComplaintDate' => $data['ComplaintDate'],
            'sector_id' => $data['sec_id'] ?? 0,
            'department' => $data['department'] ?? 0,
            'office' => $data['office'] ?? 0,
            'complaint_type' => $data['complaint_type'],
            'ComplaintText' => $data['ComplaintText'],
             'ComplainerGovernorate' => $data['ComplainerGovernorate'] ?? 0,
            // 'ComplaintSources' => $data['comsource_id'],
            'ComplaintProjectType' => $data['ComplaintProjectType'],
            'ComplaintNationalID' => $data['ComplaintNationalID'] ?? null,
            'ComplainerGender' => $data['ComplainerGender'],

            'UpdateUser' => auth()->user()->userID,
        ]);

        $complaint->sources()->sync($data['comsource_ids']);
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

    /**
     * Show the duplicate-creation ("تكرار") form for a given parent
     * complaint. Reuses the same create_edit.blade.php view used for
     * normal create/edit, flagged so personal-data fields render
     * read-only and the form posts to the duplicate-store route.
     *
     * @param  \App\Models\Complaint  $complaint  The parent/original complaint.
     * @return \Illuminate\Http\Response
     */
    public function duplicateCreate(Complaint $complaint)
    {
        // print_r($complaint);
        $requestTypes = RequestType::all();
        $govs = Gov::all();
        $sectors = Sector::all();
        $departments = Department::all();
        $comsources = Comsource::all();
        $offices = Office::all();
        $projectTypes = ProjectType::all();

        return view('dashboard.complaints.create_edit', compact(
            'complaint',
            'requestTypes',
            'govs',
            'sectors',
            'comsources',
            'offices',
            'departments',
            'projectTypes',
        ))->with('isDuplicateMode', true)
          ->with('parentComplaint', $complaint);
    }

    /**
     * Store a new duplicate ("تكرار") complaint against the given parent.
     *
     * The parent complaint itself is NEVER modified here. Personal-data
     * fields are not trusted from the request (since they're rendered
     * read-only) — they're taken directly from the parent record so
     * they cannot be tampered with client-side.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint  $complaint  The parent/original complaint.
     * @return \Illuminate\Http\Response
     */
    public function duplicateStore(Request $request, Complaint $complaint)
    {
        $data = $request->validate([

            'ComplaintGovernorate' => 'nullable|integer',
            'ComplaintDate' => 'required|date|before_or_equal:today',
            'sec_id' => 'nullable|integer',
            'department' => 'nullable|integer|exists:new_po.departments,dep_id',
            'office' => 'nullable|integer',
            'comsource_ids' => 'required|array|min:1',
            'comsource_ids.*' => 'integer|exists:comsources,comsourcesid',
            'complaint_type' => 'required|in:internal,external',
            'ComplaintText'    => 'required|string',
            'ComplaintProjectType' => 'required|exists:ben.sectors,ID',

        ], [

            'ComplaintDate.required' => 'يرجى إدخال تاريخ الشكوى',
            'ComplaintDate.before_or_equal' => 'لا يمكن إدخال تاريخ مستقبلي',

            'comsource_ids.required' => 'يرجى اختيار مصدر الشكوى',
            'comsource_ids.min' => 'يرجى اختيار مصدر شكوى واحد على الأقل',
            'ComplaintText.required' => 'يرجى إدخال نص البيان',
            'complaint_type.required' => 'يرجى اختيار نوعية وتوجيه البيان',
            'complaint_type.in'       => 'نوعية البيان غير صحيحة',
            'ComplaintProjectType.required' => 'يرجى اختيار نوع النشاط',

        ]);

        $duplicate = Complaint::create([
            // Personal data is copied as-is from the parent — never from
            // the request — because these fields are read-only in the form.
            'RequestType' => $complaint->RequestType,
            'ComplainerName' => $complaint->ComplainerName,
            'ComplainerEmail' => $complaint->ComplainerEmail,
            'ComplainerPhone' => $complaint->ComplainerPhone,
            'ComplainerGovernorate' => $complaint->ComplainerGovernorate,
            'ComplaintNationalID' => $complaint->ComplaintNationalID,
            'ComplainerGender' => $complaint->ComplainerGender,

            // Editable fields come from the duplicate form itself.
            'ComplaintGovernorate' => $data['ComplaintGovernorate'] ?? null,
            'ComplaintDate' => $data['ComplaintDate'],
            'sector_id' => $data['sec_id'] ?? 0,
            'department'  => $data['department'] ?? 0,
            'ComplaintText' => $data['ComplaintText'],
            'office' => $data['office'] ?? 0,
            'complaint_type' => $data['complaint_type'],
            'ComplaintProjectType' => $data['ComplaintProjectType'],

            // Linkage + initial status for every newly created duplicate.
            'parent_id' => $complaint->ComplaintID,
            'ComplaintStatus' => 3,

            'username' => auth()->user()->userID,
        ]);

        $duplicate->sources()->sync($data['comsource_ids']);

        alert()->success('تم بنجاح', 'تم إضافة تكرار الشكوى بنجاح');

        return redirect()->route('complaints.show', $complaint);
    }

    /**
     * Lightweight datatable feed (parent + all its children) shown inside
     * the "مكرر" modal on the complaint show page.
     *
     * Same dual-purpose pattern already used by index(): on a normal GET
     * this returns the Blade view (table shell + its own DataTable JS
     * pointed back at this same route); on the AJAX call that the
     * DataTable JS itself fires against this route, Yajra intercepts it
     * and returns the JSON payload instead — render() handles both cases.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
public function duplicatesIndex(Complaint $complaint)
{
    $root = $complaint->parent_id ? $complaint->parent : $complaint;

    $dataTable = app(\App\DataTables\ComplaintDuplicatesDataTable::class)
        ->forComplaint($root);

    // DataTables AJAX calls always include a 'draw' parameter
    if (request()->has('draw')) {
        return $dataTable->ajax();
    }

    return $dataTable->render(
        'dashboard.complaints.duplicates_modal',
        compact('root') + ['complaint' => $root]
    );
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