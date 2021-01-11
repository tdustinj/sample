<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Utilities\CustomHelpers;
use App\Models\Fulfillment;
use Carbon\Carbon;

class FulfillmentTrackingController extends Controller
{

	protected $help;
	protected $filter = "";	// ship company filter

    public function __construct(CustomHelpers $help) {
    	//$this->middleware('auth:api');
    	$this->help = $help;
    }


    public function index(Request $request) {

    	$fulfillments = array();
    	if (($request['filterBy']) && ($request['filterBy'] == 'no-contact')) {
    		$this->filter = "no-contact";
	    	$no_contact = Fulfillment::trackedItems()->with('workorder:id,customer_contacted')->get();	//temp hold var
	    	foreach ($no_contact as $row) {	
	    		if (!$row->workorder->customer_contacted) {	//filter for not contacted
	    			$fulfillments[] = $row;	// now we have the fulfillments that we need
	    		}
	    	}
    	} elseif ($request['filterBy'] && ($request['filterBy'] != 'no-filter')) {	
    		$this->filter = $request['filterBy'];
    		// if filterBy is set and != 'no-filter' or 'no-contact' then ship company is all that's left
    		$fulfillments = Fulfillment::trackedItems()->with('workorder:id,customer_contacted')->where('vendor_shipping_company', '=', $this->filter)->get();
    	} else {
    		// Either a GET or no-filter is left
    		$fulfillments = Fulfillment::trackedItems()->with('workorder:id,customer_contacted')->get();
    	}	
    	// New array for re-organizing
		$trackedItemsArray = array();	
		$newArray = array();
		$filters = array('no-filter', 'no-contact');
		// This re-organizes the transactions based on number of days out as the array key

		foreach ($fulfillments as $item) {	

			$item->tracking_url = $this->trackingUrl($item->vendor_tracking_number, $item->vendor_shipping_company);
			$item->customer_contacted = $item->workorder->customer_contacted;
			$item->expected_delivery_date = substr($item->expected_delivery_date, 0, 10);
			// These next few lines calculate when delivery is expected which affects the color of the box in the view

			$dateshipped = date_create($item->vendor_shipping_date); 
			$today = date_create(date("Y-m-d")); 
			$interval = date_diff($dateshipped, $today);
			$eta = date_create($item->vendor_promise_delivery_date);
			$eta_interval = date_diff($today, $eta);
			$item->eta = "days" . $eta_interval->format("%r%a");


			// All items shipped on same day are stored in same array (a days out column on the board)
			$trackedItemsArray['items'][$interval->days][] = $item;	

			// Send only the current ship companies that are currently in use for filtering	
			if (!in_array($item->vendor_shipping_company, $filters)) {	
				$filters[] = $item->vendor_shipping_company;
			}
			
		}

		
		if (sizeof($trackedItemsArray)) {
			for ($counter=32; $counter >= 0; $counter--) { 
				if (isset($trackedItemsArray['items'][$counter])) {
					// $thisvar = string($counter);
					$daysAsText = "Days Out " . $counter;
					$newArray['items'][$daysAsText] = $trackedItemsArray['items'][$counter];
				}
			}			
		}

		//return json_encode($newArray); die;
		$newArray['filters'] = $filters;
		$newArray['filterBy'] = $this->filter;

		return json_encode($newArray);	

    }


    public function buildQueryString($request) {

    	$queryString = "SELECT * from fulfillment WHERE vendor_shipping_status = 'Shipped' and status = 1"; 
    	if (($request['filterBy']) && ($request['filterBy'] == 'no-contact')) {
    		$this->filter = "no-contact";
    		$queryString .= " and customer_contact = 0";
    	} elseif ($request['filterBy'] && ($request['filterBy'] != 'no-filter')) {
    		$this->filter = $request['filterBy'];
    		$queryString .= " and vendor_shipping_company = '$this->filter'";
    	}

    	return $queryString;

    }


    public function deliver($tid) {
    	// Find the unique fulfillment by id but then...
		$shipTransaction = Fulfillment::where('tid', '=', $tid)->get();
	    $invoice = $shipTransaction[0]->invoice_num;
	    $bol_tracking = $shipTransaction[0]->bol_tracking;
	    $user = "";	
	    
	    // ...deliver all items with matching workorder # and tracking number	
		$shipTransactions = Fulfillment::where('bol_tracking', '=', $bol_tracking)->where('invoice_num', '=', $invoice)->get();
		foreach ($shipTransactions as $transaction) {
			$transaction->status = 0;
			$transaction->action = "Delivered";
			$transaction->order_status = "Delivered";
			$transaction->updated_at = $this->help->timeStamp();
			$transaction->user = $user;
			$transaction->save();
			$msg = $transaction->description . " Tracked Item Delivered!";
			$this->help->insertInvoiceMessage($invoice, $msg, $user);
		}   
	    return redirect('/shipping-tracker'); 

    }


    public function remove($tid) {

			$shipTransaction = Fulfillment::where('tid', '=', $tid)->get();
		    $invoice = $shipTransaction[0]->invoice_num;
		    $bol_tracking = $shipTransaction[0]->bol_tracking;	
		    $user = "Troy";	
		    // Remove all items with matching invoice and tracking number	
			$shipTransactions = Fulfillment::where('bol_tracking', '=', $bol_tracking)->where('invoice_num', '=', $invoice)->get();
			foreach ($shipTransactions as $transaction) {
				$transaction->status = 0;
				$transaction->action = "Removed without Email";
				$transaction->updated_at = $this->help->timeStamp();
				$transaction->user = $user;
				$transaction->save();
				$msg =  $transaction->description . " Tracked Item Removed Without Email";
				$this->help->insertInvoiceMessage($invoice, $msg, $user);
			}   
		    return redirect('/shipping-tracker'); 

    }


	private function trackingUrl($trackingNumber, $shipCompany) {

		  $companyClean = strtolower($shipCompany);
		  $url = "";
		  switch ($companyClean) {
		  case "ait":
		    $url = "http://fastrak.aitworldwide.com/FormattedDefault.aspx?TrackingNums=" . $trackingNumber;
		    break;
		  case "saia":
		    $url = "http://www.saia.com/Tracing/AjaxProstatusByPro.aspx?m=2&UID=&PWD=&SID=VHNNM43339668&PRONum1=" . $trackingNumber;
		    break;
		  case "cevalogistics":
		    $url = "http://www.cevalogistics.com/ceva-trak?sv=" . $trackingNumber . "&uid=";
		    break;
		  case "ups":
		    $url = "https://wwwapps.ups.com/WebTracking/track";  // main account screen
		    break;
		  case "fedex":
		    $url = "https://www.fedex.com/apps/fedextrack/?tracknumbers=" . $trackingNumber . "&cntry_code=us";
		    break;
		  case "pilot":
		    $url = "http://www.pilotdelivers.com/tracking/";
		    break;
		  case "ags":
		    $url = "https://tracking.agsystems.com/";
		    break;    
		  case "ontrac":
		    $url = "https://www.ontrac.com/trackingres.asp?tracking_number=" . $trackingNumber;
		    break;     
		  case "usps":
		    $url = "https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=2&text28777=&tLabels=" . $trackingNumber . "%2C";
		    break;       
		  case "abf":
		    $url = "https://arcb.com/tools/tracking.html#/" . $trackingNumber;
		    break;      
		  default:
		    $url = "";
		  } 

		  return $url;  

	}


}
