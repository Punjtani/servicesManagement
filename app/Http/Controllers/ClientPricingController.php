<?php

namespace App\Http\Controllers;

use App\Models\ClientPricing;
use App\Models\Service;
use App\Models\ServiceSubCategory;
use App\Models\AppartmentType;
use App\Models\ClientProperties;
use App\Models\ServiceSubCategoryAppartmentPrice;
use App\Models\ServiceAppartmentPrice;
use App\Models\ClientServicePricing;
use App\Models\ClientPropertyPriceNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientPricingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        foreach ($request->clientPropertyPricing as $r){
            $data = ClientPricing::find($r['id']);
            if($data){
                $data->price = $r['price'];
                $data->price_tag = $r['price_tag'];
                $data->save();
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
        $services = Service::with(['subServices' => function($q){
            $q->where('isActive', 1);
            $q->where('is_free_of_cost', 0);
        }])->orderBy('sequence_order','asc')->get();
        $subServices = ServiceSubCategory::where('isActive', 1)->get();
        $appartmentTypes = AppartmentType::where('isActive', 1)
        ->whereHas('client_properties', function($q) use ($id){
                $q->Where('client_properties_id', $id);
                $q->where('isActive', 1);
            })->orderBy("sort","asc")->get();
        foreach ($appartmentTypes as $appartmentType){
            foreach ($subServices as $subService){
                if($subService->is_independent == 0){
                    $propertyPricing = ClientPricing::where('property_id',$id)
                   ->where('sub_service_id',$subService->id)
                   ->where('apartment_type_id',$appartmentType->id)
                   ->first();
                   $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$subService->id)
                   ->where('appartment_type_id',$appartmentType->id)
                   ->first();
                    if(!$propertyPricing){
                        $clientPricing = new ClientPricing();
                        $clientPricing->property_id  = $id;
                        $clientPricing->service_type_id  = $subService->parent_category_id;
                        $clientPricing->sub_service_id   = $subService->id;
                        $clientPricing->apartment_type_id  = $appartmentType->id;
                        $clientPricing->price  = $servicePricing->base_price;
                        $clientPricing->save();
                    }

                }
           }

        }
        foreach ($subServices as $subService){
            if($subService->is_independent == 1){
                $propertyPricing = ClientPricing::where('property_id',$id)
                ->where('sub_service_id',$subService->id)
                ->where('apartment_type_id',null)
                ->first();
                $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$subService->id)
                   ->where('appartment_type_id',null)
                   ->first();
                if(!$propertyPricing){
                    $clientPricing = new ClientPricing();
                    $clientPricing->property_id  = $id;
                    $clientPricing->service_type_id  = $subService->parent_category_id;
                    $clientPricing->sub_service_id   = $subService->id;
                    // $clientPricing->apartment_type_id  = null;
                    $clientPricing->price  = $servicePricing->base_price;
                    $clientPricing->save();
                }

            }
        }
        $data = ClientPricing::where('property_id',$id)->with('service')
        ->with(['service.subServices' => function($q){
                $q->where('isActive', 1);
                $q->where('is_free_of_cost', 0);
            }])
        ->with(['apartment'=>function($q){
            $q->where('isActive' , 1);
        }])->get();

        $property = ClientProperties::with('client.user')->find($id);
        $clientPriceNotes = ClientPropertyPriceNotes::where('property_id',$id)->get();
        foreach($services as $index=>$service){
            $services[$index]['notes'] = ClientPropertyPriceNotes::where(['property_id'=>$id ,'service_id'=>$service->id])->get();
        }
        return response()->json([
            'clientPropertyPricing' => $data,
            'allServices' => $services,
            'appartmentTypes' => $appartmentTypes,
            'property' => $property,
            'notes' => $clientPriceNotes
        ],200);
    }

     public function copyPrices($id)
    {
        $subServices = ServiceSubCategory::where('isActive', 1)->get();
        $appartmentTypes = AppartmentType::where('isActive', 1)
        ->whereHas('client_properties', function($q) use ($id){
                $q->Where('client_properties_id', $id);
            })->get();
        foreach ($appartmentTypes as $appartmentType){
            foreach ($subServices as $subService){
                if($subService->is_independent == 0){
                    $propertyPricing = ClientPricing::where('property_id',$id)
                   ->where('sub_service_id',$subService->id)
                   ->where('apartment_type_id',$appartmentType->id)
                   ->first();
                   $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$subService->id)
                   ->where('appartment_type_id',$appartmentType->id)
                   ->first();
                    if($propertyPricing){
                        $propertyPricing->price  = $servicePricing->base_price;
                        $propertyPricing->save();
                    }
                }
           }
        }
        foreach ($subServices as $subService){
            if($subService->is_independent == 1){
                $propertyPricing = ClientPricing::where('property_id',$id)
                ->where('sub_service_id',$subService->id)
                ->where('apartment_type_id',null)
                ->first();
                $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$subService->id)
                   ->where('appartment_type_id',null)
                   ->first();
                if($propertyPricing){
                    $propertyPricing->price  = $servicePricing->base_price;
                    $propertyPricing->price_tag  = $servicePricing->price_tag;
                    $propertyPricing->save();
                }
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
    public function getClientPrice($id)
    {

        $services = Service::with(['subServices.ClientPricing'=>function($q) use ($id){
            $q->where('property_id',$id);
            }])->with(['subServices' => function($q){
                $q->where('isActive', 1);
                $q->where('is_show_to_client',1);
                $q->where('is_free_of_cost', 0);
            }])->orderBy('sequence_order','asc')->get();

        $appartmentTypes = AppartmentType::where('isActive', 1)
            ->whereHas('client_properties', function($q) use ($id){
                    $q->Where('client_properties_id', $id);
                })->get();
        foreach ($appartmentTypes as $appartmentType){

            foreach($services as $service){
                $service->notes = ClientPropertyPriceNotes::where(['property_id'=>$id,'service_id'=>$service->id])->get();

                foreach ($service->subServices as $subService){
                    if($subService->is_independent == 0){
                        $propertyPricing = ClientPricing::where('property_id',$id)
                            ->where('sub_service_id',$subService->id)
                            ->where('apartment_type_id',$appartmentType->id)
                            ->first();
                        $service->{$appartmentType->type} = $service->{$appartmentType->type} + $propertyPricing->price;
                    }else{
//                        $propertyPricing = ClientPricing::where('property_id',$id)
//                            ->where('sub_service_id',$subService->id)
//                            ->where('apartment_type_id',null)
//                            ->first();
//                        $service->{$appartmentType->type} = $service->{$appartmentType->type} + $propertyPricing->price;
                    }
                }
            }
        }

        $data = ClientPricing::where('property_id',$id)->with('service')
        ->with(['service.subServices' => function($q){
                $q->where('isActive', 1);
                $q->where('is_free_of_cost', 0);
            }])
        ->with(['apartment'=>function($q){
            $q->where('isActive' , 1);
        }])->get();
        $clientPriceNotes = ClientPropertyPriceNotes::where('property_id',$id)->get();
        $property = ClientProperties::with('client.user')->find($id);
        return response()->json([
            'clientPropertyPricing' => $data,
            'allServices' => $services,
            'appartmentTypes' => $appartmentTypes,
            'property' => $property,
            'notes' => $clientPriceNotes
        ],200);
    }
    public function updatePricingCheck(Request $request,$id){
        $service = ServiceSubCategory::with('services')->find($id);
        if($service){
            $parentServiceId = $service->services->id;
            $allDisabledCheck = ServiceSubCategory::where(['parent_category_id'=>$parentServiceId,'is_show_to_client' => 1,'isActive'=>1])->where('id','!=',$id)->get();
            if($request->flag == 0){
                if(!$allDisabledCheck->isEmpty()){
                    $service->is_show_to_client = $request->flag;
                    $service->save();
                    return response()->json([],200);
                }else{
                    return response()->json(['error' => 'Last active service cannot be hidden!'],200);
                }
            }else{
                $service->is_show_to_client = $request->flag;
                $service->save();
                return response()->json([
                ],200);
            }
        }
    }


}
