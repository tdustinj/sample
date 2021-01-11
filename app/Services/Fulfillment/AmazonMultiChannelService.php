<?php

namespace App\Services\Fulfillment;

use App\Jobs\AmazonMCCreate;
use App\Services\Fulfillment\FulfillmentGeneratorContract;
use Illuminate\Support\Facades\Log;
use App\Models\Fulfillment, App\Models\Contact;
use Carbon\Carbon;
use \FBAOutboundServiceMWS_Client;
use \FBAOutboundServiceMWS_Model_Address;
use \FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem;
use \FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList;
use \FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest;
use \FBAOutboundServiceMWS_Interface;
use \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest;
use \FBAOutboundServiceMWS_Model_NotificationEmailList;
use \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem;
use \FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList;
use \FBAOutboundServiceMWS_Model_Currency;
use \DOMDocument;

class AmazonMultiChannelService implements FulfillmentGeneratorContract
{
    public function __construct()
    {
    	$this->fulfillmentInfo = null;
        $this->request = null;
        $this->serviceType = null;

    }

    public function configShipment($config){
        $this->serviceType = $config->get('shipMethod');
    	$shipment = Fulfillment::where('fk_workorder_id', '=',$config->get('workorderId'))->where( 'id', '=',$config->get('fulfillmentId'))->with(['fulfillmentType:id,fulfillment_name', 'package.items.workorderItem:id,model_number,unit_price'])->get();
    	$contactInfo = Contact::where('id', '=', $shipment[0]->fk_ship_to_contact_id)->first();
    	$fulfillment = array('shipment'=> $shipment, 'contact'=> $contactInfo);
    	$this->fulfillmentInfo = $fulfillment;

        $serviceUrl = "https://mws.amazonservices.com/FulfillmentOutboundShipment/2010-10-01";
        $config = array(
            'ServiceURL' => $serviceUrl,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        );

        $service = new FBAOutboundServiceMWS_Client(env('AWS_ACCESS_KEY_ID'), env('AWS_SECRET_ACCESS_KEY'), $config, env('APPLICATION_NAME'),
            env('APPLICATION_VERSION'));
        $this->service = $service;
    	
        return "single";
    }

    public function getQuote(){
        $service = $this->service;
        $contactInfo = $this->fulfillmentInfo['contact'];
        $shipment = $this->fulfillmentInfo['shipment'];

        $address = new FBAOutboundServiceMWS_Model_Address();
            $address->setName($contactInfo->first_name . " " . $contactInfo->last_name);
            $address->setLine1($contactInfo->address);
            $address->setCity($contactInfo->city);
            $address->setStateOrProvinceCode($contactInfo->state);
            $address->setCountryCode($contactInfo->country);
            $address->setPostalCode($contactInfo->zip);
            $address->setPhoneNumber($contactInfo->mobile_phone);

        
        $items = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList();
        foreach($shipment[0]->package[0]->items as $fbaItem){
            $item =  new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem();
            $item->setSellerSKU($fbaItem->workorderItem->model_number);
            $item->setQuantity($fbaItem->qty);
            $item->setSellerFulfillmentOrderItemId($fbaItem->fk_fulfillment_package_id . "-" . $fbaItem->workorderItem->model_number);
            $items->withmember($item);
        }

        $request = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest();
        $request->setSellerId(env('MERCHANT_ID'));
        $request->setAddress($address);  
        $request->setItems($items);

        $invoked = $this->invokeGetFulfillmentPreview($service, $request);
        $this->request = $invoked->GetFulfillmentPreviewResult->FulfillmentPreviews;


    	return $this->request;
    }

    public function confirmFulfillment(){
        $service = $this->service;
        $contactInfo = $this->fulfillmentInfo['contact'];
        $shipment = $this->fulfillmentInfo['shipment'];
        
        AmazonMCCreate::dispatch($shipment, $contactInfo, $this->serviceType, $service);
        
        return array('created'=>$shipment);
    }

