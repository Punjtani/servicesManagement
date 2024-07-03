<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_order = (isset($request->sort_order)? $request->sort_order : 'asc' );
        $data = Role::
        Where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('id', 'LIKE', "%{$request->search}%")
            ->orderby($request->sort_by, $sort_order)->paginate($request->size);
        return response()->json([
            'roles' => $data,
        ],200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // Add the unique rule for the name and hex_value columns of the paint_colors table
            'name' => 'required|unique:roles,name',
        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Role already exist",
            ], 200);
        }
        $data = new Role();
        $data->name = $request->name;
        // $data->role_type = $request->role_type;
        $data->save();

        return response()->json([
            'data' => $data,
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Role::find($id);
        return response()->json([
            'role' => $data,
        ],200);
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
        $validator = Validator::make($request->all(), [
            // Add the unique rule for the name and hex_value columns of the paint_colors table
            'name' => 'required|unique:roles,name,'.$id.',id',

        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Role already exist",
            ], 200);
        }

        $data = Role::find($id);
        $data->name = $request->name;
        // $data->role_type = $request->role_type;
        $data->save();
        return response()->json([
            'msg' => "Record has been updated",
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Role::find($id);
        try{
            $data->delete();
            return response()->json([
                'msg' => "Record has been deleted",
            ],200);
        }catch(\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == 23000)
            {
                return response()->json([
                    'error' => "Role cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
