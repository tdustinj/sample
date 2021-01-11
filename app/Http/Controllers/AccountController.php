<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        //  $this->middleware('log')->only('index');

        //$this->middleware('subscribed')->except('store');
    }




    
    /**
     * Testing how a giant search would go.
     *
     * @return \Illuminate\Http\Response
     */
    public function testBigSearch($query)
    {
        try {
            // $account = Account::all()->take(5);
            $contacts = Contact::where('first_name', 'like', '%'. $query .'%')->get();

            return response()->json([
                'data' => $contacts->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $account = Account::all()->take(5);
            return response()->json([
                'data' => $account->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found", 'exceptionMessage' => $e->getMessage())
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
        //
    try {
        $account = new Account;
        $account->account_name = $request->account_name;
        $account->notes = $request->notes;
        $account->tax_code = $request->tax_code;
        $account->tax_id = $request->tax_id;
        $account->terms_number_days = $request->terms_number_days;
        $account->account_type = $request->account_type;
        $account->credit_line_limit = $request->credit_line_limit;
        $account->terms_payment_type = $request->terms_payment_type;
        $account->source = $request->source;

        $account->save();

        return response()->json([
            'data' => $account->toArray()
        ], 200);
    }
    catch(exception $e)
    {
        return response()->json([
            'data' => array('error'=> "Unable to Store Account", 'exceptionMessage' => $e->getMessage())
        ], 404);
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
        $account = Account::findOrFail($id);
        return response()->json([
            'data' => $account->toArray()
        ], 200);
        }

      catch(exception $e)
        {
        return response()->json([
        'data' => array('error'=> "Account not found for id: $id", 'exceptionMessage' => $e->getMessage())
        ], 404);
        }

    }



    public function search(Request $request)

    {
        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $account = Account::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $account->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
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
        //
        try {
            $account = Account::findOrFail($id);


            return response()->json([
                'data' => $account->toArray()
            ], 200);
        }
        catch(exception $e)
            {
            return response()->json([
                'data' => array('error'=> "Account not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
    public function update(Request $request, $id)
    {
        try {
            $account = Account::findOrFail($id);
            $account->account_name = $request->account_name;
            $account->notes = $request->notes;
            $account->tax_code = $request->tax_code;
            $account->tax_id = $request->tax_id;
            $account->terms_number_days = $request->terms_number_days;
            $account->account_type = $request->account_type;
            $account->credit_line_limit = $request->credit_line_limit;
            $account->terms_payment_type = $request->terms_payment_type;
            $account->source = $request->source;

            $account->save();
            return response()->json([
                'data' => $account->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Account not found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
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
            $account = Account::findOrFail($id);
            $account->delete();


            return response()->json([
                'data' => $account->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Account not found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }
}
