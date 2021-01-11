<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentTerminal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['fk_user_id'] = Auth::id();
            $payment = Payment::create($data);
            $payment->user;
            $payment->paymentMethod;
            $payment->paymentClass;
            $payment->paymentTerminal;
            $payment->paymentBatch;
            return response()->json([
                'data' => $payment->toArray()
            ], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Store Payment", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    public function paymentSettingOptions(){
        try {
            // $classTypes = PaymentClass
            $methodTypes = PaymentMethod::all();
            $terminalTypes = PaymentTerminal::all();
            $settings = array('methods'=> $methodTypes, 'terminals' => $terminalTypes);
            return response()->json([
                'data' => $settings
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found Matching", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }
}
