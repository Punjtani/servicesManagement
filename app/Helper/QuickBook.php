<?php

namespace App\Helper;

use App\Models\User;
use App\Models\Client;
use App\Models\Setting;
use App\Models\ClientSupplier;
use App\Models\ClientProperties;
use Illuminate\Support\Facades\Validator;
use QuickBooksOnline\API\Data\IPPPayment;
use QuickBooksOnline\API\Data\IPPPaymentMethod;
use QuickBooksOnline\API\Data\IPPTaxRate;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\TaxAgency;
use QuickBooksOnline\API\Facades\TaxRate;

class QuickBook
{

    private $CLIENT_ID;
    private $CLIENT_SECRET;
    private $REDIRECT_URL;
    private $ENV;

    function __construct()
    {
        $setting = Setting::where('key', "qbo_oauth_data")->value("value");
        $data = [];
        if ($setting) {
            $setting = json_decode($setting);
            $this->CLIENT_ID = $setting->client_id;
            $this->CLIENT_SECRET = $setting->client_secret;
            $this->REDIRECT_URL = $setting->redirect_url;
            $this->ENV = $setting->environment;
        } else {
            $this->CLIENT_ID = env("QBCLIENT_ID", "ABkR5cV6hu93GtfaeT4I7l9B2MWrH70iOCQzJdVfCp25L5cEYV");
            $this->CLIENT_SECRET = env("QBCLIENT_SECRET", "JU3CT4tjprD0zpHkw8Tk6n674WTmwWnhftoTg2va");
            $this->REDIRECT_URL = env("QBREDIRECT_URL", "https://ress-qa.loebigink.com/qb/oauth2/callback");
            $this->ENV = env('environment', "Development");
        }

    }

