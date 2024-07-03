<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EmployeCrew;
use App\Models\RequestedJobService;
use App\Models\ServiceJobProgresAttatchment;
use App\Models\ServiceJobProgressAfterAttatchment;
use App\Models\ServiceJobProgress;
use App\Models\ClientpaintSpec;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CompleteJobEmailEvent;

class EmployeeJobController extends Controller
{
    public function index(Request $request)
    {

        $empId = $this->userEmployee();
        $crewId = [];
        $myCrews = EmployeCrew::where('employee_id', $empId)->select('crew_id')->get();
        $i = 0;
        foreach ($myCrews as $crew) {
            $crewId[$i] = $crew['crew_id'];
            $i++;
        }

        if ($request->jobId) {
            $query = RequestedJobService::when($request->jobfilter == 'today', function ($query) {
                $query = $query->whereHas('Job', function ($q) {
                    $q->whereDate('created_at', Carbon::today());
                    $q->where('billed_date', NULL);
                });
            })
                ->when($request->jobfilter == 'completed', function ($query) {
                    $query = $query->whereNotNull('end_date');
                })
                ->when($request->jobfilter == 'not-completed', function ($query) {
                    $query = $query->where('end_date', NULL);
                })
                ->whereHas('Job', function ($query) {
                    $query = $query->where('job_cancel', 0);
                })
                ->where('job_id', $request->jobId)
                ->whereHas('Job.property', function ($query) {
                    $query = $query->where('isActive', 1);
                })
                ->where(function ($query) use ($empId, $crewId) {
                    $query->where(function ($q) use ($empId) {
                        $q->where('assigned_to_type', 'individual');
                        $q->where('assigned_to_id', $empId);
                    });
                    $query->orWhere(function ($q) use ($crewId) {
                        $q->where('assigned_to_type', 'crew');
                        $q->whereIn('assigned_to_id', $crewId);
                    });
                })
                // ->whereHas('job.apartment_type',function($q){
                //     $q->where('isActive', 1);
                // })
                ->with('Job')
                ->with('service')
                ->whereHas('service', function ($q) {
                    $q->where('isActive', 1);
                })
                ->with('job.property')
                ->with('job.apartment_type');
            //->orderby('job_id', 'desc')
            // ->orderby('service_id', 'asc')
            // ->orderby('scheduled_date', 'desc')


            // apply sorting
            if ($request->sort_by == "id" || $request->sort_by == "scheduled_date" || $request->sort_by == "scheduled_time" || $request->sort_by == "is_billed") {
                $query = $query->orderBy($request->sort_by, $request->sort_order);
            } else if ($request->sort_by == "property") {
                $query = $query->orderByJoin('job.property.title', $request->sort_order);
            } else if ($request->sort_by == "apartment_number") {
                $query = $query->orderByJoin('job.apartment_number', $request->sort_order);
            } else if ($request->sort_by == "apartment_status") {
                $query = $query->orderByJoin('job.apartment_status', $request->sort_order);
            } else if ($request->sort_by == "service_type") {
                //$query = $query->orderByJoin('jobServices.requestedJobSubService.services.category', $request->sort_order);
                $query = $query->leftJoin('requested_job_services', 'jobs.id', '=', 'requested_job_services.job_id')->select('requested_job_services.*')
                    ->leftJoin('services', 'services.id', '=', 'requested_job_services.service_id')->select('services.*')
                    ->orderBy('services.category', $request->sort_order);
            } else if ($request->sort_by == "requested_on") {
                $query = $query->orderByJoin('requestedJobService.requested_date', $request->sort_order);
            } else if ($request->sort_by == "completed_on") {
                $query = $query->orderByJoin('requestedJobService.end_date', $request->sort_order);
            } else {
                $query = $query->orderBy('id', 'desc');
            }

            $myJobs = $query->get();
        } else {
            $query = RequestedJobService::when($request->jobfilter == 'today', function ($query) {
                $query = $query->whereHas('Job', function ($q) {
                    $q->whereDate('created_at', Carbon::today());
                    $q->where('billed_date', NULL);
                    $q->where('is_quote', 0);
                });
            })->distinct('job_id')->groupby('job_id')
                ->when($request->jobfilter == 'completed', function ($query) {
                    // $query = $query->where('end_date', '!=', Null);
                    // $query = $query->where('billed_date', NULL);
                    $query = $query->whereHas('Job', function ($q) {
                        $q->where('job_status', 'completed');
                        $q->where('is_quote', 0);
                    });
                })
                ->when($request->jobfilter == 'overdue', function ($query) use ($request) {
                    $query->whereHas('Job', function ($q) {
                        $q->where('job_status', '!=', 'completed');
                        $q->where('is_quote', 0);
                    });
                    $query->where('assigned_to_type', '!=', Null)->whereDate('scheduled_date', '<', Carbon::now());
                })
                ->when($request->jobfilter == 'scheduled', function ($query) use ($request) {
                    //    dd("here");
                    $query->whereHas('Job', function ($q) {
                        $q->where('job_status', 'scheduled');
                        $q->where('is_quote', 0);
                    });
                })


                ->when($request->jobfilter == 'not-completed', function ($query) {
                    $query = $query->where('end_date', NULL);
                })
                ->whereHas('Job', function ($query) {
                    $query = $query->where('job_cancel', 0);
                    $query->where('is_quote', 0);
                })
                ->whereHas('Job.property', function ($query) {
                    $query = $query->where('isActive', 1);
                })
                ->where(function ($query) use ($empId, $crewId) {
                    $query->where(function ($q) use ($empId) {
                        $q->where('assigned_to_type', 'individual');
                        $q->where('assigned_to_id', $empId);
                    });
                    $query->orWhere(function ($q) use ($crewId) {
                        $q->where('assigned_to_type', 'crew');
                        $q->whereIn('assigned_to_id', $crewId);
                    });
                })
                // ->whereHas('job.apartment_type',function($q){
                //     $q->where('isActive', 1);
                // })
                ->with('Job')
                ->with('service')
                ->whereHas('service', function ($q) {
                    $q->where('isActive', 1);
                })
                ->with('job.property')
                ->with('job.apartment_type');


            // apply sorting
            if ($request->sort_by == "id" || $request->sort_by == "scheduled_date" || $request->sort_by == "scheduled_time" || $request->sort_by == "is_billed") {
                if ($request->sort_by == "id") {
                    $query = $query->orderBy('job_id', $request->sort_order);
                } else {
                    $query = $query->orderBy($request->sort_by, $request->sort_order);
                }
            } else if ($request->sort_by == "property") {
                $query = $query->orderByJoin('job.property.title', $request->sort_order);
            } else if ($request->sort_by == "apartment_number") {
                $query = $query->orderByJoin('job.apartment_number', $request->sort_order);
            } else if ($request->sort_by == "apartment_status") {
                $query = $query->orderByJoin('job.apartment_status', $request->sort_order);
            } else if ($request->sort_by == "requested_on") {
                $query = $query->orderByJoin('requestedJobService.requested_date', $request->sort_order);
            } else if ($request->sort_by == "completed_on") {
                $query = $query->orderByJoin('requestedJobService.end_date', $request->sort_order);
            } else {
                $query = $query->orderBy('job_id', 'desc');
            }

            //->orderby('job_id','desc')
            $myJobs = $query->get();
        }

        return response()->json([
            'jobs' => $myJobs,
        ], 200);
    }

