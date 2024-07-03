<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Events\LoginInfoEmailEvent;
use App\Models\Client;
use App\Models\ClientProperties;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Models\StaffClientProperties;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $clientId)
    {
        $staff = Staff::with('user', 'property', 'staff_roles')->where('parent_id', $clientId);
        $client = Client::find($clientId);
        if ($request->search) {
            $search = $request->search;
            $staff = $staff->whereHas('user', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
            // ->whereHas('staff_roles', function($query)use($search){
            //     $query->where('name', 'LIKE', "%{$search}%");
            // })
            // ->whereHas('property', function($query)use($search){
            //     $query->where('title', 'LIKE', "%{$search}%");
            // });
        }
        // if($request->status_filter && $request->status_filter !== "all"){
        //     if($request->status_filter == 'active'){
        //         $query =  $query->Where('is_active','=',  1);
        //     }else if($request->status_filter == 'not active'){
        //        $query =  $query->Where('is_active','=',  0);
        //     }
        // }
        if ($request->sort_by && $request->sort_order) {
            $order = $request->sort_order;
            $column = $request->sort_by;
            if ($column == "property")
                $staff = $staff->orderByJoin('property.title', $request->sort_order);
            //else if ($column == "staff_roles")
            //$staff = $staff->orderByJoin('staff_roles.name', $request->sort_order);
            // $staff = $staff->join('staff_role', 'staff_role.staff_id', '=', 'staff.id')->where('staff.isActive', 1)->select('staff_role.*')->orderBy('staff_role.name', $order);
            else
                $staff = $staff->with('user')->join('users', 'staff.user_id', '=', 'users.id')->select('staff.*')->orderBy('users.' . $column . '', '' . $order . '');
        } else {
            $staff = $staff->orderBy('id', 'asc');
        }
        $staff = $staff->paginate($request->size);
        return response()->json([
            'staff' => $staff,
            'client' => $client
        ], 200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rolesProperties($clientId)
    {
        $roles = StaffRole::where('is_property_level', 0)->orderBy('id', 'Desc')->get();
        $properties = ClientProperties::where('client_id', $clientId)->orderby('title', 'asc')->get();
        return response()->json([
            'staff_roles' => $roles,
            'properties' => $properties
        ], 200);
    }

    // public function getStaff(Request $request,$clientId,$propertyId)
    // {
    //     $client=Client::find($clientId);
    //     $clientUserId=0;
    //     if($client && $client->user){
    //         $clientUserId=$client->user->id;
    //     }
    //     $query = User::query() ->with(['client','employee'])->where('parent_id',$clientUserId);
    //     $data = $query->orderby('id','desc')->with('roles')->orderby('id','asc')->get();
    //     $property=ClientProperties::find($propertyId);
    //     $managerId=0;
    //     if($property){
    //         $managerId=$property->staff_id;
    //     }
    //     return response()->json([
    //         'users' => $data,
    //         'managerId' => $managerId,
    //     ],200);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $data = Role::where('role_type',"Client")->orderBy('id','Desc')->get();
    //     return response()->json([
    //         'roles' => $data,
    //     ],200);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $clientId)
    {
        $request['addedByStaff'] = 0;
        $password = $request->password;
        if ($password == "") {
            $password = "password";
        }
        $client = Client::find($clientId);

        $userData = array(
            "email" => $request->email,
            "password" => $password,
            "encrypted_password" => $password,
            "role" => $client->user->role,
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "is_email_enabled" => $request->is_email_enabled,

        );
        $user_id = $this->register($userData);

        if ($user_id == 0) {
            return response()->json([
                'error' => 'This Email Address already exists',
            ], 200);
        }

        // here we need to create new staff role in specific scenrio
        if (isset($request['staff_role']['id'])) {
            $request->staff_role = $request['staff_role']['id'];
        } else {
            $staffRole = new staffRole();
            $staffRole->name = $request['staff_role']['name'];
            $staffRole->is_property_level = 0;
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

        //multiple property store code start
        if ($request->property) {
            $propertyIds = array();
            if (count($request->property) > 0) {
                foreach ($request->property as $property) {
                    array_push($propertyIds, $property['id']);
                }
            }
            $staff->property()->attach($propertyIds);
        }
        if ($request['addedByStaff'] == 1) {
            staffRole::where('id', $request->staff_role)->update(array('staff_id' => $staff->id));
        }
        return response()->json([
            'user_id' => $user_id,
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

    public function get($id)
    {
        $data = Staff::with('user', 'staff_roles', 'property')->find($id);
        $staffRoles = StaffRole::where('is_property_level', 1)
            ->where('staff_id', $id)
            ->orderBy('id', 'Desc')->get();

        $globalRoles = StaffRole::where('is_property_level', 0)->orderBy('id', 'Desc')->get();
        if (count($staffRoles) > 0) {
            $roles = $globalRoles->merge($staffRoles);
        } else {
            $roles = $globalRoles;
        }
        //        if($data->staff_roles && $data->staff_roles->is_property_level == true){
        //            $roles = StaffRole::where('is_property_level', 1)->orderBy('id','Desc')->get();
        //
        //        }else{
        //            $roles = StaffRole::where('is_property_level', 0)->orderBy('id','Desc')->get();
        //        }
        return response()->json([
            'staff' => $data,
            'staff_roles' => $roles
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $clientId, $id)
    {

        $request['addedByStaff'] = 0;
        $staff = Staff::find($id);
        $data = User::find($staff->user_id);
        $data->first_name   = $request->first_name;
        $data->middle_name  = $request->middle_name;
        $data->last_name    = $request->last_name;
        $data->email        = $request->email;

        $data->is_email_enabled = $request->is_email_enabled;
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
        $user->is_email_enabled = $request['is_email_enabled'];

        $user->save();

        $data = $user;
        $data['password'] = $request['password'];
        event(new LoginInfoEmailEvent($data));
        return $user->id;
    }
    public function updateStatus(Request $request, $id)
    {
        $data = User::find($id);
        $data->is_active   = !$data->is_active;
        $data->save();
        return response()->json([
            'msg' => "Record has been updated",
        ], 200);
    }
}
