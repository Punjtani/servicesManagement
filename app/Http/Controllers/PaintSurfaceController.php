<?php

namespace App\Http\Controllers;

use App\Models\PaintSurface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PaintSurfaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = PaintSurface::with('paint_method')
            ->Where('paint_surfaces.name', 'LIKE', "%{$request->search}%")
            ->orWhere('paint_surfaces.id', 'LIKE', "%{$request->search}%");
        if ($request->sort_by == "paint_method")
            $data = $data->orderbyJoin("paint_method.name", $request->sort_order);
        else
            $data = $data->orderby($request->sort_by,$request->sort_order);


        $data = $data->paginate($request->size);

        return response()->json([
            'paint_surfaces' => $data,
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
        $validator = Validator::make($request->all(), [
            // Add the unique rule for the name and hex_value columns of the paint_colors table
            'name' => 'required|unique:paint_surfaces,name',
        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Paint Surface already exist",
            ], 200);
        }
        $data = new PaintSurface();
        $data->name = $request->name;
        $data->paint_method_id = $request->paint_method;
        $data->save();

        return response()->json([
            'msg' => "Paint Surface has been added successfully",
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
        $data = PaintSurface::find($id);
        return response()->json([
            'paint_surface' => $data,
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
        $data = PaintSurface::find($id);
        return response()->json([
            'paint_surface' => $data,
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
        $validator = Validator::make($request->all(), [
            // Add the unique rule for the name and hex_value columns of the paint_colors table
            'name' => 'required|unique:paint_surfaces,name,'.$id.',id',

        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Paint Surface already exist",
            ], 200);
        }
        $data = PaintSurface::find($id);
        $data->name = $request->name;
        $data->paint_method_id = $request->paint_method;
        $data->save();
        return response()->json([
            'msg' => "Paint Surface has been updated successfully",
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
        $data = PaintSurface::find($id);
        try{
            $data->delete();
            return response()->json([
                'msg' => "Record has been deleted",
            ],200);
        }catch(\Illuminate\Database\QueryException $e){
            if ($e->getCode() == 23000){
                return response()->json([
                    'error' => "Paint Surface cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
