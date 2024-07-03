<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Events\LoginInfoEmailEvent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = User:: with("roles")
        //     ->Where('first_name', 'LIKE', "%{$request->search}%")
        //     ->orWhere('middle_name', 'LIKE', "%{$request->search}%")
        //     ->orWhere('last_name', 'LIKE', "%{$request->search}%")
        //     ->orWhere('email', 'LIKE', "%{$request->search}%")
        //     ->orderby('id','asc')->paginate($request->size);

        //client staff revamp - updated following line
        // $clientRoles = Role::where('role_type',"Client")->pluck('id');
        $clientStaffIds = User::where('role', 2)->doesntHave('client')->pluck('id');

        //client staff revamp - updated following line
        //$query = User::query() ->with(['client','employee'])->whereNotIn('role',$clientRoles);
        //        $query = User::query()->with(['client','employee'])->whereNotIn('id',$clientStaffIds);
        $query = User::query()->with(['client', 'employee', 'staff']);
        $query = $query->with('roles');

        if ($request->search) {
            $search = $request->search;
            $query = $query->Where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        if ($request->status_filter && $request->status_filter !== "all") {
            if ($request->status_filter == 'active') {
                $query =  $query->Where('user_activated', '=',  1);
            } else if ($request->status_filter == 'inactive') {
                $query =  $query->Where('user_activated', '=',  0);
            }
        }
        if ($request->sort_by && $request->sort_order) {
            $order = $request->sort_order;
            $column = $request->sort_by;
            if ($request->sort_by == "staff_roles")
                $query = $query->orderByJoin('roles.name', $order);
            else
                $query = $query->orderBy($column, $order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }
        $data = $query->paginate($request->size);
        return response()->json([
            'users' => $data,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Role::get();
        return response()->json([
            'roles' => $data,
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
        $password = $request->password;
        if ($password == "") {
            $password = "password";
        }
        $userData = array(
            "email" => $request->email,
            "password" => $password,
            "role" => "1",
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
        );


        $user_id = $this->register($userData);

        if ($user_id == 0) {
            return response()->json([
                'error' => 'This Email Address Already Exist',
            ], 200);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return response()->json([
            'user' => $data,
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

        $data = User::find($id);
        $data->first_name   = $request->first_name;
        $data->middle_name  = $request->middle_name;
        $data->last_name    = $request->last_name;
        $data->email        = $request->email;
        if ($request->password != "") {
            $data->password = bcrypt($request->password);;
        }
        $data->save();

        return response()->json([
            'msg' => "Record has been updated",
        ], 200);
    }
    public function updateUserStatus($status, $id)
    {
        $data = User::find($id);
        $data->user_activated   = ($status == 'true') ? 1 : 0;
        $data->save();
        return response()->json([
            'msg' => "User status updated successfully !",
        ], 200);
    }


    // public function destroy($id)
    // {
    //     $data = User::find($id);
    //     try {
    //         $data->delete();
    //         return response()->json([
    //             'msg' => "Record has been deleted",
    //         ], 200);
    //     }catch(\Illuminate\Database\QueryException $e) {

    //         if ($e->getCode() == 23000)
    //         {
    //             return response()->json([
    //                 'error' => "User cannot be deleted due to existence of related data.",
    //             ], 200);

    //         }

    //     }
    // }


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
