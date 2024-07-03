<?php

namespace App\Http\Controllers;

use App\Helper\Menue;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role= Role::all()->toArray();
        $permissions= Permission::all()->toArray();
        $role_permissions= RolePermission::all()->toArray();
        $menusList=Menue::getList($request->role_id);
        
        return response()->json(['role'=>$role,'permissions'=>$permissions,'role_permissions'=>$role_permissions,'menus'=>$menusList],200);
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
    public function store(Request $request, $id)
    {
        $role = Role::find($id);
        RolePermission::updateOrCreate(
        ['module'=>$request->module,
        'role_id'=>$role->id],
        [
            'module'=>$request->module,
            'role_id'=>$role->id,
            $request->permission=>$request->checked
        ]);
        // $role->permissions()->attach($request->id);
        return response()->json([
            'msg' => "Record has been added",
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id, $permId)
    {
        $role = Role::find($id);
        $role->permissions()->detach($permId);
        return response()->json([
            'msg' => "Record has been deleted",
        ],200);
    }

    public function getMenue()
    {
        $menusList=Menue::getList(auth()->user()->role);
        
        return response()->json(['menus'=>$menusList],200);
    }




}
