<?php

namespace App\Http\Controllers;

use App\Models\ClientrefinishSpec;
use App\Models\RefinishColor;
use App\Models\MultispectColor;
use App\Models\ClientProperties;
use Illuminate\Http\Request;

class RefinishSpecController extends Controller
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

        $data['refinish_colors']         = RefinishColor::get();
        $data['multispect_colors']         = MultispectColor::get();

        return response()->json([
            'refinishValues' => $data,
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
        $deleteoldRecord = ClientrefinishSpec::where('property_id',$request->property_id)->delete();

        $data                        = new ClientrefinishSpec();
        $data->property_id           = $request->property_id;
        $data->paint_color_id        = $request->refinish_color_id;
        $data->multispect_color_id   = $request->multispect_color_id;
        $data->save();

        return response()->json([
        ],200);
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
        $data = ClientrefinishSpec::with('property', 'paintColor', 'multiSpectColor')->where('property_id',$id)->get();
        $property = ClientProperties::with('client')->find($id);
        return response()->json([
            'ClientRefinishSpec' => $data,
            'property' => $property,
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
