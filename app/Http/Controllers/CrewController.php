<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use App\Models\Department;
use App\Models\EmployeCrew;
use Illuminate\Http\Request;
use App\Models\RequestedJobService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->employee) {
            $query = Crew::whereHas('employe', function ($q) use ($user) {
                $q->where('id', $user->employee->id);
            });
        } else {
            $query = Crew::query();
        }
        $query = $query->with(['employe.user', 'department'])
            ->whereHas('department', function ($q) use ($request) {
                $q->where('isActive', 1);
                if ($request->department_filter) {
                    $q = $q->where('id', $request->department_filter);
                }
            });
        //$query = $query->orderby($request->sort_by,$request->sort_order)->with(['employe.user','department'])
        $query = $query->with(['employe.user', 'department'])
            ->whereHas('department', function ($q) {
                $q->where('isActive', 1);
            });

        if ($request->search) {
            $query = $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhereHas('employe.user', function ($q) use ($request) {
                    $q->Where('first_name', 'LIKE', "%{$request->search}%")
                        ->orWhere('middle_name', 'LIKE', "%{$request->search}%")
                        ->orWhere('last_name', 'LIKE', "%{$request->search}%");
                })
                ->orWhereHas('department', function ($q) use ($request) {
                    $q->Where('name', 'LIKE', "%{$request->search}%");
                });
        }

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "name") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "division") {
            $query = $query->orderByJoin('department.name', $request->sort_order);
        } else if ($request->sort_by == "members") {
            //$query = $query->orderByJoin('employe.user.first_name', $request->sort_order);
            $query = $query->leftJoin('employee_crews', 'employee_crews.crew_id', '=', 'crews.id')->select('employee_crews.*')
                ->leftJoin('users', 'users.id', '=', 'employee_crews.employee_id')->select('users.*')
                ->orderBy('users.first_name', $request->sort_order);
        } else {
            $query = $query->orderBy("id", "DESC");
        }

        $data = $query->paginate($request->size);
        return response()->json([
            'crews' => $data,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Department::where('isActive', 1)->with('employee', 'employee.user')->get();

        return response()->json([
            'departments' => $data,
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
        $data = new Crew();
        $data->name = $request->name;
        $data->department_id = $request->department_id;
        if (isset($request->crew_activated)) {
            $data->crew_activated = $request->crew_activated;
        } else {
            $data->crew_activated = 1;
        }
        $data->save();
        foreach ($request->employee_list as $employee) {
            $member =  new EmployeCrew();
            $member->employee_id = $employee['employee_id'];
            $member->crew_id = $data->id;
            $member->percentage = $employee['percentage'];
            $member->is_lead = $employee['lead'];
            $member->save();
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
        $data = Crew::with('employe.user')->find($id);
        return response()->json([
            'crew' => $data,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Crew::with("employe")->find($id);
        return response()->json([
            'crew' => $data,
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
        $data = Crew::find($id);
        $data->name = $request->name;
        $data->department_id = $request->department_id;
        $data->crew_activated = $request->crew_activated;
        $data->save();

        //get all member of this crew
        $crewMember = EmployeCrew::where('crew_id', $data->id)->get();

        // updated coming list of members
        foreach ($request->employee_list as $employee) {
            if (!empty($employee['id'])) {
                $member = EmployeCrew::find($employee['id']);
                $member->employee_id = $employee['employee_id'];
                $member->percentage = $employee['percentage'];
                $member->is_lead = $employee['lead'];
                $member->save();

                foreach ($crewMember as $key => $item) {
                    if ($item->id == $member->id) {
                        unset($crewMember[$key]);
                    }
                }
            } else {
                $member =  new EmployeCrew();
                $member->employee_id = $employee['employee_id'];
                $member->crew_id = $data->id;
                $member->percentage = $employee['percentage'];
                $member->is_lead = $employee['lead'];
                $member->save();
            }
        }
        // till here
        if ($crewMember->count() > 0) {
            foreach ($crewMember as $crewMem) {
                $crewMem->delete();
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
        $data = Crew::find($id);
        try {
            RequestedJobService::where('assigned_to_type', 'crew')->where('assigned_to_id', $data->id)
                ->update(['assigned_to_type' => null, 'assigned_to_id' => null]);
            $data->is_active = 0;
            $data->save();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => "Crew cannot be deleted due to existence of related data.",
                ], 200);
            }
        }
    }
}
