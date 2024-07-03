<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Client;
use App\Models\Employe;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ClientSupplier;
use App\Models\EmployeDocument;
use App\Models\RequestedJobService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->employee) {
            $query = Employe::where('user_id', $user->id);
        } else {
            $query = Employe::query();
        }
        $query = $query->with(['user', 'department'])
            ->whereHas('department', function ($q) use($request) {
                $q->where('isActive', 1);
                if ($request->department_filter) {
                    $q = $q->where('id', $request->department_filter);
                }
            });

        if ($request->search) {
            $query = $query->whereHas('user', function ($q) use ($request) {
                $q->Where('first_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$request->search}%")
                    ->orWhere('last_name', 'LIKE', "%{$request->search}%");
            });
            // ->orwhereHas('department', function($q) use ($request){
            //     $q->Where('name', 'LIKE', "%{$request->search}%");
            // });
        }
        if ($request->payroll_filter) {
            $query = $query->Where('salary_type', 'LIKE', "%{$request->payroll_filter}%");
        }
        if ($request->status_filter && $request->status_filter !== "all") {
            $query = $query->whereHas('user', function ($q) use ($request) {
                //    dd($q->get());
                if ($request->status_filter == 'active') {
                    $q->Where('user_activated', 1);
                } else if ($request->status_filter == 'inactive') {
                    $q->Where('user_activated', 0);
                }
            });
        }
        // if ($request->department_filter) {
        //     $query = $query->where('departments.department_id', $request->department_filter);
        // }

        if ($request->sort_by && $request->sort_order) {
            $order = $request->sort_order;
            $column = $request->sort_by;
            if ($column == "salary_type")
                $query = $query->orderBy('salary_type', $order);
            else if ($column == "division")
                //$query = $query->join('departments', 'departments.id', '=', 'employees.department_id')->select('departments.*')->orderBy('departments.name', $order);
                $query = $query->orderByJoin('department.name', $request->sort_order);
            else
                $query = $query->with('user')->join('users', 'employees.user_id', '=', 'users.id')->select('employees.*')->orderBy('users.' . $column . '', '' . $order . '');
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        // apply sorting
        // if ($request->sort_by == "id" || $request->sort_by == "first_name" || $request->sort_by == "middle_name" || $request->sort_by == "last_name" || $request->sort_by == "salary_type") {
        //     $query = $query->orderByJoin('user.'.$request->sort_by, $request->sort_order);
        // } else if ($request->sort_by == "division") {
        //     $query = $query->orderByJoin('department.name', $request->sort_order);
        // } else {
        //     $query = $query->orderBy("id", "DESC");
        // }

        $data = $query->paginate($request->size);
        return response()->json([
            'employees' => $data,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Department::where('isActive', 1)->orderBy('name')->get();
        return response()->json([
            'departments' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $current_date_time = Carbon::now()->timestamp;
            $destinationPath = 'uploads';
            $fileName = $current_date_time . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $fileName);
            $filepath = $destinationPath . '/' . $fileName;
        }
        $password = $request->password;
        if ($password == "") {
            $password = "password";
        }
        $userData = array(
            "email" => $request->email,
            "password" => $password,
            "encrypted_password" => $password,
            "role" => "3",
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name ?? '',
            "last_name" => $request->last_name ?? '',
            "profile_image" => isset($filepath) ?  $filepath : null,
        );
        $user_id = $this->register($userData);

        if ($user_id == 0) {
            return response()->json([
                'error' => 'This Email Address already exists',
            ], 200);
        }

        $data = new Employe();
        $data->user_id              = $user_id;
        $data->department_id        = $request->department_id ?? '';
        $data->contract_start_date  = $request->contract_start_date ?? '';
        $data->contract_end_date    = $request->contract_end_date ?? '';
        $data->salary_type          = $request->salary_type ?? '';
        $data->salary_term          = $request->salary_term ?? '';
        $data->salary_period          = $request->salary_period ?? '';
        $data->salary_value         = $request->salary_value ?? '';
        $data->t_shirt_size         = $request->t_shirt_size ?? '';
        $data->address              = $request->address ?? '';
        $data->notes                = $request->notes ?? '';
        $data->visa_information     = $request->visa_information ?? '';
        $data->date_of_birth        = $request->date_of_birth ?? '';
        $data->city        = $request->city ?? '';
        $data->state        = $request->state ?? '';
        $data->country        = $request->country ?? '';
        $data->zipcode        = $request->zipcode ?? '';
        $data->apartment_no        = $request->apartment_no ?? '';
        $data->save();


        return response()->json([
            'employee_id' => $data->id,
        ], 200);
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Employe::with('user')->find($id);
        return response()->json([
            'employee' => $data,
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'email' => ['required'],
            'first_name' => ['required'],
        ]);
        if ($v->fails()) {
            $v = $v->errors()->messages();
            return response()->json([
                'error' => $v["email"][0] ?? $v["first_name"][0],
            ], 201);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $current_date_time = Carbon::now()->timestamp;
            $destinationPath = 'uploads';
            $fileName = $current_date_time . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $fileName);
            $filepath = $destinationPath . '/' . $fileName;
            // $filename = time() . '.' . $image->getClientOriginalExtension();
            // $image->storeAs('images', $filename, 'public'); // Store the image in the 'public/images' folder
            // $filepath = 'storage/images/' . $filename;
        }
        $data = User::find($request->user_id);

        $data->first_name        = $request->first_name;
        $data->middle_name  = $request->middle_name ?? '';
        $data->last_name    = $request->last_name ?? '';
        $data->email    = $request->email;
        if (isset($filepath)) {
            $data->profile_image = $filepath;
        }

        if (isset($request->user_activated)) {
            $data->user_activated = ($request->user_activated) ? 1 : 0;
        }
        if ($request->password != "") {
            $data->password = bcrypt($request->password);
            $data->encrypted_password = $request->password;
        }
        $data->save();

        $data = Employe::find($id);
        $data->department_id        = $request->department_id ?? '';
        $data->contract_start_date  = $request->contract_start_date ?? '';
        $data->contract_end_date    = $request->contract_end_date ?? '';
        $data->salary_type          = $request->salary_type ?? '';
        $data->salary_term          = $request->salary_term ?? '';
        $data->salary_period          = $request->salary_period ?? '';
        $data->salary_value         = $request->salary_value ?? '';
        $data->t_shirt_size         = $request->t_shirt_size ?? '';
        $data->address              = $request->address ?? '';
        $data->notes                = $request->notes ?? '';
        $data->visa_information     = $request->visa_information ?? '';
        $data->date_of_birth        = $request->date_of_birth ?? '';
        $data->city        = $request->city ?? '';
        $data->state        = $request->state ?? '';
        $data->country        = $request->country ?? '';
        $data->zipcode        = $request->zipcode ?? '';
        $data->apartment_no        = $request->apartment_no ?? '';
        $data->save();


        return response()->json([
            'msg' => "Record has been updated",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Employe::find($id);
        try {
            // $data->user()->delete();
            RequestedJobService::where('assigned_to_type', 'individual')->where('assigned_to_id', $data->id)
                ->update(['assigned_to_type' => null, 'assigned_to_id' => null]);
            $data->isActive = 0;
            $data->save();
            $user = User::find($data->user_id);
            $user->is_active = 0;
            $user->save();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Employee cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }

    public function register($request)
    {
        $v = Validator::make($request, [
            'email' => ['required', 'email', Rule::unique('users')->where('is_active', 1)],
        ]);
        if ($v->fails()) {
            return 0;
        }
        $user = new User();
        $user->email = $request['email'];
        $user->first_name = $request['first_name'];
        $user->middle_name = $request['middle_name'];
        $user->last_name = $request['last_name'];
        $user->password = bcrypt($request['password']);
        $user->encrypted_password = $request['encrypted_password'];
        $user->profile_image = $request['profile_image'];
        $user->is_active = 1;
        $user->role = $request['role'];
        $user->save();
        return $user->id;
    }

    public function uploadEmployeeDocuments(Request $request, $id)
    {
        $data = Employe::find($id);
        if (isset($request->filePath) && !empty($request->filePath)) {
            $ed = new EmployeDocument();
            $ed->employee_id     = $id;
            $ed->document_title     = $request->title;
            $ed->expiry_date     = $request->expiry;
            $ed->document_number = $request->id;
            $ed->file_name       = $request->filePath;
            $ed->save();
            return response()->json([
                'msg' => 'Document uploaded successfully',
            ], 200);
        } else {
            return response()->json([
                'error' => 'Please attach a document',
            ], 200);
        }
    }

    public function getEmployeeDocuments($id, Request $request)
    {
        $data = EmployeDocument::where('employee_id', $id)
            ->where(function ($q) use ($request) {
                $q->where('document_title', 'LIKE', "%{$request->search}%");
                $q->orwhere('document_number', 'LIKE', "%{$request->search}%");
            })
            ->orderBy('id', 'desc')->paginate($request->size);
        return response()->json([
            'documents' => $data,
        ], 200);
    }

    public function deleteEmployeeDocuments($id)
    {
        $doc = EmployeDocument::find($id);
        $filePath = public_path() . '/' . $doc->file_name;
        File::delete($filePath);
        $doc->delete();
        return response()->json([
            "msg" => "Record has been deleted",
        ], 200);
    }
    public function editEmployeeDocument($id)
    {
        $data = EmployeDocument::find($id);
        return response()->json(['document' => $data]);
    }
    public function updateEmployeeDocument($id, Request $request)
    {
        $data = EmployeDocument::find($id);
        $data->document_title     = $request->title;
        $data->expiry_date     = $request->expiry;
        $data->document_number = $request->id;
        $data->file_name       = $request->filePath;
        $data->save();
        return response()->json([
            'msg' => 'Document updated successfully',
        ], 200);
    }
}
