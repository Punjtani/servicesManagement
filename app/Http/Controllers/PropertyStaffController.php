<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StaffRole;
use App\Events\LoginInfoEmailEvent;
use App\Models\Client;
use App\Models\Staff;
use App\Models\StaffClientProperties;
use App\Models\ClientProperties;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PropertyStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $property = ClientProperties::with('client')->find($id);
        $staff = Staff::with('user', 'property', 'staff_roles')->whereHas('property', function ($q) use ($id) {
            $q->where('client_properties_id', $id);
        });
        if ($request->search) {
            $search = $request->search;
            $staff = $staff->whereHas('user', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
                ->whereHas('staff_roles', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
        }
        if ($request->sort_column && $request->sort_direction) {
            $order = $request->sort_direction;
            $column = $request->sort_column;
            $staff = $staff->with('user')->join('users', 'staff.user_id', '=', 'users.id')->select('staff.*')->orderBy('users.' . $column . '', '' . $order . '');
        } else {
            $staff = $staff->orderBy('id', 'asc');
        }
        $staff = $staff->get();
        // if($request->status_filter && $request->status_filter !== "all"){
        //     if($request->status_filter == 'active'){
        //         $property =  $property->WhereHas('manager',function($query){
        //             $query->where('is_active','=',  1);
        //         });
        //     }else if($request->status_filter == 'not active'){
        //        $property =  $property->WhereHas('manager',function($query){
        //             $query->where('is_active','=',  0);
        //         });
        //     }
        // }
        return response()->json([
            'property' => $property,
            'staff' => $staff
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles($clientId, $staffId, $propertyId)
    {

        $globalRoles = StaffRole::where('is_property_level', 1)->orderBy('id', 'Desc')->get();

            $data = $globalRoles;

        // $staffRoles = StaffRole::where('is_property_level', 1)
        //     ->where('staff_id', $staffId)
        //     ->orderBy('id', 'Desc')->get();

        // $globalRoles = StaffRole::where('is_property_level', 0)->orderBy('id', 'Desc')->get();
        // if (count($staffRoles) > 0) {
        //     $data = $globalRoles->merge($staffRoles);
        // } else {
        //     $data = $globalRoles;
        // }


        $properties = ClientProperties::where('client_id', $clientId)->orderby('title', 'asc')->get();
        return response()->json([
            'staff_roles' => $data,
            'properties' => $properties
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $propertyId)
    {
        $request['addedByStaff'] = 0;
        $password = $request->password;
        if ($password == "") {
            $password = "password";
        }
        $property = ClientProperties::with('client', 'staff.staff_roles')->find($propertyId);
        $client = Client::find($property->client->id);
        $userData = array(
            "email" => $request->email,
            "password" => $password,
            "role" => $property->client->user->role,
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "is_email_enabled" => $request->is_email_enabled,

        );

        $user_id = $this->register($userData);

        if ($user_id == 0) {
            return response()->json([
                'error' => 'This Email Already Exist',
            ], 200);
        }
        // here we need to create new staff role in specific scenrio
        if (isset($request['staff_role']['id'])) {
            $request->staff_role = $request['staff_role']['id'];
        } else {
            $staffRole = new staffRole();
            $staffRole->name = $request['staff_role']['name'];
            $staffRole->is_property_level = 1;
            $staffRole->property_id = $propertyId;
            $staffRole->save();
            $request->staff_role = $staffRole->id;
            $request['addedByStaff'] = 1;
        }

        $staff = new Staff();
        $staff->user_id = $user_id;
        $staff->staff_role = $request->staff_role;
        $staff->parent_id = $client->id;
        $staff->date_of_birth = $request->date_of_birth;
        $staff->phone = $request->phone;
        $staff->notes = $request->notes;
        $staff->save();
        $property->staff()->attach($staff);

        if ($request['addedByStaff'] == 1) {
            staffRole::where('id', $request->staff_role)->update(array('staff_id' => $staff->id));
        }
        // $assignStaff = new StaffClientProperties();
        // $assignStaff->staff_id = $staff->id;
        // $assignStaff->client_properties_id = $propertyId;
        // $assignStaff->save();
        return response()->json([
            'user_id' => $user_id,
        ], 200);
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
        $user->password = bcrypt($request['password']);
        $user->encrypted_password = $request['password'];
        $user->first_name = $request['first_name'];
        $user->middle_name = $request['middle_name'];
        $user->last_name = $request['last_name'];

        $user->is_active = 1;
        $user->role = $request['role'];
        $user->save();

        $data = $user;
        $data['password'] = $request['password'];
        event(new LoginInfoEmailEvent($data));
        return $user->id;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($propertyId, $id, Request $request)
    {

        $request['addedByStaff'] = 0;
        $staff = Staff::find($id);

        $data = User::find($staff->user_id);
        $data->first_name   = $request->first_name;
        $data->middle_name  = $request->middle_name;
        $data->last_name    = $request->last_name;
        $data->email        = $request->email;

        $data->is_email_enabled        = $request->is_email_enabled;
        if ($request->password && $request->password != "") {
            $data->password = bcrypt($request->password);
            $data->encrypted_password = $request->password;
        }
        $data->save();

        // here we need to create new staff role in specific scenrio
        if (isset($request['staff_role']['id'])) {
            $request->staff_role = $request['staff_role']['id'];
        } else {
            $staffRole = new staffRole();
            $staffRole->name = $request['staff_role']['name'];
            $staffRole->is_property_level = 1;
            $staffRole->property_id = $propertyId;
            $staffRole->save();
            $request->staff_role = $staffRole->id;
            $request['addedByStaff'] = 1;
        }

        $staff->staff_role = $request->staff_role;
        $staff->date_of_birth = $request->date_of_birth;
        $staff->phone = $request->phone;
        $staff->notes = $request->notes;
        $staff->save();

        //multiple property store code start
        $propertyIds = array();
        if (count($request->property) > 0) {
            foreach ($request->property as $property) {
                array_push($propertyIds, $property['id']);
            }
        }
        $staff->property()->sync($propertyIds);
        if ($request['addedByStaff'] == 1) {
            staffRole::where('id', $request->staff_role)->update(array('staff_id' => $staff->id));
        }
        return response()->json([
            'msg' => "Record has been updated",
        ], 200);
    }

    public function get($id)
    {
        $data = Staff::with('user', 'staff_roles', 'property')->find($id);
        return response()->json([
            'staff' => $data,
        ], 200);
    }

    public function edit($clientId)
    {

        $staff = Staff::with('user', 'property', 'staff_roles')->where('parent_id', $clientId)->get();
        return response()->json([
            'staff' => $staff,
        ], 200);
    }
    public function assign($propertyId, Request $request)
    {
        $property = ClientProperties::find($propertyId);
        $property->Staff()->syncWithoutDetaching($request->staff);
        return response()->json([
            'success' => 'staff have been assigned',
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($staffId)
    {
        $data = Staff::find($staffId);
        try {
            $data->isActive = 0;
            $data->save();
            $user = User::find($data->user->id);
            $user->is_active = 0;
            $user->save();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Staff cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
