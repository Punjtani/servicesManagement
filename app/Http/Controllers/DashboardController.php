<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RequestedJobService;
use App\Models\Employe;
use App\Models\EmployeCrew;
use App\Models\Client;
use App\Models\ClientProperties;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function getJobsNew(Request $request, $filter)
    {

        $user = Auth::user();

        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
                ->where('isActive', 1)->select('id')->get();
            if ($filter == 'cancelled') {
                $query = Job::where('job_cancel', 1)
                    ->whereIn('property_id', $clientProperties);
            } else {
                $query = Job::where('job_cancel', 0)
                    ->whereIn('property_id', $clientProperties);
            }

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
            if ($filter == 'cancelled') {
                $query = Job::where('job_cancel', 1)
                    ->where('jobs.is_quote', 0)
                    ->whereIn('property_id', $clientProperties);
            } else {
                $query = Job::where('job_cancel', 0)
                    ->where('jobs.is_quote', 0)
                    ->whereIn('property_id', $clientProperties);
            }
            // ->whereHas('apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // });
        } else {
            if ($filter == 'cancelled') {
                $query = Job::whereHas('property', function ($q) {
                    $q->where('isActive', 1);
                })->where('job_cancel', 1);
            } else {
                if ($user->role == 3) {
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
                    })
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
            }
        }

        $query = $query->where('is_quote', 0); // to show only jobs

        //$query->orderby('property.title','asc')
        $query = $query
            ->with('requestedJobService.service', function ($q) {
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
        if ($filter && $filter !== "all") {
            if ($filter == "not assigned") {
                $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                    $q->where('assigned_to_type', Null);
                });
            } else if ($filter == "overdue") {
                $query = $query->where('job_status', '!=', 'completed');
                $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                    $q->where('assigned_to_type', '!=', Null)->whereDate('scheduled_date', '<', Carbon::now());
                });
            } else if ($filter == "completedNotBilled") {
                $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                    $q->where('end_date', '!=', Null)->where('billed_date', NULL);
                });
            } else if ($filter == "request") {
                $query = $query->whereHas('requestedJobService', function ($q) use ($request) {
                    $q->where('is_request', '!=', 0);
                });
            }
            if ($filter == 'cancelled') {
                $query = $query;
            } else if ($filter == "billed") {
                //                $query = $query->whereHas('requestedJobService', function($q) use ($request){
                //                            $q->where('billed_date',Null);
                //                        });
                $query = $query->where('is_billed', 1);
            } else if ($filter == "completed billed") {
                //                $query = $query->whereHas('requestedJobService', function($q) use ($request){
                //                            $q->where('billed_date',Null);
                //                        });
                $query = $query->where('job_status', 'completed')->where('is_billed', 1);
            } else if ($filter == "completed") {
                //                $query = $query->whereHas('requestedJobService', function($q) use ($request){
                //                            $q->where('billed_date',Null);
                //                        });
                $query = $query->where('job_status', 'completed');
            } else if ($filter == "not ready to bill") {

                $query = $query->where('is_billed', 0);
            } else if ($filter == "completed not ready to bill") {

                $query = $query->where('job_status', 'completed')->where('is_billed', 0);
            } else if ($filter == "completed ready to bill") {

                $query = $query->where('job_status', 'completed')->where('is_billed', 2);
            } else if ($filter == "unscheduled") {

                $query = $query->where('job_status', 'not scheduled');
            } else  if ($filter == 'today') {
                $query = $query->whereDate('created_at', Carbon::today())
                    ->with('requestedJobService', function ($q) {
                        $q->where('billed_date', NULL);
                    });
            } else if ($filter == "scheduled") {
                $query = $query->where('job_status', 'scheduled');
            } else if ($filter == "partial scheduled") {
                $query = $query->where('job_status', 'partial scheduled');
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
        // $query = $query->whereMonth('created_at', $request->data_filter);
        // $query = $query->whereHas('apartment_type', function ($q) {
        //     $q->where('isActive', 1);
        // })
        $query = $query->with('jobServices.requestedJobSubService', function ($q) {
            $q->where('is_delete', 0);
        })->with(['jobServices.requestedJobSubService' => function ($q) {
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
        return $query;
    }
    public function stats(Request $request)
    {
        if ($request->status_filter && $request->status_filter == 'all') {
            $JC =  new JobController();
            return $JC->index($request, true);
        }

        $user = Auth::user();

        $jobs = $this->getJobs();
        if ($user->role !== 3) {
            $jobs = $jobs->with(['requestedJobService.service', 'property'])
                ->with(['requestedJobService.requestedJobSubService' => function ($query) {
                    $query->select('requested_job_service_id', DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
                }]);
        }

        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
                ->where('isActive', 1)->select('id')->get();
            if ($request->status_filter == 'cancelled') {
                $jobs = $jobs->where('job_cancel', 1)->whereIn('property_id', $clientProperties);
            } else {
                $jobs = $jobs->where('job_cancel', 0)
                    ->whereIn('property_id', $clientProperties);
            }

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
            if ($request->status_filter == 'cancelled') {
                $jobs = $jobs->where('job_cancel', 1)->where('jobs.is_quote', 0)
                    ->whereIn('property_id', $clientProperties);
            } else {
                $jobs = $jobs->where('job_cancel', 0)
                    ->where('jobs.is_quote', 0)
                    ->whereIn('property_id', $clientProperties);
            }

            // ->whereHas('apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // });
        } else {

            if ($request->status_filter == 'cancelled') {
                // $this->getJobsNew($request, "cancelled")->count();
                $jobs = $jobs->where('job_cancel', 1);
                // $jobs = $this->getJobsNew($request, "cancelled");
            } else {
                $jobs = $jobs->where('job_cancel', 0)->where('jobs.is_quote', 0)->where('is_request', 0)
                    // ->whereHas('apartment_type', function ($q) {
                    //     $q->where('isActive', 1);
                    // })
                    ->whereHas('property', function ($q) {
                        $q->where('isActive', 1);
                    });
            }
        }

        $jobs = $jobs->with('requestedJobService.service', function ($q) {
            $q->where('isActive', 1);
        })
            ->with('property')
            ->with('property.client.user');

        $jobs = $jobs->with('invoice', function ($q) {
            $q->where(['is_cancelled' => 0]);
        });
        if ($request->status_filter) {
            // today's jobs
            if ($request->status_filter == 'today') {
                $jobs = $jobs->where('is_quote', 0)->whereDate('created_at', Carbon::today())
                    ->with('requestedJobService', function ($q) {
                        $q->where('billed_date', NULL);
                    });
            }
            // not assigned jobs
            else if ($request->status_filter == 'not assigned') {
                $jobs = $jobs->whereHas('requestedJobService', function ($q) {
                    $q->where('assigned_to_type', Null);
                })
                    ->with(['requestedJobService' => function ($q) {
                        $q->where('assigned_to_type', Null);
                    }]);
            }
            // over due jobs
            else if ($request->status_filter == 'overdue') {
                $jobs = $jobs->where('job_status', '!=', 'completed');
                $jobs = $jobs->whereHas('requestedJobService', function ($q) use ($request) {
                    $q->where('assigned_to_type', '!=', Null)->whereDate('scheduled_date', '<', Carbon::now());
                });
            }
            // completed not billed jobs
            else if ($request->status_filter == 'completed') {
                //                $jobs = $jobs->where('job_status', 'completed')
                //                    ->whereHas('requestedJobService', function ($q) {
                //                        $q->where('end_date', '!=', Null);
                //                        $q->where('billed_date', NULL);
                //                    });
                $jobs = $jobs->where('job_status', 'completed');
            } else if ($request->status_filter == 'not completed') {
                $jobs = $jobs->where('job_status', '!=', 'completed')->whereHas('requestedJobService', function ($q) {
                    $q->where('end_date', Null);
                });
            }
            // request jobs
            else if ($request->status_filter == 'request') {
                $jobs = $this->requestedJobs($request);
                $jobs = $jobs->paginate($request->size);
                return response()->json([
                    'jobs' => $jobs,
                ], 200);
            } else if ($request->status_filter == 'completed_billed_jobs') {
                $jobs = $jobs->where('job_status', 'completed')->where('is_billed', 1);
            } else if ($request->status_filter == 'cancelled') {
                // $jobs = Job::where('job_cancel', 1)
                //     ->with('property', function ($q) {
                //         $q->where('isActive', 1);
                //     })->with('requestedJobService.requestedJobSubService', function ($q) {
                //         $q->where('is_delete', 0);
                //     })->with('requestedJobService.service', function ($q) {
                //         $q->where('isActive', 1);
                //     });
                $jobs = $jobs;
            }
            //scheduled jobs
            else if ($request->status_filter == 'scheduled') {
                $jobs = $jobs->where('job_status', 'scheduled');

                // if($user->role == 3){
                //     $jobs = $jobs
                //         ->whereHas('requestedJobService', function($q){
                //             $q->where('start_date',Null);
                //             $q->where('scheduled_date', '!=', Null);
                //             $q->whereDate('scheduled_date', '>=', Carbon::now());
                //         });
                // } else {
                //     $jobs = $jobs
                //         ->whereHas('requestedJobService', function ($q) {
                //             $q->where('scheduled_date', '!=', Null);
                //             $q->whereDate('scheduled_date', '>=', Carbon::now());
                //         })
                //         ->with(['requestedJobService' => function ($q) {
                //             $q->where('scheduled_date', '!=', Null);
                //             $q->whereDate('scheduled_date', '>=', Carbon::now());
                //             $q->orderBy('scheduled_date');
                //         }]);
                // }
            } else if ($request->status_filter == "completed not ready to bill") {

                $jobs = $jobs->where('job_status', 'completed')->where('is_billed', 0);
            }
            //jobs with missing po number
            else if ($request->status_filter == 'missing_pos') {
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
                $clientProperties = $clientProperties->get();
                $query = Job::where('job_cancel', 0)->whereIn('property_id', $request->propertyId ?  [$request->propertyId] : $clientProperties);
                //$query = $query->orderby('id', 'desc')
                if ($request->serviceId) {
                    $query = $query
                        ->whereHas('jobServices.service', function ($q) use ($request) {
                            $q->where('isActive', 1)->where('id', $request->serviceId);
                        })->with('property')->with('property.client.user');
                } else {
                    $query = $query->with('jobServices.service')
                        ->whereHas('jobServices.service', function ($q) {
                            $q->where('isActive', 1);
                        })
                        ->with('property')->with('property.client.user');
                }

                $jobs = $query->where('po_number', Null)->orWhere('po_number', '=', '');
                if ($user->role !== 3) {
                    $jobs = $jobs->with(['requestedJobService.service', 'property'])
                        ->with(['requestedJobService.requestedJobSubService' => function ($query) {
                            $query->select('requested_job_service_id', DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
                        }]);
                }
            } else if ($request->status_filter == 'partial_scheduled') {
                $jobs = $jobs->where('job_status', 'partial scheduled');
            } else if ($request->status_filter == 'ready_to_bill') {
                $jobs = $jobs->where('job_status', 'completed')->where('is_billed', 2);
            } else if ($request->status_filter == 'all') {
                $jobs = $jobs;
            } else if ($request->status_filter == 'not_ready_to_bill') {
                $jobs = $jobs->where('is_billed', 0);
            } else {
                // not scheduled
                $jobs = $jobs->where('job_status', $request->status_filter);
            }
        }
        if ($request->propertyId) {
            $jobs = $jobs->where('property_id', $request->propertyId);
        }
        if ($request->serviceId) {
            $jobs = $jobs->whereHas('jobServices', function ($q) use ($request) {
                $q->where('service_id', $request->serviceId);
            });
        }
        $jobs->where('jobs.is_quote', 0);
        $jobs = $jobs->with('requestedBy');

        // ->orderBy('id', 'desc')requested_job_service
        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "apartment_number" || $request->sort_by == "invoice_no" || $request->sort_by == "job_status") {
            $jobs = $jobs->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $jobs = $jobs->orderByJoin('property.title', $request->sort_order);
        } else if ($request->sort_by == "requested_by") {
            $jobs = $jobs->orderByJoin('requestedBy.first_name', $request->sort_order);
        } else if ($request->sort_by == "requested_for") {
            $jobs = $jobs->orderByJoin('jobServices.requested_for', $request->sort_order);
        } else if ($request->sort_by == "service_type") {
            $jobs = $jobs->orderByJoin('jobServices.requestedJobSubService.services.category', $request->sort_order);
        } else if ($request->sort_by == "scheduled_date") {
            $jobs = $jobs->orderByJoin('requestedJobService.scheduled_date', $request->sort_order);
        } else {
            $jobs = $jobs->orderBy("id", "DESC");
        }
        if ($request->returnCount) {
            return $jobs = $jobs->count();
        }
        $jobs = $jobs->paginate($request->size);
        return response()->json([
            'jobs' => $jobs,
        ], 200);
    }

    private function requestedJobs($request)
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
            })
            ->with(['requestedJobService.requestedJobSubService' => function ($query) {
                $query->select('requested_job_service_id', DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
            }])
            ->with("property")->with('property.client.user')->with('requestedJobService.service'); //->orderby('id', 'desc');
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
        return $query;
    }

    //dashboard filter counts
    public function jobCounts(Request $request)
    {

        $user = Auth::user();
        // today's jobs
        $jobs = $this->getJobs();

        $status = [
            0 =>  "all",
            1 => "scheduled",
            2 => "unscheduled",
            3 =>  "unassigned",
            4 =>  "partial scheduled",
            5 => "completed",
            6 => "completed billed",
            7 =>  "completed ready to bill",
            8 => "not ready to bill",
            9 => "completed not ready to bill",
            10 =>  "today",
            11 => "overdue",
            12 => "cancelled"
        ];


        $scheduled_jobs = $this->getJobsNew($request, $status[1])->count();
        $not_scheduled_jobs = $this->getJobsNew($request, $status[2])->count();
        $assigned_jobs  = $this->getJobsNew($request, "not assigned")->count();
        $partial_scheduled = $this->getJobsNew($request, "partial scheduled")->count();
        $completed_jobs = $this->getJobsNew($request, "completed not ready to bill")->count();
        $completed_billed_jobs = $this->getJobsNew($request, $status[6])->count();
        $ready_to_bill = $this->getJobsNew($request, $status[7])->count();
        $not_ready_to_bill  = $this->getJobsNew($request, "not ready to bill")->count();
        $today_jobs = $this->getJobsNew($request, $status[10])->count();
        $not_completed_jobs = $this->getJobsNew($request, $status[10])->count();
        // $request_jobs  = $this->getJobsNew($request,$status[10])->count();
        $overdue_jobs  =  $this->getJobsNew($request, "overdue")->count();
        $all_jobs = $this->getJobsNew($request, "all")->count();
        $cancelled = $this->getJobsNew($request, "cancelled")->count();
        // dd($cancelled);
        if ($user->role == 3) {
            $request->merge(['returnCount' => true]);
            $request->merge(['status_filter' => "today"]);
            $today_jobs = $this->stats($request);

            $request->merge(['status_filter' => "overdue"]);
            $overdue_jobs = $this->stats($request);


            $request->merge(['status_filter' => "scheduled"]);
            $scheduled_jobs = $this->stats($request);

            $request->merge(['status_filter' => "completed"]);
            $completed_jobs = $this->stats($request);


            $request->merge(['status_filter' => "not completed"]);
            $not_completed_jobs = $this->stats($request);
        }
        // $missing_pos  = $this->getJobsNew($request,$status[10])->count();
        $request->merge(['returnCount' => true]);
        $request->merge(['status_filter' => "not assigned"]);
        $assigned_jobs = $this->stats($request);

        $request->merge(['status_filter' => "overdue"]);
        $overdue_jobs = $this->stats($request);


        //Missing PO
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
        $clientProperties = $clientProperties->get();
        $query = Job::where('job_cancel', 0)->whereIn('property_id', $clientProperties);
        //$query = $query->orderby('id', 'desc')
        $query = $query->with('jobServices.service')
            ->whereHas('jobServices.service', function ($q) {
                $q->where('isActive', 1);
            })
            ->with('property')->with('property.client.user');
        $query = $query->where('po_number', Null)->orWhere('po_number', '=', '');
        $missing_pos = $query->count();
        //End MISSING PO






        //         $jobs = $this->getJobs();
        //         $ready_to_bill = $jobs->where('job_status','completed')->where('is_billed',2)->count();

        //         $jobs = $this->getJobs();
        //         $not_ready_to_bill = $jobs->where('is_billed',0)->count();
        //         $jobs = $this->getJobs();
        //         $completed_billed_jobs = $jobs->where('job_status', 'completed')->where('is_billed',1)->count();

        //         $jobs = $this->getJobs();
        //             $today_jobs = $jobs->whereDate('created_at', Carbon::today())
        //                 ->whereHas('requestedJobService', function($q){
        //                     $q->where('billed_date',NULL);
        //                 })->count();
        //             // not assigned jobs
        //             $jobs = $this->getJobs();
        //             $assigned_jobs = $jobs->whereHas('requestedJobService', function($q){
        //                 $q->where('assigned_to_type',Null);
        //             })->count();
        //             // over due jobs
        //             $jobs = $this->getJobs();
        //             $overdue_jobs = $jobs->whereHas('requestedJobService', function($q){
        //                 $q->where('assigned_to_type','!=',Null);
        //                 $q->whereNull('start_date');
        //                 $q->whereDate('scheduled_date', '<', Carbon::now());
        //             })->count();

        //             // completed not billed jobs
        //             $jobs = $this->getJobs();
        //             $completed_jobs = $jobs->where('job_status', 'completed')->where('is_billed', 0)
        //                ->count();
        // //            $completed_jobs = $jobs->where('job_status', 'completed')
        // //                ->whereHas('requestedJobService', function($q){
        // //                    $q->where('end_date','!=',Null);
        // //                    $q->where('billed_date',NULL);
        // //                })->count();

        //             // completed not billed jobs
        //             $jobs = $this->getJobs();
        //             $not_completed_jobs = $jobs->whereHas('requestedJobService', function($q){
        //                     $q->where('end_date',Null);
        //                 })->count();
        //             // request jobs
        $jobs = $this->getJobs();
        $request_jobs = $this->requestedJobs($request)->count();

        //scheduled jobs

        // if($user->role == 3){
        // $scheduled_jobs = $jobs
        //     ->whereHas('requestedJobService', function($q){
        //         $q->where('start_date',Null);
        //         $q->where('scheduled_date', '!=', Null);
        //         $q->whereDate('scheduled_date', '>=', Carbon::now());
        //     })->count();
        // }else{
        //     $scheduled_jobs = $jobs
        //      ->whereHas('requestedJobService', function($q){
        //         $q->where('scheduled_date', '!=', Null);
        //         $q->whereDate('scheduled_date', '>=', Carbon::now());
        //     })->count();
        // }
        // not scheduled


        //missing pos
        // $jobs = $this->getJobs();
        // $missing_pos = 0;
        // if($user->role == 2){
        //     // $user = Auth:: user();
        //     // $clientId = Client::where('user_id',$user->id)->first('id');
        //     // $clientProperties = ClientProperties::where('client_id',$clientId->id)->select('id')->get();
        //     $missing_pos = $jobs->where('po_number','=',Null)
        //                         ->with(['property','requestedJobService.service'])
        //                         ->whereHas('property.client',function ($q){
        //                             $q->where('is_PO_required',1);
        //                         })->count();
        // }

        return response()->json([
            'today' => $today_jobs,
            'assigned' => $assigned_jobs,
            'overdue' => $overdue_jobs,
            'completed' => $completed_jobs,
            'not_completed' => $not_completed_jobs,
            'request' => $request_jobs,
            'scheduled' => $scheduled_jobs,
            'not_scheduled' => $not_scheduled_jobs,
            'missing_pos' => $missing_pos,
            'partial_scheduled' => $partial_scheduled,
            'ready_to_bill' => $ready_to_bill,
            'not_ready_to_bill' => $not_ready_to_bill,
            'completed_billed_jobs' => $completed_billed_jobs,
            'cancelled' => $cancelled,
            'cancelled_jobs' => $cancelled,
            "all_jobs" => $all_jobs,
        ], 200);
    }
    public function getJobs()
    {
        // $requests = \App\Models\Request::count();
        $user = Auth::user();
        if ($user->role == 2) {
            if ($user->client) {
                $clientId = Client::where('user_id', $user->id)->first('id');
                $clientProperties = ClientProperties::where('client_id', $clientId->id)
                    ->where('isActive', 1)->select('id')->get();
            } else if ($user->staff) {
                $clientId = Client::where('id', $user->staff->parent_id)->first('id');
                //                if($user->staff->staff_roles->is_property_level == true){
                $clientProperties = ClientProperties::whereHas('staff', function ($q) use ($user) {
                    $q->where('staff_id', $user->staff->id);
                });
                $clientProperties = $clientProperties->where('is_quote', 0)->where('isActive', 1)->select('id')->get();
                //               }else{
                //                   $clientId = Client::where('id',$user->staff->parent_id)->first('id');
                //                   $clientProperties = ClientProperties::where('client_id',$clientId->id)->where('is_quote',0)
                //                   ->where('isActive', 1)->select('id')->get();
                //               }
            }
            $jobs = Job::whereIn('property_id', $clientProperties)
                ->with('requestedJobService.requestedJobSubService', function ($q) {
                    $q->where('is_delete', 0);
                })->with(['requestedJobService.requestedJobSubService' => function ($q) {
                    $q->where('is_delete', 0);
                }]);
            // $jobs = $jobs->with('requestedJobService.service');
            $jobs = $jobs->with('requestedJobService.service', function ($q) {
                $q->where('isActive', 1);
            });
        } else if ($user->role == 3) {
            $empId = Employe::where('user_id', $user->id)->first('id');
            $crewId = [];
            $myCrews = EmployeCrew::where('employee_id', $empId->id)->select('crew_id')->get();
            $i = 0;
            foreach ($myCrews as $crew) {
                $crewId[$i] = $crew['crew_id'];
                $i++;
            }
            $jobs = Job::whereHas('property', function ($q) {
                $q->where('isActive', 1);
            })
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
            $jobs = Job::whereHas('property', function ($q) {
                $q->where('isActive', 1);
            })
                ->with(['requestedJobService.requestedJobSubService' => function ($q) {
                    $q->where('is_delete', 0);
                }]);
            $jobs = $jobs->with('requestedJobService.service');
            $jobs = $jobs->with('requestedJobService.service', function ($q) {
                $q->where('isActive', 1);
            });
        }
        // $jobs = $jobs->whereHas('apartment_type',function($q){
        //     $q->where('isActive', 1);
        // });
        $jobs->where('is_quote', 0)->with('property.client.user');
        // ->whereHas('requestedJobService.requestedJobSubService',function($q){
        //     $q->where('is_delete', 0);
        // })->with(['requestedJobService.requestedJobSubService'=>function($q){
        //     $q->where('is_delete', 0);
        // }]);
        return $jobs;
    }

    public function getScheduleJobs(Request $request)
    {
        $user = Auth::user();
        $jobs = $this->getJobs();
        $jobs = $jobs->with(['requestedJobService.service', 'property', 'requestedJobService.employee.user', 'requestedJobService.employeeCrew'])
            ->with(['requestedJobService.requestedJobSubService' => function ($query) {
                $query->select('requested_job_service_id', DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
            }]);
        if ($user->role == 3) {
            $jobs = $jobs
                // ->whereIn('job_status', ['scheduled', 'completed'])
                ->whereHas('requestedJobService', function ($q) {
                    $q->where('start_date', Null);
                    $q->where('scheduled_date', '!=', Null);
                    // $q->whereDate('scheduled_date', '>=', Carbon::now());
                });
        } else {
            $jobs = $jobs
                // ->whereIn('job_status', ['scheduled', 'completed'])
                ->whereHas('requestedJobService', function ($q) {
                    $q->where('scheduled_date', '!=', Null);
                    // $q->whereDate('scheduled_date', '>=', Carbon::now());
                })
                ->with(['requestedJobService' => function ($q) {
                    $q->where('scheduled_date', '!=', Null);
                    // $q->whereDate('scheduled_date', '>=', Carbon::now());
                    $q->orderBy('scheduled_date');
                }]);
        }
        if ($user->staff) {
            $jobs = $jobs->whereHas('property.staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            });
        }
        $jobs = $jobs->whereHas('requestedJobService.service', function ($q) {
            $q->where('isActive', 1);
        });
        $jobs = $jobs->orderBy('id', 'desc')->get();
        $sortedJobs = [];
        foreach ($jobs as $job) {
            $temp = array();
            foreach ($job->requestedJobService as $service) {
                $temp['id'] = $service->id;
                $temp['jobId'] = $job->id;
                $temp['title'] = $job->property->title;
                $temp['unit'] = $job->apartment_number;
                $temp['serviceType'] = $service->service ? $service->service->category : '';
                $temp['date'] = $service->scheduled_date;
                $temp['employee']['type'] = 0;
                $temp['employee']['name'] = "";
                if ($service->assigned_to_id && $service->assigned_to_type == "individual") {
                    $user = $service->employee->user;
                    $temp['employee']['type'] = 1;
                    $temp['employee']['name'] = $user->first_name . " " . $user->middle_name . " " . $user->last_name ?? "";
                } else if ($service->assigned_to_id && $service->assigned_to_type == "crew") {
                    $temp['employee']['type'] = 2;
                    $temp['employee']['name'] = $service->employeeCrew->name;
                }
                $temp['scheduleDate'] = $service->scheduled_date;
                $time = $service->scheduled_time;
                if (!empty($service->scheduled_time)) {
                    $time = Carbon::parse($service->scheduled_time)->format('h:i a');
                }
                $temp['time'] = $time;
                $temp['scheduleTime'] = $time;
                $temp['url'] = url('/edit-job/' . $job->id);
                if ($job->job_status == 'completed') {
                    $temp['textColor'] = "green";
                } else {

                    $temp['textColor'] = "red";
                }
                if ($service->service && $service->service->color !== NULL) {
                    $temp['backgroundColor'] = $service->service->color;
                } else {
                    $temp['backgroundColor'] = "white";
                }
                $temp['borderColor'] = "grey";
                $sortedJobs[] = $temp;
            }
        }

        return response()->json([
            'jobs' => $sortedJobs,
        ], 200);
    }
}
