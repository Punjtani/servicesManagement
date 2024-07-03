<?php

namespace App\Http\Controllers;

use App\Models\PaintColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaintColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = PaintColor::
        Where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('id', 'LIKE', "%{$request->search}%")
            ->orWhere('hex_value', 'LIKE', "%{$request->search}%")
            ->orderby($request->sort_by,$request->sort_order)->paginate($request->size);

        return response()->json([
            'paint_colors' => $data,
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
            'name' => 'required|unique:paint_colors,name',
        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Paint Color already exist",
            ], 200);
        }
        $data = new PaintColor();
        $data->name = $request->name;
        $data->hex_value = $request->hex_value;
        $data->save();

        return response()->json([
            'msg' => "Paint Color has been added successfully",
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
        $data = PaintColor::find($id);
        return response()->json([
            'paint_color' => $data,
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
        $data = PaintColor::find($id);
        return response()->json([
            'paint_color' => $data,
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
            'name' => 'required|unique:paint_colors,name,'.$id.',id',

        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Paint Color already exist",
            ], 200);
        }

        $data = PaintColor::find($id);
        $data->name = $request->name;
        $data->hex_value = $request->hex_value;
        $data->save();
        return response()->json([
            'msg' => "Paint Color has been updated successfully",
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
        $data = PaintColor::find($id);
        try{
            $data->delete();
            return response()->json([
                'msg' => "Record has been deleted",
            ],200);
        }catch(\Illuminate\Database\QueryException $e){
            if ($e->getCode() == 23000){
                return response()->json([
                    'error' => "Paint Color cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }

}
