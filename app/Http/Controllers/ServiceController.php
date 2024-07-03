<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Job;
use App\Models\ServiceAppartmentPrice;
use App\Models\AppartmentType;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->sort_order))
            $data = Service::orderby('category', $request->sort_order)->get();
        else
            $data = Service::get();
        return response()->json([
            'service_types' => $data,
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
        $service = Service::where(['category' => $request->category, 'payroll_type' => $request->payroll_type])->get();
        if (count($service) > 0) {
            return response()->json([
                'service' => $service,
                'message' => 'Service already exist'
            ], 200);
        } else {
            $data = new Service();
            $data->category = $request->category;
            $data->color = $request->color;
            $data->payroll_type = $request->payroll_type;
            $data->is_taxable = $request->is_taxable;
            $data->save();
            // $appartmentTypes = AppartmentType::where('isActive', 1)->get();
            // foreach($appartmentTypes as $appartmentType){
            //     $servicePricing = new ServiceAppartmentPrice();
            //     $servicePricing->service_id   = $data->id;
            //     $servicePricing->appartment_type_id  = $appartmentType->id;
            //     $servicePricing->base_price  = 10;
            //     $servicePricing->save();
            // }
            return response()->json([], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Service::find($id);
        return response()->json([
            'service_type' => $data,
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
        $data = Service::find($id);
        return response()->json([
            'service_type' => $data,
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
        $department = Service::where('id', '!=', $id)->where('category', $request->category)->first();
        if (isset($department)) {
            return response()->json([
                'service' => $department,
                'message' => 'Service already exist'
            ], 200);
        }
        $data = Service::find($id);
        $data->category = $request->category;
        $data->color = $request->color;
        $data->payroll_type = $request->payroll_type;
        $data->is_taxable = $request->is_taxable;
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
        $data = Service::find($id);
        try {
            $data->isActive = 0;
            $data->save();
            // $data->delete();
            // $jobs = Job::withCount(['requestedJobService'])->get();
            // foreach ($jobs as $job) {
            //     if($job->requested_job_service_count == 0){
            //         $job->delete();
            //     };
            // }
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Service cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
