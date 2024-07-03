<?php

namespace App\Http\Controllers;

use App\Models\ClientpaintSpec;
use App\Models\PaintColor;
use App\Models\PaintFinishing;
use App\Models\PaintGrade;
use App\Models\PaintManufacturer;
use App\Models\PaintMethod;
use App\Models\PaintSurface;
use App\Models\ClientProperties;
use Illuminate\Http\Request;

class PaintSpecController extends Controller
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
        $data['paint_manufacturers']  = PaintManufacturer::get();
        $data['paint_surfaces']       = PaintSurface::with('paint_method')->get();
        $data['paint_colors']         = PaintColor::get();
        $data['paint_finishes']       = PaintFinishing::get();
        $data['paint_grades']         = PaintGrade::get();
        $data['paint_methods']        = PaintMethod::get();

        return response()->json([
            'paintValues' => $data,
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
        $deleteoldRecord = ClientpaintSpec::where('property_id',$request->property_id)->delete();

        $index = 0;
        foreach ($request->paint_surface_id as $item){
            $data                        = new ClientpaintSpec();
            $data->property_id           = $request->property_id;
            $data->paint_provider        = $request->paint_provider;
            $data->paint_manufacturer_id = $request->paint_manufacturer_id[$index];
            $data->paint_surface_id      = $request->paint_surface_id[$index];
            $data->paint_color_id        = $request->paint_color_id[$index];
            $data->paint_finish_id       = $request->paint_finish_id[$index];
            $data->paint_grade_id        = $request->paint_grade_id[$index];
            $data->coats                 = $request->coats[$index];
            $data->paint_method_id       = $request->paint_method_id[$index];
            $data->save();
            $index++;
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
        $data = ClientpaintSpec::with(['paintFinish', 'paintGrade', 'paintSurface', 'paintMethod'
        , 'paintManufacturer', 'paintColor','property'])->where('property_id',$id)->get();
        $property = ClientProperties::with('client')->find($id);
        return response()->json([
            'clientPaintSpec' => $data,
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
