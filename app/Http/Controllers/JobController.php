<?php

namespace App\Http\Controllers;

use App\Mail\SendQuoteEmail;
use App\Models\ClientPricing;
use App\Models\ClientProperties;
use App\Models\Client;
use App\Models\Crew;
use App\Models\Employe;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\JobAttatchment;
use App\Models\JobServiceReadyChecklist;
use App\Models\RequestedJobService;
use App\Models\RequestedJobSubService;
use App\Models\Service;
use App\Models\ServiceJobProgress;
use App\Models\Department;
use App\Models\ServiceReadyChecklist;
use App\Models\AppartmentType;
use App\Models\EmployeCrew;
use App\Models\JobServiceDetails;
use App\Models\PriceIncrement;
use App\Models\User;
use Illuminate\Support\Facades\App;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CreateJobEmailEvent;
use App\Events\ScheduleJobEmailEvent;
use App\Events\AssignJobEmailEvent;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\DashboardController as DC;
use DB;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $withCurrency = false)
    {

        // \DB::connection()->enableQueryLog();

        // $ccEmail = config('mail.admin_address');
        // $emails = config('mail.cc_address');
        // $data = [];
        // Mail::send('email_template/new_job',compact('data'), function($mailData) use ($emails,$ccEmail){
        //     $mailData->to($emails);
        //     $mailData->cc($ccEmail);
        //     $mailData->from(config('mail.from_address'));
        //     $mailData->subject('New Job Created!');
        // });
        // dd("jd");
        $user = Auth::user();
        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
                ->where('isActive', 1)->select('id')->get();
            $query = Job::where('job_cancel', 0)
                ->where('jobs.is_quote', 0) // added for to get only jobs
                ->whereIn('property_id', $clientProperties);
            // ->whereHas('apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // });
        } else if ($user->staff) {
            //            if($user->staff->staff_roles->is_property_level == true){
            $clientProperties = ClientProperties::whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            })->where('isActive', 1)->select('id')->get();
            //            }else{
            //                $clientId = Client::where('user_id',$user->staff->parent_id)->first('id');
            //                $clientProperties = ClientProperties::where('client_id',$clientId->id)->where('is_quote',0)
            //                    ->where('isActive', 1)->select('id')->get();
            //            }
            // $query = DB::table('jobs')->select('jobs.*', '')->where('job_cancel', 0)->where('jobs.is_quote',0)->whereIn('property_id',$clientProperties)->whereHas('apartment_type',function($q){
            //     $q->where('isActive', 1);
            // });
            $query = Job::where('job_cancel', 0)
                ->where('jobs.is_quote', 0)
                ->whereIn('property_id', $clientProperties);
            // ->whereHas('apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // });
        } else if ($user->role == 3) {
            $empId = Employe::where('user_id', $user->id)->first('id');
            $crewId = [];
            $myCrews = EmployeCrew::where('employee_id', $empId->id)->select('crew_id')->get();
            $i = 0;
            foreach ($myCrews as $crew) {
                $crewId[$i] = $crew['crew_id'];
                $i++;
            }
            $query = Job::whereHas('property', function ($q) {
                $q->where('isActive', 1);
            })->where('jobs.is_quote', 0)
                ->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                })
                ->whereHas('requestedJobService', function ($query) use ($empId, $crewId) {
                    $query->where('assigned_to_type', 'individual');
                    $query->where('assigned_to_id', $empId->id);
                    $query->orWhere(function ($query) use ($crewId) {
                        $query->where('assigned_to_type', 'crew');
                        $query->whereIn('assigned_to_id', $crewId);
                    });
                })
                ->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->with(['requestedJobService.requestedJobSubService' => function ($q) {
                    $q->where('is_delete', 0);
                }])
                ->with(['requestedJobService.service', 'property'])
                ->with(['requestedJobService.requestedJobSubService' => function ($query) {
                    $query->select('requested_job_service_id', DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
                }])
                ->with('requestedJobService', function ($query) use ($empId, $crewId) {
                    $query->where('assigned_to_type', 'individual');
                    $query->where('assigned_to_id', $empId->id);
                    $query->orWhere(function ($query) use ($crewId) {
                        $query->where('assigned_to_type', 'crew');
                        $query->whereIn('assigned_to_id', $crewId);
                    });
                });
        } else {

            $query = Job::where('job_cancel', 0)->where('jobs.is_quote', 0)->where('is_request', 0)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('property', function ($q) {
                    $q->where('isActive', 1);
                });
        }
        if ($withCurrency && $user->role !== 3) {

            $query = $query->with(['requestedJobService.service', 'property'])
                ->with(['requestedJobService.requestedJobSubService' => function ($query) {
                    $query->select('requested_job_service_id', DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
                }]);
        }

        //$query->orderby('property.title','asc')
        $query = $query->with('requestedJobService.service', function ($q) {
            $q->where('isActive', 1);
        })
            ->with('property')
            ->with('property.client.user');

        $query = $query->with('invoice', function ($q) {
            $q->where(['is_cancelled' => 0]);
        });

        if ($request->propertyId) {
            // $propertyIds = explode(',', $request->propertyId);
            $query = $query->where('property_id', $request->propertyId);
        }
        if ($request->serviceId) {
            //    $serviceIds = explode(',', $request->serviceId);
            $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                $q->where('service_id', $request->serviceId);
            });
        }
        if ($request->jobId) {
            $query = $query->where('id', $request->jobId);
        }
        // status filter
        if ($request->status_filter && $request->status_filter !== "all") {
            if ($request->status_filter == "not assigned") {
                $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                    $q->where('assigned_to_type', Null);
                });
            } else if ($request->status_filter == "overdue") {
                $query = $query->where('job_status', '!=', 'completed');
                $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                    $q->where('assigned_to_type', '!=', Null)->whereDate('scheduled_date', '<', Carbon::now());
                });
            } else if ($request->status_filter == "completedNotBilled") {
                $query = $query->with('requestedJobService', function ($q) use ($request) {
                    $q->where('end_date', '!=', Null)->where('billed_date', NULL);
                });
            } else if ($request->status_filter == "request") {
                $query = $query->with('requestedJobService', function ($q) use ($request) {
                    $q->where('is_request', '!=', 0);
                });
            } else if ($request->status_filter == "billed") {
                //                $query = $query->whereHas('requestedJobService', function($q) use ($request){
                //                            $q->where('billed_date',Null);
                //                        });
                $query = $query->where('is_billed', 1);
            } else if ($request->status_filter == "completed billed") {
                //                $query = $query->whereHas('requestedJobService', function($q) use ($request){
                //                            $q->where('billed_date',Null);
                //                        });
                $query = $query->where('job_status', 'completed')->where('is_billed', 1);
            } else if ($request->status_filter == "not ready to bill") {

                $query = $query->where('is_billed', 0);
            } else if ($request->status_filter == "completed not ready to bill") {

                $query = $query->where('job_status', 'completed')->where('is_billed', 0);
            } else if ($request->status_filter == "completed ready to bill") {

                $query = $query->where('job_status', 'completed')->where('is_billed', 2);
            } else if ($request->status_filter == "unscheduled") {

                $query = $query->where('job_status', 'not scheduled');
            } else  if ($request->status_filter == 'today') {
                $query = $query->whereDate('jobs.created_at', Carbon::today())
                    ->with('requestedJobService', function ($q) {
                        $q->where('billed_date', NULL);
                    });
            } else {
                $query = $query->where('job_status', $request->status_filter);
            }
        }

        // data filter
        if ($request->data_filter) {
            if (strpos($request->data_filter, ",")) {
                $dates = explode(',', $request->data_filter);
                $query = $query->whereHas('jobServices', function ($q) use ($dates) {
                    $q->whereBetween('requested_date', $dates);
                });
            } else {
                $date = Carbon::parse($request->data_filter);
                $date     = $date->format('Y-m-d');
                // $date = date('Y-m-d', strtotime($request->data_filter));
                $query = $query->whereHas('jobServices', function ($q) use ($date) {
                    $q->whereDate('requested_date', date($date));
                });
            }
        }
        if ($request->apartment_filter) {
            $query = $query->where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
        }
        if ($request->po_number_filter) {
            $query = $query->where('po_number', 'LIKE', "%{$request->po_number_filter}%");
        }
        // $query = $query->whereMonth('created_at', $request->data_filter);
        // $query = $query->whereHas('apartment_type', function ($q) {
        //     $q->where('isActive', 1);
        // })
        $query = $query->with(['jobServices.requestedJobSubService' => function ($q) {
            $q->where('is_delete', 0);
        }]);

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "apartment_number" || $request->sort_by == "invoice_no" || $request->sort_by == "job_status" || $request->sort_by == "is_billed") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $query = $query->orderByJoin('property.title', $request->sort_order);
        } else if ($request->sort_by == "payment") {
            $query = $query->orderByJoin('invoice.is_paid', $request->sort_order);
        } else if ($request->sort_by == "service_type") {

            // $query = $query->with([
            //     'services' => function($q){
            //          $q->orderBy('category', 'desc');
            //      },
            //     'jobServices.requestedJobSubService.services'
            // ]);

            //$query = $query->orderByJoin('jobServices.requestedJobSubService.services.category', $request->sort_order);

            $query = $query->leftJoin('requested_job_services', 'jobs.id', '=', 'requested_job_services.job_id')->select('requested_job_services.*')
                ->leftJoin('services', 'services.id', '=', 'requested_job_services.service_id')->select('services.*')
                //->leftJoin('client_properties', 'client_properties.id', '=', 'jobs.property_id')->select('client_properties.*')
                ->orderBy('services.category', $request->sort_order);
        } else if ($request->sort_by == "requested_on") {
            $query = $query->orderByJoin('requestedJobService.requested_date', $request->sort_order);
        } else if ($request->sort_by == "completed_on") {
            $query = $query->orderByJoin('requestedJobService.end_date', $request->sort_order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }


        $data = $query->paginate($request->size);

        return response()->json([
            'jobs' => $data,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $services = Service::with(['subServices' => function ($q) {
            $q->where('isActive', 1);
        }])->get();
        $serviceReadyChecklist = ServiceReadyChecklist::get();
        $appartmentTypes = AppartmentType::where('isActive', 1)->orderBy('sort')->get();
        $priceIncrement = PriceIncrement::get();
        if ($user->client) {
            $client_id = $user->client->id;
            $properties = ClientProperties::with(["appartment_types" => function ($q) {
                $q->where('isActive', 1);
            }])->with("client")
                ->where('client_id', $client_id)
                ->where('isActive', 1)
                ->where('is_quote', 0)
                ->get();
            $clients = Client::where('user_id', $user->id)->with("user")->get();
        } else if ($user->staff) {
            //            if($user->staff->staff_roles->is_property_level == true){
            $properties = ClientProperties::with(["appartment_types" => function ($q) {
                $q->where('isActive', 1);
            }])->whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            })
                ->with("client")
                ->where('isActive', 1)
                ->get();
            //            }else{
            //                $client_id = $user->staff->parent_id;
            //                $properties = ClientProperties::with(["appartment_types"=>function($q){
            //                    $q->where('isActive', 1);
            //                }])->with("client")
            //                    ->where('client_id', $client_id)
            //                    ->where('isActive',1)
            //                    ->where('is_quote',0)
            //                    ->get();
            //            }
            $clients = Client::where('user_id', $user->staff->user_id)->with("user")->get();
        } else {
            $clients = Client::with("user")->whereHas('user', function ($q) {
                return $q->where('is_active', 1);
            })->get();
            $properties = ClientProperties::with(["appartment_types" => function ($q) {
                $q->where('isActive', 1);
            }])->with("client")
                ->whereIn('client_id', $clients->pluck('id'))->where('is_quote', 0)
                ->where('isActive', 1)
                ->get();
        }

        return response()->json([
            'services' => $services,
            'serviceReadyChecklist' => $serviceReadyChecklist,
            'appartmentTypes' => $appartmentTypes,
            'properties' => $properties,
            'clients' => $clients,
            'priceIncrement' => $priceIncrement
        ], 200);
    }

    /**
     * properties the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function properties($id)
    {
        $user = Auth::user();
        $properties = ClientProperties::where('client_id', $id)->where('is_quote', 0)
            ->where('isActive', 1)->get();
        if ($user->staff) {
            if ($user->staff->staff_roles->is_property_level == true) {
                $properties = $properties->whereHas('staff', function ($q) use ($user) {
                    $q->where('staff_id', $user->staff->id);
                });
            } else {
                $properties = ClientProperties::where('client_id', $user->staff->parent_id)->where('is_quote', 0)
                    ->where('isActive', 1)->get();
            }
        }

        return response()->json([
            'properties' => $properties,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = new Job();
        $data->property_id = $request->property_id;
        $data->apartment_type_id = $request->apartment_type_id;
        $data->apartment_number = $request->apartment_number;
        $data->apartment_status = $request->apartment_status;
        $data->po_number = $request->po_number;
        $data->is_assigned = '0';
        $data->job_status = 'not scheduled';
        $data->is_quote = $request->type && $request->type == 'quote' ? 1 : 0;
        $data->type = $request->type && $request->type == 'quote' ? $request->type : "job";

        if ($request->quick_action && $request->quick_action == "awaiting_response") {
            $data->quote_status = "awaiting_response";
        }
        if ($request->quick_action && $request->quick_action == "convert_to_job") {
            $data->is_quote = 0;
            $data->quote_status = "converted";
        }

        $data->created_by = Auth::user()->id;
        if (Auth::user()->role == 1 || Auth::user()->role == 4) {
            $data->job_status = 'not scheduled';
        } else {
            $data->job_status = 'requested';
        }
        if (Auth::user()->role == 2) {
            $data->is_request = 1;
            $data->requested_by = Auth::user()->id;
        }
        $data->save();

        // job id
        $jobId = $data->id;
        // save job service ready check list
        foreach ($request->job_service_ready_checklist as $checklist) {

            $jobReadyCheckList = new JobServiceReadyChecklist();
            $jobReadyCheckList->job_id = $jobId;
            $jobReadyCheckList->service_ready_checklist_id = $checklist;
            $jobReadyCheckList->save();
        }
        // any schedule service
        $scheduleService = false;
        // any assigned servie
        $assignService = false;
        // requested job services
        foreach ($request->requested_job_services as $requested_job_service) {

            $job_service = new RequestedJobService();
            $job_service->job_id = $jobId;
            $job_service->service_id = $requested_job_service['service_type_id'];
            if (isset($requested_job_service['requested_date'])) {
                $requested_date = Carbon::parse($requested_job_service['requested_date']);
                $job_service->requested_date = $requested_date->format('Y:m:d');
            }

            // $job_service->requested_date = date('Y:m:d', strtotime($requested_job_service['requested_date']));

            if (!empty($requested_job_service['requested_for'])) {
                if (Auth::user()->role == 2) {
                    $requested_for = Carbon::parse($requested_job_service['requested_for']);
                    $job_service->requested_for = $requested_for->format('Y:m:d');
                }
                // $job_service->requested_for = date('Y:m:d', strtotime($requested_job_service['requested_for']));
            }

            if (Auth::user()->role == 1 || Auth::user()->role == 4) {
                // $job_service->scheduled_date = date('Y:m:d', strtotime($requested_job_service['requested_date']));
                //to assign job
                if (!empty($requested_job_service['assigned_to_id'])) {
                    $assignService = true;
                    $job_service->assigned_to_id = $requested_job_service['assigned_to_id'];
                    $job_service->assigned_to_type = $requested_job_service['assigned_to_type'];
                }
                if (!empty($requested_job_service['is_fixed_price'])) {
                    $job_service->is_fixed_price = $requested_job_service['is_fixed_price'];
                    $job_service->payroll_price = $requested_job_service['payroll_price'];
                }
                // to schedule
                if (!empty($requested_job_service['scheduled_date'])) {
                    $scheduleService = true;
                    $scheduled_date = Carbon::parse($requested_job_service['scheduled_date']);
                    $job_service->scheduled_date = $scheduled_date->format('Y:m:d');
                    // $job_service->scheduled_date     = date('Y:m:d', strtotime($requested_job_service['scheduled_date']));
                    $job_service->scheduled_time = $requested_job_service['scheduled_time'];
                    $job_service->anytime = $requested_job_service['anytime'];
                }
            }

            if (!empty($requested_job_service['description'])) {
                $job_service->description = $requested_job_service['description'];
            }


            $job_service->save();

            if (isset($requested_job_service['filePaths'])) {
                $jobFile = JobAttatchment::where(['job_id' => $jobId, 'rjs_id' => $job_service->id])->delete();
                foreach ($requested_job_service['filePaths'] as $filePath) {
                    $jobFile = new JobAttatchment();
                    $jobFile->job_id = $jobId;
                    $jobFile->rjs_id = $job_service->id;
                    $jobFile->filename = $filePath;
                    $jobFile->save();
                }
            }

            // to save sub services
            $requestedJobSubServiceId = $job_service->id;
            foreach ($requested_job_service['requested_job_sub_services'] as $index => $requested_job_sub_service) {
                $job_sub_service = new RequestedJobSubService();
                $job_sub_service->order = $index;
                $job_sub_service->quantity = isset($requested_job_sub_service['quantity']) && $requested_job_sub_service['quantity'] > 0 ? $requested_job_sub_service['quantity'] : 1;
                $job_sub_service->requested_job_service_id = $requestedJobSubServiceId;
                $job_sub_service->base_price = $requested_job_sub_service['price'];
                $job_sub_service->total_price = $requested_job_sub_service['price'];
                $job_sub_service->sub_service_id = $requested_job_sub_service['sub_service_id'];
                $job_sub_service->description = isset($requested_job_sub_service['description']) ? $requested_job_sub_service['description'] : "";
                $job_sub_service->save();
            }
        }

        $notScheduledCount = RequestedJobService::where('job_id', $jobId)->where('scheduled_date', Null)->count();
        if ($notScheduledCount == 0 && is_array($request->requested_job_services) && count($request->requested_job_services) > 0) {
            $job = Job::find($jobId);
            $job->job_status = 'scheduled';
            $job->save();
        }
        // if(isset($request->filePaths)) {
        //     foreach ($request->filePaths as $filePath) {
        //         $jobFile = new JobAttatchment();
        //         $jobFile->job_id = $jobId;
        //         $jobFile->filename = $filePath;
        //         $jobFile->save();
        //     }
        // }
        $job = Job::with('property')
            ->with('property.staff')
            ->with('property.client.user')
            ->with('jobServices.service')
            ->with('apartment_type')
            // ->whereHas('apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // })
            ->whereHas('jobServices.service', function ($q) {
                $q->where('isActive', 1);
            });

        $data = $job->find($jobId);
        // event(new CreateJobEmailEvent($data));
        // schedule job service email notification
        if ($scheduleService == true) {
            $data = $job
                ->with(['jobServices' => function ($q) {
                    $q->where('scheduled_date', '!=', Null);
                    $q->orderBy('scheduled_date');
                }])->find($jobId);
            event(new ScheduleJobEmailEvent($data));
        }
        // assign job service email notification
        if ($assignService == true) {
            $data = $job->with('jobServices.employee.user')
                ->with('jobServices.employeeCrew.employe.user')
                ->with(['jobServices' => function ($q) {
                    $q->where('assigned_to_id', '!=', Null);
                }])->find($jobId);
            // event(new AssignJobEmailEvent($data));
        }
        if ($request->quick_action && $request->quick_action == "send_email") {
            $this->sendAsEmail($request, $jobId);
        }
        return response()->json([
            'job_id' => $jobId,
        ], 200);
    }

    public function copyQuote($quoteId)
    {
        $quote = Job::with('requestedJobService')
            ->with('requestedJobService.employee.user')
            ->with('requestedJobService.employeeCrew')
            ->with('requestedJobService.requestedJobSubService')
            ->with('requestedJobService.jobAttatchment')
            ->with('ServiceReadyCheckList')
            ->with('requestedJobService.service')
            ->with('property.client.user')
            ->with(['invoice' => function ($q) {
                $q->where(['is_cancelled' => 0]);
            }])
            ->with(['requestedJobService.requestedJobSubService' => function ($q) {
                $q->where('is_delete', 0);
            }])
            ->find($quoteId);

        $data = new Job();
        $data->property_id = $quote->property_id;
        $data->apartment_type_id = $quote->apartment_type_id;
        $data->apartment_number = $quote->apartment_number;
        $data->apartment_status = $quote->apartment_status;
        $data->po_number = $quote->po_number;
        $data->is_assigned = '0';
        $data->job_status = 'not scheduled';
        $data->is_quote = 1;
        $data->type = 'quote';
        $data->created_by = Auth::user()->id;
        if (Auth::user()->role == 1 || Auth::user()->role == 4) {
            $data->job_status = 'not scheduled';
        } else {
            $data->job_status = 'requested';
        }
        if (Auth::user()->role == 2) {
            $data->is_request = 1;
            $data->requested_by = Auth::user()->id;
        }
        $data->save();

        // job id
        $jobId = $data->id;
        // save job service ready check list
        if ($quote->ServiceReadyCheckList) {
            foreach ($quote->ServiceReadyCheckList as $checklist) {
                $oldCheckListData = JobServiceReadyChecklist::find($checklist->id);
                $jobReadyCheckList = new JobServiceReadyChecklist();
                $jobReadyCheckList->job_id = $oldCheckListData->job_id;
                $jobReadyCheckList->service_ready_checklist_id = $oldCheckListData->service_ready_checklist_id;
                $jobReadyCheckList->save();
            }
        }

        // any schedule service
        $scheduleService = false;
        // any assigned servie
        $assignService = false;
        // requested job services
        //        return $quote->requestedJobService;
        foreach ($quote->requestedJobService as $requested_job_service) {
            $oldRequestedService = RequestedJobService::find($requested_job_service->id);

            //            return $oldRequestedService->service_id;
            $job_service = new RequestedJobService();
            $job_service->job_id = $jobId;
            $job_service->service_id = $oldRequestedService->service_id;
            $job_service->requested_date = $oldRequestedService->requested_date;

            if (Auth::user()->role == 1 || Auth::user()->role == 4) {
                if (!empty($oldRequestedService->is_fixed_price)) {
                    $job_service->is_fixed_price = $oldRequestedService->is_fixed_price;
                    $job_service->payroll_price = $oldRequestedService->payroll_price;
                }
            }

            if (!empty($oldRequestedService->description)) {
                $job_service->description = "";
            }

            $job_service->save();

            // to save sub services
            $requestedJobSubServiceId = $job_service->id;
            foreach ($oldRequestedService->requestedJobSubService as $index => $requested_job_sub_service) {
                $oldSubService = RequestedJobSubService::find($requested_job_sub_service->id);
                $job_sub_service = new RequestedJobSubService();
                $job_sub_service->order = $index;
                $job_sub_service->requested_job_service_id = $requestedJobSubServiceId;
                $job_sub_service->base_price = $oldSubService->base_price;
                $job_sub_service->total_price = $oldSubService->total_price;
                $job_sub_service->sub_service_id = $oldSubService->sub_service_id;
                $job_sub_service->description = "";
                $job_sub_service->save();
            }
        }


        return response()->json([
            'job_id' => $jobId,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data = Job::with('jobServices.service')
            ->with('jobServices.requestedJobSubService.subService')
            ->with('property')
            ->with('property.client.user')
            ->with('apartment_type')
            ->with('invoice')
            ->with('jobServiceReadyCheckList.serviceReady')
            // ->whereHas('apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // })
            ->with(['requestedJobService.requestedJobSubService' => function ($q) {
                $q->where('is_delete', 0);
            }])
            ->find($id);

        $data->completedJobs = RequestedJobService::where('job_id', $id)->where('end_date', '!=', null)->count();
        $data->remainingJobs = RequestedJobService::where('job_id', $id)->where('end_date', '=', null)->count();

        // job time sheet
        $totalminutes = 0;
        $serviceids = RequestedJobService::where('job_id', $id)->select('id')->get();

        $timesheet = ServiceJobProgress::with('requestedJobService.requestedJobSubService.subService')
            ->with('requestedJobService.service')
            ->with('requestedJobService.job.property')
            ->with('requestedJobService.job.apartment_type')
            ->whereIn('requested_job_service_id', $serviceids)
            // ->whereHas('requestedJobService.job.apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // })
            ->get();

        foreach ($timesheet as $time) {
            if ($time->end_time) {
                $diff = Carbon::parse($time->end_time)->diffInMinutes(Carbon::parse($time->start_time));
                $totalminutes = $totalminutes + $diff;
                $hoursProgress = intdiv($diff, 60) . ':' . ($diff % 60);
                $time->hours = $hoursProgress;
                $time->in_progress = false;
            } else {
                $time->in_progress = true;
            }
        }
        $hours = intdiv($totalminutes, 60) . ':' . ($totalminutes % 60);

        return response()->json([
            'job' => $data,
            // 'jobParentCategories' => $jobParentCategories,
            'timeRecords' => $timesheet,
            'totalHours' => $hours,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Job::with('requestedJobService')
            ->with('requestedJobService.employee.user')
            ->with('requestedJobService.employeeCrew')
            ->with('requestedJobService.requestedJobSubService')
            ->with('requestedJobService.jobAttatchment')
            ->with('ServiceReadyCheckList')
            ->with('requestedJobService.service')
            ->with('property.client.user')
            ->with('invoice')
            // ->with(['invoice' => function ($q) {
            //     $q->where(['is_cancelled' => 0]);
            // }])
            ->with(['requestedJobService.requestedJobSubService' => function ($q) {
                $q->where('is_delete', 0);
            }])
            ->find($id);

        $data->remainingJobs = RequestedJobService::where('job_id', $id)->where('end_date', '=', null)->count();
        return response()->json([
            'job' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        get_settings();
        $job = Job::find($id);
        $job->property_id = $request->property_id;
        $job->apartment_type_id = $request->apartment_type_id;
        $job->apartment_number = $request->apartment_number;
        $job->apartment_status = $request->apartment_status;
        $job->po_number = $request->po_number;
        if ($request->quick_action && $request->quick_action == "awaiting_response") {
            $job->quote_status = "awaiting_response";
        }
        if ($request->quick_action && $request->quick_action == "convert_to_job") {
            $job->job_status = "not scheduled";
            $job->is_quote = 0;
            $job->quote_status = "converted";
        }
        $job->save();
        $jobId = $job->id;
        $job->ServiceReadyChecklist()->sync($request->job_service_ready_checklist);

        // from here
        $requestedJobService = $job->RequestedJobService()->get();
        $newScheduleServices = [];
        $newAssignedServices = [];

        // loop of coming required services
        // dd($request->requested_job_services[1]);
        foreach ($request->requested_job_services as $requested_job_service) {
            if (!empty($requested_job_service['requested_service_id'])) {
                $job_service = RequestedJobService::find($requested_job_service['requested_service_id']);
                //now to update that record
                if (Auth::user()->role == 2 && isset($requested_job_service['requested_for'])) {

                    $requested_for = Carbon::parse($requested_job_service['requested_for']);
                    $job_service->requested_for = $requested_for->format('Y:m:d');
                }

                // $job_service->requested_for = date('Y:m:d', strtotime($requested_job_service['requested_for']));
                if (!empty($requested_job_service['description'])) {
                    $job_service->description = $requested_job_service['description'];
                }
                if (isset($requested_job_service['filePaths'])) {
                    $jobFile = JobAttatchment::where(['job_id' => $jobId, 'rjs_id' => $job_service->id])->delete();
                    foreach ($requested_job_service['filePaths'] as $filePath) {
                        $jobFile = new JobAttatchment();
                        $jobFile->job_id = $jobId;
                        $jobFile->rjs_id = $job_service->id;
                        $jobFile->filename = $filePath;
                        $jobFile->save();
                    }
                }
                //to assign job
                if ((Auth::user()->role == 1 || Auth::user()->role == 4)) {
                    if (!empty($requested_job_service['assigned_to_id'])) {
                        //for email event
                        if ($job_service->assigned_to_id == null || $job_service->assigned_to_id != $requested_job_service['assigned_to_id']) {
                            array_push($newAssignedServices, $job_service->id);
                        };

                        $job_service->assigned_to_id = $requested_job_service['assigned_to_id'];
                        $job_service->assigned_to_type = $requested_job_service['assigned_to_type'];
                    } else {
                        $job_service->assigned_to_id = NULL;
                        $job_service->assigned_to_type = NULL;
                        $job_service->assigned_to_type = NULL;
                        $job_service->assigned_to_id = NULL;
                        // $job_service->anytime = 0;
                        // $job_service->scheduled_date = NULL;
                        // $job_service->scheduled_time = NULL;
                        // $requested_job_service['scheduled_date'] = NULL;
                    }
                }
                // dd($requested_job_service['scheduled_date']);
                //to schedule job
                if (!empty($requested_job_service['scheduled_date'])) {

                    // for email event
                    if ($job_service->scheduled_date == null || $job_service->scheduled_date != $requested_job_service['scheduled_date']) {
                        array_push($newScheduleServices, $job_service->id);
                    };

                    $scheduled_date = Carbon::parse($requested_job_service['scheduled_date']);
                    //check if shcedule date and time updated

                    $oldValue = $job_service->scheduled_date;
                    $newValue = 'new value';

                    $dateChanged = RequestedJobService::where('scheduled_date', $job_service->scheduled_date)->where('scheduled_date', '<>', $scheduled_date)->exists();
                    $timeChanged = RequestedJobService::where('scheduled_time', $job_service->scheduled_time)->where('scheduled_time', '<>', $requested_job_service['scheduled_time'])->exists();
                    $anytimeChanged = RequestedJobService::where('anytime', $job_service->anytime)->where('anytime', '<>', $requested_job_service['anytime'])->exists();

                    if ($dateChanged || $timeChanged || $anytimeChanged) {
                        $job_service->schedule_update_at = Carbon::now()->toDateTimeString();
                    }

                    $job_service->scheduled_date = $scheduled_date->format('Y:m:d');
                    $job_service->scheduled_time = $requested_job_service['scheduled_time'];
                    $job_service->anytime = $requested_job_service['anytime'];
                }
                if ((Auth::user()->role == 1 || Auth::user()->role == 4)) {
                    $job_service->is_fixed_price = $requested_job_service['is_fixed_price'];
                    $job_service->payroll_price = $requested_job_service['payroll_price'];
                }
                $job_service->save();
                // to save sub services
                $requestedJobSubServiceId = $job_service->id;
                $job_service->RequestedJobSubService()->delete();
                foreach ($requested_job_service['requested_job_sub_services'] as $index => $requested_job_sub_service) {;
                    $job_sub_service = new RequestedJobSubService();
                    $job_sub_service->order = $index;
                    $job_sub_service->requested_job_service_id = $requestedJobSubServiceId;
                    $job_sub_service->base_price = $requested_job_sub_service['price'];
                    $job_sub_service->sub_service_id = $requested_job_sub_service['sub_service_id'];
                    $job_sub_service->description = isset($requested_job_sub_service['description']) ? $requested_job_sub_service['description'] : "";
                    if (isset($requested_job_sub_service['is_invoice'])) {
                        $job_sub_service->is_invoice = $requested_job_sub_service['is_invoice'];
                    }
                    if (isset($requested_job_sub_service['quantity'])) {
                        $job_sub_service->quantity = isset($requested_job_sub_service['quantity']) && $requested_job_sub_service['quantity'] > 0 ? $requested_job_sub_service['quantity'] : 1;
                        $job_sub_service->total_price = $job_sub_service->base_price * $job_sub_service->quantity;
                    } else {
                        $job_sub_service->total_price = $job_sub_service->base_price;
                    }
                    $job_sub_service->save();
                }
                foreach ($requestedJobService as $key => $item) {
                    if ($item->id == $job_service->id) {
                        unset($requestedJobService[$key]);
                    }
                }
            } else {
                $job_service = new RequestedJobService();
                $job_service->job_id = $jobId;
                $job_service->service_id = $requested_job_service['service_type_id'];

                if (isset($requested_job_service['requested_date'])) {
                    $requested_date = Carbon::parse($requested_job_service['requested_date']);
                    $job_service->requested_date = $requested_date->format('Y:m:d');
                }


                if (!empty($requested_job_service['requested_for'])) {
                    if (Auth::user()->role == 2) {
                        $requested_for = Carbon::parse($requested_job_service['requested_for']);
                        $job_service->requested_for = $requested_for->format('Y:m:d');
                    }
                }
                if (Auth::user()->role == 1 || Auth::user()->role == 4) {
                    //to assign job
                    if (!empty($requested_job_service['assigned_to_id'])) {
                        $job_service->assigned_to_id = $requested_job_service['assigned_to_id'];
                        $job_service->assigned_to_type = $requested_job_service['assigned_to_type'];
                    }
                    if (!empty($requested_job_service['is_fixed_price'])) {
                        $job_service->is_fixed_price = $requested_job_service['is_fixed_price'];
                        $job_service->payroll_price = $requested_job_service['payroll_price'];
                    }
                }

                if (!empty($requested_job_service['description'])) {
                    $job_service->description = $requested_job_service['description'];
                }
                // to schedule
                if (!empty($requested_job_service['scheduled_date'])) {

                    $scheduled_date = Carbon::parse($requested_job_service['scheduled_date']);
                    $job_service->scheduled_date = $scheduled_date->format('Y:m:d');
                    $job_service->scheduled_time = $requested_job_service['scheduled_time'];
                    $job_service->anytime = $requested_job_service['anytime'];
                }
                $job_service->save();

                // to save sub services
                $requestedJobSubServiceId = $job_service->id;
                foreach ($requested_job_service['requested_job_sub_services'] as $index => $requested_job_sub_service) {
                    $job_sub_service = new RequestedJobSubService();
                    $job_sub_service->order = $index;
                    $job_sub_service->requested_job_service_id = $requestedJobSubServiceId;
                    $job_sub_service->base_price = $requested_job_sub_service['price'];
                    $job_sub_service->total_price = $requested_job_sub_service['price'];
                    $job_sub_service->sub_service_id = $requested_job_sub_service['sub_service_id'];
                    $job_sub_service->description = isset($requested_job_sub_service['description']) ? $requested_job_sub_service['description'] : "";
                    $job_sub_service->save();
                }
            }
        }
        $notAssignedCount = RequestedJobService::where('job_id', $jobId)
            ->where('assigned_to_id', null)->count();
        if ($notAssignedCount == 0) {
            $job = Job::find($jobId);
            $job->is_assigned = 1;
            $job->is_request = 0;
            $job->save();
        } else {
            $job = Job::find($jobId);
            $job->is_assigned = 0;
            $job->save();
        }
        // will applicable in case of
        $notScheduledCount = RequestedJobService::where('job_id', $jobId)->where('scheduled_date', Null)->count();
        //        if($notScheduledCount==0){
        //            $job  = Job::find($jobId);
        //            if( $job->job_status == 'not scheduled'){
        //                $job->job_status  ='scheduled';
        //                $job->save();
        //            }
        //        }
        //for client
        //        if($notScheduledCount==0 && $notAssignedCount==0){
        //            $job  = Job::find($jobId);
        //            if( $job->job_status == 'requested'){
        //                $job->job_status  ='scheduled';
        //                $job->save();
        //            }
        //        }
        // till here
        //update job status


        if ($requestedJobService->count() > 0) {
            foreach ($requestedJobService as $requestService) {
                $requestService->RequestedJobSubService()->delete();
                $requestService->delete();
            }
            // $requestedJobService->each->RequestedJobSubService()->delete();
            // $requestedJobService->each->delete();
        }

        $job = Job::with('property')
            ->with('property.staff')
            ->with('property.client.user')
            ->with('jobServices.service')
            ->with('apartment_type');
        // ->whereHas('apartment_type', function ($q) {
        //     $q->where('isActive', 1);
        // });

        $this->updateJobStatus($jobId);

        // schedule job service email notification
        if (!empty($newScheduleServices)) {
            $data = $job
                ->with(['jobServices' => function ($q) use ($newScheduleServices) {
                    $q->whereIn('id', $newScheduleServices);
                    $q->orderBy('scheduled_date');
                }])->find($jobId);
            //   event(new ScheduleJobEmailEvent($data));
        }
        // assign job service email notification
        if (!empty($newAssignedServices)) {
            $data = $job->with('jobServices.employee.user')
                ->with('jobServices.employeeCrew.employe.user')
                ->with(['jobServices' => function ($q) use ($newAssignedServices) {
                    $q->whereIn('id', $newAssignedServices);
                }])->find($jobId);
            event(new AssignJobEmailEvent($data));
        }
        if ($request->quick_action && $request->quick_action == "send_email") {
            $this->sendAsEmail($request, $jobId);
        }
        return response()->json([
            'job_id' => $jobId,
        ], 200);
    }

    public function updateJobStatus($jobId)
    {
        $job = Job::with('jobServices')->find($jobId);
        //        return $job->jobServices;
        $allUnscheduled = $job->jobServices->every(function ($item) {
            return $item->scheduled_date === NULL;
        });
        $allScheduled = $job->jobServices->every(function ($item) {
            return $item->scheduled_date !== NULL;
        });
        $allCompleted = $job->jobServices->every(function ($item) {
            return $item->end_date !== NULL;
        });

        if ($allUnscheduled) {
            $job->job_status = "not scheduled";
        } else {
            $job->job_status = "partial scheduled";
            $job->is_request  = 0;
        }
        if ($allScheduled && count($job->jobServices) > 0) {
            $job->job_status = "scheduled";
            $job->is_request  = 0;
        }
        if ($allCompleted && count($job->jobServices) > 0) {
            $job->job_status = "completed";
            $job->is_request  = 0;
        }
        // if ($job->job_status == "completed" && $job->is_billed == 0){
        //     $job->is_billed = 2;
        // }
        $job->save();
    }

    public function sendAsEmail($request, $jobId)
    {
        $job = Job::with('requestedJobService')
            ->with('requestedJobService.employee.user')
            ->with('requestedJobService.employeeCrew')
            ->with('requestedJobService.requestedJobSubService')
            ->with('requestedJobService.service')
            ->with('property.client.user')
            ->with(['requestedJobService.requestedJobSubService' => function ($q) {
                $q->where('is_delete', 0);
            }])
            ->find($jobId);

        $totalInvoice = 0;
        foreach ($job['jobServices'] as $service) {
            if ($service['service']) {
                foreach ($service['requestedJobSubService'] as $subService) {
                    $totalInvoice = $totalInvoice + $subService->total_price;
                }
            }
        }

        $f_name = $job->property->client->user ? $job->property->client->user->first_name : "";
        $lname = $job->property->client->user ? $job->property->client->user->last_name : "";
        $email = $job->property->client->user ? $job->property->client->user->email : "";
        $emailData = ['email' => $email, 'first_name' => $f_name, 'last_name' => $lname];
        $clientnName = $f_name . " " . $lname;
        $bodyData = "Hello " . $clientnName . ",<br/>
       Thank you for asking us to quote on your project,
        Please find a detailed copy of our quote attached to this email. <br/>
        The quote total is $" . number_format((float)$totalInvoice, 2, '.', '') . " as of " . date('m/d/y') . " <br/>
        If you have any questions or concerns regarding this quote, please don't hesitate to get in touch with us at info@reservicesystems.com.<br/>
        Sincerely,
        Real Estate Service Systems LLC";
        $request->request->add(
            [
                'body' => $bodyData,
                'subject' => " Quote from Real Estate Service Systems LLC - " . date('m/d/y'),
                'email' => [$emailData]
            ],
        );

        //        return $request;

        $this->sendQuoteEmail($request, $jobId);
    }

    public function isReadyToBill($jobId, $status)
    {
        $job = Job::find($jobId);
        if ($job->job_status == "completed") {

            if ($status == "true") {
                //                return $status;
                $job->is_billed = 2;
            } else {
                $job->is_billed = 0;
            }
            //            $job->is_billed = ($status == true) ? 2 : 0;
            $job->save();
            return response()->json([
                'msg' => "Updated Successfully",
            ], 200);
        } else {
            return response()->json([
                'error' => "Failed to update",
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Job::find($id);
        $data->job_cancel = 1;
        $data->save();

        return response()->json([
            'msg' => "Job has been cancelled",
        ], 200);
    }


    public function propertyServices($propertyId, $apartmentTypeId = null)
    {
        $data = ClientPricing::with('service')
            ->whereHas('serviceSubCategory', function ($q) {
                $q->Where('isActive', 1);
            })->with('serviceSubCategory', function ($q) {
                $q->Where('isActive', 1);
            })
            // ->with('service.jobsubServices')
            ->where('property_id', $propertyId)
            ->where(function ($q) use ($apartmentTypeId) {
                if ($apartmentTypeId) {
                    $q->where('apartment_type_id', $apartmentTypeId);
                }
                // $q->orWhere('apartment_type_id', null);
            })
            ->groupBy('service_type_id', 'sub_service_id')
            ->get();



        // $parentServices = ClientPricing::with('service')->distinct()->get(['service_type_id']);
        $resultArray = \DB::table('client_pricings')
            ->join('services', 'client_pricings.service_type_id', '=', 'services.id')
            ->where('services.isActive', 1)
            ->distinct()->select('client_pricings.service_type_id', 'services.id', 'services.category', 'services.sequence_order')->orderBy('sequence_order', 'asc')->get();
        $parentServices = [];
        foreach ($resultArray as $key => $value) {
            $parentServices[$key]['service'] = $value;
            $parentServices[$key]['service_type_id'] = $value->service_type_id;
        }
        return response()->json([
            'services' => $data,
            'parentServices' => $parentServices,
        ], 200);
    }

    public function initialData(Request $request)
    {

        $user = Auth::user();
        if ($user->client) {
            $client_id = $user->client->id;
            $clientProperties = ClientProperties::with(["appartment_types" => function ($q) {
                $q->where('isActive', 1);
            }])->with('client')->where('client_id', $client_id)
                ->where('is_PO_required', 1)->where('is_quote', 0)->where('isActive', 1)->orderBy('title')->get();
        } else if ($user->staff) {
            $clientProperties = ClientProperties::with(["appartment_types" => function ($q) {
                $q->where('isActive', 1);
            }])->with('client')->whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            })->where('isActive', 1)
                ->orderBy('title')->get();
        } else {
            $clientProperties = ClientProperties::with(["appartment_types" => function ($q) {
                $q->where('isActive', 1);
            }])->with('client')->where('is_quote', 0)->where('isActive', 1)->orderBy('title')->get();
        }
        $serviceCategories = Service::orderBy('category')->get();
        return response()->json([
            'properties' => $clientProperties,
            'serviceCategories' => $serviceCategories,
        ], 200);
    }

    public function assignInitialData($jobId, $parentServiceID)
    {

        $service = Service::find($parentServiceID);
        // $searchValues = preg_split('/\s+/', $service->category, -1, PREG_SPLIT_NO_EMPTY);
        $crews = Crew::with('department')->where(['department_id' => $service->department_id, 'is_active' => 1, 'crew_activated' => 1])->get();
        $employees = Employe::with('user')->whereHas('user', function ($q) {
            return $q->where('is_active', 1)->where('user_activated', 1);
        })
            ->with('department')
            ->whereHas('department', function ($q) use ($service) {
                $q->where('id', $service->department_id);
            })->get();
        $assignDetail = RequestedJobService::where('job_id', $jobId)->where('service_id', $parentServiceID)->first();


        return response()->json([
            'crews' => $crews,
            'employees' => $employees,
            'assignDetail' => $assignDetail,
        ], 200);
    }

    public function ScheduleDates(Request $request)
    {

        foreach ($request['job_services'] as $service) {
            $data = RequestedJobService::find($service['id']);
            $scheduled_date = Carbon::parse($service['scheduled_date']);
            $data->scheduled_date = $scheduled_date->format('Y:m:d');
            // $data->scheduled_date = date('Y:m:d', strtotime($service['scheduled_date']));
            $data->scheduled_time = $service['scheduled_time'];
            $data->anytime = $service['anytime'];
            $data->save();
        }
    }

    public function RequestedJobs(Request $request)
    {
        $user = Auth::user();

        $query = Job::where('is_request', 1)->where('job_cancel', 0)
            ->whereHas('property', function ($q) {
                $q->where('isActive', 1);
            })
            // ->whereHas('apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // })
            ->with('requestedJobService.requestedJobSubService', function ($q) {
                $q->where('is_delete', 0);
            })->with('requestedJobService.service', function ($q) {
                $q->where('isActive', 1);
            })->with("property")->with('property.client.user')->with('requestedJobService.service'); //->orderby('id', 'desc');
        if ($request->propertyId) {
            $propertyIds = explode(',', $request->propertyId);
            $query = $query->whereIn('property_id', $propertyIds);
        }
        if ($request->serviceId) {
            $serviceIds = explode(',', $request->serviceId);
            $query = $query->whereHas('jobServices', function ($q) use ($serviceIds) {
                $q->whereIn('service_id', $serviceIds);
            });
        }
        // date filter
        if ($request->date_filter) {
            $date = $request->date_filter;
            // $date     = $date->format('Y-m-d');
            // $date = date('Y-m-d', strtotime($request->date_filter));
            $query = $query->whereHas('jobServices', function ($q) use ($date) {
                $q->whereDate('requested_for', $date);
            });
        }
        //unit filter
        if ($request->apartment_filter) {
            $query = $query->Where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
        }
        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::where('client_id', $clientId->id)
                ->where('isActive', 1)->select('id')->get();
            $query = $query->whereIn('property_id', $clientProperties);
        } else if ($user->staff) {
            $clientId = Client::where('id', $user->staff->parent_id)->first('id');
            //            if ($user->staff->staff_roles->is_property_level == true) {
            $clientProperties = ClientProperties::whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            });
            $clientProperties = $clientProperties->where('is_quote', 0)->where('isActive', 1)->select('id')->get();
            //            } else {
            //                $clientId = Client::where('id', $user->staff->parent_id)->first('id');
            //                $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
            //                    ->where('isActive', 1)->select('id')->get();
            //            }
            $query = $query->whereIn('property_id', $clientProperties);
        }
        $query = $query->with('jobServices.service')->with('requestedBy')
            ->with('jobServices.service', function ($q) {
                $q->where('isActive', 1);
            });

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "apartment_number" || $request->sort_by == "invoice_no" || $request->sort_by == "job_status" || $request->sort_by == "is_billed") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $query = $query->orderByJoin('property.title', $request->sort_order);
        } else if ($request->sort_by == "requested_by") {
            $query = $query->orderByJoin('requestedBy.first_name', $request->sort_order);
        } else if ($request->sort_by == "requested_for") {
            $query = $query->orderByJoin('jobServices.requested_for', $request->sort_order);
        } else if ($request->sort_by == "service_type") {
            $query = $query->orderByJoin('jobServices.requestedJobSubService.services.category', $request->sort_order);
        } else {
            $query = $query->orderBy("id", "DESC");
        }

        $jobs = $query->paginate($request->size);
        return response()->json([
            'jobs' => $jobs,
        ], 200);
    }

    public function CancelledJobs(Request $request)
    {
        $user = Auth::user();

        $query = Job::where('job_cancel', 1)->with('jobServices.service') //->orderby('id', 'desc')
            ->whereHas('property', function ($q) {
                $q->where('isActive', 1);
            })->with('jobServices.service', function ($q) {
                $q->where('isActive', 1);
            })->with("property")
            ->with('property.client.user');
        if ($request->propertyId && $request->propertyId != 'all') {
            $query = $query->where('property_id', $request->propertyId);
        }
        if ($request->serviceId && $request->serviceId != 'all') {
            $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                $q->where('service_id', $request->serviceId);
            });
        }
        // data filter
        if ($request->data_filter) {
            if (strpos($request->data_filter, ",")) {
                $dates = explode(',', $request->data_filter);
                $query = $query->whereHas('jobServices', function ($q) use ($dates) {
                    $q->whereBetween('requested_date', $dates);
                });
            } else {
                $date = Carbon::parse($request->data_filter);
                $date = $date->format('Y-m-d');
                // $date = date('Y-m-d', strtotime($request->data_filter));
                $query = $query->whereHas('jobServices', function ($q) use ($date) {
                    $q->whereDate('requested_date', date($date));
                });
            }
        }
        if ($request->apartment_filter) {
            $query = $query->where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
        }
        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::where('client_id', $clientId->id)
                ->where('isActive', 1)->select('id')->get();
            $query = $query->whereIn('property_id', $clientProperties);
        } else if ($user->staff) {
            $clientId = Client::where('id', $user->staff->parent_id)->first('id');
            //            if ($user->staff->staff_roles->is_property_level == true) {
            $clientProperties = ClientProperties::whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            });
            $clientProperties = $clientProperties->where('is_quote', 0)->where('isActive', 1)->select('id')->get();
            //            } else {
            //                $clientId = Client::where('id', $user->staff->parent_id)->first('id');
            //                $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
            //                    ->where('isActive', 1)->select('id')->get();
            //            }
            $query = $query->whereIn('property_id', $clientProperties);
        }

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "apartment_number" || $request->sort_by == "invoice_no" || $request->sort_by == "job_status" || $request->sort_by == "is_billed") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $query = $query->orderByJoin('property.title', $request->sort_order);
        } else if ($request->sort_by == "requested_date") {
            $query = $query->orderByJoin('jobServices.requested_date', $request->sort_order);
        } else if ($request->sort_by == "service_type") {
            $query = $query->orderByJoin('jobServices.requestedJobSubService.services.category', $request->sort_order);
        } else {
            $query = $query->orderBy("id", "DESC");
        }

        $jobs = $query->paginate($request->size);
        return response()->json([
            'jobs' => $jobs,
        ], 200);
    }

    public function getRequiredPoJobs(Request $request)
    {
        $user = Auth::user();
        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::whereHas('client', function ($q) {
                $q->where('is_PO_required', 1);
            })->where('client_id', $clientId->id)->where('is_quote', 0)->where('is_PO_required', 1)
                ->where('isActive', 1)->select('id');
        } else if ($user->staff) {
            $clientId = Client::where('id', $user->staff->parent_id)->first('id');
            $clientProperties = ClientProperties::whereHas('client', function ($q) {
                $q->where('is_PO_required', 1);
            })->where('client_id', $clientId->id)->where('is_quote', 0)->where('is_PO_required', 1)
                ->where('isActive', 1)->select('id');
        } else {
            $clientProperties = ClientProperties::whereHas('client', function ($q) {
                $q->where('is_PO_required', 1);
            })->where('is_quote', 0)->where('is_PO_required', 1)
                ->where('isActive', 1)->select('id');
        }


        if ($user->staff) {
            $clientProperties = $clientProperties->whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            });
        }

        if ($request->propertyId) {
            $clientProperties = $clientProperties->Where("id", $request->propertyId);
        }
        $clientProperties = $clientProperties->get();
        $query = Job::where('job_cancel', 0)->whereIn('property_id', $clientProperties);
        //$query = $query->orderby('id', 'desc')
        if ($request->apartment_filter) {
            $query = $query->where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
        }

        if ($request->date_filter) {
            // $date = $request->date_filter;
            // // $date     = $date->format('Y-m-d');
            // // $date = date('Y-m-d', strtotime($request->date_filter));
            // $query = $query->whereHas('jobServices', function ($q) use ($date) {
            //     $q->whereDate('requested_for', $date);
            // });

            $date = Carbon::parse($request->data_filter);
            $date     = $date->format('Y-m-d');
            // $date = date('Y-m-d', strtotime($request->data_filter));
            $query = $query->whereHas('jobServices', function ($q) use ($date) {
                $q->whereDate('requested_date', date($date));
            });
        }
        $query = $query->with('jobServices.service')
            ->whereHas('jobServices.service', function ($q) use ($request) {
                $q->where('isActive', 1);
                if ($request->serviceId) {
                    $q->where("id", $request->serviceId);
                }
            })
            ->with('property')->with('property.client.user');
        $query = $query->where('po_number', Null)->orWhere('po_number', '=', '');


        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "apartment_number" || $request->sort_by == "invoice_no" || $request->sort_by == "job_status" || $request->sort_by == "is_billed") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $query = $query->orderByJoin('property.title', $request->sort_order);
        } else if ($request->sort_by == "requested_date") {
            $query = $query->orderByJoin('jobServices.requested_date', $request->sort_order);
        } else if ($request->sort_by == "service_type") {
            $query = $query->orderByJoin('jobServices.requestedJobSubService.services.category', $request->sort_order);
        } else {
            $query = $query->orderBy("id", "DESC");
        }

        $data = $query->paginate($request->size);
        return response()->json([
            'jobs' => $data,
        ], 200);
    }

    public function generateInvoice($id)
    {
        $job = Job::where('id', $id)->with('property')->with('property.client')->first();
        $net_payment_term = $job->property->client->net_payment_term ? $job->property->client->net_payment_term : 30;
        $dt = Carbon::now()->addDays($net_payment_term);
        $invoice = Invoice::where(['job_id' => $id, 'is_cancelled' => 0])->first();
        if ($invoice) {
            Invoice::where('job_id', $id)->update(['due_date' => $dt->toDateString()]);
        } else {
            $invoice = new Invoice();
            $invoice->job_id = $id;
            $invoice->due_date = $dt->toDateString();
            $invoice->save();
        }
        // $currentDate = Carbon::now();
        // $jobData = RequestedJobService::where('job_id',$id)->update(['billed_date' => $currentDate]);
        $job->invoice_no = $invoice->id;
        $job->is_billed = 1;
        $job->save();
        return response()->json([
            'invoice_id' => $invoice->id,
        ], 200);
    }

    public function unscheduleService($id, $parentServiceId = null)
    {
        if ($parentServiceId) {
            $requestedJobService = RequestedJobService::where(['job_id' => $id, 'service_id' => $parentServiceId])->update(['assigned_to_type' => NULL, 'assigned_to_id' => NULL, 'anytime' => 0, 'scheduled_time' => NULL, 'scheduled_date' => NULL]);
        } else {
            $job = Job::find($id);
            // $isStarted = RequestedJobService::where(['job_id' => $id])->whereNotNull('start_date')->count();
            foreach ($job->requestedJobService as $services) {
                $rjs = RequestedJobService::find($services->id);
                $rjs->assigned_to_type = NULL;
                $rjs->assigned_to_id = NULL;
                $rjs->anytime = 0;
                $rjs->scheduled_date = NULL;
                $rjs->scheduled_time = NULL;
                $rjs->save();
            }
        }
        $notAssignedCount = RequestedJobService::where('job_id', $id)->where('assigned_to_id', null)->count();
        $notScheduledCount = RequestedJobService::where('job_id', $id)->where('scheduled_date', Null)->count();
        if ($notAssignedCount > 0) {
            $job = Job::find($id);
            $job->is_assigned = 0;
            $job->save();
        }
        //        if($notScheduledCount > 0){
        //            $job  = Job::find($id);
        //            if( $job->job_status == 'scheduled'){
        //                $job->job_status  ='not scheduled';
        //                $job->save();
        //            }
        //        }
        $this->updateJobStatus($id);
        return response()->json(['message' => 'Job has been unscheduled'], 200);
    }

    public function cancelJobNotes(Request $request, $id)
    {
        if (!empty($request->cancelNotes)) {
            $job = Job::find($id);
            $job->cancel_notes = $request->cancelNotes;
            $job->save();
        }
        return response()->json([
            'msg' => 'Notes saved successfully!',
        ], 200);
    }

    public function allJobsCount(Request $request)
    {
        $user = Auth::user();
        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
                ->where('isActive', 1)->select('id')->get();
            $query = Job::where('job_cancel', 0)->whereIn('property_id', $clientProperties)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                });
            $all_jobs = $query->count();
            $cancelled_jobs = Job::where('job_cancel', 1)->whereIn('property_id', $clientProperties)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                })->count();
            $requested_jobs = Job::where('is_request', 1)->where('job_cancel', 0)->whereIn('property_id', $clientProperties)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                })->count();
        } else if ($user->staff) {
            //            if ($user->staff->staff_roles->is_property_level == true) {
            $clientProperties = ClientProperties::whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            });
            $clientProperties = $clientProperties->where('is_quote', 0)->where('isActive', 1)->select('id')->get();
            //            } else {
            //                $clientId = Client::where('id', $user->staff->parent_id)->first('id');
            //                $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
            //                    ->where('isActive', 1)->select('id')->get();
            //            }
            $query = Job::where('job_cancel', 0)->whereIn('property_id', $clientProperties)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                });
            $all_jobs = $query->count();
            $cancelled_jobs = Job::where('job_cancel', 1)->whereIn('property_id', $clientProperties)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                })->count();
            $requested_jobs = Job::where('is_request', 1)->where('job_cancel', 0)->whereIn('property_id', $clientProperties)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                })->count();
        } else {
            $query = Job::where('job_cancel', 0)->where('is_request', 0)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('property', function ($q) {
                    $q->where('isActive', 1);
                })->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                });
            $all_jobs = $query->count();
            $cancelled_jobs = Job::where('job_cancel', 1)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('property', function ($q) {
                    $q->where('isActive', 1);
                })->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                })->count();
            $requested_jobs = Job::where('is_request', 1)->where('job_cancel', 0)
                // ->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })
                ->whereHas('property', function ($q) {
                    $q->where('isActive', 1);
                })->whereHas('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->whereHas('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                })->count();
        }

        // Require po code

        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::whereHas('client', function ($q) {
                $q->where('is_PO_required', 1);
            })->where('client_id', $clientId->id)->where('is_quote', 0)
                ->where('isActive', 1)->select('id');
        } else if ($user->staff) {
            $clientId = Client::where('id', $user->staff->parent_id)->first('id');
            $clientProperties = ClientProperties::whereHas('client', function ($q) {
                $q->where('is_PO_required', 1);
            })->where('client_id', $clientId->id)->where('is_quote', 0)
                ->where('isActive', 1)->select('id');
        } else {
            $clientProperties = ClientProperties::whereHas('client', function ($q) {
                $q->where('is_PO_required', 1);
            })->where('is_quote', 0)
                ->where('isActive', 1)->select('id');
        }


        if ($user->staff) {
            $clientProperties = $clientProperties->whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            });
        }
        $clientProperties = $clientProperties->get();
        $query = Job::where('job_cancel', 0)->whereIn('property_id', $clientProperties);
        //$query = $query->orderby('id', 'desc')
        $query = $query->with('jobServices.service')
            ->whereHas('jobServices.service', function ($q) {
                $q->where('isActive', 1);
            })
            ->with('property')->with('property.client.user');
        $required_po = $query->where('po_number', Null)->orWhere('po_number', '=', '')->count();

        try {



            $user = Auth::user();
            if ($user->client) {
                $clientId = Client::where('user_id', $user->id)->first('id');
                $clientProperties = ClientProperties::where('client_id', $clientId->id)
                    ->where('isActive', 1)->select('id')->get();

                //            $query = Job::where('type', 'quote')->where('quote_status', '!=' ,'draft')->whereIn('property_id',$clientProperties)->whereHas('apartment_type',function($q){
                //                $q->where('isActive', 1);
                //            })->orWhere('created_by',$user->id);


                // $query = Job::where('type', 'quote')->whereIn('property_id', $clientProperties)->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // });
                $query = Job::where('type', 'quote')->whereIn('property_id', $clientProperties);
            } else if ($user->staff) {
                //            if($user->staff->staff_roles->is_property_level == true){
                $clientProperties = ClientProperties::whereHas('staff', function ($q) use ($user) {
                    $q->where('staff_id', $user->staff->id);
                })->where('isActive', 1)->select('id')->get();
                //            }else{
                //                $clientId = Client::where('user_id',$user->staff->parent_id)->first('id');
                //                $clientProperties = ClientProperties::where('client_id',$clientId->id)->where('is_quote',0)
                //                    ->where('isActive', 1)->select('id')->get();
                //            }

                // $query = Job::where('quote_status', '!=', 'draft')->whereIn('property_id', $clientProperties)->whereHas('apartment_type', function ($q) {
                //     $q->where('isActive', 1);
                // })->orWhere('created_by', $user->id)->where('type', 'quote');
                $query = Job::where('quote_status', '!=', 'draft')->whereIn('property_id', $clientProperties)->orWhere('created_by', $user->id)->where('type', 'quote');
            } else {
                $query = Job::where('type', 'quote')->with('apartment_type', function ($q) {
                    $q->where('isActive', 1);
                })->whereHas('property', function ($q) {
                    $q->where('isActive', 1);
                });
            }
            //$query = $query->orderby($request->sort_by,$request->sort_order)
            $query = $query
                ->with('requestedJobService.service', function ($q) {
                    $q->where('isActive', 1);
                })
                ->with('property')->with('property.client:id,company')->with('property.client.user');

            // apply sorting
            if ($request->sort_by == "id" || $request->sort_by == "apartment_number" || $request->sort_by == "created_at" || $request->sort_by == "job_status" || $request->sort_by == "quote_status") {
                $query = $query->orderBy($request->sort_by, $request->sort_order);
            } else if ($request->sort_by == "property") {
                $query = $query->orderByJoin('property.title', $request->sort_order);
            } else if ($request->sort_by == "requested_for") {
                $query = $query->orderByJoin('jobServices.requested_for', $request->sort_order);
            } else if ($request->sort_by == "service_type") {
                $query = $query->orderByJoin('requestedJobService.service.category', $request->sort_order);
            } else {
                $query = $query->orderBy("id", "DESC");
            }


            $query = $query->with('invoice', function ($q) {
                $q->where(['is_cancelled' => 0]);
            });
            if ($request->propertyId && $request->propertyId != 'all') {
                // $propertyIds = explode(',', $request->propertyId);
                $query = $query->where('property_id', $request->propertyId);
            }
            if ($request->serviceId && $request->serviceId != 'all') {
                //    $serviceIds = explode(',', $request->serviceId);
                $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                    $q->where('service_id', $request->serviceId);
                });
            }
            if ($request->jobId) {
                $query = $query->where('id', $request->jobId);
            }
            // status filter
            if ($request->quote_status_filter && $request->quote_status_filter !== "all") {
                $query = $query->where('quote_status', $request->quote_status_filter);
            }

            if ($request->status_filter && $request->status_filter !== "all") {
                if ($request->status_filter == "not assigned") {
                    $query = $query->with('requestedJobService', function ($q) use ($request) {
                        $q->where('assigned_to_type', Null);
                    });
                } else if ($request->status_filter == "overdue") {
                    $query = $query->where('job_status', '!=', 'completed');
                    $query = $query->with('requestedJobService', function ($q) use ($request) {
                        $q->where('assigned_to_type', '!=', Null)->whereDate('scheduled_date', '<', Carbon::now());
                    });
                } else if ($request->status_filter == "completedNotBilled") {
                    $query = $query->with('requestedJobService', function ($q) use ($request) {
                        $q->where('end_date', '!=', Null)->where('billed_date', NULL);
                    });
                } else if ($request->status_filter == "request") {
                    $query = $query->with('requestedJobService', function ($q) use ($request) {
                        $q->where('is_request', '!=', 0);
                    });
                } else if ($request->status_filter == "billed") {
                    //                $query = $query->whereHas('requestedJobService', function($q) use ($request){
                    //                            $q->where('billed_date',Null);
                    //                        });
                    $query = $query->where('is_billed', 1);
                } else if ($request->status_filter == "not billed") {

                    $query = $query->where('is_billed', 0);
                } else {
                    $query = $query->where('job_status', $request->status_filter);
                }
            }

            // data filter
            if ($request->data_filter) {
                if (strpos($request->data_filter, ",")) {
                    $dates = explode(',', $request->data_filter);
                    $query = $query->whereHas('jobServices', function ($q) use ($dates) {
                        $q->whereBetween('requested_date', $dates);
                    });
                } else {
                    $date = Carbon::parse($request->data_filter);
                    $date     = $date->format('Y-m-d');
                    // $date = date('Y-m-d', strtotime($request->data_filter));
                    $query = $query->with('jobServices', function ($q) use ($date) {
                        $q->whereDate('requested_date', date($date));
                    });
                }
            }
            if ($request->apartment_filter) {
                $query = $query->where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
            }
            if ($request->po_number_filter) {
                $query = $query->where('po_number', '=', "$request->po_number_filter");
            }
            // $query = $query->whereMonth('created_at', $request->data_filter);
            // $query = $query->with('apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // })
            $query = $query->with('jobServices.requestedJobSubService', function ($q) {
                $q->where('is_delete', 0);
            })->with(['jobServices.requestedJobSubService' => function ($q) {
                $q->where('is_delete', 0);
            }]);
            $qoute_counts = $query->count();;
        } catch (Exception $e) {
        }

        try {
            $user = Auth::user();
            if ($user->employee) {
                $query = Employe::where('user_id', $user->id);
            } else {
                $query = Employe::query();
            }
            $query = $query->with(['user', 'department'])
                ->whereHas('department', function ($q) {
                    $q->where('isActive', 1);
                });

            $employee_counts = $query->count();
        } catch (Exception $e) {
        }


        try {
            $user = Auth::user();
            $query = Invoice::with('job')
                ->with('job.apartment_type')
                ->with('job.property')
                ->with('job.jobServices.service')
                ->with('job.property.client.user');

            $query = $query->whereHas('job', function ($q) {
                $q->where('job_cancel', 0);
            });
            // $query = $query->whereHas('job.apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // });
            $query = $query->whereHas('job.property', function ($q) {
                $q->where('isActive', 1);
            });
            $query = $query->whereHas('job.jobServices.service', function ($q) {
                $q->where('isActive', 1);
            });

            if ($user->client) {
                $clientId = Client::where('user_id', $user->id)->first('id');
                $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
                    ->where('isActive', 1)->select('id')->get();
                $jobIds = Job::where('is_quote', 0)->whereIn('property_id', $clientProperties)
                    // ->whereHas('apartment_type', function ($q) {
                    //     $q->where('isActive', 1);
                    // })
                    ->select('id')->get();
                $query = $query->whereIn('job_id', $jobIds);
            } else if ($user->staff) {
                $clientProperties = ClientProperties::whereHas('staff', function ($q) use ($user) {
                    $q->where('staff_id', $user->staff->id);
                })->where('isActive', 1)->select('id')->get();

                $jobIds = Job::where('is_quote', 0)->whereIn('property_id', $clientProperties)
                    // ->whereHas('apartment_type', function ($q) {
                    //     $q->where('isActive', 1);
                    // })
                    ->select('id')->get();
                $query = $query->whereIn('job_id', $jobIds);
            }

            $query = $query->with(['job.requestedJobService' => function ($q) {
                $q->orderBy('end_date', 'desc');
            }])->whereHas('job.requestedJobService.requestedJobSubService', function ($q) {
                $q->where('is_delete', 0);
            });
            $invoices_count = $query->count();
        } catch (Exception $e) {
        }

        try {

            $user = Auth::user();
            $query = Invoice::with('job')
                ->with('job.apartment_type')
                ->with('job.property')
                ->with('job.jobServices.service')
                ->with('job.property.client.user')
                ->where('is_paid', '=', 0)
                ->where('is_cancelled', '=', 0)
                ->where('due_date', '<', now());
            $query = $query->whereHas('job', function ($q) {
                $q->where('job_cancel', 0);
            });
            // $query = $query->whereHas('job.apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // });
            $query = $query->whereHas('job.property', function ($q) {
                $q->where('isActive', 1);
            });
            $query = $query->whereHas('job.jobServices.service', function ($q) {
                $q->where('isActive', 1);
            });

            if ($user->role == 2) {
                $query = $query->whereHas('job.property.client.user', function ($query) use ($user) {
                    $query->where('id', $user->id);
                });
            }
            if (auth()->user()->parent) {
                $query = $query->whereHas('job.property', function ($query) use ($user) {
                    $query->where('staff_id', $user->id);
                });
            }

            $query = $query->with(['job.requestedJobService' => function ($q) {
                $q->orderBy('end_date', 'desc');
            }])->whereHas('job.requestedJobService.requestedJobSubService', function ($q) {
                $q->where('is_delete', 0);
            });


            $pastDueCount = $query->count();
        } catch (Exception $e) {
        }

        try {
            $query = Invoice::with('job')
                ->with('job.apartment_type')
                ->with('job.property')
                ->with('job.jobServices.service')
                ->with('job.property.client.user')
                ->where('is_cancelled', '=', 0)
                ->where('is_paid', '=', 0);
            $query = $query->whereHas('job', function ($q) {
                $q->where('job_cancel', 0);
            });
            // $query = $query->whereHas('job.apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // });
            $query = $query->whereHas('job.property', function ($q) {
                $q->where('isActive', 1);
            });
            $query = $query->whereHas('job.jobServices.service', function ($q) {
                $q->where('isActive', 1);
            });

            if ($user->role == 2) {
                $query = $query->whereHas('job.property.client.user', function ($query) use ($user) {
                    $query->where('id', $user->id);
                });
            }
            if (auth()->user()->parent) {
                $query = $query->whereHas('job.property', function ($query) use ($user) {
                    $query->where('staff_id', $user->id);
                });
            }

            $query = $query->with(['job.requestedJobService' => function ($q) {
                $q->orderBy('end_date', 'desc');
            }])->whereHas('job.requestedJobService.requestedJobSubService', function ($q) {
                $q->where('is_delete', 0);
            }); //->orderBy('id', 'DESC')



            $awaiting_payment = $query->count();
        } catch (Exception $e) {
        }
        try {
            $user = Auth::user();

            $user = Auth::user();
            $query = Invoice::with('job')
                ->with('job.apartment_type')
                ->with('job.property')
                ->with('job.property.client.user');

            $query = $query->whereHas('job', function ($q) {
                $q->where('job_cancel', 0);
            });
            // $query = $query->whereHas('job.apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // });
            $query = $query->where('is_cancelled', 1);
            $query = $query->with(['job.requestedJobService' => function ($q) {
                $q->orderBy('end_date', 'desc');
            }]);

            $canceled_invoice = $query->count();
        } catch (Exception $e) {
        }

        try {
            $clientStaffIds = User::where('role', 2)->doesntHave('client')->pluck('id');

            $query = User::query()->with(['client', 'employee', 'staff']);
            $query = $query->with('roles');



            $user_count = $query->count();
        } catch (Exception $e) {
        }



        try {
            $user = Auth::user();
            if ($user->client) {
                $client_id = $user->client->id;
                $client_properties = ClientProperties::with("appartment_types")->where('client_id', $client_id)
                    ->where('isActive', 1)->where('is_quote', 0)->pluck('id');
            } else if ($user->staff) {

                $client_properties = ClientProperties::with("appartment_types")->whereHas('staff', function ($q) use ($user) {
                    $q->where('staff_id', $user->staff->id);
                })->where('isActive', 1)->pluck('id');
            } else {
                $client_properties = ClientProperties::where('is_quote', 0)->where('isActive', 1)->pluck('id');
            }

            $jobs = Job::query();
            $jobs = $jobs->with(['invoice' => function ($q) {
                $q->where(['is_cancelled' => 0]);
            }]);
            $jobs = $jobs->whereIn('property_id', $client_properties);
            // $jobs = $jobs->where('job_status','completed')->where('job_cancel',0);
            if ($request->status_filter) {
                if ($request->status_filter == "completed") {
                    $jobs = $jobs->where('job_status', 'completed');
                } else if ($request->status_filter == "cancelled") {
                    $jobs = $jobs->where('job_cancel', 1);
                }
            } else {
                $jobs = $jobs->where(function ($q) {
                    $q->where('job_status', 'completed');
                    $q->orWhere('job_cancel', 1);
                });
            }


            $jobs = $jobs->with(['requestedJobService.requestedJobSubService' => function ($query) {
                $query->select('requested_job_service_id', \DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
            }]);
            $jobs = $jobs->with('property')->with('property.client.user');
            $jobs = $jobs->with('requestedJobService.service');
            $jobs = $jobs->whereHas('requestedJobService.service', function ($q) {
                $q->where('isActive', 1);
            });
        } catch (Exception $e) {
        }
        $clients_counts =  Client::count();
        $jobs = Job::query();
        $jobs = $jobs->with(['invoice' => function ($q) {
            $q->where(['is_cancelled' => 0]);
        }]);
        $job_history_count = $jobs->whereIn('property_id', $client_properties);
        $jobs = $jobs->whereHas('requestedJobService.service', function ($q) {
            $q->where('isActive', 1);
        });
        $jobs = $jobs->where(function ($q) {
            $q->where('job_status', 'completed');
            $q->orWhere('job_cancel', 1);
        });
        $job_history_count = $jobs->count();
        $dataArray = [];
        try {
            $dc = new DC();
            $dataArray = $dc->jobCounts($request)->getData(true);
        } catch (Exception $e) {
        }
        return response()->json(array_merge([
            'client_counts' =>   $clients_counts,
            'cancelled_jobs' => $cancelled_jobs,
            'requests' => $requested_jobs,
            'all_jobs' => $all_jobs,
            'required_po' => isset($required_po) ? $required_po : null,
            'qoute_counts' => isset($qoute_counts) ? $qoute_counts : null,
            'employee_counts' => isset($employee_counts) ? $employee_counts : null,
            'invoices_count' => isset($invoices_count) ? $invoices_count : null,
            'pastDueCount' => isset($pastDueCount) ? $pastDueCount : null,
            'awaiting_payment' => isset($awaiting_payment) ? $awaiting_payment : null,
            'canceled_invoice'  => isset($canceled_invoice) ? $canceled_invoice : null,
            'user_count' => isset($user_count) ? $user_count : null,
            'job_history_count' => isset($job_history_count) ? $job_history_count : null,

        ], $dataArray), 200);
    }

    public function sendQuoteEmail(Request $request, $id)
    {
        //        return $request;
        get_settings();
        $job = Job::with('requestedJobService')
            ->with('requestedJobService.employee.user')
            ->with('requestedJobService.employeeCrew')
            ->with('requestedJobService.requestedJobSubService')
            ->with('requestedJobService.service')
            ->with('property.client.user')
            ->with(['requestedJobService.requestedJobSubService' => function ($q) {
                $q->where('is_delete', 0);
            }])
            ->find($id);

        if ($job && $job->quote_status == "draft") {
            $job->quote_status = "awaiting_response";
            $job->save();
        }

        $totalInvoice = 0;
        foreach ($job['jobServices'] as $service) {
            if ($service['service']) {
                foreach ($service['requestedJobSubService'] as $subService) {
                    $totalInvoice = $totalInvoice + $subService->total_price;
                }
            }
        }
        $taxPercentage = $job['property']['taxType'] ? $job['property']['taxType']->rate : 0;
        $totalServicePrice = 0;
        $tax = 0;
        if ($taxPercentage) {
            foreach ($job['jobServices'] as $service) {
                if ($service['service']) {
                    foreach ($service['requestedJobSubService'] as $subService) {
                        if ($job->apartment_status == 'vacant') {
                            if ($service['service'] && $service['service']->is_taxable) {
                                $totalServicePrice += $subService->total_price;
                            }
                        }
                    }
                }
            }
            $tax = ($taxPercentage / 100) * $totalServicePrice;
        }
        $totalTax = round($tax, 2);

        $pdf = PDF::loadView('pdf/quote/quote-pdf', compact('job', 'totalInvoice', 'totalTax'));


        $emailList = array();
        foreach ($request->email as $key => $email) {
            array_push($emailList, $email['email']);
        }
        $data = new \stdClass();
        $data->body = str_replace("\n", "<br>", $request->body);
        $data->subject = $request->subject;
        $data->email = $request->email;
        $data->quote_id = $id;


        try {
            Mail::send('emails.qoute_email', compact('data'), function ($mailData) use ($emailList, $pdf, $data) {
                $mailData->to($emailList);
                $mailData->cc(config('app.mail_cc_address'));
                $mailData->from(config('app.mail_from_address'));
                $mailData->attachData($pdf->output(), 'quote.pdf', ['mime' => 'application/pdf']);
                //$mailData->subject($data['job']['property']->title ." (". $data['job']->apartment_number .") ". 'Invoice');
                $mailData->subject($data->subject);
            });
        } catch (\Throwable $th) {
        }


        return response()->json([
            "message" => "email sent"
        ], 200);
    }

    public function updateQuoteStatus($id, $status)
    {
        $job = Job::find($id);
        if ($job) {
            if ($status == "converted") {
                $job->job_status = "not scheduled";
                $job->is_quote = 0;
            }
            $job->quote_status = $status;
            $job->save();
        }

        return response()->json([
            "status" => "success"
        ], 200);
    }

    public function getQuoteById($id)
    {
        $data = Job::with('requestedJobService')
            ->with('createdBy', function ($q) {
                $q->with('roles');
            })
            ->with('requestedJobService.employee.user')
            ->with('requestedJobService.employeeCrew')
            ->with('requestedJobService.requestedJobSubService')
            ->with('requestedJobService.jobAttatchment')
            ->with('ServiceReadyCheckList')
            ->with('requestedJobService.service')
            ->with('property.client.user')
            ->with(['invoice' => function ($q) {
                $q->where(['is_cancelled' => 0]);
            }])
            ->with(['requestedJobService.requestedJobSubService' => function ($q) {
                $q->where('is_delete', 0);
            }])->where('type', 'quote')->find($id);

        if ($data) {
            $data->remainingJobs = RequestedJobService::where('job_id', $id)->where('end_date', '=', null)->count();

            return response()->json([
                'job' => $data,
                'status' => "success",
            ], 200);
        } else {
            return response()->json([
                'job' => "",
                'status' => "not_found",
            ], 200);
        }
    }

    public function qoutePdf($id, $type)
    { {
            $job = Job::with('requestedJobService')
                ->with('requestedJobService.employee.user')
                ->with('requestedJobService.employeeCrew')
                ->with('requestedJobService.requestedJobSubService')
                ->with('requestedJobService.service')
                ->with('property.client.user')
                ->with(['requestedJobService.requestedJobSubService' => function ($q) {
                    $q->where('is_delete', 0);
                }])
                ->find($id);

            $totalInvoice = 0;
            foreach ($job['jobServices'] as $service) {
                if ($service['service']) {
                    foreach ($service['requestedJobSubService'] as $subService) {
                        $totalInvoice = $totalInvoice + $subService->total_price;
                    }
                }
            }
            $taxPercentage = $job['property']['taxType'] ? $job['property']['taxType']->rate : 0;
            $totalServicePrice = 0;
            $tax = 0;
            if ($taxPercentage) {
                foreach ($job['jobServices'] as $service) {
                    if ($service['service']) {
                        foreach ($service['requestedJobSubService'] as $subService) {
                            if ($job->apartment_status == 'vacant') {
                                if ($service['service'] && $service['service']->is_taxable) {
                                    $totalServicePrice += $subService->total_price;
                                }
                            }
                        }
                    }
                }
                $tax = ($taxPercentage / 100) * $totalServicePrice;
            }
            $totalTax = round($tax, 2);


            if ($type == "download") {
                $pdf = PDF::loadView('pdf/quote/quote-pdf', compact('job', 'totalInvoice', 'totalTax'));
                return $pdf->download('quote.pdf');
            } else if ($type == "preview") {
                return view('pdf/quote/quote-pdf', compact('job', 'totalInvoice', 'totalTax'));
            }
        }
    }
}