    public function requestedService($id)
    {

        $empId = $this->userEmployee();

        $requestdService = RequestedJobService::with([
            'job',
            'service',
            'requestedJobSubService.subService',
            'job.property',
            'job.property.staff',
            'job.property.client',
            'job.property.client.user',
            'job.requestedJobService.jobAttatchment',
            'job.apartment_type'
        ])
            // ->whereHas('job.apartment_type',function($q){
            //     $q->where('isActive', 1);
            // })
            ->find($id);

        $paintSpec = ClientpaintSpec::with([
            'paintFinish', 'paintGrade', 'paintSurface', 'paintMethod', 'paintManufacturer', 'paintColor'
        ])->where('property_id', $requestdService->job->property_id)->get();

        $progress = ServiceJobProgress::where('requested_job_service_id', $id)->get();

        $item = ServiceJobProgress::where('created_by', $empId)
            ->where('end_time', null)->first();
        if ($item) {
            return response()->json([
                'otherJobStarted' => true,
                'requestedService' => $requestdService,
                'progress' => $progress,
                'paintSpec' => $paintSpec
            ], 200);
        } else {
            return response()->json([
                'otherJobStarted' => false,
                'requestedService' => $requestdService,
                'progress' => $progress,
                'paintSpec' => $paintSpec
            ], 200);
        }
    }



