<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeCrew;
use App\Models\Employe;
use App\Models\Crew;
use Auth;

class EmployeeCrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     $data = Crew::orWhereHas('employee_crew.employe.user', function($q) use ($request){
    //         $q->Where('first_name', 'LIKE', "%{$request->search}%")
    //             ->orWhere('middle_name', 'LIKE', "%{$request->search}%")
    //             ->orWhere('last_name', 'LIKE', "%{$request->search}%");
    //         })
    //         ->orWhereHas('department', function($q) use ($request){
    //             $q->Where('name', 'LIKE', "%{$request->search}%");
    //         })
    //         ->orderby('id','desc')
    //         ->paginate($request->size);

    //     return response()->json([
    //         'result' => $data,
    //     ],200);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     return response()->json([
    //         'crews' => Crew::all(),
    //         'employees' => Employe::with('user')->get()
    //     ]);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {


    //     $request->validate([
    //         'crew_id' => 'required',
    //         'employee_id' => 'required',
    //         'percentage' => 'required',
    //         'is_lead' => 'required'
    //     ]);

    //     $exists = EmployeCrew::where([
    //         'crew_id' => $request->crew_id,
    //         'employee_id' => $request->employee_id
    //     ])->first();
    //     if($exists) {
    //         return response()->json(['error' => 'Member already added to the crew'], 400);
    //     }

    //     if($request->is_lead==1){
    //         $record = EmployeCrew::where('crew_id',$request->crew_id)->where('is_lead',1)->first();
    //         if($record){
    //             $record->is_lead=0;
    //             $record->save();
    //         }
    //     }

    //     $employee_crew = EmployeCrew::create([
    //         'crew_id' => $request->crew_id,
    //         'employee_id' => $request->employee_id,
    //         'percentage' => $request->percentage,
    //         'is_lead' => $request->is_lead,
    //         'created_by' => Auth::user()->id
    //     ]);

    //     return response()->json(['id' => $employee_crew->id], 200);
    // }

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
    // public function edit($id)
    // {
    //     return response()->json(['data' => Crew::with('employee_crew.employe.user')->where('id', $id)->first() ]);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request)
    // {
    //     foreach($request->data as $row)
    //     {
    //         EmployeCrew::where('id', $row['id'])->update([
    //             'crew_id' => $row['crew_id'],
    //             'employee_id' => $row['employee_id'],
    //             'percentage' => $row['percentage'],
    //             'is_lead' => $row['is_lead'],
    //             'updated_by' => Auth::user()->id
    //         ]);
    //     }

    //     return response()->json([], 200);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     EmployeCrew::where('id', $id)->delete();

    //     return response()->json([], 200);
    // }
}
