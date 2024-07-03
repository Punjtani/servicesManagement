<?php

namespace App\Http\Controllers;

use App\Models\PaintMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PaintMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = PaintMethod::
        Where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('id', 'LIKE', "%{$request->search}%")
            ->orderby($request->sort_by == "paint_method" ? "name" : $request->sort_by  ,$request->sort_order)->paginate($request->size);
        ;
        return response()->json([
            'paint_methods' => $data,
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
            'name' => 'required|unique:paint_methods,name',
        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Paint Method already exist",
            ], 200);
        }
        $data = new PaintMethod();
        $data->name = $request->name;
        $data->save();

        return response()->json([
            'msg' => "Paint Method has been added successfully",
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
        $data = PaintMethod::find($id);
        return response()->json([
            'paint_method' => $data,
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
        $data = PaintMethod::find($id);
        return response()->json([
            'paint_method' => $data,
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
            'name' => 'required|unique:paint_methods,name,'.$id.',id',

        ]);

        // If the validation fails, return a JSON response with the errors
        if ($validator->fails()) {
            return response()->json([
                'error' => "Paint Method already exist",
            ], 200);
        }
        $data = PaintMethod::find($id);
        $data->name = $request->name;
        $data->save();
        return response()->json([
            'msg' => "Paint Method has been updated successfully",
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
        $data = PaintMethod::find($id);
        try{
            $data->delete();
            return response()->json([
                'msg' => "Record has been deleted",
            ],200);
        }catch(\Illuminate\Database\QueryException $e){
            if ($e->getCode() == 23000){
                return response()->json([
                    'error' => "Paint Method cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }

}