    public function clockIn($id, Request $request)
    {

        try {
            $request->time = Carbon::createFromFormat('m-d-Y H:i', $request->time)->format('Y-m-d H:i');
        } catch (\Throwable $th) {
            //throw $th;
        }

        $empId = $this->userEmployee();
        $data = new ServiceJobProgress();
        $data->created_by               = $empId;
        $data->start_time               = $request->time;
        $data->requested_job_service_id = $id;
        $data->save();

        //enter start date
        $requestedService = RequestedJobService::find($id);
        if (isset($requestdService) && $requestdService != null) {
            $requestedService->start_date = date("Y-m-d");
            $requestedService->save();
        }


        return response()->json([
            'message' => 'Clock In Successfully',
        ], 200);
    }

    public function clockOut($id, Request $request)
    {
        $empId = $this->userEmployee();
        $data = ServiceJobProgress::find($id);
        $data->end_time = $request->time;
        $data->save();

        return response()->json([
            'message' => 'Clock Out Successfully',
        ], 200);
    }

    public function jobCompleted($id, Request $request)
    {
        //        $empId = $this->userEmployee();
        $progress = ServiceJobProgress::where('requested_job_service_id', $id)->count();
        if ($progress == 0) {
            return response()->json([
                'noProgress' => true,
            ], 200);
        }
        $data = ServiceJobProgress::where('requested_job_service_id', $id)
            ->where('end_time', null)->first();

        if ($data) {
            return response()->json([
                'timerRunning' => true,
            ], 200);
        }
        $service = RequestedJobService::find($id);
        try {
            $sjp = ServiceJobProgress::where('requested_job_service_id', $service['id'])->first();
            $service->end_date =  Carbon::parse($sjp->end_time)->format('Y-m-d');
        } catch (\Exception $e) {
            $service->end_date = date("Y-m-d");
        }

        $service->notes    = $request->notes;
        $service->save();

        if (isset($request->uploadedImagesPath) && !empty($request->uploadedImagesPath)) {
            $service->ServiceJobProgresAttatchment()->delete();
            foreach ($request->uploadedImagesPath as $image) {
                $im = new ServiceJobProgresAttatchment();
                $im->requested_job_service_id = $service->id;
                $im->file_name               = $image;
                $im->save();
            }
        }
        if (isset($request->afterUploadedImagesPath) && !empty($request->afterUploadedImagesPath)) {
            $service->ServiceJobProgressAfterAttatchment()->delete();
            foreach ($request->afterUploadedImagesPath as $image) {
                $im = new ServiceJobProgressAfterAttatchment();
                $im->requested_job_service_id = $service->id;
                $im->file_name               = $image;
                $im->save();
            }
        }
        $remainingJobs = RequestedJobService::where('job_id', $service->job_id)->where('end_date', '=', null)->count();
        if ($remainingJobs == 0) {
            $job = Job::find($service->job_id);
            $job->job_status = 'completed';
            $job->save();
        }

        // email trigger
        $data = Job::with('property')
            ->with('property.staff')
            ->with('property.client.user')
            ->with('jobServices.service')
            ->with('apartment_type')
            ->with('jobServices.employee.user')
            ->with('jobServices.employeeCrew.employe.user')
            ->with(['jobServices' => function ($q) use ($service) {
                $q->where('id', $service->id);
            }])
            // ->whereHas('apartment_type',function($q){
            //     $q->where('isActive', 1);
            // })
            ->find($service->job_id);

        $jobcontroller =  new JobController();
        $jobcontroller->updateJobStatus($service->job_id);
        event(new CompleteJobEmailEvent($data));
    }
    public function jobProgress($id, Request $request)
    {
        $empId = $this->userEmployee();
        $progress = ServiceJobProgress::where('requested_job_service_id', $id)->count();
        if ($progress == 0) {
            return response()->json([
                'disabled' => true,
            ], 200);
        }
        $data = ServiceJobProgress::where('requested_job_service_id', $id)
            ->where('created_by', $empId)
            ->where('end_time', null)->first();

        if ($data) {
            return response()->json([
                'disabled' => true,
            ], 200);
        } else {
            return response()->json([
                'disabled' => false,
            ], 200);
        }
    }
    public function employeejobProgress($serviceid, $employeeId)
    {
        $emp = Employe::where('id', $employeeId)->select('id')->first();
        $progress = ServiceJobProgress::where('requested_job_service_id', $serviceid)->count();
        $requestedJob = RequestedJobService::select('id', 'end_date', 'notes')->find($serviceid);
        if ($progress == 0) {
            return response()->json([
                'status' => "not_started",
                'completed' => $requestedJob && $requestedJob->end_date != null ? true : false,
            ], 200);
        }
        $data = ServiceJobProgress::where('requested_job_service_id', $serviceid)
            ->where('end_time', null)->first();

        if ($data) {
            return response()->json([
                'status' => "in_progress",
                'completed' => $requestedJob && $requestedJob->end_date != null ? true : false,
            ], 200);
        } else {

            return response()->json([
                'status' => "ready_to_complete",
                'completed' => $requestedJob && $requestedJob->end_date != null ? true : false,
            ], 200);
        }
    }