    public function createFulfillment($shipment, $contactInfo, $serviceType, $service){
            $request = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest();
            $request->setSellerId(env('MERCHANT_ID'));

            $address = new FBAOutboundServiceMWS_Model_Address();
                // $address->setName($contactInfo->first_name . " " . $contactInfo->last_name);
                $address->setName('Peter Christie');
                $address->setLine1($contactInfo->address);
                // $address->setLine2($orderRequest["AddressFieldTwo"]);
                // $address->setLine3($orderRequest["AddressFieldThree"]);
                $address->setCity($contactInfo->city);
                $address->setCountryCode($contactInfo->country);
                $address->setStateOrProvinceCode($contactInfo->state);
                $address->setPostalCode($contactInfo->zip);
                $address->setPhoneNumber($contactInfo->mobile_phone);

            $email = new FBAOutboundServiceMWS_Model_NotificationEmailList();
                $email->setmember('pchristie@walts.com');

            $request->setNotificationEmailList($email);
            $request->setDestinationAddress($address);
            $request->setDisplayableOrderId($shipment[0]->fk_workorder_id);
            $request->setFulfillmentAction('Hold'); // Ship ||  Hold
            $request->setFulfillmentMethod('Consumer');
            $request->setFulfillmentPolicy('FillOrKill');
            $request->setSellerFulfillmentOrderId($shipment[0]->id);
            $request->setDisplayableOrderDateTime(now()->toIso8601String());
            $request->setDisplayableOrderComment("Thank you for your purchase from Walts!");
            $request->setShippingSpeedCategory($serviceType);

            $items = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList();
            foreach($shipment[0]->package[0]->items as $fbaItem){
                $item = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem();
                    $item->setSellerSKU($fbaItem->workorderItem->model_number);
                    $item->setQuantity($fbaItem->qty);
                    $item->setSellerFulfillmentOrderItemId($fbaItem->fk_fulfillment_package_id . "-" . $fbaItem->workorderItem->model_number);// Need to confirm that we should name
                    $item->setGiftMessage('Thank you for your purchase from Walts!');
                    $item->setFulfillmentNetworkSKU($fbaItem->workorderItem->model_number);
                    $item->setOrderItemDisposition('Sellable');
                    $price = new FBAOutboundServiceMWS_Model_Currency();
                        $price->setCurrencyCode("USD");
                        $price->setValue(round($fbaItem->workorderItem->unit_price * .8, 2));
                    $item->setPerUnitDeclaredValue($price);
                $items->withmember($item);
            }
            $request->setItems($items);
            print_r($request);

            $result = $this->invokeCreateFulfillmentOrder($service, $request);
            print_r($result);
    }

    /**
     * Get Get Fulfillment Preview Action Sample
     * Gets competitive pricing and related information for a product identified by
     * the MarketplaceId and ASIN.
     *
     * @param FBAOutboundServiceMWS_Interface $service instance of FBAOutboundServiceMWS_Interface
     * @param mixed $request FBAOutboundServiceMWS_Model_GetFulfillmentPreview or array of parameters
     */
    private function invokeGetFulfillmentPreview(FBAOutboundServiceMWS_Interface $service, $request)
    {

        $result = "Trying To Call GetFulfillmentPreview";
        try {
            $response = $service->GetFulfillmentPreview($request);

          //  echo("Service Response\n");
          //  echo("=============================================================================\n");

            $dom = new DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $result = simplexml_load_string($dom->saveXML());
      //        echo("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

        } catch(FBAOutboundServiceMWS_Exception $ex) {
            print_r($ex);
          //  echo("Caught Exception: " . $ex->getMessage() . "\n");
          //  echo("Response Status Code: " . $ex->getStatusCode() . "\n");
          //  echo("Error Code: " . $ex->getErrorCode() . "\n");
            echo("Error Type: " . $ex->getErrorType() . "\n");
          //  echo("Request ID: " . $ex->getRequestId() . "\n");
            echo("XML: " . $ex->getXML() . "\n");
           // echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
         //   print_r($orderInfo);
            
        }
           
         return $result;
    }

    private function invokeCreateFulfillmentOrder(FBAOutboundServiceMWS_Interface $service, $request)
    {
        $result = array();
        try {
            echo "Hello";
            $response = $service->CreateFulfillmentOrder($request);
            $dom = new DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $result['xml'] = simplexml_load_string($dom->saveXML());
            print_r($result['xml']);
        //     //echo("Service Response\n");
        //     //echo("=============================================================================\n");

        //     $dom = new DOMDocument();
        //     $dom->loadXML($response->toXML());
        //     $dom->preserveWhiteSpace = false;
        //     $dom->formatOutput = true;
        //     $result["code"] = "200";
        //     $result["responseXml"] = $dom->saveXML();
        //     $xml = simplexml_load_string($dom->saveXML());
        //     // $result["amazonRequestId"] = $xml->ResponseMetadata->RequestId;
        //     // $result["ResponseHeaderMetadata"] =  $response->getResponseHeaderMetadata();

        } catch(FBAOutboundServiceMWS_Exception $ex) {
            $result["error"] =  $ex->getMessage();
            $result["code"] = $ex->getStatusCode() ;
            $result["responseXml"] = $ex->getXML();
            $result["amazonRequestId"] = $ex->getRequestId();
            $result["ResponseHeaderMetadata"] =  $ex->getResponseHeaderMetadata();


        }
        return $result;
    }

}

?>