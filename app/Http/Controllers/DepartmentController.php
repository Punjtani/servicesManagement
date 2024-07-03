<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Service;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Department::with('deparment_service')->Where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('id', 'LIKE', "%{$request->search}%")
            ->orderby($request->sort_by, $request->sort_order)->paginate($request->size);
        // dd($data);
        return response()->json([
            'departments' => $data,
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $department = Department::where('name', $request->name)->first();
        if (isset($department)) {
            return response()->json([
                'service' => $department,
                'message' => 'Department already exist'
            ], 200);
        }
        $data = new Department();
        $data->name = $request->name;
        $data->service_id = $request->service_id ?? null;
        $data->payroll_type = $request->payroll_type;
        $data->save();

        return response()->json([], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Department::find($id);
        return response()->json([
            'department' => $data,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Department::with('deparment_service')->find($id);
        $data->service = $data->deparment_service ? $data->deparment_service : null;
        return response()->json([
            'department' => $data,
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
        $department = Department::where('name', $request->name)->where('id', '!=', $id)->first();
        if (isset($department)) {
            return response()->json([
                'service' => $department,
                'message' => 'Devision already exist'
            ], 200);
        }
        $data = Department::find($id);
        $data->name = $request->name;
        $data->service_id = $request->service_id ?? null;
        $data->payroll_type = $request->payroll_type;
        if ($data->save()) {
            $service = Service::find($request->service_id);
            if ($service) {
                $service->department_id = $id;
                $service->save();
            }
        }

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
        $data = Department::find($id);
        try {
            $data->isActive = 0;
            $data->save();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Division cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