    public function generateQBUrl()
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'RedirectURI' => $this->REDIRECT_URL,
            'scope' => "com.intuit.quickbooks.accounting",
            'baseUrl' => $this->ENV
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        return $authorizationCodeUrl;
    }

    public function generateToken($code, $realmId)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'RedirectURI' => $this->REDIRECT_URL,
            'scope' => "com.intuit.quickbooks.accounting",
            'baseUrl' => $this->ENV
        ));
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);
        $dataService->updateOAuth2Token($accessTokenObj);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        $tokenData = [];
        $tokenData['access_token'] = $accessTokenValue;
        $tokenData['refresh_token'] = $refreshTokenValue;
        return $tokenData;
    }

    public function refreshToken($refreshToken)
    {
        $oauth2LoginHelper = new OAuth2LoginHelper($this->CLIENT_ID, $this->CLIENT_SECRET);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        $tokenData = [];
        $tokenData['access_token'] = $accessTokenValue;
        $tokenData['refresh_token'] = $refreshTokenValue;
        return $tokenData;
    }

    public function syncCustomers($accessTokenValue, $refreshTokenValue, $realmId)
    {
        $this->fetchCustomers(1, 10, $accessTokenValue, $refreshTokenValue, $realmId);
        $this->fetchCustomersProperty(1, 10, $accessTokenValue, $refreshTokenValue, $realmId);
    }


    public function fetchCustomers($start, $max, $accessToken, $refreshToken, $QBORealmID)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));
        $allCustomers = $dataService->Query("Select * from Customer", $start, $max);
        //   dd($allCustomers);
        if ($allCustomers && count($allCustomers) > 0) {
            foreach ($allCustomers as $customer) {
                if ($customer->ParentRef) {
                    continue;
                }
                $data = array();
                if ($customer->PrimaryEmailAddr) {
                    $emails = explode(",", $customer->PrimaryEmailAddr->Address);
                    if (!isset($emails[0])) {
                        continue;
                    }
                    $data['email'] = $emails[0] ?? null;
                    // $data['email'] = $customer->PrimaryEmailAddr->Address ?? null;
                    $data['first_name'] = $customer->GivenName ?? null;
                    $data['middle_name'] = $customer->MiddleName ?? null;
                    $data['last_name'] = $customer->FamilyName ?? null;
                    $data['company'] = $customer->CompanyName ?? null;
                    $data['notes'] = $customer->Notes ?? null;
                    $data['street_address'] = $customer->BillAddr->Line1 ?? null;
                    $data['city'] = $customer->BillAddr->City ?? null;
                    $data['state'] = $customer->BillAddr->CountrySubDivisionCode ?? null;
                    $data['country'] = $customer->BillAddr->County ?? null;
                    $data['zipcode'] = $customer->BillAddr->PostalCode ?? null;
                    $data['phone'] = $customer->PrimaryPhone->FreeFormNumber ?? null;
                    $web = "";
                    if ($customer->WebAddr) {
                        $web = $customer->WebAddr->URI ?? null;
                    }
                    $data['web'] = $web;
                    // $data['manager'] = 1;
                    $data['contact_title'] = $customer->Title ?? null;
                    $data['contact_name'] = $customer->ContactName ?? null;
                    $this->store($data);
                }
            }
            $this->fetchCustomers($start + 10, $max, $accessToken, $refreshToken, $QBORealmID);
        }
    }


    public function fetchCustomersProperty($start, $max, $accessToken, $refreshToken, $QBORealmID)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));
        $allCustomers = $dataService->Query("Select * from Customer", $start, $max);
        //   dd($allCustomers);
        if ($allCustomers && count($allCustomers) > 0) {
            foreach ($allCustomers as $customer) {
                $data = array();
                if ($customer->ParentRef) {
                    $this->addPropertyToCustomer($customer, $customer->ParentRef, $accessToken, $refreshToken, $QBORealmID);
                }
            }
            $this->fetchCustomersProperty($start + 10, $max, $accessToken, $refreshToken, $QBORealmID);
        }
    }

    public function addPropertyToCustomer($customer, $parentId, $accessToken, $refreshToken, $QBORealmID)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));
        $parentCustomer = $dataService->FindById("Customer", $parentId);
        if ($parentCustomer && $parentCustomer->CompanyName) {
            $client = Client::where('company', $parentCustomer->CompanyName)->first();
            if ($client) {
                $clientProperty = ClientProperties::where('title', $customer->CompanyName)->where('client_id', $client->id)->first();
                if ($clientProperty) {
                    $data = $clientProperty;
                } else {
                    $data = new ClientProperties();
                }
                // $data               = new ClientProperties();
                $data->client_id = $client->id;
                $data->title = $customer->CompanyName;
                $data->street1 = $customer->BillAddr->Line1 ?? null;
                $data->street2 = $customer->BillAddr->Line2 ?? null;
                $data->city = $customer->BillAddr->City ?? null;
                $data->state = $customer->BillAddr->CountrySubDivisionCode ?? null;
                $data->zipcode = $customer->BillAddr->PostalCode ?? null;
                $data->country = $customer->BillAddr->County ?? null;
                $data->tax_type_id = 1;
                $data->tax_rate = 0;
                $data->suppliers = 0;
                $data->billing_address = $customer->BillAddr->Line1 ?? "N/A";
                $data->phone = $customer->PrimaryPhone->FreeFormNumber ?? null;
                // $data->manager      = 0;
                $data->save();
            }

        }

    }

    public function store($data)
    {
        $password = "password";
        $userData = array(
            "email" => $data['email'],
            "password" => $password,
            "role" => "2",
            "first_name" => $data['first_name'],
            "middle_name" => $data['middle_name'],
            "last_name" => $data['last_name'],
        );

        $user_id = $this->register($userData);

        if ($user_id == 0) {
            return 0;
        }
        $client = new Client();
        $client->user_id = $user_id;
        $client->company = $data['company'];
        $client->street_address = $data['street_address'];
        $client->notes = $data['notes'];
        $client->city = $data['city'];
        $client->state = $data['state'];
        $client->country = $data['country'];
        $client->zipcode = $data['zipcode'];
        $client->phone = $data['phone'];
        $client->web = $data['web'];
        // $client->manager       = $data['manager'];
        $client->contact_title = $data['contact_title'];
        $client->contact_name = $data['contact_name'];
        $client->save();

//        $dataClient = new ClientSupplier();
//        $dataClient->client_id = $client->id;
//        $dataClient->details = "";
//        $dataClient->map_longitude = 0;
//        $dataClient->map_latitude = 0;
//        $dataClient->save();
        return 1;
    }

    public function register($request)
    {
        $v = Validator::make($request, [
            'email' => 'required|email|unique:users',
        ]);
        if ($v->fails()) {
            return 0;
        }
        $user = new User();
        $user->email = $request['email'];
        $user->first_name = $request['first_name'];
        $user->middle_name = $request['middle_name'];
        $user->last_name = $request['last_name'];
        $user->password = bcrypt($request['password']);
        $user->is_active = 1;
        $user->role = $request['role'];
        $user->save();
        return $user->id;
    }

    public function syncInvoice($data, $accessToken, $refreshToken, $QBORealmID)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));
        $targetInvoiceArray = $dataService->Query("select * from Invoice where DocNumber='" . $data['DocNumber'] . "'");
        if (!empty($targetInvoiceArray) && sizeof($targetInvoiceArray) == 1) {
            $theInvoice = current($targetInvoiceArray);
            $updatedInvoice = Invoice::update($theInvoice, $data);
            $updatedResult = $dataService->Update($updatedInvoice);
            $error = $dataService->getLastError();
            if ($error) {
                return 0;
            } else {
                return 1;
            }
        }
        $invoiceToCreate = Invoice::create($data);
        $resultObj = $dataService->Add($invoiceToCreate);
        $error = $dataService->getLastError();
        if ($error) {
            return 0;
        } else {
            return 1;
        }
    }

    public function findClient($clientCompany, $accessToken, $refreshToken, $QBORealmID)
    {
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));
        $customer = $dataService->Query("SELECT * FROM Customer where CompanyName='" . $clientCompany . "'");
        if ($customer && isset($customer[0])) {
            return $customer[0]->Id;
        } else {
            $customerId = $this->createCustomer($dataService, $clientCompany);
            if ($customerId == 0) {
                return 0;
            } else {
                return $customerId;
            }
        }
    }

    public function createCustomer($dataService, $clientCompany)
    {
        $customer = Customer::create([
            "CompanyName" => $clientCompany,
            "DisplayName" => $clientCompany,
        ]);
        $resultObj = $dataService->Add($customer);
        $error = $dataService->getLastError();
        if ($error) {
            return 0;
        } else {
            return $resultObj->Id;
        }
    }

    public function findItems($lineItems, $accessToken, $refreshToken, $QBORealmID)
    {
        $lineItemsArray = array();
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));
        foreach ($lineItems as $item) {
            $tempArray = array();
            $itemRes = $dataService->Query("SELECT * FROM Item where Name='" . $item['name'] . "'");
            if ($itemRes && isset($itemRes[0])) {
                $temp = array();
                $temp['value'] = $itemRes[0]->Id;
                $temp['name'] = $itemRes[0]->Name;
                $tempArray['ItemRef'] = $temp;
            } else {
                $itemData = $this->createItem($dataService, $item);
                if (count($itemData) > 0) {
                    $tempArray['ItemRef'] = $itemData;
                }
            }
            $tempArray['UnitPrice'] = $item['price'];
            $tempArray['Qty'] = $item['quantity'];
            $lineItemsData["Description"] = $item['description'];
            $lineItemsData["DetailType"] = "SalesItemLineDetail";
            $lineItemsData["SalesItemLineDetail"] = $tempArray;
            $lineItemsData["Amount"] = $item['quantity'] * $item['price'];
            array_push($lineItemsArray, $lineItemsData);
        }
        return $lineItemsArray;
    }
    public function getTaxDetail($taxData, $accessToken, $refreshToken, $QBORealmID)
    {
        $lineItemsArray = array();
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));


        if ($taxData->taxType){
            $taxAgencies = $dataService->Query("SELECT * FROM TaxAgency");
            // Set up the TaxRate object
            $existingTaxAgency = null;

            foreach ($taxAgencies as $taxAgency) {
                if ($taxAgency->DisplayName == "ress") {
                    $existingTaxAgency = $taxAgency;
                    break;
                }
            }
            if ($existingTaxAgency){

                $taxRate = TaxRate::create([
                    "Name" => $taxData->taxType->name,
                    "RateValue" => $taxData->tax_rate,
                    "AgencyRef" => [
                        "value" => $existingTaxAgency->Id,
                    ],
                ]);

            return    $taxRates = $dataService->Query("SELECT * FROM TaxRate WHERE Name = 'DC Sales Tax'");
                if ($taxRates && count($taxRates) > 0) {

                    // The tax rate already exists
//                    $existingTaxRate = reset($taxRates); // Get the first tax rate in the result set
//                    $taxRate->Id = $existingTaxRate->Id; // Use the existing tax rate ID
                } else {

                    // The tax rate does not exist, so create a new one
                    $result = $dataService->Add($taxRate);
                    if (!$result || $result->Status != "Success") {
                        // Handle error
                        $error = $dataService->getLastError();
                        // Log or display the error message
                        echo "Error adding tax rate: " ;
                    } else {
                        // Handle success
                        $taxRateId = $result->Id; // Get the ID of the added tax rate
                        $taxRate->Id = $taxRateId; // Set the tax rate ID for later use
                        // Do something with the tax rate ID
                    }
                }
                return $taxRate;
            }
        }


    }

    public function createItem($dataService, $item)
    {
        $item = Item::create([
            "Name" => $item['name'],
            "Type" => "Service",
            "Active" => true,
            "UnitPrice" => $item['price'],
            "Qty" => $item['quantity'],
            "Taxable" => $item['taxable'],
            "IncomeAccountRef" => [
                "name" => "Sales of Product Income",
                "value" => "79"
            ],
        ]);
        $data = array();
        $resultObj = $dataService->Add($item);
        $error = $dataService->getLastError();
        if ($error) {
            return $data;
        } else {
            $data['value'] = $resultObj->Id;
            $data['name'] = $resultObj->Name;
            return $data;
        }
    }

    public function syncInvoicePayment($invoiceId, $totalAmount, $accessToken, $refreshToken, $QBORealmID, $clientId, $payment_method)
    {

        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));

        $checkPaymentMethod = $dataService->Query("SELECT * FROM PaymentMethod where Name='" . $payment_method . "'");
        $paymentMethodId = "";
        if ($checkPaymentMethod) {
            $payment_method = $checkPaymentMethod[0]->Name;
            $paymentMethodId = $checkPaymentMethod[0]->Id;
        } else {
            // Set up the PaymentMethod object
            $paymentMethod = new IPPPaymentMethod();
            $paymentMethod->Name = $payment_method; // Set the name of the payment method
            $paymentMethod->Type = "NON_CREDIT_CARD"; // Set the type of payment method
            $paymentMethod->Active = true; // Set the payment method as active

            $result = $dataService->Add($paymentMethod);
            if (!$result) {
                return 0;
            }else{
                $paymentMethodId = $result->Id;
            }


        }

        $targetInvoiceArray = $dataService->Query("select * from Invoice where DocNumber='" . $invoiceId . "'");

        if (!empty($targetInvoiceArray) && sizeof($targetInvoiceArray) == 1) {

            //receive payment for the invoice
            $paymentObj = Payment::create([
                "CustomerRef" => ["value" => $clientId],
                "TotalAmt" => $totalAmount,
                "PaymentMethodRef" => [
                    "value" => $paymentMethodId, // Set the ID of the payment method to use
                    "name" => $payment_method // Set the name of the payment method
                ],
                "Line" => [
                    "Amount" => $totalAmount,
                    "LinkedTxn" => ["TxnId" => $targetInvoiceArray[0]->Id, "TxnType" => "Invoice"]
                ]
            ]);
            $result = $dataService->Add($paymentObj);
            $error = $dataService->getLastError();
            if ($error) {
                return 0;
            } else {
                return $result->Id;
            }
        }
    }
    public function updateQBPayment($transaction, $amount,$payment_method, $accessToken, $refreshToken, $QBORealmID)
    {

        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $this->CLIENT_ID,
            'ClientSecret' => $this->CLIENT_SECRET,
            'accessTokenKey' => $accessToken,
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $QBORealmID,
            'baseUrl' => $this->ENV
        ));
        $payment = $dataService->Query("SELECT * FROM Payment WHERE Id ='$transaction->qb_payment_id'");
        $payment = $payment[0];

        $checkPaymentMethod = $dataService->Query("SELECT * FROM PaymentMethod where Name='" . $payment_method . "'");
        $paymentMethodId = "";
        if ($checkPaymentMethod) {
            $payment_method = $checkPaymentMethod[0]->Name;
            $paymentMethodId = $checkPaymentMethod[0]->Id;
        } else {
            // Set up the PaymentMethod object
            $paymentMethod = new IPPPaymentMethod();
            $paymentMethod->Name = $payment_method; // Set the name of the payment method
            $paymentMethod->Type = "NON_CREDIT_CARD"; // Set the type of payment method
            $paymentMethod->Active = true; // Set the payment method as active

            $result = $dataService->Add($paymentMethod);
            if (!$result) {
                return 0;
            }else{
                $paymentMethodId = $result->Id;
            }
            }
//        return $payment;
        if ($payment){
            $payment->TotalAmt = $amount; // Replace with the new payment amount
            $payment->PrivateNote = $transaction->notes; // Replace with the new private note
            $payment->PaymentMethodRef = $paymentMethodId;
            $result = $dataService->Update($payment);
            if (!$result) {
                return "Not synced with QB";
            } else {
                return "updated Successfully";
            }
        }



    }
}
