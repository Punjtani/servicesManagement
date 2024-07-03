<?php

namespace App\Http\Controllers;

use App\Helper\QuickBook;
use App\Models\Client;
use App\Models\ClientProperties;
use App\Models\Deposits;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\RequestedJobService;
use App\Models\RequestedJobSubService;
use App\Models\InvoiceAddition;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use PDF;
use Mail;
use Event;
use App\Events\SendInvoiceEmailEvent;
use App\Models\User;
use App\Models\Credits;
use App\Models\Staff;
use Exception;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        ini_set('max_execution_time', '0');
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
        // $query = $query->where('is_cancelled',0);

        if ($user->client) {
            $clientId = Client::where('user_id', $user->id)->first('id');
            $clientProperties = ClientProperties::where('client_id', $clientId->id)->where('is_quote', 0)
                ->where('isActive', 1)->select('id')->get();
            $jobIds = Job::where('is_quote', 0)->whereIn('property_id', $clientProperties)
                // ->whereHas('apartment_type',function($q){
                //     $q->where('isActive', 1);
                // })
                ->select('id')->get();
            $query = $query->whereIn('job_id', $jobIds);
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
            $jobIds = Job::where('is_quote', 0)->whereIn('property_id', $clientProperties)
                // ->whereHas('apartment_type',function($q){
                //     $q->where('isActive', 1);
                // })
                ->select('id')->get();
            $query = $query->whereIn('job_id', $jobIds);
        }

        //        if ($user->role == 2) {
        //            $query = $query->whereHas('job.property.client.user', function ($query) use ($user) {
        //                $query->where('id', $user->id);
        //            });
        //        }
        //        if (auth()->user()->parent) {
        //            $query = $query->whereHas('job.property', function ($query) use ($user) {
        //                $query->where('staff_id', $user->id);
        //            });
        //        }

        // if($request->search){
        //     $query = $query->whereHas('job.property',function($query) use ($request){
        //         $query->Where('title','LIKE',"%{$request->search}%");
        //     });
        //     $query = $query->orWhereHas('job',function($query) use ($request){
        //         $query->Where('apartment_number','LIKE',"%{$request->search}%");
        //     });
        //     $query = $query->orWhere('id',$request->search);
        // }
        if ($request->invoice_number) {
            $query = $query->where('id', $request->invoice_number);
        }
        if ($request->property_filter) {
            $query = $query->whereHas('job.property', function ($query) use ($request) {
                $query->Where('id', $request->property_filter);
            });
        }
        if ($request->apartment_filter) {
            $query = $query->whereHas('job', function ($query) use ($request) {
                $query->Where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
            });
        }
        if ($request->po_number_filter) {
            $query = $query->whereHas('job', function ($query) use ($request) {
                $query->Where('po_number', 'LIKE', "%{$request->po_number_filter}%");
            });
        }
        if ($request->date_filter) {
            $query->Where('created_at', 'LIKE', "{$request->date_filter}%");
        }
        if ($request->due_date_filter) {
            $query->Where('due_date', 'LIKE', "{$request->due_date_filter}%");
        }
        if ($request->status_filter && $request->status_filter !== "all") {
            $query = $query->when($request->status_filter == 'paid', function ($q) {
                $q->Where('is_paid', 1);
            })
                ->when($request->status_filter == 'unpaid', function ($q) {
                    $q->Where('is_paid', 0);
                    $q->Where('is_cancelled', 0);
                })->when($request->status_filter == 'partial paid', function ($q) {
                    $q->Where('is_paid', 2);
                    $q->Where('is_cancelled', 0);
                })
                ->when($request->status_filter == 'cancelled', function ($q) {
                    $q->Where('is_cancelled', 1);
                });
        }
        $query = $query->with(['job.requestedJobService' => function ($q) {
            $q->orderBy('end_date', 'desc');
        }])->whereHas('job.requestedJobService.requestedJobSubService', function ($q) {
            $q->where('is_delete', 0);
        });
        // ->orderBy($request->sort_by, $request->sort_order)

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "due_date" || $request->sort_by == "created_at" || $request->sort_by == "is_paid") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $query = $query->orderByJoin('property.title', $request->sort_order);
        } else if ($request->sort_by == "apartment_number") {
            $query = $query->orderByJoin('job.apartment_number', $request->sort_order);
        } else if ($request->sort_by == "job_id") {
            $query = $query->orderByJoin('job.id', $request->sort_order);
        } else if ($request->sort_by == "status") {
            $query = $query->orderBy('is_paid', $request->sort_order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $data = $query->paginate($request->size);

        //        foreach ($data as $invoice) {
        //            $invoice->is_paid = $this->checkPaymentStatus($invoice);
        //        }

        return response()->json([
            'invoices' => $data,
        ], 200);
    }

    public function pastDueInvoices(Request $request)
    {
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
        // $query = $query->where('is_cancelled',0);
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
        // if($request->search){
        //     $query = $query->whereHas('job.property',function($query) use ($request){
        //         $query->Where('title','LIKE',"%{$request->search}%");
        //     });
        //     $query = $query->orWhereHas('job',function($query) use ($request){
        //         $query->Where('apartment_number','LIKE',"%{$request->search}%");
        //     });
        //     $query = $query->orWhere('id',$request->search);
        // }
        if ($request->invoice_number) {
            $query = $query->where('id', $request->invoice_number);
        }
        if ($request->property_filter) {
            $query = $query->whereHas('job.property', function ($query) use ($request) {
                $query->Where('id', $request->property_filter);
            });
        }
        if ($request->apartment_filter) {
            $query = $query->whereHas('job', function ($query) use ($request) {
                $query->Where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
            });
        }
        if ($request->date_filter) {
            $query->Where('created_at', 'LIKE', "{$request->date_filter}%");
        }
        if ($request->status_filter && $request->status_filter !== "all") {
            $query = $query->when($request->status_filter == 'paid', function ($q) {
                $q->Where('is_paid', 1);
            })
                ->when($request->status_filter == 'unpaid', function ($q) {
                    $q->Where('is_paid', 0);
                    $q->Where('is_cancelled', 0);
                })
                ->when($request->status_filter == 'cancelled', function ($q) {
                    $q->Where('is_cancelled', 1);
                });
        }
        $query = $query->with(['job.requestedJobService' => function ($q) {
            $q->orderBy('end_date', 'desc');
        }])->whereHas('job.requestedJobService.requestedJobSubService', function ($q) {
            $q->where('is_delete', 0);
        });
        //->orderBy('id', 'DESC')

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "due_date" || $request->sort_by == "created_at" || $request->sort_by == "is_paid") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $query = $query->orderByJoin('property.title', $request->sort_order);
        } else if ($request->sort_by == "apartment_number") {
            $query = $query->orderByJoin('job.apartment_number', $request->sort_order);
        } else if ($request->sort_by == "job_id") {
            $query = $query->orderByJoin('job.id', $request->sort_order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $data = $query->paginate($request->size);

        return response()->json([
            'invoices' => $data,
        ], 200);
    }

    public function paymentAwaitingInvoices(Request $request)
    {
        $user = Auth::user();
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
        // $query = $query->where('is_cancelled',0);
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
        // if($request->search){
        //     $query = $query->whereHas('job.property',function($query) use ($request){
        //         $query->Where('title','LIKE',"%{$request->search}%");
        //     });
        //     $query = $query->orWhereHas('job',function($query) use ($request){
        //         $query->Where('apartment_number','LIKE',"%{$request->search}%");
        //     });
        //     $query = $query->orWhere('id',$request->search);
        // }
        if ($request->invoice_number) {
            $query = $query->where('id', $request->invoice_number);
        }
        if ($request->property_filter) {
            $query = $query->whereHas('job.property', function ($query) use ($request) {
                $query->Where('id', $request->property_filter);
            });
        }
        if ($request->apartment_filter) {
            $query = $query->whereHas('job', function ($query) use ($request) {
                $query->Where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
            });
        }
        if ($request->date_filter) {
            $query->Where('created_at', 'LIKE', "{$request->date_filter}%");
        }
        if ($request->status_filter && $request->status_filter !== "all") {
            $query = $query->when($request->status_filter == 'paid', function ($q) {
                $q->Where('is_paid', 1);
            })
                ->when($request->status_filter == 'unpaid', function ($q) {
                    $q->Where('is_paid', 0);
                    $q->Where('is_cancelled', 0);
                })
                ->when($request->status_filter == 'cancelled', function ($q) {
                    $q->Where('is_cancelled', 1);
                });
        }
        $query = $query->with(['job.requestedJobService' => function ($q) {
            $q->orderBy('end_date', 'desc');
        }])->whereHas('job.requestedJobService.requestedJobSubService', function ($q) {
            $q->where('is_delete', 0);
        }); //->orderBy('id', 'DESC')

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "due_date" || $request->sort_by == "created_at" || $request->sort_by == "is_paid") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $query = $query->orderByJoin('job.property.title', $request->sort_order);
        } else if ($request->sort_by == "apartment_number") {
            $query = $query->orderByJoin('job.apartment_number', $request->sort_order);
        } else if ($request->sort_by == "job_id") {
            $query = $query->orderByJoin('job.id', $request->sort_order);
        } else if ($request->sort_by == "status") {
            $query = $query->orderBy('is_paid', $request->sort_order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $data = $query->paginate($request->size);
        return response()->json([
            'invoices' => $data,
        ], 200);
    }

    public function invoice($id)
    {
        $totalPaid = 0;
        $data = $this->getInvoiceDetails($id);
        $totalInvoice = $this->calculateTotalInvoice($data);
        $totalTax = $this->calculateTax($data);

        foreach ($data['transactions'] as $transaction) {
            $totalPaid = $totalPaid + $transaction->amount;
        }
        // $updated_date = date_format($data->updated_at, 'd_m_Y_H_i_s');
        $destinationPath = 'public/pdf/';
        $fileName = $destinationPath . $id . '.pdf';
        $exists = Storage::disk('local')->has($fileName);
        // if ($exists) {
        //     Storage::delete($fileName);
        //    $pdf = PDF::loadView('pdf/invoice/invoice-pdf', compact('data', 'totalInvoice', 'totalTax'));
        //     Storage::put($fileName, $pdf->output());
        // } else {
        //     $pdf = PDF::loadView('pdf/invoice/invoice-pdf', compact('data', 'totalInvoice', 'totalTax'));
        //     Storage::put($fileName, $pdf->output());
        // }
        $staff = $data->job->property->staff;
        $client = $data['job']['property']['client']['user'];
        $email = [];
        foreach ($staff as $s) {
            if ($s && $s->is_email_enabled) {
                array_push($email, $s->email);
            }
        }
        if ($email == []) {
            array_push($email, $client->email);
        }
        $data->email = $email;
        return response()->json([
            'invoice' => $data,
            'totalInvoice' => round($totalInvoice, 2),
            'totalPaid' => round($totalPaid, 2),
            'totalTax' => $totalTax,
        ], 200);
    }

    public function invoicePayment(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);
        $deposits = Deposits::where('invoice_id', $request->invoice_id)->sum('amount');
        $remainingAmount = $invoice->payable - $deposits;
        try {
            $request->transaction_date = Carbon::createFromFormat('m-d-Y', $request->transaction_date)->format('Y-m-d');
        } catch (\Throwable $th) {
            //throw $th;
        }
        $data = new Deposits();
        //deposit by client credit and remove credit amount if paying by client's credit
        if ($request->transaction_method == 'Credit') {

            $data->invoice_id = $request->invoice_id;
            $data->transaction_date = $request->transaction_date;
            $data->method = $request->transaction_method;
            $data->amount = $request->amount;
            $data->notes = $request->notes;
            if (isset($request->check_no)) {
                $data->check_no = $request->check_no;
            }
            if (isset($request->confirmation_no)) {
                $data->confirmation_no = $request->confirmation_no;
            }
            $data->save();

            $clientCredit = Credits::where('client_id', $request->client_id)->first();
            $clientCredit->amount = $clientCredit->amount - $request->amount;
            $clientCredit->save();
        } else {

            $data->invoice_id = $request->invoice_id;
            $data->transaction_date = $request->transaction_date;
            $data->method = $request->transaction_method;
            $data->amount = $request->amount;
            $data->notes = $request->notes;
            if (isset($request->check_no)) {
                $data->check_no = $request->check_no;
            }
            if (isset($request->confirmation_no)) {
                $data->confirmation_no = $request->confirmation_no;
            }
            $data->save();
        }

        //add client's credit amount if extra paid
        if ($request->amount > $remainingAmount) {
            $credit = 0;
            $credit = $request->amount - $remainingAmount;
            if ($credit > 0) {
                $clientCredit = Credits::where('client_id', $request->client_id)->first();
                if ($clientCredit) {
                    $clientCredit->amount = $clientCredit->amount + $credit;
                    $clientCredit->save();
                } else {
                    $clientCredit = new Credits();
                    $clientCredit->client_id = $request->client_id;
                    $clientCredit->amount = $credit;
                    $clientCredit->save();
                }
            }
        }
        //change status if amount is fully paid (after the new payment information is saved)
        $invoice = Invoice::find($request->invoice_id);
        $deposits = Deposits::where('invoice_id', $request->invoice_id)->sum('amount');
        $deposits = Deposits::where('invoice_id', $request->invoice_id)->sum('amount');
        $remainingAmount = $invoice->payable - $deposits;

        if ($remainingAmount <= 0) {
            $invoice->is_paid = 1;
        } else if ($remainingAmount > 0) {
            $invoice->is_paid = 2;
        }
        $invoice->save();

        if ($invoice->is_qb_synced) {
            $qbPaymentId = $this->syncPayment($request);
            if ($qbPaymentId) {
                $data->qb_payment_id = $qbPaymentId;
                $data->save();
            }
        }


        return response()->json([], 200);
    }

    public function syncPayment($request)
    {
        //        return 4545454;
        $data = Invoice::find($request->invoice_id);
        $clientCompany = "";
        if ($data) {
            if ($data->job->property->client->company) {
                $clientCompany = $data->job->property->client->company;
            }
        }

        //        $totalInvoice = 0;
        //        foreach ($data['job']['jobServices'] as $service) {
        //            foreach ($service['requestedJobSubService'] as $subService) {
        //
        //                $totalInvoice = $totalInvoice + $subService->base_price;
        //            }
        //        }

        $qbToken = Setting::where("key", "qb_token")->value("value");
        $qb = new QuickBook();
        if ($qbToken) {
            $qbToken = json_decode($qbToken);
            $tokenData["access_token"] = $qbToken->access_token;
            $tokenData["refresh_token"] = $qbToken->refresh_token;
            $tokenData["realm_id"] = $qbToken->realm_id;
            $qb = new QuickBook();
            // refresh & save new tokens
            try {
                $newTokenData = $qb->refreshToken($tokenData['refresh_token']);
            } catch (Exception $ex) {
                $authorizationCodeUrl = $qb->generateQBUrl();
                return redirect()->away($authorizationCodeUrl);
            }
            $newTokenData["realm_id"] = $tokenData["realm_id"];
            Setting::updateOrCreate([
                "key" => "qb_token"
            ], [
                "key" => "qb_token",
                "value" => json_encode($newTokenData),
            ]);
            $clientId = $qb->findClient($clientCompany, $newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"]);

            $paymentId = $qb->syncInvoicePayment($request->invoice_id, $request->amount, $newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"], $clientId, $request->transaction_method);
            return $paymentId;
        }
    }

    public function updateTransaction(Request $request)
    {
        // if (isset($request->transaction_date) && !empty($request->transaction_date)) {

        // }
        try {
            $request->transaction_date = Carbon::createFromFormat('m-d-Y', $request->transaction_date)->format('Y-m-d');
        } catch (\Throwable $th) {
            //throw $th;
        }
        $transaction = Deposits::where('invoice_id', $request->invoice_id)
            ->where('id', $request->transaction_id)->first();
        $transaction->transaction_date = $request->transaction_date;
        $transaction->method = $request->transaction_method;
        $transaction->amount = $request->amount;
        $transaction->notes = $request->notes;

        $transaction->check_no = $request->check_no ?? "";
        $transaction->confirmation_no = $request->confirmation_no ?? "";

        $transaction->save();


        //deposit by client credit and remove credit amount if paying by client's credit
        if ($request->transaction_method == 'Credit') {
            $clientCredit = Credits::where('client_id', $request->client_id)->first();
            $clientCredit->amount = $clientCredit->amount - $request->amount;
            $clientCredit->save();
        }
        $invoice = Invoice::find($request->invoice_id);
        $deposits = Deposits::where('invoice_id', $request->invoice_id)->sum('amount');
        $remainingAmount = $invoice->payable - $deposits;

        if ($remainingAmount <= 0) {
            $invoice->is_paid = 1;
        } else if ($remainingAmount > 0) {
            $invoice->is_paid = 2;
        }

        $invoice->save();

        //add client's credit amount if extra paid

        if ($invoice->payable < $deposits) {
            $credit = $deposits - $invoice->payable;

            if ($credit > 0) {
                $clientCredit = Credits::where('client_id', $request->client_id)->first();
                if ($clientCredit) {
                    $clientCredit->amount = $clientCredit->amount + $credit;
                    $clientCredit->save();
                } else {
                    $clientCredit = new Credits();
                    $clientCredit->client_id = $request->client_id;
                    $clientCredit->amount = $credit;
                    $clientCredit->save();
                }
            }
        }

        if ($transaction->qb_payment_id) {
            $sync =  $this->syncUpdatePayment($transaction, $request);
            return response()->json([
                "response" => $sync
            ], 200);
        }
        return response()->json([], 200);
    }
    public function syncUpdatePayment($transaction, $request)
    {
        $qbToken = Setting::where("key", "qb_token")->value("value");

        if ($qbToken) {
            $qbToken = json_decode($qbToken);
            $tokenData["access_token"] = $qbToken->access_token;
            $tokenData["refresh_token"] = $qbToken->refresh_token;
            $tokenData["realm_id"] = $qbToken->realm_id;
            $qb = new QuickBook();
            // refresh & save new tokens
            try {
                $newTokenData = $qb->refreshToken($tokenData['refresh_token']);
            } catch (Exception $ex) {
                $authorizationCodeUrl = $qb->generateQBUrl();
                return redirect()->away($authorizationCodeUrl);
            }
            $newTokenData["realm_id"] = $tokenData["realm_id"];
            Setting::updateOrCreate([
                "key" => "qb_token"
            ], [
                "key" => "qb_token",
                "value" => json_encode($newTokenData),
            ]);

            return $qb->updateQBPayment($transaction, $request->amount, $request->transaction_method, $newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"]);
        }
    }

    public function editInvoice(Request $request, $id)
    {
        // return $request;
        // dd($request);
        if ($request->invoice_due_date) {
            $date = Carbon::createFromFormat('Y-m-d', $request->invoice_due_date);
            $due_date = $date->format('Y-m-d');
            Invoice::where('id', $id)->update(['due_date' => $due_date]);
        }
        if ($request->po_number) {
            Job::where('id', $request->id)->update(['po_number' => $request->po_number]);
        }
        foreach ($request->job_services as $r) {
            $data = RequestedJobService::find($r['id']);
            $data->scheduled_date = $r['scheduled_date'];
            $data->end_date = $r['end_date'] ?? null;
            $data->save();
            $any_true = false;
            foreach ($r['requested_job_sub_service'] as $index => $subService) {
                $item = RequestedJobSubService::find($subService['id']);
                $item->description = $subService['description'];
                $item->base_price = $subService['base_price'];
                $item->quantity = $subService['quantity'];
                $item->total_price = $subService['total_price'];
                $item->is_invoice = $subService['is_invoice'];
                $item->order = $index;
                $item->save();
                if ($subService['is_invoice']) {
                    $any_true = true;
                }
            }
            if (!$any_true) {
                $data->delete();
            }
        }

        if (isset($request->lineItems) && !empty($request->lineItems)) {
            // $data->invoice_line_item()->delete();
            foreach ($request->lineItems as $item) {
                $data = $this->getInvoiceDetails($id);
                if (!$data['job']['jobServices']->contains('service_id', $item['subService']['service_type_id'])) {
                    $service = new RequestedJobService();
                    $service->service_id = $item['subService']['service_type_id'];
                    $service->job_id = $data['job']['id'];
                    $service->requested_date = Carbon::now();
                    $service->save();
                } else {
                    $service = $data['job']['jobServices']->where('service_id', $item['subService']['service_type_id'])->first();
                }

                $subService = new RequestedJobSubService();
                $subService->requested_job_service_id = $service->id;
                $subService->sub_service_id = $item['subService']['sub_service_id'];
                $subService->description = $item['description'];
                $subService->base_price = $item['base_price'];
                $subService->quantity = $item['quantity'];
                $subService->total_price = $item['total_price'];
                $subService->save();
            }
        }
        // $data = $this->getInvoiceDetails($id);
        // $data->touch();
        // $totalInvoice = $this->calculateTotalInvoice($data);
        // $totalTax = $this->calculateTax($data);
        // $updated_date = date_format($data->updated_at, 'd_m_Y_H_i_s');
        // $destinationPath = 'public/pdf/';
        // $fileName = $destinationPath . $id .'_' . $updated_date. '.pdf';
        // $exists = Storage::disk('local')->has($fileName);
        // if (!$exists) {
        //     $pdf = PDF::loadView('pdf/invoice/invoice-pdf', compact('data','totalInvoice','totalTax'));
        //     Storage::put($fileName, $pdf->output());
        // }
        $this->updateInvoicePdf($id);
        return response()->json([], 200);
    }

    public function downloadInvoice($id)
    {
        $invoice = Invoice::find($id);
        // $timeStamp = date_format($invoice->updated_at, 'd_m_Y_H_i_s');
        $filePath = storage_path() . '/app/public/pdf/';
        $fileName = $filePath . $id . '.pdf';
        $headers = array('Content-Type: application/pdf',);
        return response()->download($fileName, 'invoice.pdf', $headers);
    }

    public function getInvoiceDetails($id)
    {
        $data = Invoice::with('job')
            ->with('job.jobServices')
            ->with('job.jobServices.service')
            ->with('job.jobServices.serviceJobProgresAttatchment')
            ->with('job.jobServices.serviceJobProgressAfterAttatchment')
            ->with('job.property')
            ->with('job.property.staff')
            ->with(['job.property.taxType' => function ($q) {
                $q->where('isActive', 1);
            }])
            ->with('job.property.client.user')
            ->with('job.apartment_type')
            ->with('job.jobServices.requestedJobSubService.subService')
            ->with('transactions')
            // ->with('invoice_line_item')
            ->with(['job.jobServices.requestedJobSubService' => function ($q) {
                $q->where('is_invoice', 1);
                $q->orderBy('order', 'asc');
            }])
            // ->whereHas('job.apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // })
            ->with(['job.requestedJobService.requestedJobSubService' => function ($q) {
                $q->where('is_delete', 0);
            }])
            ->find($id);

        //check payment fully paid or partial

        // $depositsCount = Deposits::where('invoice_id', $id)->count();
        // $deposits = Deposits::where('invoice_id', $id)->sum('amount');

        $depositsCount = Deposits::where('invoice_id', $id)->count();
        $deposits = Deposits::where('invoice_id', $id)->sum('amount');

        if ($depositsCount > 0) {
            if ($data->payable > $deposits) {
                $data->payment_status = "partial_paid";
                $data->is_paid == 2;
                $i = Invoice::find($id);
                $i->is_paid = 2;
                $i->save();
            } else if ($data->payable <= $deposits) {
                $data->payment_status = "fully_paid";
                $i = Invoice::find($id);
                $i->is_paid = 1;
                $i->save();
            }
        }

        //check is due date passed

        if ($data->is_paid == 0 && $data->due_date < now()) {
            $data->is_due_passed = 1;
        } else {
            $data->is_due_passed = 0;
        }

        return $data;
    }

    public function calculateTotalInvoice($data)
    {
        $totalInvoice = 0;
        foreach ($data['job']['jobServices'] as $service) {
            if ($service['service']) {
                foreach ($service['requestedJobSubService'] as $subService) {
                    $totalInvoice = $totalInvoice + $subService->total_price;
                }
            }
        }
        // foreach ($data['invoice_line_item'] as $item) {
        //         $totalInvoice = $totalInvoice + $item->price;
        // }
        return $totalInvoice;
    }

    public function calculateTax($data)
    {
        $taxPercentage = $data['job']['property']['taxType'] ? $data['job']['property']['taxType']->rate : 0;
        $totalServicePrice = 0;
        $tax = 0;
        if ($taxPercentage) {
            foreach ($data['job']['jobServices'] as $service) {
                if ($service['service']) {
                    foreach ($service['requestedJobSubService'] as $subService) {
                        if ($data['job']->apartment_status == 'vacant') {
                            if ($service['service'] && $service['service']->is_taxable) {
                                $totalServicePrice += $subService->total_price;
                            }
                        }
                    }
                }
            }
            $tax = ($taxPercentage / 100) * $totalServicePrice;
        }
        return round($tax, 2);
    }

    public function sendInvoiceEmail(Request $request, $id)
    {
        $data = $this->getInvoiceDetails($id);
        $totalInvoice = $this->calculateTotalInvoice($data);
        $totalTax = $this->calculateTax($data);
        $data->body = $request->body;
        $data->subject = $request->subject;
        $data->email = $request->email;
        $pdf = PDF::loadView('pdf/invoice/invoice-pdf', compact('data', 'totalInvoice', 'totalTax'));
        event(new SendInvoiceEmailEvent($data, $totalInvoice, $pdf, $totalTax));
        $currentDate = Carbon::now();
        $jobData = RequestedJobService::where('job_id', $data['job']['id'])->update(['billed_date' => $currentDate]);
        return response()->json([], 200);
    }

    public function cancelInvoice($id)
    {
        $invoice = Invoice::find($id);
        if ($invoice->is_paid) {
            return response()->json(['error' => 'Invoice has already been paid'], 200);
        } else {
            Invoice::where('id', $id)->update(['is_cancelled' => 1]);
            $currentDate = Carbon::now();
            $jobData = RequestedJobService::where('job_id', $invoice->job_id)->update(['billed_date' => NULL]);
            return response()->json(['message' => 'Invoice has been cancelled'], 200);
        }
    }

    public function getCancelledInvoices(Request $request)
    {
        $user = Auth::user();
        $query = Invoice::with('job')
            ->with('job.apartment_type')
            ->with('job.property')
            ->with('job.property.client.user');
        if ($request->invoice_number) {
            $query = $query->where('id', $request->invoice_number);
        }
        if ($request->property_filter) {
            $query = $query->whereHas('job.property', function ($query) use ($request) {
                $query->Where('id', $request->property_filter);
            });
        }
        if ($request->apartment_filter) {
            $query = $query->whereHas('job', function ($query) use ($request) {
                $query->Where('apartment_number', 'LIKE', "%{$request->apartment_filter}%");
            });
        }
        if ($request->date_filter) {
            $query->Where('created_at', 'LIKE', "{$request->date_filter}%");
        }
        if ($request->status_filter && $request->status_filter !== "all") {
            $query = $query->when($request->status_filter == 'paid', function ($q) {
                $q->Where('is_paid', 1);
                $q->Where('is_cancelled', 1);
            })
                ->when($request->status_filter == 'unpaid', function ($q) {
                    $q->Where('is_paid', 0);
                    $q->Where('is_cancelled', 1);
                });
        }
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

        // apply sorting
        if ($request->sort_by == "id" || $request->sort_by == "due_date" || $request->sort_by == "created_at" || $request->sort_by == "is_paid") {
            $query = $query->orderBy($request->sort_by, $request->sort_order);
        } else if ($request->sort_by == "property") {
            $query = $query->orderByJoin('job.property.title', $request->sort_order);
        } else if ($request->sort_by == "apartment_number") {
            $query = $query->orderByJoin('job.apartment_number', $request->sort_order);
        } else if ($request->sort_by == "job_id") {
            $query = $query->orderByJoin('job.id', $request->sort_order);
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $data = $query->paginate($request->size);
        return response()->json([
            'invoices' => $data,
        ], 200);
    }

    public function changeInvoiceDate(Request $request, $id)
    {
        if ($request->date) {
            $invoice = Invoice::with('job.property.client')->find($id);
            $net_payment_term = $invoice->job->property->client->net_payment_term ? $invoice->job->property->client->net_payment_term : 30;
            $due_date = Carbon::createFromFormat('Y-m-d', $request->date)->addDays($net_payment_term);
            Invoice::where('id', $id)->update(['created_at' => date($request->date), 'due_date' => $due_date]);
            return response()->json(['message' => 'Invoice date has been updated'], 200);
        }
    }

    public function updateInvoicePdf($id)
    {
        $data = $this->getInvoiceDetails($id);
        // $data->touch();
        $totalInvoice = $this->calculateTotalInvoice($data);
        $totalTax = $this->calculateTax($data);
        // $updated_date = date_format($data->updated_at, 'd_m_Y_H_i_s');
        ini_set('max_execution_time', 300);
        $destinationPath = 'public/pdf/';
        $fileName = $destinationPath . $id . '.pdf';
        $exists = Storage::disk('local')->has($fileName);
        if ($exists) {
            Storage::delete($fileName);
            $pdf = PDF::loadView('pdf/invoice/invoice-pdf', compact('data', 'totalInvoice', 'totalTax'));
            Storage::put($fileName, $pdf->output());
        } else {
            $pdf = PDF::loadView('pdf/invoice/invoice-pdf', compact('data', 'totalInvoice', 'totalTax'));
            Storage::put($fileName, $pdf->output());
        }
        return response()->json([], 200);
    }

    public function getPaymentInformation($id, $clientId)
    {
        $invoice = Invoice::find($id);
        $deposits = Deposits::where('invoice_id', $id)->sum('amount');
        $credits = Credits::where('client_id', $clientId)->pluck('amount')->first();
        $payableAmount = $invoice->payable - $deposits;
        return response()->json([
            'payableAmount' => round($payableAmount, 2),
            'creditAmount' => round($credits, 2),
        ], 200);
    }

    public function updateInvoiceTotal(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        // if ($request->invoiceTotal == 0) {
        //     $invoice->is_paid = 0;
        // } else {
        //     $invoice->is_paid =  2;
        // }
        //else if ($invoice->payable <= $request->invoiceTotal) {
        //     $invoice->is_paid =  1;
        // }
        $invoice->payable = $request->invoiceTotal;
        $invoice->save();

        $depositsCount = Deposits::where('invoice_id', $id)->count();
        $deposits = Deposits::where('invoice_id', $id)->sum('amount');

        if ($depositsCount > 0) {
            $i = Invoice::find($id);
            if ($i->payable > $deposits) {

                $i->is_paid = 2;
                $i->save();
            } else if ($i->payable <= $deposits) {

                // $i = Invoice::find($id);
                $i->is_paid == 1;
                $i->save();
            }
        }

        return response()->json(['message' => 'Invoice total has been saved'], 200);
    }

    public function getStaffEmails($clientId)
    {
        $staff = Staff::whereHas('property', function ($q) use ($clientId) {
            $q->where('client_properties_id', $clientId);
        });
        $staff = $staff->with('user')->join('users', 'staff.user_id', '=', 'users.id')->select('users.email', 'users.first_name', 'users.last_name');
        $staff = $staff->get();

        $client = User::whereHas('client', function ($q) use ($clientId) {
            $q->where('id', $clientId);
        })->select('email', 'first_name', 'last_name')->get();
        return response()->json([
            'staff' => $staff,
            'client' => $client
        ], 200);
    }

    public function checkPaymentStatus($invoice)
    {
        $depositsCount = Deposits::where('invoice_id', $invoice->id)->count();
        $deposits = Deposits::where('invoice_id', $invoice->id)->sum('amount');

        if ($depositsCount > 0) {
            if ($invoice->payable > $deposits) {
                return 2;
            } else if ($invoice->payable <= $deposits) {
                return $invoice->is_paid;
            }
        } else {
            return $invoice->is_paid;
        }
    }
}
