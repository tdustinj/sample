<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, App\Models\Contact, App\Models\Quote, App\Models\WorkOrder, App\Models\Invoice, Illuminate\Support\Facades\Response;



use App\Http\Requests;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

      //  $this->middleware('log')->only('index');

        //$this->middleware('subscribed')->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {
            $contacts = Contact::all()->take(5);

            return response()->json([
                'data' => $contacts->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Contacts Found", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //echo "hit the store create new contact";
    try {
      $contact = new Contact();
    //   $contact->first_name = $request->first_name;
    //   $contact->last_name = $request->last_name;
    //   $contact->middle_initial = $request->middle_initial;
    //   $contact->personal_email = $request->personal_email;
    //   $contact->work_email = $request->work_email;
    //   $contact->third_party_email = $request->third_party_email;
    //   $contact->home_phone = $request->home_phone;
    //   $contact->work_phone = $request->work_phone;
    //   $contact->mobile_phone = $request->mobile_phone;
    //   $contact->address = $request->address;
    //   $contact->address2 = $request->address2;
    //   $contact->city = $request->city;
    //   $contact->state = $request->state;
    //   $contact->zip = $request->zip;
      
        $contact->source = $request->source;
        $contact->account_id = 1;

        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        $contact->middle_initial = $request->middle_initial;
        // $contact->secondary_email = $request->secondary_email;
        $contact->primary_email = $request->primary_email;
        // $contact->third_party_email = $request->third_party_email;
        $contact->primary_phone = $request->primary_phone;
        $contact->secondary_phone = $request->secondary_phone;
        // $contact->tertiary_phone = $request->tertiary_phone;
        $contact->address = $request->address;
        $contact->address2 = $request->address2;
        $contact->city = $request->city;
        $contact->state = $request->state;
        $contact->zip = $request->zip;

      $contact->save();
      return Response::json([
          'data' => $contact->toArray()
      ], 200);
    }
    catch(\Exception $e) {
        return Response::json([
            'error' => $e->getMessage()
        ], 400);
    }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            return response()->json([
                'data' => $contact->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Contact not found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $contact = Contact::findOrFail($id);


            return response()->json([
                'data' => $contact->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {

            return response()->json([
                'data' => array('error'=> "Contact not found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $updateKey = $request->get('updateKey');
        $updateValue = $request->get('updateValue');

        try {
            $contact = Contact::findOrFail($request->get('id'));
            
            // $contact->$updateKey = $updateValue;
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->middle_initial = $request->middle_initial;
            $contact->secondary_email = $request->secondary_email;
            $contact->primary_email = $request->primary_email;
            $contact->third_party_email = $request->third_party_email;
            $contact->primary_phone = $request->primary_phone;
            $contact->secondary_phone = $request->secondary_phone;
            $contact->tertiary_phone = $request->tertiary_phone;
            $contact->address = $request->address;
            $contact->address2 = $request->address2;
            $contact->city = $request->city;
            $contact->state = $request->state;
            $contact->zip = $request->zip;

            $contact->save();
            return Response::json([
                'data' => $contact->toArray()
            ], 200);
        }
        catch(\Exception $e) {
            return Response::json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();


            return response()->json([
                'data' => $contact->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Contact Found", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    public function search(Request $request)
    {
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        $searchSize = $request->get('searchSize');
        $resizedContact = array();
        
        try {

            $contacts = Contact::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();

             foreach($contacts as $contact ) {
                 if ($searchSize === "light") {
                     //$contactModel = new Contact;
                     $resizedContact[] = Contact::getJsonLight($contact->toArray());
                 } elseif ($searchSize === "medium") {

                     $resizedContact[] = Contact::getJsonMedium($contact->toArray());
                 } else {
                     $resizedContact[] = $contact->toArray();
                 }
             }


            return response()->json([
                'data' => $contacts->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }


    public function contactHistory(Request $request){
      // $searchKey = $request->get('searchKey');
      // echo $searchKey;
      $searchKey = $request->get('searchKey');
      $searchValue = $request->get('searchValue');
      // print_r($searchValue);
      $searchSize = $request->get('searchSize');
        $contactHistoryQuotesClosedList = array();
        $contactHistoryQuotesOpenList = array();
        $contactHistoryOrdersClosedList = array();
        $contactHistoryOrdersOpenList = array();
        $contactHistoryQuotesClosed = array();
        $contactHistoryQuotesOpen = array();
        $contactHistoryOrdersClosed = array();
        $contactHistoryOrdersOpen = array();

        $contactInvoiceList = array();
        try{
        
        $contactQuotes = Quote::where($searchKey, '=', $searchValue)->get();
      
        $contactOrders = WorkOrder::where($searchKey, '=', $searchValue)->get();
        $contactInvoices = Invoice::where($searchKey, '=', $searchValue)->get();
        

        foreach ($contactQuotes as $quote) {
          if ($quote['status'] !== "Converted") {
            $contactHistoryQuotesOpen[] = $quote;
          } else {
            $contactHistoryQuotesClosed[] = $quote;
          }
        }

        foreach ($contactOrders as $order) {
          if ($order['workorder_status'] !== "Converted") {
            $contactHistoryOrdersOpen[] = $order;
          } else {
            $contactHistoryOrdersClosed[] = $order;
          }
        }

        foreach ($contactInvoices as $invoice) {
                $contactInvoices[] = $invoice;
            }

        if($searchSize === "light"){

            foreach($contactHistoryQuotesOpen as $quote) {
                $contactHistoryQuotesOpenList[] = Quote::getJsonLight($quote->toArray());
            }
            foreach($contactHistoryQuotesClosed as $quote) {
                $contactHistoryQuotesClosedList[] = Quote::getJsonLight($quote->toArray());
            }

            foreach($contactHistoryOrdersOpen as $workOrder) {
                $contactHistoryOrdersOpenList[] = WorkOrder::getJsonLight($workOrder->toArray());
            }

            foreach($contactHistoryOrdersClosed as $workOrder) {
                $contactHistoryOrdersClosedList[] = WorkOrder::getJsonLight($workOrder->toArray());
            }
            foreach($contactInvoices as $invoice) {
                $contactInvoiceList[] = Invoice::getJsonLight($invoice->toArray());
            }

        }

        else{
          //
            foreach($contactHistoryQuotesOpen as $quote) {
                $contactHistoryQuotesOpenList[] = $quote->toArray();
            }
            foreach($contactHistoryQuotesClosed as $quote) {
                $contactHistoryQuotesClosedList[] = $quote->toArray();
            }

            foreach($contactHistoryOrdersOpen as $workorder) {
                $contactHistoryOrdersOpenList[] = $workorder->toArray();
            }

            foreach($contactHistoryOrdersClosed as $workorder) {
                $contactHistoryOrdersClosedList[] = $workorder->toArray();
            }
            foreach($contactInvoices as $invoice) {
                $contactInvoiceList[] = $invoice->toArray();
            }

        }
     

        return response()->json([
              //  'quotes' => $contactQuotes,
              //  'orders' => $contactOrders,
                'invoices' => $contactInvoiceList,
                'openQuotes' => $contactHistoryQuotesOpenList,
                'closedQuotes' => $contactHistoryQuotesClosedList,
                'openOrders' => $contactHistoryOrdersOpenList,
                'closedOrders' => $contactHistoryOrdersClosedList,

            ], 200);
        }
      catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Error  $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function searchForQuotes(Request $request){
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $contacts = Contact::where($searchKey, 'like', $searchValue .'%')->get();
            $shipQuotes= array();
            foreach($contacts as $quotes){
                foreach($quotes->shipContactQuote as $quote){
                    $quote->shipTo;
                    $quote->soldTo;
                    $quote->billTo;
                    $shipQuotes[] = $quote;
                }
            }
            return response()->json([
                // 'data' => array('data' => $contacts, 'quotes' => $shipQuotes, 'attempt' => $contactToAttempt) //$contacts->toArray()
                'data' => $shipQuotes
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Contacts with Quotes Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function searchForQuotesByName(Request $request){
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        try {
            $contacts = Contact::where('first_name', 'like', '%' . $firstName . '%')->where('last_name', 'like', '%' . $lastName . '%')->get();
            $shipQuotes= array();
            foreach($contacts as $quotes){
                foreach($quotes->shipContactQuote as $quote){
                    $quote->shipTo;
                    $shipQuotes[] = $quote;
                }
            }
            return response()->json([
                // 'data' => array('data' => $shipQuotes,  'attempt' => $contactToAttempt) //$contacts->toArray()
                'data' => $shipQuotes,
                'attempt' => $contacts->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Contacts with Quotes Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }
    public function searchForWorkorders(Request $request){
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $contacts = Contact::where($searchKey, 'like', $searchValue .'%')->get();
            $shipWorkOrders = array();
            foreach($contacts as $workOrders){
                foreach($workOrders->shipContactQuote as $workOrder){
                    $workOrder->shipTo;
                    $workOrder->soldTo;
                    $workOrder->billTo;
                    $shipWorkOrders[] = $workOrder;
                }
            }
            return response()->json([
                // 'data' => array('data' => $contacts, 'quotes' => $shipQuotes, 'attempt' => $contactToAttempt) //$contacts->toArray()
                'data' => $shipWorkOrders
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Contacts with Quotes Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function searchForWorkordersByName(Request $request){
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        try {
            $contacts = Contact::where('first_name', 'like', '%' . $firstName . '%')->where('last_name', 'like', '%' . $lastName . '%')->get();
            $shipWorkOrders = array();
            foreach($contacts as $workOrders){
                foreach($workOrders->shipContactWorkOrder as $workorder){
                    $workorder->shipTo;
                    $workorder->soldTo;
                    $workorder->billTo;
                    $shipWorkOrders[] = $workorder;
                }
            }
            return response()->json([
                // 'data' => array('data' => $shipQuotes,  'attempt' => $contactToAttempt) //$contacts->toArray()
                'data' => $shipWorkOrders,
                'attempt' => $contacts->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Contacts with Quotes Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }


    public function searchForContactsByName(Request $request){
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        try {
            $contacts = Contact::where('first_name', 'like', '%' . $firstName . '%')->where('last_name', 'like', '%' . $lastName . '%')->get();
            return response()->json([
                'data' => $contacts->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Contacts Found Matching $firstName - $lastName", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }



}
