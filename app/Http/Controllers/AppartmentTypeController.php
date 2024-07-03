<?php

namespace App\Http\Controllers;

use App\Models\AppartmentType;
use App\Models\ServiceSubCategoryAppartmentPrice;
use App\Models\ServiceSubCategory;
use App\Models\ServiceAppartmentPrice;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AppartmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->sort_order) {
            $appartmentTypes = AppartmentType::where('isActive', 1)
                ->Where('type', 'LIKE', "%{$request->search}%")
                ->orderby($request->sort_by, $request->sort_order)
                ->paginate($request->size);
        } else {
            $appartmentTypes = AppartmentType::where('isActive', 1)
                ->Where('type', 'LIKE', "%{$request->search}%")
                ->orderby($request->sort_by)
                ->paginate($request->size);
        }

        return response()->json([
            'appartment_types' => $appartmentTypes,
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
        $sortVal12 = AppartmentType::where('type', $request->appartment_type_name)->first();
        $sortVal = AppartmentType::orderBy('sort', 'asc')->select('sort')->first();
        // $v = Validator::make($request->all(), [
        //     'type' => ['required', 'appartment_type_name', Rule::unique('appartment_types')->where('is_active', 1)],
        // ]);
        if (isset($sortVal12)) {

            return response()->json([
                'service' => $sortVal12,
                'message' => 'Unit Type already exist'
            ], 200);
        }
        $appartmentType = new AppartmentType();
        $appartmentType->type = $request->appartment_type_name;
        $appartmentType->sort = $sortVal->sort - 1;
        $appartmentType->save();
        //to save price for this apartment
        $subServices = ServiceSubCategory::where('isActive', 1)->get();
        foreach ($subServices as $subService) {
            if ($subService->is_independent == 0) {
                $servicePricing = new ServiceSubCategoryAppartmentPrice();
                $servicePricing->sub_service_id = $subService->id;
                $servicePricing->appartment_type_id = $appartmentType->id;
                $servicePricing->base_price = 10;
                $servicePricing->save();
            }
        }
        $services = Service::orderBy('sequence_order', 'asc')->get();
        foreach ($services as $service) {
            //            $servicePricing = new ServiceAppartmentPrice();
            //            $servicePricing->service_id   = $service->id;
            //            $servicePricing->appartment_type_id  = $appartmentType->id;
            //            $servicePricing->base_price  = 10;
            //            $servicePricing->save();
        }
        //to add new sub service to each client property
        // $properties = ClientProperties:: get();
        // foreach ($properties as $property){

        //     if($data->is_independent == 0){
        //         $appartmentTypes = AppartmentType::where('isActive', 1)
        //         ->whereHas('client_properties', function($q) use ($property){
        //             $q->Where('client_properties_id', $property->id);
        //         })->get();
        //         foreach ($appartmentTypes as $appartmentType){
        //             $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$data->id)
        //                 ->where('appartment_type_id',$appartmentType->id)
        //                 ->first();

        //             $clientPricing = new ClientPricing();
        //             $clientPricing->property_id  = $property->id;
        //             $clientPricing->service_type_id  = $data->parent_category_id;
        //             $clientPricing->sub_service_id   = $data->id;
        //             $clientPricing->apartment_type_id  = $appartmentType->id;
        //             $clientPricing->price  = $servicePricing->base_price;
        //             $clientPricing->save();
        //         }

        // }
        return response()->json([
            'msg' => "Record has been added",
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
        $appartmentType = AppartmentType::find($id);
        return response()->json([
            'appartment_type' => $appartmentType,
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
        $appartmentType = AppartmentType::find($id);
        return response()->json([
            'appartment_type' => $appartmentType,

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
        $appartment = AppartmentType::where('id', '!=', $id)->where('type', $request->appartment_type_name)->first();
        if (isset($appartment)) {
            return response()->json([
                'service' => $appartment,
                'message' => 'Unit type already exist'
            ], 200);
        }
        $appartmentType = AppartmentType::find($id);
        if (isset($request->appartment_type_name)) {
            $appartmentType->type = $request->appartment_type_name;
        }
        // if(isset($request->sort)){
        //     $appartmentType->sort = $request->sort;
        // }
        $appartmentType->save();
        return response()->json([
            'msg' => "Record has been updated",
        ], 200);
    }

    /**
     * save the position of appartments
     *
     */
    public function save(Request $request)
    {

        foreach ($request->appartment as $index => $item) {
            $appartmentType = AppartmentType::find($item['id']);
            $appartmentType->sort = $index;
            $appartmentType->save();
        }
        return response()->json([
            'msg' => "Order has been updated",
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
        $appartmentType = AppartmentType::find($id);
        try {

            $appartmentType->isActive = 0;
            $appartmentType->save();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Unit Type cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
