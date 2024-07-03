<?php

namespace App\Http\Controllers;

use App\Models\PaintColor;
use App\Models\PaintFinishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PaintFinishingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = PaintFinishing::
        Where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('id', 'LIKE', "%{$request->search}%")
            ->orderby($request->sort_by,$request->sort_order)->paginate($request->size);

        return response()->json([
            'paint_finishings' => $data,
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
            'name' => 'required|unique:paint_finishings,name',
        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Paint Finishing already exist",
            ], 200);
        }
        $data = new PaintFinishing();
        $data->name = $request->name;
        $data->save();

        return response()->json([
            'msg' => "Paint Finishing has been added successfully",
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
        $data = PaintFinishing::find($id);
        return response()->json([
            'paint_finishing' => $data,
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
        $data = PaintFinishing::find($id);
        return response()->json([
            'paint_finishing' => $data,
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
            'name' => 'required|unique:paint_finishings,name,'.$id.',id',

        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Paint Finishing already exist",
            ], 200);
        }
        $data = PaintFinishing::find($id);
        $data->name = $request->name;
        $data->save();
        return response()->json([
            'msg' => "Paint Finishing has been updated successfully",
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
        $data = PaintFinishing::find($id);
        try{
            $data->delete();
            return response()->json([
                'msg' => "Record has been deleted",
            ],200);
        }catch(\Illuminate\Database\QueryException $e){
            if ($e->getCode() == 23000){
                return response()->json([
                    'error' => "Paint Finish cannot be deleted due to existence of related data.",
                ], 200);
            }
        }

    }

}
