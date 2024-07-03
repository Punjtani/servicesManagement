<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\PriceIncrement;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\ClientProperties;

class PriceIncrementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $priceIncrement = \App\Models\PriceIncrement::where('property_id',$id)
        ->with('service')
        // ->with('client')
        ->where(function ($q) use ($request){
            $q->Where('year', 'LIKE', "%{$request->search}%");
            $q->orWhere('percentage', 'LIKE', "%{$request->search}%");
        })        
        // ->orderby('sort','asc')
        ->orderby('id','desc')
        ->paginate($request->size);

        return response()->json([
            'price_increments' => $priceIncrement,
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
        $exists = PriceIncrement::where(['year' => $request->year, 'service_id' => $request->service_id,'property_id' => $request->property_id])->first();
        if(!$exists){
            $priceIncrement = new PriceIncrement();
            $priceIncrement->year = $request->year;
            $priceIncrement->percentage = $request->percentage;
            $priceIncrement->service_id = $request->service_id;
            // $priceIncrement->client_id = $request->client_id;
            $priceIncrement->property_id = $request->property_id;
            $priceIncrement->save();    
            return response()->json([
            ],200);
        }else{
            return response()->json([
                'error' => "Price rule with this service and year already present!",
            ], 200);
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
        $priceIncrement = \App\Models\PriceIncrement::find($id);
        return response()->json([
            'priceIncrement' => $priceIncrement,

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
        $priceIncrement = \App\Models\PriceIncrement::find($id);
        return response()->json([
            'priceIncrement' => $priceIncrement,

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
        $exists = PriceIncrement::where(['year' => $request->year, 'service_id' => $request->service_id,'property_id' => $request->property_id])->where('id','!=',$request->id)->first();
        if(!$exists){
            $priceIncrement = PriceIncrement::find($id);
            if(isset($request->year)){
                $priceIncrement->year =  $request->year;
            }
            if(isset($request->percentage)){
                $priceIncrement->percentage = $request->percentage;
            }
            $priceIncrement->service_id = $request->service_id;
            // $priceIncrement->client_id = $request->client_id;
            $priceIncrement->property_id = $request->property_id;
            $priceIncrement->save();
            return response()->json([
                'msg' => "Record has been updated",
            ],200);
        }else{
            return response()->json([
                'error' => "Price rule with this service and year already present!",
            ], 200);            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $priceIncrement = PriceIncrement::find($id);
        try{
            $priceIncrement->delete();
            return response()->json([
                'msg' => "Record has been deleted",
            ],200);
        }catch(\Illuminate\Database\QueryException $e){
            if ($e->getCode() == 23000)            {
                return response()->json([
                    'error' => "Price Increment cannot be deleted due to existence of related data.",
                ], 200);

            }
        }
        
        
    }
    public function getServices(){
        $services = Service::orderby('sequence_order')->get();
        $clients = Client::orderby('company')->get();
        return response()->json([
            'services' => $services,
            'clients' => $clients,
        ], 200);
    }
    public function getPropertyInfo($id){
        $property = ClientProperties::find($id);
        return response()->json([
            'property' => $property,
        ], 200);
    }

}
