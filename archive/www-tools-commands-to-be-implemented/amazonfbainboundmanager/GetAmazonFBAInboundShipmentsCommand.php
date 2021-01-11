<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetAmazonFBAInboundShipmentsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:get-amazon-fba-inbound-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the List of Amazon FBA Inbound Shipments ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $amazonDistributionCenters = \AmazonWarehouse::where('status', '=', 1)->get();
        $amazonFcList = array();
        //print_r($amazonDistributionCenters);
        foreach($amazonDistributionCenters as $fc){
            $amazonFcList[$fc->code] = $fc;
        }
       // print_r($amazonFcList);
        $workflows = array("WORKING", "SHIPPED", "IN_TRANSIT", "DELIVERED", "CHECKED_IN", "RECEIVING", "CLOSED");
        foreach($workflows as $status) {
            $this->ListInboundShipments($status, $amazonFcList);
        }

    }
    public function ListInboundShipments($status, $amazonFcList)
    {

        $serviceUrl = "https://mws.amazonservices.com/FulfillmentInboundShipment/2010-10-01";
        $mwsApi = new AmazonMwsAPIUtil($serviceUrl);
        $service = new FBAInboundServiceMWS_Client($mwsApi->AWS_ACCESS_KEY_ID, $mwsApi->AWS_SECRET_ACCESS_KEY, $mwsApi->APPLICATION_NAME,
            $mwsApi->APPLICATION_VERSION, $mwsApi->config);



        $request = new FBAInboundServiceMWS_Model_ListInboundShipmentsRequest();
        $request->setSellerId($mwsApi->MERCHANT_ID);
        /*
         * ShipmentStatus values:
WORKING - The shipment was created by the seller, but has not yet shipped.
        READY TO SHIP -
SHIPPED - The shipment was picked up by the carrier.
IN_TRANSIT - The carrier has notified the Amazon fulfillment center that it is aware of the shipment.
DELIVERED - The shipment was delivered by the carrier to the Amazon fulfillment center.
CHECKED_IN - The shipment was checked-in at the receiving dock of the Amazon fulfillment center.
RECEIVING - The shipment has arrived at the Amazon fulfillment center, but not all items have been marked as received.
CLOSED - The shipment has arrived at the Amazon fulfillment center and all items have been marked as received.
CANCELLED - The shipment was cancelled by the seller after the shipment was sent to the Amazon fulfillment center.
DELETED - The shipment was cancelled by the seller before the shipment was sent to the Amazon fulfillment center.
ERROR - There was an error with the shipment and it was not processed by Amazon.

         */
        echo "\n\r \n\r \n\r" . "!!!!!!!!!!-------------------- Fetching Status = $status ------------------!!!!!!!!!!!!!!!!!!!!!!" . "\n\r \n\r \n\r";
       // define('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');
        $lastUpdatedAfter = new DateTime();

        $now = date("Y-m-d hh:mm:ss");
        $lastUpdatedAfter->modify('-7 day');
        $lastUpdatedAfter = $lastUpdatedAfter->format("c");
        $lastUpdatedBefore = new DateTime();
        $lastUpdatedBefore = $lastUpdatedBefore->format("c");


        // var_dump($lastUpdatedBefore);
        // var_dump($lastUpdatedAfter);
        $status = array($status);
        $statusList = new \FBAInboundServiceMWS_Model_ShipmentStatusList();
        $statusList->setmember($status);
        $request->setShipmentStatusList($statusList);
        $request->setLastUpdatedAfter($lastUpdatedAfter);
        $request->setLastUpdatedBefore($lastUpdatedBefore);
        $nextToken = 'initial';
        $shipmentList = array();
        while($nextToken != '') {
            if ($nextToken == 'initial'){
                $result = $this->invokeListInboundShipments($service, $request);
                $xmlObj = simplexml_load_string($result);

                $json = json_encode($xmlObj);
                // return $json;
                $result = json_decode($json, TRUE);
               // echo "<pre>";
               // print_r($result);
                if(isset($result["ListInboundShipmentsResult"]["NextToken"])){
                    $nextToken = $result["ListInboundShipmentsResult"]["NextToken"];
                }
                else{
                    $nextToken = '';
                }
                // print_r($result);
                // exit;
                if(isset($result["ListInboundShipmentsResult"]["ShipmentData"]["member"])) {
                    if (isset($result["ListInboundShipmentsResult"]["ShipmentData"]["member"][0])) {
                        foreach ($result["ListInboundShipmentsResult"]["ShipmentData"]["member"] as $shipment) {
                          //  echo "============================ Shipment Information ===========================================  $nextToken" . "\n\r";
                            //print_r($shipment);
                            $fbaShipment = new AmazonFBAInboundShipment();
                            $fbaShipment->ShipmentId = $shipment["ShipmentId"];
                            $fbaShipment->ShipmentName = $shipment["ShipmentName"];
                            $fbaShipment->ShipFromAddress_Name = $shipment["ShipFromAddress"]["Name"];
                            $fbaShipment->ShipFromAddress_AddressLine1 = $shipment["ShipFromAddress"]["AddressLine1"];
                            if (isset($shipment["ShipFromAddress"]["AddressLine2"])) {
                                $fbaShipment->ShipFromAddress_AddressLine2 = $shipment["ShipFromAddress"]["AddressLine2"];
                            }
                            $fbaShipment->ShipFromAddress_City = $shipment["ShipFromAddress"]["City"];
                            $fbaShipment->ShipFromAddress_StateOrProvinceCode = $shipment["ShipFromAddress"]["StateOrProvinceCode"];
                            $fbaShipment->ShipFromAddress_CountryCode = $shipment["ShipFromAddress"]["CountryCode"];
                            $fbaShipment->ShipFromAddress_PostalCode = $shipment["ShipFromAddress"]["PostalCode"];
                            $fbaShipment->DestinationFulfillmentCenterId = $shipment["DestinationFulfillmentCenterId"];
                            $fbaShipment->DestinationFulfillmentCenterId_Address = $this->getAmazonFC($shipment["DestinationFulfillmentCenterId"], $amazonFcList);

                            $fbaShipment->ShipmentStatus = $shipment["ShipmentStatus"];
                            $fbaShipment->LabelPrepType = $shipment["LabelPrepType"];
                            $fbaShipment->AreCasesRequired = $shipment["AreCasesRequired"];
                            try {
                                $fbaShipment->save();
                            } catch (PDOException $exception) {
                                echo "\n\r \n\r \n\r" . "_____________________________________________Multi Item" . "\n\r";
                                // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                                print_r($exception->getMessage());
                            }

                            sleep(1);

                        }
                    } else {
                      //  echo "============================ Shipment Information ===========================================  $nextToken" . "\n\r";
                        $shipment = $result["ListInboundShipmentsResult"]["ShipmentData"]["member"];
                        $fbaShipment = new AmazonFBAInboundShipment();
                        $fbaShipment->ShipmentId = $shipment["ShipmentId"];
                        $fbaShipment->ShipmentName = $shipment["ShipmentName"];
                        $fbaShipment->ShipFromAddress_Name = $shipment["ShipFromAddress"]["Name"];
                        $fbaShipment->ShipFromAddress_AddressLine1 = $shipment["ShipFromAddress"]["AddressLine1"];
                        if (isset($shipment["ShipFromAddress"]["AddressLine2"])) {
                            $fbaShipment->ShipFromAddress_AddressLine2 = $shipment["ShipFromAddress"]["AddressLine2"];
                        }
                        $fbaShipment->ShipFromAddress_City = $shipment["ShipFromAddress"]["City"];
                        $fbaShipment->ShipFromAddress_StateOrProvinceCode = $shipment["ShipFromAddress"]["StateOrProvinceCode"];
                        $fbaShipment->ShipFromAddress_CountryCode = $shipment["ShipFromAddress"]["CountryCode"];
                        $fbaShipment->ShipFromAddress_PostalCode = $shipment["ShipFromAddress"]["PostalCode"];
                        $fbaShipment->DestinationFulfillmentCenterId = $shipment["DestinationFulfillmentCenterId"];
                        $fbaShipment->DestinationFulfillmentCenterId_Address = $this->getAmazonFC($shipment["DestinationFulfillmentCenterId"], $amazonFcList);
                        $fbaShipment->ShipmentStatus = $shipment["ShipmentStatus"];
                        $fbaShipment->LabelPrepType = $shipment["LabelPrepType"];
                        $fbaShipment->AreCasesRequired = $shipment["AreCasesRequired"];
                        $fbaShipment->amazon_last_update_status = $now;

                        try {
                            $fbaShipment->save();
                        } catch (PDOException $exception) {
                            // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                            echo "\n\r \n\r \n\r" . "_____________________________________________Single Item" . "\n\r";

                            print_r($exception->getMessage());
                        }

                    }
                }
            }
            else{


                $request = new \FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenRequest();
                $request->setSellerId($mwsApi->MERCHANT_ID);
                $request->setNextToken($nextToken);
                $result = $this->invokeListInboundShipmentsByNextToken($service, $request);
                $xmlObj = simplexml_load_string($result);

                $json = json_encode($xmlObj);
                // return $json;
                $result = json_decode($json, TRUE);
               //  echo "<pre>";
               // print_r($result);
                if(isset($result["ListInboundShipmentsByNextTokenResult"]["NextToken"])){
                    $nextToken = $result["ListInboundShipmentsByNextTokenResult"]["NextToken"];
                }
                else{
                    $nextToken = '';
                }
                // print_r($result);
                if(isset($result["ListInboundShipmentsResult"]["ShipmentData"]["member"])) {
                    if (isset($result["ListInboundShipmentsByNextTokenResult"]["ShipmentData"]["member"][0])) {
                        foreach ($result["ListInboundShipmentsByNextTokenResult"]["ShipmentData"]["member"] as $shipment) {
                          //  echo "============================ Shipment Information Next  ===========================================  " . "\n\r";
                            // print_r($shipment);
                            $fbaShipment = new AmazonFBAInboundShipment();
                            $fbaShipment->ShipmentId = $shipment["ShipmentId"];
                            $fbaShipment->ShipmentName = $shipment["ShipmentName"];
                            $fbaShipment->ShipFromAddress_Name = $shipment["ShipFromAddress"]["Name"];
                            $fbaShipment->ShipFromAddress_AddressLine1 = $shipment["ShipFromAddress"]["AddressLine1"];
                            if (isset($shipment["ShipFromAddress"]["AddressLine2"])) {
                                $fbaShipment->ShipFromAddress_AddressLine2 = $shipment["ShipFromAddress"]["AddressLine2"];
                            }
                            $fbaShipment->ShipFromAddress_City = $shipment["ShipFromAddress"]["City"];
                            $fbaShipment->ShipFromAddress_StateOrProvinceCode = $shipment["ShipFromAddress"]["StateOrProvinceCode"];
                            $fbaShipment->ShipFromAddress_CountryCode = $shipment["ShipFromAddress"]["CountryCode"];
                            $fbaShipment->ShipFromAddress_PostalCode = $shipment["ShipFromAddress"]["PostalCode"];
                            $fbaShipment->DestinationFulfillmentCenterId = $shipment["DestinationFulfillmentCenterId"];

                            $fbaShipment->DestinationFulfillmentCenterId_Address = $this->getAmazonFC($shipment["DestinationFulfillmentCenterId"], $amazonFcList);

                            $fbaShipment->ShipmentStatus = $shipment["ShipmentStatus"];
                            $fbaShipment->LabelPrepType = $shipment["LabelPrepType"];
                            $fbaShipment->AreCasesRequired = $shipment["AreCasesRequired"];
                            $fbaShipment->amazon_last_update_status = $now;
                            try {
                                $fbaShipment->save();
                            } catch (PDOException $exception) {
                                // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                                print_r($exception->getMessage());
                            }

                            sleep(1);

                        }
                    } else {
                       // echo "============================ Shipment Information Next  ===========================================  " . "\n\r";
                        $shipment = $result["ListInboundShipmentsByNextTokenResult"]["ShipmentData"]["member"];
                        $fbaShipment = new AmazonFBAInboundShipment();
                        $fbaShipment->ShipmentId = $shipment["ShipmentId"];
                        $fbaShipment->ShipmentName = $shipment["ShipmentName"];
                        $fbaShipment->ShipFromAddress_Name = $shipment["ShipFromAddress"]["Name"];
                        $fbaShipment->ShipFromAddress_AddressLine1 = $shipment["ShipFromAddress"]["AddressLine1"];
                        if (isset($shipment["ShipFromAddress"]["AddressLine2"])) {
                            $fbaShipment->ShipFromAddress_AddressLine2 = $shipment["ShipFromAddress"]["AddressLine2"];
                        }
                        $fbaShipment->ShipFromAddress_City = $shipment["ShipFromAddress"]["City"];
                        $fbaShipment->ShipFromAddress_StateOrProvinceCode = $shipment["ShipFromAddress"]["StateOrProvinceCode"];
                        $fbaShipment->ShipFromAddress_CountryCode = $shipment["ShipFromAddress"]["CountryCode"];
                        $fbaShipment->ShipFromAddress_PostalCode = $shipment["ShipFromAddress"]["PostalCode"];
                        $fbaShipment->DestinationFulfillmentCenterId = $shipment["DestinationFulfillmentCenterId"];
                        $fbaShipment->DestinationFulfillmentCenterId_Address = $this->getAmazonFC($shipment["DestinationFulfillmentCenterId"], $amazonFcList);

                        $fbaShipment->ShipmentStatus = $shipment["ShipmentStatus"];
                        $fbaShipment->LabelPrepType = $shipment["LabelPrepType"];
                        $fbaShipment->AreCasesRequired = $shipment["AreCasesRequired"];
                        $fbaShipment->amazon_last_update_status = $now;
                        try {
                            $fbaShipment->save();
                        } catch (PDOException $exception) {
                            // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                            print_r($exception->getMessage());
                        }

                        sleep(1);

                    }
                }
            }

        }

        return $shipmentList;
    }


    public function invokeListInboundShipments(FBAInboundServiceMWS_Interface $service, $request)
    {
        try {
            $response = $service->ListInboundShipments($request);

            //  echo("Service Response\n");
            //  echo("=============================================================================\n");

            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            return $dom->saveXML();
            // echo("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");

        } catch(FBAInboundServiceMWS_Exception $ex) {
            //  echo("Caught Exception: " . $ex->getMessage() . "\n");
            //  echo("Response Status Code: " . $ex->getStatusCode() . "\n");
            //  echo("Error Code: " . $ex->getErrorCode() . "\n");
            //  echo("Error Type: " . $ex->getErrorType() . "\n");
            //  echo("Request ID: " . $ex->getRequestId() . "\n");
            return $ex->getXML();
            // echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
        }
    }

    public function invokeListInboundShipmentsByNextToken(FBAInboundServiceMWS_Interface $service, $request)
    {
        try {
            //  print_r($service);
            //  print_r($request);
            $response = $service->ListInboundShipmentsByNextToken($request);

            //  echo("Service Response\n");
            //  echo("=============================================================================\n");

            $dom = new \DOMDocument();
            $dom->loadXML($response->toXML());
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            // echo("ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
            return $dom->saveXML();

        } catch(FBAInboundServiceMWS_Exception $ex) {
            // echo("Caught Exception: " . $ex->getMessage() . "\n");
            // echo("Response Status Code: " . $ex->getStatusCode() . "\n");
            // echo("Error Code: " . $ex->getErrorCode() . "\n");
            // echo("Error Type: " . $ex->getErrorType() . "\n");
            // echo("Request ID: " . $ex->getRequestId() . "\n");
            //echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
            return $ex->getXML();

        }
    }

    public function getAmazonFC($fcId, $fcList){
        echo $fcId;
        if(isset($fcList[$fcId])) {
            $fcInfo = $fcList[$fcId];
            //print_r($fcInfo);
            $fcAddress = $fcInfo->type . "\n\r" . $fcInfo->address . " " . $fcInfo->address_2 . "\n\r" . $fcInfo->city . ", " . $fcInfo->state . " " . $fcInfo->zip;
        }
        else{
            $fcAddress = 'Not in POS Amazon Warehouse List';
        }
        return $fcAddress;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('example', InputArgument::OPTIONAL, 'An example.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example.', null),
        );
    }

//To remove all the hidden text not displayed on a webpage
    public function strip_html_tags($str)
    {
        $str = preg_replace('/(<|>)\1{2}/is', '', $str);
        $str = preg_replace(
            array(// Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
            ),
            "", //replace above with nothing
            $str);
        $str = $this->replaceWhitespace($str);
        $str = strip_tags($str);
        return $str;
    }

//To replace all types of whitespace with a single space
    public function replaceWhitespace($str)
    {
        $result = $str;
        foreach (array(
                     "  ", " \t", " \r", " \n",
                     "\t\t", "\t ", "\t\r", "\t\n",
                     "\r\r", "\r ", "\r\t", "\r\n",
                     "\n\n", "\n ", "\n\t", "\n\r",
                 ) as $replacement) {
            $result = str_replace($replacement, $replacement[0], $result);
        }
        return $str !== $result ? $this->replaceWhitespace($result) : $result;
    }


}


