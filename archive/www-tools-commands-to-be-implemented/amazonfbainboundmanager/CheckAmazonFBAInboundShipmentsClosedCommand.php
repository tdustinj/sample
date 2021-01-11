<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckAmazonFBAInboundShipmentsClosedCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:check-amazon-fba-inbound-shipments-closed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check that FBA Inbound shipments are still closed';

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
    public function fire(){
        $lastUpdatedAfter = new DateTime();

        $now = date("Y-m-d hh:mm:ss");
        $lastUpdatedAfter->modify('-30 day');
        $lastUpdatedAfter = $lastUpdatedAfter->format("c");
        $lastUpdatedBefore = new DateTime();
        $lastUpdatedBefore = $lastUpdatedBefore->format("c");

        $amazonDistributionCenters = \AmazonWarehouse::where('status', '=', 1)->get();
        $amazonFcList = array();
        //print_r($amazonDistributionCenters);
        foreach($amazonDistributionCenters as $fc){
            $amazonFcList[$fc->code] = $fc;
        }
        $orderList = array();
        $now =  date("Y-m-d hh:mm:ss");
        $orderListQuery = AmazonFBAInboundShipment::where('ShipmentStatus', '=', 'CLOSED')->get(); //->pluck('id', 'ShipmentId');
        foreach($orderListQuery as $order){
            $orderList[] = $order->ShipmentId;

        }
        $orderListQuery = null;
       // print_r($orderList);
        $serviceUrl = "https://mws.amazonservices.com/FulfillmentInboundShipment/2010-10-01";
        $mwsApi = new AmazonMwsAPIUtil($serviceUrl);
        $service = new FBAInboundServiceMWS_Client($mwsApi->AWS_ACCESS_KEY_ID, $mwsApi->AWS_SECRET_ACCESS_KEY, $mwsApi->APPLICATION_NAME,
            $mwsApi->APPLICATION_VERSION, $mwsApi->config);



        $request = new FBAInboundServiceMWS_Model_ListInboundShipmentsRequest();
        $request->setSellerId($mwsApi->MERCHANT_ID);
        $shiplist = New FBAInboundServiceMWS_Model_ShipmentIdList();
        $shiplist->setmember($orderList);
        $request->setShipmentIdList($shiplist);
        $request->setLastUpdatedAfter($lastUpdatedAfter);
        $request->setLastUpdatedBefore($lastUpdatedBefore);
      //  dd($request->getShipmentIdList());
      // $statusList->setmember($status);
    //   // $request->setShipmentStatusList($statusList);

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
                if(isset($result["ListInboundShipmentsResult"]["ShipmentData"]["member"])) {
                    if (isset($result["ListInboundShipmentsResult"]["ShipmentData"]["member"][0])) {
                        foreach ($result["ListInboundShipmentsResult"]["ShipmentData"]["member"] as $shipment) {
                            echo "============================ Shipment Information if=========================================== " . "\n\r";
                             $fbaShipment = AmazonFBAInboundShipment::where('ShipmentId', '=',$shipment["ShipmentId"])->get();
                            // $fbaShipment[0]->ShipmentName = $shipment["ShipmentName"]; 
                            $fbaShipment[0]->ShipFromAddress_Name = $shipment["ShipFromAddress"]["Name"];
                            $fbaShipment[0]->ShipFromAddress_AddressLine1 = $shipment["ShipFromAddress"]["AddressLine1"];
                            if (isset($shipment["ShipFromAddress"]["AddressLine2"])) {
                                $fbaShipment[0]->ShipFromAddress_AddressLine2 = $shipment["ShipFromAddress"]["AddressLine2"];
                            }
                            $fbaShipment[0]->ShipFromAddress_City = $shipment["ShipFromAddress"]["City"];
                            $fbaShipment[0]->ShipFromAddress_StateOrProvinceCode = $shipment["ShipFromAddress"]["StateOrProvinceCode"];
                            $fbaShipment[0]->ShipFromAddress_CountryCode = $shipment["ShipFromAddress"]["CountryCode"];
                            $fbaShipment[0]->ShipFromAddress_PostalCode = $shipment["ShipFromAddress"]["PostalCode"];
                            $fbaShipment[0]->DestinationFulfillmentCenterId = $shipment["DestinationFulfillmentCenterId"];
                            $fbaShipment[0]->DestinationFulfillmentCenterId_Address = $this->getAmazonFC($shipment["DestinationFulfillmentCenterId"], $amazonFcList);




                            $fbaShipment[0]->ShipmentStatus = $shipment["ShipmentStatus"];
                            $fbaShipment[0]->LabelPrepType = $shipment["LabelPrepType"];
                            $fbaShipment[0]->AreCasesRequired = $shipment["AreCasesRequired"];
                            try {
                                if($fbaShipment[0]->ShipmentStatus != $shipment["ShipmentStatus"]) {
                                    $fbaShipment[0]->save();
                                }
                            } catch (PDOException $exception) {
                                echo "\n\r \n\r \n\r" . "_____________________________________________Multi Item" . "\n\r";
                                // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                                print_r($exception->getMessage());
                            }

                            sleep(1);

                        }
                    } else {
                        echo "============================ Shipment Information else ===========================================  $nextToken" . "\n\r";
                        $shipment = $result["ListInboundShipmentsResult"]["ShipmentData"]["member"];
                        $fbaShipment = AmazonFBAInboundShipment::where('ShipmentId', '=',$shipment["ShipmentId"])->get();
                        $fbaShipment[0]->ShipmentName = $shipment["ShipmentName"];
                        $fbaShipment[0]->ShipFromAddress_Name = $shipment["ShipFromAddress"]["Name"];
                        $fbaShipment[0]->ShipFromAddress_AddressLine1 = $shipment["ShipFromAddress"]["AddressLine1"];
                        if (isset($shipment["ShipFromAddress"]["AddressLine2"])) {
                            $fbaShipment[0]->ShipFromAddress_AddressLine2 = $shipment["ShipFromAddress"]["AddressLine2"];
                        }
                        $fbaShipment[0]->ShipFromAddress_City = $shipment["ShipFromAddress"]["City"];
                        $fbaShipment[0]->ShipFromAddress_StateOrProvinceCode = $shipment["ShipFromAddress"]["StateOrProvinceCode"];
                        $fbaShipment[0]->ShipFromAddress_CountryCode = $shipment["ShipFromAddress"]["CountryCode"];
                        $fbaShipment[0]->ShipFromAddress_PostalCode = $shipment["ShipFromAddress"]["PostalCode"];
                        $fbaShipment[0]->DestinationFulfillmentCenterId = $shipment["DestinationFulfillmentCenterId"];
                        $fbaShipment[0]->DestinationFulfillmentCenterId_Address = $this->getAmazonFC($shipment["DestinationFulfillmentCenterId"], $amazonFcList);

                        if($fbaShipment[0]->ShipmentStatus != $shipment["ShipmentStatus"]){
                            $fbaShipment[0]->amazon_last_update_status = $now;
                        }
                        $fbaShipment[0]->ShipmentStatus = $shipment["ShipmentStatus"];
                        $fbaShipment[0]->LabelPrepType = $shipment["LabelPrepType"];
                        $fbaShipment[0]->AreCasesRequired = $shipment["AreCasesRequired"];
                        try {
                            $fbaShipment[0]->save();
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
              //  print_r($result);
                if(isset($result["ListInboundShipmentsByNextTokenResult"]["NextToken"])){
                    $nextToken = $result["ListInboundShipmentsByNextTokenResult"]["NextToken"];
                }
                else{
                    $nextToken = '';
                }
                // print_r($result);
                if(isset($result["ListInboundShipmentsByNextTokenResult"]["ShipmentData"]["member"])) {
                    if (isset($result["ListInboundShipmentsByNextTokenResult"]["ShipmentData"]["member"][0])) {
                        foreach ($result["ListInboundShipmentsByNextTokenResult"]["ShipmentData"]["member"] as $shipment) {
                            echo "============================ Shipment Information Next  if===========================================  " . "\n\r";
                            $fbaShipment = AmazonFBAInboundShipment::where('ShipmentId', '=',$shipment["ShipmentId"])->get();
                            $fbaShipment[0]->ShipmentName = $shipment["ShipmentName"];
                            $fbaShipment[0]->ShipFromAddress_Name = $shipment["ShipFromAddress"]["Name"];
                            $fbaShipment[0]->ShipFromAddress_AddressLine1 = $shipment["ShipFromAddress"]["AddressLine1"];
                            if (isset($shipment["ShipFromAddress"]["AddressLine2"])) {
                                $fbaShipment[0]->ShipFromAddress_AddressLine2 = $shipment["ShipFromAddress"]["AddressLine2"];
                            }
                            $fbaShipment[0]->ShipFromAddress_City = $shipment["ShipFromAddress"]["City"];
                            $fbaShipment[0]->ShipFromAddress_StateOrProvinceCode = $shipment["ShipFromAddress"]["StateOrProvinceCode"];
                            $fbaShipment[0]->ShipFromAddress_CountryCode = $shipment["ShipFromAddress"]["CountryCode"];
                            $fbaShipment[0]->ShipFromAddress_PostalCode = $shipment["ShipFromAddress"]["PostalCode"];
                            $fbaShipment[0]->DestinationFulfillmentCenterId = $shipment["DestinationFulfillmentCenterId"];
                            $fbaShipment[0]->DestinationFulfillmentCenterId_Address = $this->getAmazonFC($shipment["DestinationFulfillmentCenterId"], $amazonFcList);

                            if($fbaShipment[0]->ShipmentStatus != $shipment["ShipmentStatus"]){
                                $fbaShipment[0]->amazon_last_update_status = $now;
                            }
                            $fbaShipment[0]->ShipmentStatus = $shipment["ShipmentStatus"];
                            $fbaShipment[0]->LabelPrepType = $shipment["LabelPrepType"];
                            $fbaShipment[0]->AreCasesRequired = $shipment["AreCasesRequired"];
                            try {
                                $fbaShipment[0]->save();
                            } catch (PDOException $exception) {
                                // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                                print_r($exception->getMessage());
                            }

                            sleep(1);

                        }
                    } else {
                        echo "============================ Shipment Information Next  else===========================================  " . "\n\r";
                        $shipment = $result["ListInboundShipmentsByNextTokenResult"]["ShipmentData"]["member"];
                        $fbaShipment = AmazonFBAInboundShipment::where('ShipmentId', '=',$shipment["ShipmentId"])->get();
                        $fbaShipment[0]->ShipmentName = $shipment["ShipmentName"];
                        $fbaShipment[0]->ShipFromAddress_Name = $shipment["ShipFromAddress"]["Name"];
                        $fbaShipment[0]->ShipFromAddress_AddressLine1 = $shipment["ShipFromAddress"]["AddressLine1"];
                        if (isset($shipment["ShipFromAddress"]["AddressLine2"])) {
                            $fbaShipment[0]->ShipFromAddress_AddressLine2 = $shipment["ShipFromAddress"]["AddressLine2"];
                        }
                        $fbaShipment[0]->ShipFromAddress_City = $shipment["ShipFromAddress"]["City"];
                        $fbaShipment[0]->ShipFromAddress_StateOrProvinceCode = $shipment["ShipFromAddress"]["StateOrProvinceCode"];
                        $fbaShipment[0]->ShipFromAddress_CountryCode = $shipment["ShipFromAddress"]["CountryCode"];
                        $fbaShipment[0]->ShipFromAddress_PostalCode = $shipment["ShipFromAddress"]["PostalCode"];
                        $fbaShipment[0]->DestinationFulfillmentCenterId = $shipment["DestinationFulfillmentCenterId"];
                        $fbaShipment[0]->DestinationFulfillmentCenterId_Address = $this->getAmazonFC($shipment["DestinationFulfillmentCenterId"], $amazonFcList);

                        if($fbaShipment[0]->ShipmentStatus != $shipment["ShipmentStatus"]){
                            $fbaShipment[0]->amazon_last_update_status = $now;
                        }
                        $fbaShipment[0]->ShipmentStatus = $shipment["ShipmentStatus"];
                        $fbaShipment[0]->LabelPrepType = $shipment["LabelPrepType"];
                        $fbaShipment[0]->AreCasesRequired = $shipment["AreCasesRequired"];
                        try {
                            $fbaShipment[0]->save();
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

}



