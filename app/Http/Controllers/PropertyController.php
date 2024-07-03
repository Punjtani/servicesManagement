<?php

namespace App\Http\Controllers;

use App\Models\ClientProperties;
use App\Models\TaxType;
use App\Models\AppartmentType;
use App\Models\ServiceSubCategory;
use App\Models\ClientPricing;
use App\Models\ServiceSubCategoryAppartmentPrice;
use App\Models\ClientpaintSpec;
use App\Models\ClientrefinishSpec;
use App\Models\QuoteAttatchment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = TaxType::where('isActive', 1)->get();
        $appartments = AppartmentType::where('isActive', 1)->orderBy('sort', 'asc')->get();

        return response()->json([
            'tax_types' => $data,
            'appartment_types' => $appartments
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
        $validator = \Validator::make($request->all(), [
            // 'title' => 'required|unique:client_properties,title',
            //            'invoice_submission_email' => 'unique:client_properties,invoice_submission_email|nullable',
            'client_id' => 'required',
            'title' => [
                'required',
                Rule::unique('client_properties')->where(function ($query) use ($request) {
                    return $query->where('client_id', $request->client_id)->whereNull('deleted_at');
                })
            ],
        ]);

        if ($validator->fails()) {
            $responseArr['message'] = $validator->errors();;
            $responseArr['token'] = '';
            return response()->json($responseArr, Response::HTTP_CONFLICT);
        }

        $data               = new ClientProperties();
        $data->invoice_submission_email    = $request->invoice_submission_email;
        $data->is_PO_required        = $request->is_po_required;
        $data->client_id    = $request->client_id;
        $data->title        = $request->title;
        $data->street1      = $request->street1;
        $data->street2      = $request->street2;
        $data->city         = $request->city;
        $data->state        = $request->data_state;
        $data->zipcode      = $request->zipcode;
        $data->country      = $request->country;
        $data->tax_type_id  = $request->data_tax_type_id;
        $data->tax_rate     = $request->tax_rate;
        $data->suppliers    = $request->suppliers;
        $data->billing_address      = $request->billing_address;
        $data->billing_address_2    = $request->billing_address_2;
        $data->billing_city         = $request->billing_city;
        $data->billing_state        = $request->data_billing_state;
        $data->billing_zipcode      = $request->billing_zipcode;
        $data->billing_country      = $request->billing_country;
        $data->is_same_address      = $request->is_same_address;
        $data->phone      = $request->phone;
        // $data->manager      = $request->manager;

        if ($request->is_quote) {
            $data->is_quote = $request->is_quote;
            $data->save();
            if (isset($request->filePaths) && !empty($request->filePaths)) {
                $data->QuoteAttatchment()->delete();
                foreach ($request->filePaths as $image) {
                    $im = new QuoteAttatchment();
                    $im->client_properties_id = $data->id;
                    $im->file_name               = $image;
                    $im->save();
                }
            }
        } else {
            $data->save();
        }

        $apartments = AppartmentType::find($request->appartment_types);
        $data->appartment_types()->sync($apartments);

        $id = $data->id;
        $subServices = ServiceSubCategory::get();
        $appartmentTypes = AppartmentType::where('isActive', 1)->whereHas('client_properties', function ($q) use ($id) {
            $q->Where('client_properties_id', $id);
        })->get();

        foreach ($appartmentTypes as $appartmentType) {
            foreach ($subServices as $subService) {
                $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id', $subService->id)
                    ->where('appartment_type_id', $appartmentType->id)
                    ->first();
                if ($subService->is_independent == 0) {
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
        foreach ($subServices as $subService) {
            $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id', $subService->id)
                ->where('appartment_type_id', null)
                ->first();
            if ($subService->is_independent == 1) {

                $clientPricing = new ClientPricing();
                $clientPricing->property_id  = $id;
                $clientPricing->service_type_id  = $subService->parent_category_id;
                $clientPricing->sub_service_id   = $subService->id;
                $clientPricing->apartment_type_id  = null;
                $clientPricing->price  = $servicePricing->base_price;
                $clientPricing->save();
            }
        }
        return response()->json([], 200);
    }

    public function clientQuoteApprove($id)
    {
        $data = ClientProperties::find($id);
        $data->is_quote = 0;
        $data->save();

        return response()->json([
            'msg' => "Record has been updated",
        ], 200);
    }

    public function storeStaff(Request $request)
    {
        $property = ClientProperties::find($request->property);
        if ($property) {
            $property->staff_id = $request->selected_manager;
            $property->save();
        }
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
        $data = ClientProperties::with("appartment_types", "taxType", "client:id,company")->with("quoteAttatchment")->find($id);
        if (!$data->tax_rate || $data->tax_rate == 0) {
            $type = TaxType::find($data->tax_type_id);
            if ($type) {
                $data->tax_rate = $type->rate;
                $data->save();
            }
        }
        return response()->json([
            'property' => $data,
        ], 200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function billing(Request $request, $id)
    {
        $data = ClientProperties::find($id);
        // $data->is_PO_required        = $request->po_number;
        $data->is_separate_billing   = $request->separate_billing;
        $data->net_payment_term      = $request->net_payment_terms;
        $data->max_invoice_amount    = $request->max_invoice_amount;
        $data->save();

        return response()->json([
            'msg' => "Record has been updated",
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
        //        if (!empty($request->invoice_submission_email)){
        //            $check = ClientProperties::where('id','!=',$id)->where('invoice_submission_email',$request->invoice_submission_email)->first();
        //            if ($check){
        //                return response()->json([
        //                    'code' => 302,
        //                    'msg' => "This Email has already been taken !",
        //                ],200);
        //            }
        //        }
        if (!empty($request->title)) {
            $checkTitle = ClientProperties::whereNull('deleted_at')->where('id', '!=', $id)->where('client_id', $request->client['id'])->where('title', $request->title)->first();
            if ($checkTitle) {
                return response()->json([
                    'code' => 302,
                    'msg' => "Property Name already exist!",
                ], 200);
            }
        }

        $data = ClientProperties::find($id);
        //        $data->client_id    = $request->client_id;

        $data->invoice_submission_email        = $request->invoice_submission_email ?? NULL;
        $data->is_PO_required        = $request->is_po_required;
        $data->title        = $request->title;
        $data->street1      = $request->street1;
        $data->street2      = $request->street2;
        $data->city         = $request->city;
        $data->state        = $request->data_state;
        $data->zipcode      = $request->zipcode;
        $data->country      = $request->country;
        $data->tax_type_id  = $request->data_tax_type_id;
        $data->tax_rate     = $request->tax_rate;
        $data->suppliers    = $request->suppliers;
        $data->billing_address      = $request->billing_address;
        $data->billing_address_2    = $request->billing_address_2;
        $data->billing_city         = $request->billing_city;
        $data->billing_state        = $request->data_billing_state;
        $data->billing_zipcode      = $request->billing_zipcode;
        $data->billing_country      = $request->billing_country;
        $data->is_same_address      = $request->is_same_address;
        $data->phone      = $request->phone;
        // $data->manager    = $request->manager;

        if ($request->is_quote) {
            $data->is_quote = $request->is_quote;
            if (isset($request->filePaths) && !empty($request->filePaths)) {
                $data->QuoteAttatchment()->delete();
                foreach ($request->filePaths as $image) {
                    $im = new QuoteAttatchment();
                    $im->client_properties_id = $data->id;
                    $im->file_name               = $image;
                    $im->save();
                }
            }
        }
        $data->save();
        // $data->appartment_types()->attach($apartments);
        $apartments = AppartmentType::find($request->appartment_types);
        $data->appartment_types()->sync($apartments);

        $id = $data->id;
        ClientPricing::where('property_id', $id)->delete();
        $subServices = ServiceSubCategory::get();
        $appartmentTypes = AppartmentType::where('isActive', 1)->whereHas('client_properties', function ($q) use ($id) {
            $q->Where('client_properties_id', $id);
        })->get();

        foreach ($appartmentTypes as $appartmentType) {
            foreach ($subServices as $subService) {
                $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id', $subService->id)
                    ->where('appartment_type_id', $appartmentType->id)
                    ->first();
                if ($subService->is_independent == 0) {
                    $clientPricing = new ClientPricing();
                    $clientPricing->property_id  = $id;
                    $clientPricing->service_type_id  = $subService->parent_category_id;
                    $clientPricing->sub_service_id   = $subService->id;
                    $clientPricing->apartment_type_id  = $appartmentType->id;
                    $clientPricing->price  = $servicePricing['base_price'];
                    $clientPricing->save();
                }
            }
        }
        foreach ($subServices as $subService) {
            $servicePricing = ServiceSubCategoryAppartmentPrice::where('sub_service_id', $subService->id)
                ->where('appartment_type_id', null)
                ->first();
            if ($subService->is_independent == 1) {

                $clientPricing = new ClientPricing();
                $clientPricing->property_id  = $id;
                $clientPricing->service_type_id  = $subService->parent_category_id;
                $clientPricing->sub_service_id   = $subService->id;
                $clientPricing->apartment_type_id  = null;
                $clientPricing->price  = $servicePricing['base_price'];
                $clientPricing->save();
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
        $data = ClientProperties::find($id);
        try {
            // $data->isActive = 0;
            // $data->save();
            $data->delete();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Property cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