    public function jobReport($id, Request $request)
    {
        $data = RequestedJobService::find($id);
        if ($data) {
            $data->notes    = $request->notes;
            $data->save();
            if ($request->modifiedBefore == "modified") {
                $data->ServiceJobProgresAttatchment()->delete();
            }
            if (isset($request->uploadedImagesPath) && !empty($request->uploadedImagesPath)) {
                $data->ServiceJobProgresAttatchment()->delete();
                foreach ($request->uploadedImagesPath as $image) {
                    $im = new ServiceJobProgresAttatchment();
                    $im->requested_job_service_id = $data->id;
                    $im->file_name               = $image;
                    $im->save();
                }
            }
            if ($request->modifiedAfter == "modified") {
                $data->ServiceJobProgressAfterAttatchment()->delete();
            }
            if (isset($request->afterUploadedImagesPath) && !empty($request->afterUploadedImagesPath)) {
                $data->ServiceJobProgressAfterAttatchment()->delete();
                foreach ($request->afterUploadedImagesPath as $image) {
                    $im = new ServiceJobProgressAfterAttatchment();
                    $im->requested_job_service_id = $data->id;
                    $im->file_name               = $image;
                    $im->save();
                }
            }

            return response()->json([
                'message' => 'Report Saved Successfully',
            ], 200);
        }
    }
    public function savejobReport($id, Request $request)
    {
        $requestedService = RequestedJobService::find($id);
        if (isset($request->start_time, $request->end_time) && $request->start_time >= $request->end_time) {
            return response()->json([
                'error' => 'Start Date should be less than or Equal to End Date',
            ], 200);
        } else {
            if ($requestedService) {
                $checkprogres = ServiceJobProgress::where('requested_job_service_id', $requestedService->id)->first();
                if (empty($checkprogres)) {
                    if ($request->start_time && $request->has('start_time')) {
                        $data = new ServiceJobProgress();
                        $data->created_by               = $requestedService->assigned_to_id ? $requestedService->assigned_to_id : Auth::user()->id;
                        $data->start_time               = $request->start_time;
                        $data->requested_job_service_id = $id;
                        $data->save();

                        //enter start date
                        $requestedService->start_date = date("Y-m-d");
                        $requestedService->save();
                    }
                }

                if ($request->end_time && $request->has('end_time')) {
                    $data = ServiceJobProgress::where('requested_job_service_id', $requestedService->id)->first();
                    if ($data->start_time) {
                        $data->end_time = $request->end_time;
                        $data->save();

                        //enter end date to requested service
                        //                $requestedService->end_date = $request->end_time;
                        //                $requestedService->save();
                    }
                }

                $requestedService->notes    = $request->notes;
                $requestedService->save();
                if ($request->removedFiles) {
                    if (isset($request->removedFiles['before'])) {
                        ServiceJobProgresAttatchment::where('requested_job_service_id', $requestedService->id)
                            ->whereIn('file_name', $request->removedFiles['before'])->delete();
                    }
                    if (isset($request->removedFiles['after'])) {
                        ServiceJobProgressAfterAttatchment::where('requested_job_service_id', $requestedService->id)
                            ->whereIn('file_name', $request->removedFiles['after'])->delete();
                    }
                }
                if (isset($request->uploadedImagesPath) && !empty($request->uploadedImagesPath)) {
                    $requestedService->ServiceJobProgresAttatchment()->delete();
                    foreach ($request->uploadedImagesPath as $image) {
                        $im = new ServiceJobProgresAttatchment();
                        $im->requested_job_service_id = $requestedService->id;
                        $im->file_name               = $image;
                        $im->save();
                    }
                }
                if (isset($request->afterUploadedImagesPath) && !empty($request->afterUploadedImagesPath)) {
                    $requestedService->ServiceJobProgressAfterAttatchment()->delete();
                    foreach ($request->afterUploadedImagesPath as $image) {
                        $im = new ServiceJobProgressAfterAttatchment();
                        $im->requested_job_service_id = $requestedService->id;
                        $im->file_name               = $image;
                        $im->save();
                    }
                }

                return response()->json([
                    'message' => 'Report Saved Successfully',
                ], 200);
            }
        }
    }

