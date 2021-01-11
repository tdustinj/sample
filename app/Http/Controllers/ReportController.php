<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    var $today;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->today = date("Y-m-d");

        //  $this->middleware('log')->only('index');

        //$this->middleware('subscribed')->except('store');
    }
    /*
     * Route::post('/report/inventory', 'ReportControllerController@inventoryReport');
    Route::post('/report/quote', 'ReportControllerController@quoteReport');
    Route::post('/report/order', 'ReportControllerController@orderReport');
    Route::post('/report/invoice', 'ReportControllerController@invoiceReport');
    Route::post('/report/purchase', 'ReportControllerController@purchaseReport');
    Route::post('/report/tax', 'ReportControllerController@taxReport');

     */

   public function inventoryReport(Request $request){
        // this report will accept a name and call an private method  report
       // privae methods will be as follows _inventoryRreportName()
       $startDate = $request->input('startDate', $this->today);
       $endDate = $request->input('endDate', $this->today);

       $result = '';

       $location = $request->input('location', '%');
       $condition = $request->input('condition', '%');
       $availability = $request->input('availability', '%');
       $productId = $request->input('productId', 'NULL');
       $queryStyle = $request->input('queryStyle', 'productId');
       $brandId = $request->input('brandId', '%');
       $reportName = $request->input('reportName');


       try {
           switch($reportName){
               case "_INVENTORY_MODEL_ALL_ANY_AVAILABLE_" :
                   $result = $this->_inventoryProductOnHand($productId);
                   break;
               default :
                   $result = "{'error' : 'You must pass a reportName and optional productId'}";
           }
           return response()->json([
               'data' => $result->toArray()
           ], 200);
       }

       catch(exception $e)
       {
           return response()->json([
               'data' => array('error'=> "Unknown Error", 'exceptionMessage' => $e->getMessage())
           ], 404);
       }


   }
   private function _inventoryProductOnHand($productId, $location = "ALL", $condition = "ANY", $availablilty = "AVAILABLE" ){


   }

    public function taxReport(Request $request){
        // this report will accept a name and call an private method  report
        // privae methods will be as follows _inventoryRreportName()
        $result = '';

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $result = '';

        $region = $request->input('region');

        $queryStyle = $request->input('queryStyle');

        $reportName = $request->input('reportName');

        try {
            switch($reportName){
                case "_INVENTORY_MODEL_ALL_ANY_AVAILABLE_" :
                    $result = $this->_taxRange($productId);
                    break;
                default :
                    $result = "{'error' : 'You must pass a reportName and optional productId'}";
            }
            return response()->json([
                'data' => $result->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unknown Error", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }


    }
    private function _taxRange(){


    }

    public function purchaseReport(Request $request ){
        $result = '';

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $result = '';


        $productId = $request->input('productId');
        $queryStyle = $request->input('queryStyle');
        $brandId = $request->input('brandId');
        $reportName = $request->input('reportName');

        try {
            switch($reportName){
                case "_INVENTORY_MODEL_ALL_ANY_AVAILABLE_" :
                    $result = $this->_purchaseRange($productId);
                    break;
                default :
                    $result = "{'error' : 'You must pass a reportName and optional productId'}";
            }
            return response()->json([
                'data' => $result->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unknown Error", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }


    }
    private function _purchaseRange( ){


    }


    public function quoteReport(Request $request){
        // this report will accept a name and call an private method  report
        // privae methods will be as follows _inventoryRreportName()
        $result = '';

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $result = '';

        $quoteType = $request->input('quoteType');


        $userId = $request->input('userId');
        $quoteStatus = $request->input('quoteStatus');
        $reportName = $request->input('reportName');

        try {
            switch($reportName){
                case "_INVENTORY_MODEL_ALL_ANY_AVAILABLE_" :
                    $result = $this->_openQuote($productId);
                    break;
                default :
                    $result = "{'error' : 'You must pass a reportName and optional productId'}";
            }
            return response()->json([
                'data' => $result->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unknown Error", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }


    }
    private function _openQuote( ){


    }

    public function orderReport(Request $request){
        // this report will accept a name and call an private method  report
        // privae methods will be as follows _inventoryRreportName()
        $result = '';

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $result = '';

        $quoteType = $request->input('orderType');


        $userId = $request->input('userId');
        $orderStatus = $request->input('orderStatus');
        $reportName = $request->input('reportName');

        try {
            switch($reportName){
                case "_INVENTORY_MODEL_ALL_NEW_AVAILABLE" :
                    $result = $this->_openOrders();
                    break;
                default :
                    $result = "{'error' : 'You must pass a reportName and optional productId'}";
            }
            return response()->json([
                'data' => $result->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unknown Error", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }


    }
    private function _openOrders( ){


    }

    public function invoiceReport(Request $request){
        // this report will accept a name and call an private method  report
        // privae methods will be as follows _inventoryRreportName()
        $result = '';

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $result = '';

        $invoiceType = $request->input('invoiceType');


        $userId = $request->input('userId');

        $reportName = $request->input('reportName');

        try {
            switch($reportName){
                case "_INVENTORY_MODEL_ALL_NEW_AVAILABLE" :
                    $result = $this->_invoiceByType();
                    break;
                default :
                    $result = "{'error' : 'You must pass a reportName and optional productId'}";
            }
            return response()->json([
                'data' => $result->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unknown Error", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }


    }
    private function _invoiceByType( ){


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
}
