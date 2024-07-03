<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TaxType;
use App\Models\ClientLogo;
use App\Models\Staff;
use App\Models\ClientProperties;
use App\Models\RequestedJobService;
use App\Models\QuoteAttatchment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->client) {
            $query = Client::where('user_id', $user->id)->with(['properties' => function ($q) {
                $q->where('isActive', 1);
            }]);
        } else if ($user->staff) {
            if ($user->staff->staff_roles->is_property_level == true) {
                $query = Client::where('id', $user->staff->parent_id)
                    ->with(['properties' => function ($q) use ($user) {
                        $q->where('isActive', 1);
                        $q->whereHas('staff', function ($qq) use ($user) {
                            $qq->where('staff_id', $user->staff->id);
                        });
                    }]);
            } else {
                $query = Client::where('id', $user->staff->parent_id)->with(['properties' => function ($q) {
                    $q->where('isActive', 1);
                }]);
            }
        } else {
            $query = Client::query()->with(['properties' => function ($q) {
                $q->where('isActive', 1);
            }]);
        }
        $query = $query->orderby('company', 'asc')->with('user');
        if ($request->status_filter && $request->status_filter !== "all") {
            $query = $query->whereHas('user', function ($q) use ($request) {
                if ($request->status_filter == 'active') {
                    $q->Where('user_activated', 1);
                } else if ($request->status_filter == 'not active') {
                    $q->Where('user_activated', 0);
                }
            });
        }
        if ($request->search) {
            $keyword = $request->search;
            $query = $query->whereHas("user", function ($q) use ($keyword) {
                $q->where('email', 'LIKE', "%" . $keyword . "%");
            });

            $query = $query->orWhere(function ($query) use ($keyword) {
                $query->where('company', 'LIKE', "%{$keyword}%");
                $query->orWhereHas("properties", function ($query) use ($keyword) {
                    $query->where('title', "LIKE", "%" . $keyword . "%");
                    $query->where('isActive', 1);
                });
            });
            // dd($query->get());
        }
        if ($user->staff && $user->staff->staff_roles->is_property_level == true) {
            $query = $query->withCount(['properties' => function ($q) use ($user) {
                $q->where('is_quote', '!=', 1);
                $q->where('isActive', 1);
                $q->whereHas('staff', function ($qq) use ($user) {
                    $qq->where('staff_id', $user->staff->id);
                });
            }]);
            $query = $query->withCount(['properties as quotes_count' => function ($q) use ($user) {
                $q->where('is_quote', '!=', 0);
                $q->where('isActive', 1);
                $q->whereHas('staff', function ($qq) use ($user) {
                    $qq->where('staff_id', $user->staff->id);
                });
            }]);
        } else {
            $query = $query->withCount(['properties' => function ($q) {
                $q->where('is_quote', '!=', 1);
                $q->where('isActive', 1);
            }]);
            $query = $query->withCount(['properties as quotes_count' => function ($q) {
                $q->where('is_quote', '!=', 0);
                $q->where('isActive', 1);
            }]);
        }
        $query = $query->with(['staff.user', 'staff' => function ($q) {
            $q->with('staff_roles')->whereHas('staff_roles', function ($q) {
                $q->where('is_property_level', 0);
            });
        }]);
        $query = $query->withCount('staff as client_staff_count');
        $query = $query->with('properties.taxType', 'properties.quoteAttatchment', 'properties.staff.user', 'properties.staff.staff_roles', 'logo');
        // dd($query->get());
        $data = $query->paginate($request->size);
        return response()->json([
            'clients' => $data,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $current_date_time = Carbon::now()->timestamp;
            $destinationPath = 'uploads';
            $fileName = $current_date_time . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $fileName);
            $filepath = $destinationPath . '/' . $fileName;
        }
        $password = $request->password;
        if ($password == "") {
            $password = "password";
        }
        $userData = array(
            "email" => $request->email,
            "password" => $password,
            "role" => "2",
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name ?? '',
            "last_name" => $request->last_name ?? '',
            "profile_image" => isset($filepath) ?  $filepath : null,
            "user_activated" => $request->user_activated,
        );

        $user_id = $this->register($userData);

        if ($user_id == 0) {
            return response()->json([
                'error' => 'This Email Address Already Exist',
            ], 201);
        }


        $data = new Client();
        $data->user_id        = $user_id;
        $data->apartment_name = $request->apartment_name ?? '';
        $data->company        = $request->company ?? '';
        $data->street_address = $request->street_address ?? '';
        $data->notes          = $request->notes ?? '';
        $data->city           = $request->city ?? '';
        $data->state          = $request->state ?? '';
        $data->country        = $request->country ?? '';
        $data->zipcode        = $request->zipcode ?? '';
        $data->phone          = $request->phone ?? '';
        $data->web            = $request->web ?? '';
        $data->contact_title  = $request->contact_title ?? '';
        $data->contact_name   = $request->contact_name ?? '';
        $data->date_of_birth  = $request->date_of_birth  ?? '';
        $data->save();
        if (isset($request->filePaths) && !empty($request->filePaths)) {
            $clientLogo = new ClientLogo();
            $clientLogo->client_id = $data->id;
            $clientLogo->file_name = $request->filePaths;
            $clientLogo->save();
        }

        return response()->json([
            'client_id' => $data->id,
        ], 200);
    }

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
    public function edit($id)
    {
        $data = Client::with('user')->with('logo')->find($id);
        return response()->json([
            'client' => $data,
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

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $current_date_time = Carbon::now()->timestamp;
            $destinationPath = 'uploads';
            $fileName = $current_date_time . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $fileName);
            $filepath = $destinationPath . '/' . $fileName;
        }
        $data = User::find($request->user_id);
        $data->first_name        = $request->first_name;
        $data->middle_name  = $request->middle_name ?? '';
        $data->last_name    = $request->last_name ?? '';
        $data->email          = $request->email;
        $data->profile_image = isset($filepath) ?  $filepath : $data->profile_image;
        if (isset($request->user_activated)) {
            $data->user_activated    = ($request->user_activated) ? 1 : 0;
        }
        if ($request->password != "") {
            $data->password = bcrypt($request->password);;
            $data->encrypted_password = $request->password;
        }
        $data->save();

        $data = Client::find($id);
        $data->user_id        = $request->user_id ?? '';
        $data->apartment_name = $request->apartment_name ?? '';
        // $data->name           = $request->apartment_name ?? '';
        $data->company        = $request->company ?? '';
        $data->street_address = $request->street_address ?? '';
        $data->notes          = $request->notes ?? '';
        $data->city           = $request->city ?? '';
        $data->state          = $request->state ?? '';
        $data->country        = $request->country ?? '';
        $data->zipcode        = $request->zipcode ?? '';
        $data->phone          = $request->phone ?? '';
        $data->web            = $request->web ?? '';
        // $data->manager       = $request->manager ?? '';
        $data->contact_title  = $request->contact_title ?? '';
        $data->contact_name   = $request->contact_name ?? '';
        $data->date_of_birth  = $request->date_of_birth ?? '';
        // $data->is_PO_required  = $request->is_PO_required;
        $data->save();

        if ($request->logoModified == "modified") {
            $data->logo()->delete();
        }
        if (isset($request->filePaths) && !empty($request->filePaths)) {
            $data->logo()->delete();
            $clientLogo = new ClientLogo();
            $clientLogo->client_id = $id;
            $clientLogo->file_name = $request->filePaths;
            $clientLogo->save();
        }
        if (isset($request->filePaths) && empty($request->filePaths)) {
            $data->logo()->delete();
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
        $client = Client::find($id);
        try {
            //inactive client and staff user
            $users =  User::where('id', $client->user_id)
                ->orWhereHas('staff', function ($q) use ($id) {
                    $q->where('parent_id', $id);
                })->get();
            if ($users) {
                foreach ($users as $user) {
                    $user->is_active = 0;
                    $user->save();
                }
            }
            //inactive staff profile
            $staffs = Staff::where('parent_id', $id)->get();
            if ($staffs) {
                foreach ($staffs as $staff) {
                    $staff->isActive = 0;
                    $staff->save();
                }
            }
            //inactive property
            $properties = ClientProperties::where('client_id', $id)->get();
            if ($properties) {
                foreach ($properties as $property) {
                    $property->isActive = 0;
                    $property->save();
                }
            }
            //inactive client profile
            $client->isActive = 0;
            $client->save();
            return response()->json([
                'msg' => "Record has been deleted",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {

            return response()->json([
                'error' => "Client cannot be deleted due to existence of related data.",
            ], 200);
        }
    }


    public function register($request)
    {
        // $v = Validator::make($request, [
        //     'email' => ['required', 'email', Rule::unique('users')->where('is_active', 1)],
        // ]);
        // if ($v->fails()) {
        //     return 0;
        // }
        $user = new User();
        $user->email = $request['email'];
        $user->first_name = $request['first_name'];
        $user->middle_name = $request['middle_name'];
        $user->last_name = $request['last_name'];
        $user->password = bcrypt($request['password']);
        if (isset($request['user_activated'])) {
            $user->user_activated = ($request['user_activated']) ? 1 : 0;
        }
        $user->profile_image = $request['profile_image'];
        $user->is_active = 1;
        $user->role = $request['role'];
        $user->save();
        return $user->id;
    }
    // public function getclientManagers(){

    //     $data = User::where('role', 1)->get();
    //     return response()->json([
    //         'managers' => $data,
    //     ],200);
    // }

    public function clientProperty($id, Request $request)
    {
        $client = Client::find($id);
        $data = ClientProperties::with('taxType')
            ->with('appartment_types')
            ->where('client_id', $id)
            ->where('is_quote', 0)
            ->where(function ($query) use ($request) {
                $query->where('title', 'LIKE', "%{$request->search}%")
                    ->orwhereHas('taxType', function ($q) use ($request) {
                        $q->where('name', 'LIKE', "%{$request->search}%");
                    });
            });

        $user = Auth::user();
        if ($user->staff) {
            $data = $data->whereHas('staff', function ($qq) use ($user) {
                $qq->where('staff_id', $user->staff->id);
            });
        }
        $data = $data->orderby($request->sort_by, $request->sort_order)->paginate($request->size);
        return response()->json([
            'properties' => $data,
            'client' => $client
        ], 200);
    }
    public function staffProperty(Request $request)
    {
        $data = ClientProperties::with('taxType')
            ->with('appartment_types')
            ->where('staff_id', auth()->user()->id)
            ->where(function ($query) use ($request) {
                $query->where('title', 'LIKE', "%{$request->search}%")
                    ->orwhereHas('taxType', function ($q) use ($request) {
                        $q->where('name', 'LIKE', "%{$request->search}%");
                    });
            })
            ->where('isActive', 1)
            ->orderby('id', 'desc')->paginate($request->size);
        return response()->json([
            'properties' => $data,
        ], 200);
    }

    public function clientQuote(Request $request, $id = null)
    {
        if ($id) {
            $data = ClientProperties::with('taxType')
                ->with('appartment_types')
                ->with('quoteAttatchment')
                ->with('client')
                ->where('client_id', $id)
                ->where('is_quote', 1)
                ->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', "%{$request->search}%")
                        ->orwhereHas('taxType', function ($q) use ($request) {
                            $q->where('name', 'LIKE', "%{$request->search}%");
                        });
                })
                ->where('isActive', 1)
                ->orderby('id', 'desc')->paginate($request->size);
        } else {
            $data = ClientProperties::with('taxType')
                ->with('appartment_types')
                ->with('quoteAttatchment')
                ->with('client')
                ->where('is_quote', 1)
                ->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', "%{$request->search}%")
                        ->orwhereHas('taxType', function ($q) use ($request) {
                            $q->where('name', 'LIKE', "%{$request->search}%");
                        });
                })
                ->where('isActive', 1)
                ->orderby('id', 'desc')->paginate($request->size);
        }
        return response()->json([
            'quotes' => $data,
        ], 200);
    }
    public function getQuoteList(Request $request, $id = null)
    {


        $user = Auth::user();
        if (($user->client || ($request->clientId && $request->clientId != "null" && $request->clientId) && !$user->staff)) {
            if (($request->clientId && $request->clientId != "null" && $request->clientId)) {
                $clientProperties = ClientProperties::where('client_id', $request->clientId)
                    ->where('isActive', 1)->select('id')->get();

                $query = Job::where('type', 'quote')->whereIn('property_id', $clientProperties)->with('apartment_type', function ($q) {
                    $q->where('isActive', 1);
                });
                // if ($user->client)
                // {
                //     $query = $query ->orWhere('created_by', $request->clientId);
                // }


            } else {
                $clientId = Client::where('user_id', $user->id)->first('id');
                $clientProperties = ClientProperties::where('client_id', $clientId->id)
                    ->where('isActive', 1)->select('id')->get();

                //            $query = Job::where('type', 'quote')->where('quote_status', '!=' ,'draft')->whereIn('property_id',$clientProperties)->whereHas('apartment_type',function($q){
                //                $q->where('isActive', 1);
                //            })->orWhere('created_by',$user->id);


                $query = Job::where('type', 'quote')->whereIn('property_id', $clientProperties)->with('apartment_type', function ($q) {
                    $q->where('isActive', 1);
                })->orWhere('created_by', $user->id);
            }
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
        // if ($request->data_filter) {
        //     if (strpos($request->data_filter, ",")) {
        //         $dates = explode(',', $request->data_filter);
        //         $query = $query->whereHas('jobServices', function ($q) use ($dates) {
        //             $q->whereBetween('requested_date', $dates);
        //         });
        //     } else {
        //         $date = Carbon::parse($request->data_filter);
        //         $date     = $date->format('Y-m-d');
        //         // $date = date('Y-m-d', strtotime($request->data_filter));
        //         $query = $query->with('jobServices', function ($q) use ($date) {
        //             $q->whereDate('requested_date', date($date));
        //         });
        //     }
        // }

        if ($request->data_filter) {
            if (strpos($request->data_filter, ",")) {
                $dates = explode(',', $request->data_filter);
                // $query = $query->whereHas('created_at', function ($q) use ($dates) {
                //     $q->whereBetween('created_at', $dates);
                // });
                $query = $query->whereBetween('created_at', $dates);

            } else {
                $query = $query->whereDate('created_at', $request->data_filter);
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
        $data = $query->paginate($request->size);
        return response()->json([
            'quotes' => $data,
        ], 200);
    }

    // public function getclientSupplier($id){

    //     $data = ClientSupplier::where('client_id',$id)->first();
    //     return response()->json([
    //         'supplier' => $data,
    //     ],200);
    // }

    // public function updateclientSupplier(Request $request , $id){

    //     $data          = ClientSupplier::find($id);
    //     $data->details = $request->details;
    //     $data->save();
    //     return response()->json([
    //         'msg' => "Record has been updated",
    //     ],200);
    // }


    public function appHistoryData()
    {
        $user = Auth::user();
        if ($user->client) {
            $client_id = $user->client->id;
            $properties = ClientProperties::with("appartment_types")->with("client")->where('client_id', $client_id)
                ->where('is_quote', 0)->where('isActive', 1)->get();
        } else if ($user->staff) {
            //            if($user->staff->staff_roles->is_property_level == true){
            $properties = ClientProperties::with("appartment_types")->with("client")->whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            })->where('isActive', 1)->get();
            //            }else{
            //                $properties = ClientProperties::with("appartment_types")->where('client_id', $user->staff->parent_id)
            //                ->where('is_quote',0)->where('isActive',1)->get();
            //            }
        } else {
            $properties = ClientProperties::with("client")->where('is_quote', 0)->where('isActive', 1)->get();
        }
        $services = Service::orderBy('sequence_order', 'asc')->get();
        return response()->json([
            'services' => $services,
            'properties' => $properties
        ], 200);
    }

    public function searchClientHistory(Request $request)
    {
        // $client_id = Auth::user()->client->id;
        // $client_properties = ClientProperties::where('client_id', $client_id)->where('is_quote',0)->pluck('id');
        $user = Auth::user();
        if ($user->client) {
            $client_id = $user->client->id;
            $client_properties = ClientProperties::with("appartment_types")->where('client_id', $client_id)
                ->where('isActive', 1)->where('is_quote', 0)->pluck('id');
        } else if ($user->staff) {
            //            if($user->staff->staff_roles->is_property_level == true){
            $client_properties = ClientProperties::with("appartment_types")->whereHas('staff', function ($q) use ($user) {
                $q->where('staff_id', $user->staff->id);
            })->where('isActive', 1)->pluck('id');
            //            }else{
            //                $client_properties = ClientProperties::with("appartment_types")->where('client_id', $user->staff->parent_id)
            //            ->where('isActive',1)->where('is_quote',0)->pluck('id');
            //            }
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
        if ($request->propertyId) {
            $jobs = $jobs->where('property_id', $request->propertyId);
        }
        if ($request->unit_number) {
            $jobs = $jobs->where('apartment_number', 'like', '%' . $request->unit_number . '%');
        }
        if ($request->po_number) {
            $jobs = $jobs->where('po_number', 'like', '%' . $request->po_number . '%');
        }
        if ($request->job_number) {
            $jobs = $jobs->where('id', $request->job_number);
        }
        if ($request->invoice_number) {
            $invoice_number = $request->invoice_number;
            $jobs = $jobs->whereHas('invoice', function ($query) use ($invoice_number) {
                $query->where('id', $invoice_number);
            });
        }
        // if($request->completion_date){
        //     $billed_date = $request->completion_date;
        //     $jobs = $jobs->whereHas('requestedJobService',function($query) use ($billed_date){
        //                 $query->whereDate('end_date',$billed_date);
        //                 // $query->where('billed_date','=',$billed_date);
        //     });
        // }
        if ($request->completed_date_filter) {
            $period = $request->completed_date_filter;
            $jobs = $jobs->whereHas('requestedJobService', function ($query) use ($period) {
                $query->whereBetween('end_date', $period);
                // $query->where('billed_date','=',$billed_date);
            });
        }
        // if($request->completion_date_end){
        //     $jobs = $jobs->whereHas('requestedJobService',function($query) use ($request){
        //         $query->whereDate('end_date','<=',$request->completion_date_end);
        //         // $query->where('billed_date','=',$billed_date);
        //     });
        // }
        if ($request->scheduled_date_filter) {
            $period = $request->scheduled_date_filter;
            $jobs = $jobs->whereHas('requestedJobService', function ($query) use ($period) {
                $query->whereBetween('scheduled_date', $period);
                // $query->where('billed_date','=',$billed_date);
            });
        }
        // if($request->scheduled_date_end){
        //     $jobs = $jobs->whereHas('requestedJobService',function($query) use ($request){
        //         $query->whereDate('scheduled_date', '<=',$request->scheduled_date_end);
        //         // $query->where('billed_date','=',$billed_date);
        //     });
        // }
        if ($request->serviceId) {
            $service = $request->serviceId;
            $jobs = $jobs->whereHas('requestedJobService', function ($query) use ($service) {
                $query->where('service_id', $service);
            });
        }
        // if($request->scheduled_date){
        //     $scheduled_date = $request->scheduled_date;
        //     $jobs = $jobs->whereHas('requestedJobService', function($query) use ($scheduled_date){
        //         $query->whereDate('scheduled_date','=',$scheduled_date);
        //     });
        // }
        $jobs = $jobs->with(['requestedJobService.requestedJobSubService' => function ($query) {
            $query->select('requested_job_service_id', \DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
        }]);
        $jobs = $jobs->with('property')->with('property.client.user');
        $jobs = $jobs->with('requestedJobService.service');
        $jobs = $jobs->whereHas('requestedJobService.service', function ($q) {
            $q->where('isActive', 1);
        });

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "apartment_number" || $request->sort_by == "po_number" || $request->sort_by == "job_status" || $request->sort_by == "total") {
            $jobs = $jobs->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $jobs = $jobs->orderByJoin('property.title', $request->sort_order);
        } else if ($request->sort_by == "invoice_no") {
            $jobs = $jobs->orderByJoin('invoice.id', $request->sort_order);
        } else if ($request->sort_by == "service_type") {
            $jobs = $jobs->orderByJoin('jobServices.requestedJobSubService.services.category', $request->sort_order);
        } else {
            $jobs = $jobs->orderBy('id', 'desc');
        }

        $jobs = $jobs->paginate($request->size);
        return response()->json([
            'jobs' => $jobs,
        ], 200);
    }
    public function getRequiredPoJobs(Request $request)
    {
        $user = Auth::user();
        $clientId = Client::where('user_id', $user->id)->first('id');
        $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
            ->where('isActive', 1)->select('id')->get();
        $query = Job::whereIn('property_id', $clientProperties)->where('po_number', '=', Null);
        $query = $query->orderby('id', 'desc')
            ->with(['requestedJobService.requestedJobSubService' => function ($query) {
                $query->select('requested_job_service_id', \DB::raw('sum(base_price) as total'))->groupBy('requested_job_service_id');
            }])
            ->with(['property', 'requestedJobService.service'])
            ->whereHas('property', function ($q) {
                $q->where('is_PO_required', 1);
            });
        $data = $query->paginate($request->size);
        return response()->json([
            'jobs' => $data,
        ], 200);
    }
    public function checkQBStatus()
    {
        $qbToken = Setting::where('key', 'qb_token')->first();
        $isSynced = false;
        if ($qbToken) {
            $isSynced = true;
        }
        return response()->json([
            'isSynced' => $isSynced,
        ], 200);
    }
    public function updateClientBilling(Request $request, $id)
    {

        if (!empty($request->contact_email)) {
            $check = Client::where('id', '!=', $id)->where('contact_email', $request->contact_email)->first();
            if ($check) {
                return response()->json([
                    'code' => 302,
                    'msg' => "This Email has already been taken !",
                ], 200);
            }
        }
        $data = Client::find($id);

        $data->contact_email        = $request->contact_email ?? NULL;
        $data->is_PO_required        = $request->is_po_required;
        $data->is_separate_billing   = $request->separate_billing;
        $data->net_payment_term      = $request->net_payment_terms;
        $data->max_invoice_amount    = $request->max_invoice_amount;
        $data->save();
        return response()->json([
            'code' => 200,
            'msg' => "Record has been updated",
        ], 200);
    }
    public function updatePropertyStatus($status, $id)
    {
        $data = ClientProperties::find($id);
        $data->isActive   = ($status == 'true') ? 1 : 0;
        $data->save();
        return response()->json([
            'msg' => "Status updated successfully !",
        ], 200);
    }
}
