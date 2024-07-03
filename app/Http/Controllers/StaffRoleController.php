<?php

namespace App\Http\Controllers;

use App\Models\StaffRole;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
class StaffRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $staffRoles = StaffRole::query();
        $staffRoles->where('is_property_level', '=', 0);
        if ($request->search) {
            $staffRoles = $staffRoles->where('name', 'LIKE', "%{$request->search}%");
        }
        $staffRoles = $staffRoles->orderby('name')->paginate($request->size);
        return response()->json([
            'staff_roles' => $staffRoles,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new StaffRole();
        $data->name = $request->name;
        $data->save();
        return response()->json([
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\StaffRole $staffRole
     * @return \Illuminate\Http\Response
     */
    public function show(StaffRole $staffRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\StaffRole $staffRole
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffRole $staffRole, $id)
    {
        $data = StaffRole::find($id);
        return response()->json([
            'staff_role' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\StaffRole $staffRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffRole $staffRole, $id)
    {
        $data = StaffRole::find($id);
        $data->name = $request->name;
        $data->save();
        return response()->json([
            'msg' => "Record has been updated",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\StaffRole $staffRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staffRole = StaffRole::with('staff')->find($id);
        $staffUsers = $staffRole->staff;
        try {
            $staffRole->isActive = 0;
            $staffRole->save();
            if (count($staffUsers) > 0) {
                foreach ($staffUsers as $staff) {
                    $data = Staff::find($staff->id);
                    $data->isActive = 0;
                    $data->save();
                    $user = User::find($data->user->id);
                    $user->is_active = 0;
                    $user->save();
                }
            }
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Staff Role cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }

    public function staffRolesList(Request $request)
    {
        $staffRoles = StaffRole::query();
        //$staffRoles->where('is_property_level', '=', 0);
        if ($request->search) {
            $staffRoles = $staffRoles->where('name', 'LIKE', "%{$request->search}%");
        }
        $staffRoles = $staffRoles->orderby($request->sort_by, $request->sort_order)->paginate($request->size);

        return response()->json([
            'roles' => $staffRoles,
        ], 200);
    }

    public function createRole(Request $request)
    {
        $check = StaffRole::where('name', $request->name)->where('is_property_level', 1)->first();

        if ($check) {
            return response()->json([
                'error' => "Role Already Exist on property level",
                'code' => 403,
                'id' => $check->id
            ], 200);
        } else {
            $global_check = StaffRole::where('name', $request->name)
                ->where('is_property_level', 0)
                ->first();
            if ($global_check) {
                return response()->json([
                    'error' => "Role Already Exist",
                    'code' => 200,
                    'id' => $global_check->id
                ], 200);
            } else {
                $data = new StaffRole();
                $data->name = $request->name;
                $data->is_property_level = 0;
                $data->isActive = 1;
                // $data->role_type = $request->role_type;
                $data->save();
                return response()->json([
                    'msg' => "Record has been updated",
                ], 200);
            }
        }

    }

    public function editStaffRole($id)
    {
        $data = StaffRole::find($id);
        return response()->json([
            'role' => $data,
        ], 200);
    }
    public function updateRoleStatus($status, $id)
    {
        $data = StaffRole::find($id);
        $data->isActive   = ($status == 'true') ? 1 : 0;
        $data->save();
        return response()->json([
            'msg' => "Status updated successfully!",
        ],200);
    }

    public function updateStaffRole(Request $request, $id)
    {
        $check = StaffRole::where('name', $request->name)
            ->where('is_property_level', 1)
            ->first();
        if ($check) {
            return response()->json([
                'error' => "Role Already Exist on property level",
                'code' => 403,
            ], 200);
        } else {
            $global_check = StaffRole::where('name', $request->name)
                ->where('is_property_level', 0)
                ->where('id', '!=', $id)->first();
            if ($global_check) {
                return response()->json([
                    'error' => "Role Already Exist",
                    'code' => 200,
                ], 200);
            } else {
                $data = StaffRole::find($id);
                $data->name = $request->name;
                // $data->role_type = $request->role_type;
                $data->save();
                return response()->json([
                    'msg' => "Record has been updated",
                ], 200);
            }
        }


    }
    public function convertRoleAsGlobal(Request $request, $id)
    {
        $role = StaffRole::where('name', $request->name)
            ->where('is_property_level', 1)
            ->first();
        if ($role) {
            $role->is_property_level = 0;
            $role->property_id = null;
            $role->staff_id = null;
            $role->save();
            return response()->json([
                'message' => "Updated Successfully",
            ], 200);

        }

    }

    public function toggleStaffRoleLevel(Request $request, $id) {
        try {
            $role = StaffRole::find($id);
            $role_name = $role->name;
            if (Str::is('*-v*', $role_name)) {
                $role_name = Str::before($role_name, '-v');
            }

        $existsRecord = StaffRole::where("id", "!=", $id )->where('name', 'like', $role_name . '%')->first();

        if ($role && (isset($request->forceCreate) || ($existsRecord && $existsRecord->id)))
        {
            $oldRole = StaffRole::where('name', 'like', $role_name . '%')
                ->orderByRaw('CAST(SUBSTRING_INDEX(name, "-v", -1) AS UNSIGNED) DESC')->first();
            if ($oldRole) {
                $oldVersion = (int) Str::after($oldRole->name, '-v');
                $newVersion = $oldVersion + 1;
                StaffRole::create(['name' => $role_name . '-v' . $newVersion, "is_property_level" => $role->is_property_level ?  0 : 1]);
            } else {
                $newVersion = 1;
                 StaffRole::create(['name' => $role_name . '-v' . $newVersion, "is_property_level" => $role->is_property_level ?  0 : 1]);
            }
           // StaffRole::create(['name' => $role_name . '-v' . $newVersion, "is_property_level" => $role->is_property_level ?  0 : 1]);

            return response()->json([
                'message' => "Successful.",
            ], 200);

        } else if($role) {
                $role->is_property_level = $role->is_property_level ? 0 : 1;
                $role->property_id = null;
                $role->staff_id = null;
                $role->save();
                return response()->json([
                    'message' => "Successful",
                ], 200);
        }
        } catch(\Exception $e){}

        return response()->json([
            'message' => "No Record Found",
        ], 404);
    }

    public function deleteStaffRole($id)
    {
        $data = StaffRole::find($id);
        try {
            $data->delete();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Role cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
