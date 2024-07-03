<?php

namespace App\Http\Controllers;

use App\Models\ServiceSubCategory;
use App\Models\Service;
use App\Models\AppartmentType;
use App\Models\ServiceSubCategoryAppartmentPrice;
use App\Models\ClientProperties;
use App\Models\ClientPricing;
use Illuminate\Http\Request;
use App\Models\RequestedJobSubService;
use App\Models\Job;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($parentId, Request $request)
    {
        $data = ServiceSubCategory::
            where('parent_category_id',$parentId)
            ->Where('name', 'LIKE', "%{$request->search}%")
            ->where('isActive', 1)
            ->orderby($request->sort_by,$request->sort_order)->paginate($request->size);
        return response()->json([
            'sub_categories' => $data,
        ],200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new ServiceSubCategory();
        $data->name = $request->name;
        $data->is_independent = $request->is_independent;
        $data->is_free_of_cost = $request->is_free_of_cost;
        $data->parent_category_id  = $request->parent_category_id ;
        $data->save();
        // to add new subservice to general pricing
        if($data->is_independent == 1){
            $servicePricing = new ServiceSubCategoryAppartmentPrice();
            $servicePricing->sub_service_id = $data->id;
            $servicePricing->appartment_type_id = null;
            if ($data->is_free_of_cost == 1){
                $servicePricing->base_price = 0;
            }else{
                $servicePricing->base_price = 10;
            }
            $servicePricing->save();
        }else if($data->is_independent == 0){
            $appartmentTypes = AppartmentType:: where('isActive', 1)->get();
            foreach ($appartmentTypes as $appartmentType){
                $servicePricing = new ServiceSubCategoryAppartmentPrice();
                $servicePricing->sub_service_id = $data->id;
                $servicePricing->appartment_type_id = $appartmentType->id;
                if ($data->is_free_of_cost == 1){
                    $servicePricing->base_price = 0;
                }else{
                    $servicePricing->base_price = 10;
                }
                $servicePricing->save();
            }
        }
        //to add new sub service to each client property
        $properties = ClientProperties:: get();
        foreach ($properties as $property){
            if($data->is_independent == 0){
                $appartmentTypes = AppartmentType::where('isActive', 1)
                ->whereHas('client_properties', function($q) use ($property){
                    $q->Where('client_properties_id', $property->id);
                })->get();
                foreach ($appartmentTypes as $appartmentType){
                    $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$data->id)
                        ->where('appartment_type_id',$appartmentType->id)
                        ->first();

                    $clientPricing = new ClientPricing();
                    $clientPricing->property_id  = $property->id;
                    $clientPricing->service_type_id  = $data->parent_category_id;
                    $clientPricing->sub_service_id   = $data->id;
                    $clientPricing->apartment_type_id  = $appartmentType->id;
                    $clientPricing->price  = $servicePricing->base_price;
                    $clientPricing->save();
                }

            }
            else if($data->is_independent == 1){
                $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$data->id)
                ->where('appartment_type_id',null)
                ->first();

                $clientPricing = new ClientPricing();
                $clientPricing->property_id  = $property->id;
                $clientPricing->service_type_id  = $data->parent_category_id;
                $clientPricing->sub_service_id   = $data->id;
                $clientPricing->apartment_type_id  = null;
                $clientPricing->price  = $servicePricing->base_price;
                $clientPricing->save();

            }

        }

        return response()->json([
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
        $data = ServiceSubCategory::find($id);
        return response()->json([
            'sub_category' => $data,
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
        $data = ServiceSubCategory::find($id);
        $data->name = $request->name;
        $data->is_independent = $request->is_independent;
        $data->is_free_of_cost = $request->is_free_of_cost;
        $data->save();

        if($data->is_independent == 1){
            $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$data->id)
            ->where('appartment_type_id',null)->first();
            if(!$servicePricing){
                $servicePricing = new ServiceSubCategoryAppartmentPrice();
                $servicePricing->sub_service_id = $data->id;
                $servicePricing->appartment_type_id = null;
                if ($data->is_free_of_cost == 1){
                    $servicePricing->base_price = 0;
                }else{
                    $servicePricing->base_price = 10;
                }
                $servicePricing->save();
            }
        }else if($data->is_independent == 0){
            $appartmentTypes = AppartmentType:: where('isActive', 1)->get();
            foreach ($appartmentTypes as $appartmentType){
                $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$data->id)
                ->where('appartment_type_id',$appartmentType->id)->first();
                if(!$servicePricing){
                    $servicePricing = new ServiceSubCategoryAppartmentPrice();
                    $servicePricing->sub_service_id = $data->id;
                    $servicePricing->appartment_type_id = $appartmentType->id;
                    if ($data->is_free_of_cost == 1){
                        $servicePricing->base_price = 0;
                    }else{
                        $servicePricing->base_price = 10;
                    }
                    $servicePricing->save();
                }
            }
        }
        //to add new sub service to each client property
        $properties = ClientProperties:: get();
        foreach ($properties as $property){
            ClientPricing::where('property_id',$property->id)
            ->where('service_type_id',$data->parent_category_id)
            ->where('sub_service_id',$data->id)->delete();
            if($data->is_independent == 0){
                $appartmentTypes = AppartmentType::where('isActive', 1)->whereHas('client_properties', function($q) use ($property){
                    $q->Where('client_properties_id', $property->id);
                })->get();
                foreach ($appartmentTypes as $appartmentType){
                    $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$data->id)
                        ->where('appartment_type_id',$appartmentType->id)
                        ->first();

                    $clientPricing = new ClientPricing();
                    $clientPricing->property_id  = $property->id;
                    $clientPricing->service_type_id  = $data->parent_category_id;
                    $clientPricing->sub_service_id   = $data->id;
                    $clientPricing->apartment_type_id  = $appartmentType->id;
                    $clientPricing->price  = $servicePricing->base_price;
                    $clientPricing->save();
                }

            }
            else if($data->is_independent == 1){
                $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$data->id)
                ->where('appartment_type_id',null)
                ->first();

                $clientPricing = new ClientPricing();
                $clientPricing->property_id  = $property->id;
                $clientPricing->service_type_id  = $data->parent_category_id;
                $clientPricing->sub_service_id   = $data->id;
                $clientPricing->apartment_type_id  = null;
                $clientPricing->price  = $servicePricing->base_price;
                $clientPricing->save();

            }

        }

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

        try {
            $requestedJobs = RequestedJobSubService::where('sub_service_id', $id)->update(['is_delete' => 1]);
            $data = ServiceSubCategory::find($id);
            $data->isActive = 0;
            $data->save();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        }catch(\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == 23000)
            {
                return response()->json([
                    'error' => "Subcategory cannot be deleted due to existence of related data.",
                ], 200);

            }

        }
    }

}
