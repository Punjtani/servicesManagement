<?php

namespace App\Http\Controllers;

use App\Models\ServiceSubCategory;
use App\Models\Service;
use App\Models\AppartmentType;
use App\Models\ServiceSubCategoryAppartmentPrice;
use App\Models\ServiceAppartmentPrice;
use App\Models\ClientPriceNotes;
use Illuminate\Http\Request;

class SubCategoryPriceController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPrice()
    {
        // services = Service::with('subServices')->get();
        $subServices = ServiceSubCategory::where('isActive', 1)->get();
        $appartmentTypes = AppartmentType::where('isActive', 1)->orderBy("sort", "asc")->get();
        foreach ($appartmentTypes as $appartmentType) {

            foreach ($subServices as $subService) {
                if ($subService->is_independent == 0) {
                    $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id', $subService->id)
                        ->where('appartment_type_id', $appartmentType->id)
                        ->first();
                    if (!$servicePricing) {
                        $servicePricing = new ServiceSubCategoryAppartmentPrice();
                        $servicePricing->sub_service_id   = $subService->id;
                        $servicePricing->appartment_type_id  = $appartmentType->id;
                        $servicePricing->base_price  = 10;
                        $servicePricing->save();
                    }
                }
            }
        }
        foreach ($subServices as $subService) {
            if ($subService->is_independent == 1) {
                $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id', $subService->id)
                    ->where('appartment_type_id', null)
                    ->first();
                if (!$servicePricing) {
                    $servicePricing = new ServiceSubCategoryAppartmentPrice();
                    $servicePricing->sub_service_id   = $subService->id;
                    // $clientPricing->apartment_type_id  = null;
                    $servicePricing->base_price  = 10;
                    $servicePricing->save();
                }
            }
        }
        $data = Service::with(['subServices' => function ($q) {
            $q->where('isActive', 1);
            $q->where('is_free_of_cost', 0);
        }])
            ->orderBy('sequence_order', 'asc')->get();
        $appartmentTypes = AppartmentType::where('isActive', 1)->orderBy("sort", "asc")->get();
        $serviceSubCategoryAppartmentPrice = ServiceSubCategoryAppartmentPrice::get();
        $notes = clientPriceNotes::whereNotNull('service_id')->get();
        foreach ($data as $index => $service) {
            $data[$index]['notes'] = clientPriceNotes::whereNotNull('service_id')->where('service_id', $service->id)->get();
        }

        return response()->json([
            'services' => $data,
            'appartmentTypes' => $appartmentTypes,
            'serviceSubCategoryAppartmentPrice' => $serviceSubCategoryAppartmentPrice,
            'notes' => $notes
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

        foreach ($request->subServicePricing as $r) {
            $data = ServiceSubCategoryAppartmentPrice::find($r['id']);
            $data->base_price = $r['base_price'];
            $data->price_tag = $r['price_tag'] ?? "";
            $data->save();
        }
        return response()->json([], 200);
    }
}