    public function timesheet(Request $request, $empId = null)
    {

        $totalminutes = 0;
        if (!isset($empId)) {
            $empId = $this->userEmployee();
        }
        $period = [];
        if ($request->date_filter) {
            $period = explode(',', $request->date_filter);
        }
        $query = ServiceJobProgress::with('requestedJobService.requestedJobSubService.subService')
            ->with('requestedJobService.service')
            ->with('requestedJobService.job.property')
            ->with('requestedJobService.job.apartment_type')
            ->where('created_by', $empId)
            // ->whereHas('requestedJobService.job.apartment_type', function($q){
            //     $q->where('isActive', 1);
            // })
            ->whereHas('requestedJobService.service', function ($q) {
                $q->where('isActive', 1);
            })
            ->whereBetween('start_time', $period);
        // ->whereDate('start_time', '<=', $request->endDate)

        // apply sorting
        if ($request->sort_by != "") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query = $query->orderBy("id", "DESC");
        }

        $timesheet = $query->get();
        foreach ($timesheet as $time) {
            if ($time->end_time) {
                $diff = Carbon::parse($time->end_time)->diffInMinutes(Carbon::parse($time->start_time));
                $totalminutes = $totalminutes + $diff;
                $hoursProgress = intdiv($diff, 60) . 'h:' . ($diff % 60) . 'm';
                $time->hours = $hoursProgress;
                $time->in_progress = false;
            } else {
                $time->in_progress = true;
            }
        }
        $hours = intdiv($totalminutes, 60) . 'h:' . ($totalminutes % 60) . 'm';

        return response()->json([
            'timeRecords' => $timesheet,
            'totalHours' => $hours,
        ], 200);
    }

    public function userEmployee()
    {
        $emp = Employe::where('user_id', Auth::user()->id)->select('id')->first();
        return (!is_null($emp)) ? $emp->id : '';
    }
    public function editTimeSheet($id)
    {
        $timesheet = ServiceJobProgress::find($id);
        return response()->json([
            'timeRecord' => $timesheet
        ]);
    }
    public function updateTimeSheet($id, Request $request)
    {
        $timesheet = ServiceJobProgress::find($id);
        if ($timesheet) {
            $timesheet->start_time = $request->start_date;
            $timesheet->end_time = $request->end_date;
            // $timesheet->notes = $request->notes;
            $timesheet->save();
        }
        return response()->json([
            'timeRecord' => $timesheet
        ]);
    }
    public function getNotesAttachment($id)
    {

        $notes = RequestedJobService::with('serviceJobProgresAttatchment', 'serviceJobProgressAfterAttatchment', 'job')->select('id', 'end_date', 'notes', 'scheduled_date')->find($id);
        if ($notes) {
            $notes->date_disable_from = $notes && isset($notes->scheduled_date) ? Carbon::parse($notes->scheduled_date)->subDay(1) : "";
        }

        if ($notes && $notes->end_date != null) {
            return response()->json([
                'progress' => $notes,
                'completed' => true,
            ]);
        } else {
            return response()->json([
                'progress' => $notes,
                'completed' => false
            ]);
        }
    }
}
