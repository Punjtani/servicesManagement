<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Setting;
use App\Helper\QuickBook;
use Exception;
use Illuminate\Http\Request;

class QBOController extends Controller
{
    public function syncQBO()
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

            $this->syncNewCustomers($newTokenData);
            return redirect('/clients');
        } else {
            $qb = new QuickBook();
            $authorizationCodeUrl = $qb->generateQBUrl();
            return redirect()->away($authorizationCodeUrl);
        }
    }

    public function qboCallback(Request $request)
    {
        if (isset($_GET['code']) && isset($_GET['realmId'])) {
            $qb = new QuickBook();
            $tokenData = $qb->generateToken($_GET['code'], $_GET['realmId']);
            $tokenData['realm_id'] = $_GET['realmId'];
            Setting::updateOrCreate([
                "key" => "qb_token"
            ], [
                "key" => "qb_token",
                "value" => json_encode($tokenData),
            ]);
            if (isset($tokenData['access_token']) && isset($tokenData['refresh_token'])) {
                $this->syncNewCustomers($tokenData);
            }
        }
        return redirect('/clients');
    }

    public function syncNewCustomers($tokenData)
    {
        $qb = new QuickBook();
        $qb->syncCustomers($tokenData['access_token'], $tokenData['refresh_token'], $tokenData['realm_id']);
    }

    public function syncInvoice($invoiceId)
    {
        $totalInvoice = 0;
        $totalPaid = 0;
        $data = Invoice::with('job')
            ->with('job.jobServices')
            ->with('job.jobServices.service')
            ->with('job.property')
            ->with('job.property.client.user')
            ->with('job.apartment_type')
            ->with('job.jobServices.requestedJobSubService.subService')
            ->with('transactions')
            // ->whereHas('job.apartment_type', function ($q) {
            //     $q->where('isActive', 1);
            // })
            ->find($invoiceId);
//        $totalTax = $this->calculateTax($data);
//        $totalInvoice =  $totalInvoice + $totalTax;
        $clientCompany = "";
        if ($data) {
            if ($data->job->property->client->company) {
                $clientCompany = $data->job->property->client->company;
            }
        }

        $taxPercentage = $data['job']['property']['taxType'] ? $data['job']['property']['taxType']->rate : 0;
        // dd("Client");
        $lineItems = array();
        foreach ($data['job']['jobServices'] as $service) {
            foreach ($service['requestedJobSubService'] as $subService) {
                if ($subService->subService) {
                    $temp = array();

                    if ($data['job']->apartment_status == 'vacant') {
                        if ($service['service'] && $service['service']->is_taxable) {
                            $subService->base_price += ($taxPercentage / 100) * $subService->base_price;
                            $temp['taxable'] = true;
                        } else {
                            $temp['taxable'] = false;
                        }
                    }
                    $temp['name'] = $subService->subService->name;
                    $temp['price'] = $subService->base_price;
                    $temp['description'] = $subService->description;
                    $temp['quantity'] = $subService->quantity;

                    $lineItems[] = $temp;
                }
                $totalInvoice = $totalInvoice + $subService->base_price;
            }
        }

//        return $lineItems;

        foreach ($data['transactions'] as $transaction) {
            $totalPaid = $totalPaid + $transaction->amount;
        }
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
            // $newTokenData = $qb->refreshToken($tokenData['refresh_token']);
            $newTokenData["realm_id"] = $tokenData["realm_id"];
            Setting::updateOrCreate([
                "key" => "qb_token"
            ], [
                "key" => "qb_token",
                "value" => json_encode($newTokenData),
            ]);
//            $taxCode=$qb->getTaxDetail($data['job']->property,$newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"]);
            $lineItemsArray = $qb->findItems($lineItems, $newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"]);
            $clientId = $qb->findClient($clientCompany, $newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"]);
            if ($clientId <= 0) {
                return redirect('/view-invoice/' . $invoiceId . "?success=0");
            }
            $invoiceData = [
                "DocNumber" => $invoiceId,
                "Line" => $lineItemsArray,
//                 "Line" => [
//                     [
//                         "Description" => "From Ress StreamLink",
//                         "Amount" => 10,
//                         "DetailType" => "SalesItemLineDetail",
//                         "SalesItemLineDetail" => [
//                             "ItemRef" => [
//                                 "value" => 51,
//                                 "name" => "Services"
//                             ],
//                             "UnitPrice" => 10,
//                             "Qty" => 2,
//                             // $lineItemsArray
//                         ]
//                     ]
//                 ],
                "CustomerRef" => [
                    "value" => $clientId,
                ]
            ];
            $status = $qb->syncInvoice($invoiceData, $newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"]);
            if ($status) {
                // sync invoice payment transactions

                foreach ($data['transactions'] as $transaction) {
                    if ($transaction->qb_payment_id){
                        $qb->updateQBPayment($transaction, $transaction->amount,$transaction->method, $newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"]);
                    }
                    else{
                        $paymentId = $qb->syncInvoicePayment($data->id, $transaction->amount, $newTokenData["access_token"], $newTokenData["refresh_token"], $newTokenData["realm_id"], $clientId, $transaction->method);
                        if ($paymentId){
                            $transaction->qb_payment_id = $paymentId;
                            $transaction->save();
                        }

                    }
                }
                $data->is_qb_synced = 1;
                $data->save();
                return redirect('/view-invoice/' . $invoiceId . "?success=1");
            } else {
                return redirect('/view-invoice/' . $invoiceId . "?success=0");
            }
        } else {
            return redirect('/view-invoice/' . $invoiceId . "?success=0");
        }
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
}
