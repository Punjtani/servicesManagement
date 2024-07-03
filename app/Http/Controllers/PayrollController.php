<?php

namespace App\Http\Controllers;

use App\Models\Deposits;
use App\Models\Invoice;
use App\Models\Employe;
use App\Models\Job;
use App\Models\RequestedJobService;
use App\Models\EmployeCrew;
use App\Models\Service;
use App\Models\ServiceSubCategory;
use App\Models\AppartmentType;
use App\Models\DepartmentPayrollPrice;
use App\Models\Department;
use App\Models\ManagementExtraAndPayroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\SendInvoiceEmailEvent;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employee = Employe::with('department')->with('user')->find($request->id);
        $subService =  (int) $request->service_sub_category;
        $payrollPrices = DepartmentPayrollPrice::where('department_id', $employee->department->id)->get();
        $crewId = [];
        $myCrews = EmployeCrew::where('employee_id', $request->id)->get();
        $i = 0;
        foreach ($myCrews as $crew) {
            $crewId[$i] = $crew['crew_id'];
            $i++;
        }

        $serviceId = [];
        $services = Service::Where('department_id' , $employee->department->id)->get();

        $i = 0;
        foreach ($services as $service) {
            $serviceId[$i] = $service['id'];
            $i++;
        }


        $jobs = Job::
        with('property')
        ->with('apartment_type')
        ->with('requestedJobService.service')
        ->with('requestedJobService.requestedJobSubService.subService')
        ->when($subService, function ($query) use ($subService){
            $query->whereHas('requestedJobService.requestedJobSubService.subService', function($query) use ($subService){
                $query->where('sub_service_id',$subService);
            });
        })
        ->with('requestedJobService.jobProgress')
        ->where('job_status','completed')
        ->where('job_cancel', 0)
        // ->whereHas('apartment_type',function($q){
        //         $q->where('isActive', 1);
        //     })
        ->whereHas('invoice')
        ->whereHas('requestedJobService', function ($query) use ($request, $employee, $crewId, $serviceId) {
            $query->whereIn('service_id', $serviceId);
            $query->where('assigned_to_type', 'individual');
            $query->where('assigned_to_id', $employee->id);
            // if($request->start_date){
            //     $query->whereDate('end_date','>=', $request->start_date);
            // }
            // if($request->end_date){
            //     $query->whereDate('end_date','<=', $request->end_date);
            // }
            if($request->dateRange){
                $period = explode(',',$request->dateRange);
                $query->whereBetween('end_date', $period);
            }

            $query->orWhere(function ($query) use ($request, $crewId, $serviceId) {
                $query->whereIn('service_id', $serviceId);
                $query->where('assigned_to_type', 'crew');
                $query->whereIn('assigned_to_id', $crewId);
            //     if($request->start_date){

            //     $query->whereDate('end_date','>=', $request->start_date);
            // }
            // if($request->end_date){

            //     $query->whereDate('end_date','<=',  $request->end_date);
            // }


            });
                    // $query->orderBy('end_date');

        })
        ->with(['requestedJobService'=> function ($query) use ($request, $employee, $crewId, $serviceId) {
            $query->whereIn('service_id', $serviceId);
            $query->where('assigned_to_type', 'individual');
            $query->where('assigned_to_id', $employee->id);
            // if($request->start_date){
            //     $query->whereDate('end_date','>=', $request->start_date);
            // }
            // if($request->end_date){
            //     $query->whereDate('end_date','<=', $request->end_date);
            // }
            if($request->dateRange){
                $period = explode(',',$request->dateRange);
                $query->whereBetween('end_date', $period);
            }

            $query->orWhere(function ($query) use ($request, $crewId, $serviceId) {
                $query->whereIn('service_id', $serviceId);
                $query->where('assigned_to_type', 'crew');
                $query->whereIn('assigned_to_id', $crewId);
            //     if($request->start_date){

            //     $query->whereDate('end_date','>=', $request->start_date);
            // }
            // if($request->end_date){

            //     $query->whereDate('end_date','<=',  $request->end_date);
            // }
            if($request->dateRange){
                $period = explode(',',$request->dateRange);
                $query->whereBetween('end_date', $period);
            }


            });
                    // $query->orderBy('end_date');

        }])
        ->with(['managementExtraAndPayroll'=> function ($query) use ($employee) {
            $query->where('employee_id', $employee->id);
        }
            ])
        ->get();
        return response()->json([
            'employee' => $employee,
            'employee_crew'=>$myCrews,
            'payroll_prices'=> $payrollPrices,
            'jobs'=>$jobs
        ], 200);
    }

            /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDepartmentPayrollPrice($id)
    {
        $department = Department::find($id);
//        $service = Service::with('subServices')->where('category' , 'LIKE', "%{$department->name}%")->first();
        $service = Service::with('subServices')->where('id' , $department->service_id)->first();
       if (!$service){
           return response()->json([
               'error' => "Service category not found",
           ],200);
       }
        $subServices = ServiceSubCategory::where('parent_category_id', $service->id)
        ->get();
        $appartmentTypes = AppartmentType::where('isActive', 1)->get();
        foreach ($appartmentTypes as $appartmentType){

            foreach ($subServices as $subService){
                if($subService->is_independent == 0){
                    $servicePricing = DepartmentPayrollPrice::
                    where('department_id', $id)
                    ->where('sub_service_id',$subService->id)
                   ->where('appartment_type_id',$appartmentType->id)
                   ->first();
                    if(!$servicePricing){
                        $servicePricing = new DepartmentPayrollPrice();
                        $servicePricing->department_id = $id;
                        $servicePricing->sub_service_id   = $subService->id;
                        $servicePricing->appartment_type_id  = $appartmentType->id;
                        $servicePricing->payroll_price  = 0;
                        $servicePricing->save();
                    }

                }
           }

        }
        foreach ($subServices as $subService){
            if($subService->is_independent == 1){
                $servicePricing = DepartmentPayrollPrice::where('department_id', $id)
                ->where('sub_service_id',$subService->id)
                ->where('appartment_type_id',null)
                ->first();
                if(!$servicePricing){
                    $servicePricing = new DepartmentPayrollPrice();
                    $servicePricing->sub_service_id   = $subService->id;
                    $servicePricing->department_id  = $id;
                    $servicePricing->payroll_price  = 0;
                    $servicePricing->save();
                }

            }
        }
        $data = $service;
        $appartmentTypes = AppartmentType::where('isActive', 1)->get();
        $serviceSubCategoryAppartmentPrice = DepartmentPayrollPrice::get();
        return response()->json([
            'services' => $data,
            'appartmentTypes'=> $appartmentTypes,
            'serviceSubCategoryAppartmentPrice' => $serviceSubCategoryAppartmentPrice
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
        foreach ($request->payrollPricing as $r){
            $data = DepartmentPayrollPrice::find($r['id']);
            $data->payroll_price = $r['payroll_price'];
            $data->save();
        }
        return response()->json([
        ],200);
    }

      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addManagementExtra(Request $request)
    {
        $data = ManagementExtraAndPayroll::where('job_id', $request->job_id)
        ->where('employee_id', $request->employee_id)->first();
        if($data){
            $data->amount = $request->amount;
            $data->save();
        }else{
            $item = new ManagementExtraAndPayroll();
            $item->job_id = $request->job_id;
            $item->employee_id = $request->employee_id;
            $item->amount = $request->amount;
            $item->save();
        }
    }

          /**
     * get a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getManagementExtra($job_id, $employee_id)
    {
        $data = ManagementExtraAndPayroll::where('job_id', $job_id)
        ->where('employee_id', $employee_id)->get();
        return response()->json([
            'management_extra' => $data,
        ],200);
    }

      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPaidPayroll(Request $request)
    {
        $data = ManagementExtraAndPayroll::where('job_id', $request->job_id)
        ->where('employee_id', $request->employee_id)->first();
        if($data){
            $data->is_paid_payroll = $request->is_paid_payroll;
            $data->paid_payroll_price = $request->paid_payroll_price;
            $data->save();
        }else{
            $item = new ManagementExtraAndPayroll();
            $item->job_id = $request->job_id;
            $item->employee_id = $request->employee_id;
            $item->is_paid_payroll = $request->is_paid_payroll;
            $item->paid_payroll_price = $request->paid_payroll_price;
            $item->save();
        }
    }

    public function getServiceSubCategories(Request $request){
        $employee = Employe::with('department.service')->find($request->id);
        $parentId =  $employee->department->service->id ?? "";
        $data = ServiceSubCategory::where('parent_category_id',$parentId)->orderby('id','desc')->get();
        return response()->json([
            'sub_categories' => $data,
        ],200);
    }
}
