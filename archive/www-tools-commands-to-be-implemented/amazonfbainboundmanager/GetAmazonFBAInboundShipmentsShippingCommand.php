<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RefactorGetAmazonFBAInboundShipmentsShippingCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:get-amazon-fba-inbound-shipments-shipping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the List of Amazon FBA Inbound Shipments Shipping ';

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
        $shippingInfoList = array();
        $shippingInfo = AmazonFBAInboundShipmentShipping::all();
        foreach($shippingInfo as $shipment) {
            $shippingInfoList[$shipment->shipmentid_line_number] = $shipment->shipmentid_line_number;
        }
        $nonClosedFBAOrders = AmazonFBAInboundShipment::where('ShipmentStatus', '!=', 'DELETED')
                                                      ->where('ShipmentStatus', '!=', 'CLOSED')
                                                      ->where('ShipmentStatus', '!=', 'CANCELLED')
                                                      ->orderBy('ShipmentId', 'DESC')->get();
        foreach($nonClosedFBAOrders as $shipment) {
             print_r("================= " . $shipment->ShipmentId . "========================== \n\r");
             $this->GetTransportContent($shipment->ShipmentId, $shippingInfoList);
        }
    }

    public function GetTransportContent($shipmentId, $shippingInfoList){
        $serviceUrl = "https://mws.amazonservices.com/FulfillmentInboundShipment/2010-10-01";
        $mwsApi = new AmazonMwsAPIUtil($serviceUrl);
        $service = new FBAInboundServiceMWS_Client($mwsApi->AWS_ACCESS_KEY_ID, $mwsApi->AWS_SECRET_ACCESS_KEY, $mwsApi->APPLICATION_NAME, $mwsApi->APPLICATION_VERSION, $mwsApi->config);
        $request = new \FBAInboundServiceMWS_Model_GetTransportContentRequest();
        $request->setSellerId($mwsApi->MERCHANT_ID);
        $request->setShipmentId($shipmentId);
        $result = $this->invokeGetTransportContent($service, $request);
        $xmlObj = simplexml_load_string($result);
        $json = json_encode($xmlObj);
        $shippingInformation = json_decode($json, TRUE);
        $packages = array();
        if(isset($shippingInformation["GetTransportContentResult"])) {
            switch($shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportHeader"]["ShipmentType"]){
                case "LTL" :
                   echo "LTL Shipment ";
                   if(isset($shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["PartneredLtlData"])){
                    $packages = $this->getPartneredLtlData($shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["PartneredLtlData"]);
                   }else{
                    $packages = $this->getNonPartneredLtlData($shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["NonPartneredLtlData"]);
                   }
                     break;
                case "SP" :
                    echo "SP Shipment ";
                    if(isset($shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["PartneredSmallParcelData"])) {
                        $packages = $this->getPartneredSmallParcelData($shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["PartneredSmallParcelData"]);
                    }else{
                        $packages = $this->getNonPartneredSmallParcelData($shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportDetails"]["NonPartneredSmallParcelData"]);
                    }
                    break;
                default:
                    print_r($shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportDetails"]);
                    break;
            }

            $totalPackages = sizeof($packages);
            $i = 0;
            foreach($packages as $package){
                $shippingKey = $shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportHeader"]["ShipmentId"] . '-' . $i;
                echo $shippingKey . "\n\r";
                if(array_key_exists($shippingKey, $shippingInfoList)) {
                    $transportContent =  AmazonFBAInboundShipmentShipping::where('shipmentid_line_number', '=',$shippingKey)->get();
                }else{
                    $transportContent[] = new AmazonFBAInboundShipmentShipping();
                }

                $transportContent[0]->TransportStatus = $shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportResult"]["TransportStatus"];
                $transportContent[0]->SellerId = $shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportHeader"]["SellerId"];
                $transportContent[0]->IsPartnered = $shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportHeader"]["IsPartnered"];
                $transportContent[0]->ShipmentType = $shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportHeader"]["ShipmentType"];
                $transportContent[0]->shipmentid_line_number = $shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportHeader"]["ShipmentId"] . '-' . $i;
                $transportContent[0]->ShipmentId = $shippingInformation["GetTransportContentResult"]["TransportContent"]["TransportHeader"]["ShipmentId"];
                // amazon does not provide packe level shipping cost so we will just divide total by number of packages
                if($totalPackages > 1){
                    $shippingCost =  $package["PartneredEstimate"]/ $totalPackages;
                }else{
                    $shippingCost =  $package["PartneredEstimate"];
                }
                $transportContent[0]->PartneredEstimate = $shippingCost;
                $transportContent[0]->Length = $package["Length"];
                $transportContent[0]->Width = $package["Width"];
                $transportContent[0]->Height = $package["Height"];
                $transportContent[0]->Unit = $package["Unit"];
                $transportContent[0]->Weight = $package["Weight"];
                $transportContent[0]->Weight_Unit = $package["Weight_Unit"];
                $transportContent[0]->CarrierName = $package["CarrierName"];
                $transportContent[0]->TrackingId = $package["TrackingId"];
                $transportContent[0]->PackageStatus = $package["PackageStatus"];
                $transportContent[0]->IsStacked = $package["IsStacked"];
                $transportContent[0]->TotalWeight = $package["TotalWeight"];
                $transportContent[0]->SellerDeclaredValue = $package["SellerDeclaredValue"];
                $transportContent[0]->BoxCount = $package["BoxCount"];
                $transportContent[0]->FreightReadyDate = $package["FreightReadyDate"];
                $transportContent[0]->PreviewPickupDate = $package["PreviewPickupDate"];
                $transportContent[0]->PreviewDeliveryDate  = $package["PreviewDeliveryDate"];
                $transportContent[0]->PreviewFreightClass = $package["PreviewFreightClass"];
                $transportContent[0]->AmazonReferenceId  = $package["AmazonReferenceId"];
                $transportContent[0]->IsBillOfLadingAvailable = $package["IsBillOfLadingAvailable"];
                try {
                   $transportContent[0]->save();
                }catch (PDOException $exception) {
                   echo "\n\r \n\r \n\r" . "_____________________________________________Insert" . "\n\r";
                   // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                   print_r($exception->getMessage());
                }
                $i++;
            }
        }
        sleep(1);
        return $result;


    }
    public function getNonPartneredLtlData($ltlData){
        $shipmentDataList = array();
        if(isset($ltlData["PartneredEstimate"])) {
            $partneredEstimate = $ltlData["PartneredEstimate"]["Amount"]["Value"];
        }else{
            $partneredEstimate = 0;
        }
        if(isset($ltlData["PalletList"]["member"]["0"])){
            echo "Non Partneded LTL Multi Item Shipment" . "\n\r\n\r";
            foreach($ltlData["PalletList"]["member"] as $package){
                $shipmentData = array();
                    $shipmentData["Length"] = $this->Value($package, array('Dimensions','Length'), "");
                    $shipmentData["Width"] = $this->Value($package, array('Dimensions','Width'), "");
                    $shipmentData["Height"] = $this->Value($package, array('Dimensions','Height'), "");
                    $shipmentData["Unit"] = $this->Value($package, array('Dimensions','Unit'), "");
                    $shipmentData["Weight"] = $this->Value($package, array('Weight','Value'), "");
                    $shipmentData["Weight_Unit"] = $this->Value($package, array('Weight','Unit'), "");
                    $shipmentData["CarrierName"] =  $this->Value($package, array('CarrierName'), "");
                    $shipmentData["TrackingId"] = $this->Value($package, array('TrackingId'), "");
                    $shipmentData["PackageStatus"] = $this->Value($package, array('PackageStatus'), "");
                    $shipmentData["PartneredEstimate"] = $partneredEstimate;
                $shipmentDataList[] = $shipmentData;
            }
        }elseif(isset($ltlData["PalletList"]["member"])){
            $package = $ltlData["PalletList"]["member"];
            echo "Non Partnered LTL Single Item Shipment" . "\n\r\n\r";
            $shipmentData = array();
                $shipmentData["Length"] = $this->Value($package, array('Dimensions','Length'), "");
                $shipmentData["Width"] = $this->Value($package, array('Dimensions','Width'), "");
                $shipmentData["Height"] = $this->Value($package, array('Dimensions','Height'), "");
                $shipmentData["Unit"] = $this->Value($package, array('Dimensions','Unit'), "");
                $shipmentData["Weight"] = $this->Value($package, array('Weight','Value'), "");
                $shipmentData["Weight_Unit"] = $this->Value($package, array('Weight','Unit'), "");
                $shipmentData["CarrierName"] =  $this->Value($package, array('CarrierName'), "");
                $shipmentData["TrackingId"] = $this->Value($package, array('TrackingId'), "");
                $shipmentData["PackageStatus"] = $this->Value($package, array('PackageStatus'), "");
                $shipmentData["PartneredEstimate"] = $partneredEstimate;
            $shipmentDataList[] = $shipmentData;
        }
        else{
            echo "Non Partnered LTL No Shipment Data" . "\n\r";
            // print_r($ltlData);
        }
        return $shipmentDataList;

    }
    public function getPartneredLtlData($ltlData){
        $shipmentDataList = array();
       // print_r($ltlData);
        if(isset($ltlData["PartneredEstimate"])) {
            $partneredEstimate = $ltlData["PartneredEstimate"]["Amount"]["Value"];
        }
        else{
            $partneredEstimate = 0;
        }
        if(isset($ltlData["PalletList"]["member"]["0"])){
           echo "Multi Item Partnerd LTL Shipment" . "\n\r\n\r";
            foreach($ltlData["PalletList"]["member"] as $package){
                $shipmentData = array();
                    $shipmentData["Length"] = $this->Value($package, array('Dimensions', 'Length'), ""); 
                    $shipmentData["Width"] = $this->Value($package, array('Dimensions', 'Width'), "");
                    $shipmentData["Height"] = $this->Value($package, array('Dimensions', 'Height'), "");
                    $shipmentData["Unit"] = $this->Value($package, array('Dimensions', 'Unit'), "");
                    $shipmentData["Weight"] = $this->Value($package, array('Weight', 'Value'), "");
                    $shipmentData["Weight_Unit"] = $this->Value($package, array('Weight', 'Unit'), "");
                    $shipmentData["IsStacked"] = $this->Value($package, array('IsStacked'), "");
                    $shipmentData["TotalWeight"] = $this->Value($ltlData, array('TotalWeight', 'Value'), "");
                    $shipmentData["SellerDeclaredValue"] = $this->Value($ltlData, array('SellerDeclaredValue', 'Value'), "");
                    $shipmentData["BoxCount"] = $this->Value($ltlData, array('BoxCount'), "");
                    $shipmentData["FreightReadyDate"] = $this->Value($ltlData, array('FreightReadyDate'), "");
                    $shipmentData["PreviewPickupDate"] = $this->Value($ltlData, array('PreviewPickupDate'), "");
                    $shipmentData["PreviewDeliveryDate"] = $this->Value($ltlData, array('PreviewDeliveryDate'), "");
                    $shipmentData["PreviewFreightClass"] = $this->Value($ltlData, array('PreviewFreightClass'), "");
                    $shipmentData["IsBillOfLadingAvailable"] = $this->Value($ltlData, array('IsBillOfLadingAvailable'), "");
                    $shipmentData["PartneredEstimate"] = $partneredEstimate;
                    $shipmentData["CarrierName"] = $this->Value($ltlData, array('CarrierName'), "");
                    $shipmentData["TrackingId"] = $this->Value($ltlData, array('TrackingId'), "");
                    $shipmentData["PackageStatus"] = $this->Value($ltlData, array('PackageStatus'), "");
                    $shipmentData["AmazonReferenceId"]  =  $this->Value($ltlData, array('AmazonReferenceId'), "");
                $shipmentDataList[] = $shipmentData;
            }
        }
        elseif(isset($ltlData["PalletList"]["member"])){
            $package = $ltlData["PalletList"]["member"];
            echo "Single Item Partnered LTL Shipment" . "\n\r\n\r";
            $shipmentData = array();
                $shipmentData["Length"] = $this->Value($package, array('Dimensions', 'Length'), "");
                $shipmentData["Width"] = $this->Value($package, array('Dimensions', 'Width'), "");
                $shipmentData["Height"] = $this->Value($package, array('Dimensions', 'Height'), "");
                $shipmentData["Unit"] = $this->Value($package, array('Dimensions', 'Unit'), "");
                $shipmentData["Weight"] = $this->Value($package, array('Weight', 'Value'), "");
                $shipmentData["Weight_Unit"] = $this->Value($package, array('Weight', 'Unit'), "");
                $shipmentData["IsStacked"] = $this->Value($package, array('IsStacked'), "");
                $shipmentData["TotalWeight"] = $this->Value($ltlData, array('TotalWeight', 'Value'), "");
                $shipmentData["SellerDeclaredValue"] = $this->Value($ltlData, array('SellerDeclaredValue', 'Value'), "");
                $shipmentData["BoxCount"] = $this->Value($ltlData, array('BoxCount'), "");
                $shipmentData["FreightReadyDate"] = $this->Value($ltlData, array('FreightReadyDate'), "");
                $shipmentData["PreviewPickupDate"] = $this->Value($ltlData, array('PreviewPickupDate'), "");
                $shipmentData["PreviewDeliveryDate"] = $this->Value($ltlData, array('PreviewDeliveryDate'), "");
                $shipmentData["PreviewFreightClass"] = $this->Value($ltlData, array('PreviewFreightClass'), "");
                $shipmentData["IsBillOfLadingAvailable"] = $this->Value($ltlData, array('IsBillOfLadingAvailable'), "");
                $shipmentData["PartneredEstimate"] = $partneredEstimate;
                $shipmentData["CarrierName"] = $this->Value($ltlData, array('CarrierName'), "");
                $shipmentData["TrackingId"] = $this->Value($ltlData, array('TrackingId'), "");
                $shipmentData["PackageStatus"] = $this->Value($ltlData, array('PackageStatus'), "");
                $shipmentData["AmazonReferenceId"]  =  $this->Value($ltlData, array('AmazonReferenceId'), "");
            $shipmentDataList[] = $shipmentData;
        }
        else{
            echo "Non Partnered LTL No Shipment Data" . "\n\r";
            print_r($ltlData);
        }
        return $shipmentDataList;

    }
    public function getPartneredSmallParcelData($smallParcelData){
        echo "In getPartneredSmallParcelData";
        $shipmentDataList = array();
        if(isset($smallParcelData["PartneredEstimate"])) {
            $partneredEstimate = $smallParcelData["PartneredEstimate"]["Amount"]["Value"];
        }else{
            $partneredEstimate = 0;
        }
        if(isset($smallParcelData["PackageList"]["member"]["0"])){
           echo "SP Multi Item Shipment" . "\n\r\n\r";
            foreach($smallParcelData["PackageList"]["member"] as $package){
                $shipmentData = array();
                    $shipmentData["Length"] = $this->Value($package, array('Dimensions', 'Length'), ""); 
                    $shipmentData["Width"] = $this->Value($package, array('Dimensions', 'Width'), ""); 
                    $shipmentData["Height"] = $this->Value($package, array('Dimensions', 'Height'), ""); 
                    $shipmentData["Unit"] = $this->Value($package, array('Dimensions', 'Unit'), ""); 
                    $shipmentData["Weight"] = $this->Value($package, array('Weight', 'Value'), ""); 
                    $shipmentData["Weight_Unit"] = $this->Value($package, array('Weight', 'Unit'), ""); 
                    $shipmentData["CarrierName"] = $this->Value($package, array('CarrierName'), ""); 
                    $shipmentData["TrackingId"] = $this->Value($package, array('TrackingId'), ""); 
                    $shipmentData["PackageStatus"] = $this->Value($package, array('PackageStatus'), ""); 
                    $shipmentData["PartneredEstimate"] = $partneredEstimate;
                    $shipmentData["IsStacked"] = $this->Value($package, array('IsStacked'), "");  
                    $shipmentData["TotalWeight"] = $this->Value($package, array('TotalWeight'), "");  
                    $shipmentData["SellerDeclaredValue"] = $this->Value($package, array('SellerDeclaredValue'), "");  
                    $shipmentData["BoxCount"] = $this->Value($package, array('BoxCount'), "");  
                    $shipmentData["FreightReadyDate"] = $this->Value($package, array('FreightReadyDate'), "");  
                    $shipmentData["PreviewPickupDate"] = $this->Value($package, array('PreviewPickupDate'), "");  
                    $shipmentData["PreviewDeliveryDate"]  = $this->Value($package, array('PreviewDeliveryDate'), "");  
                    $shipmentData["PreviewFreightClass"] = $this->Value($package, array('PreviewFreightClass'), "");  
                    $shipmentData["AmazonReferenceId"]  = $this->Value($package, array('AmazonReferenceId'), "");  
                    $shipmentData["IsBillOfLadingAvailable"] = $this->Value($package, array('IsBillOfLadingAvailable'), "");  
                $shipmentDataList[] = $shipmentData;
            }
        }
        elseif(isset($smallParcelData["PackageList"]["member"])){
            $package = $smallParcelData["PackageList"]["member"];
            echo "SP Single Item Shipment" . "\n\r\n\r";
            $shipmentData = array();
                $shipmentData["Length"] = $this->Value($package, array('Dimensions', 'Length'), ""); 
                $shipmentData["Width"] = $this->Value($package, array('Dimensions', 'Width'), ""); 
                $shipmentData["Height"] = $this->Value($package, array('Dimensions', 'Height'), ""); 
                $shipmentData["Unit"] = $this->Value($package, array('Dimensions', 'Unit'), ""); 
                $shipmentData["Weight"] = $this->Value($package, array('Weight', 'Value'), ""); 
                $shipmentData["Weight_Unit"] = $this->Value($package, array('Weight', 'Unit'), ""); 
                $shipmentData["CarrierName"] = $this->Value($package, array('CarrierName'), ""); 
                $shipmentData["TrackingId"] = $this->Value($package, array('TrackingId'), ""); 
                $shipmentData["PackageStatus"] = $this->Value($package, array('PackageStatus'), ""); 
                $shipmentData["PartneredEstimate"] = $partneredEstimate;
                $shipmentData["IsStacked"] = $this->Value($package, array('IsStacked'), "");  
                $shipmentData["TotalWeight"] = $this->Value($package, array('TotalWeight'), "");  
                $shipmentData["SellerDeclaredValue"] = $this->Value($package, array('SellerDeclaredValue'), "");  
                $shipmentData["BoxCount"] = $this->Value($package, array('BoxCount'), "");  
                $shipmentData["FreightReadyDate"] = $this->Value($package, array('FreightReadyDate'), "");  
                $shipmentData["PreviewPickupDate"] = $this->Value($package, array('PreviewPickupDate'), "");  
                $shipmentData["PreviewDeliveryDate"]  = $this->Value($package, array('PreviewDeliveryDate'), "");  
                $shipmentData["PreviewFreightClass"] = $this->Value($package, array('PreviewFreightClass'), "");  
                $shipmentData["AmazonReferenceId"]  = $this->Value($package, array('AmazonReferenceId'), "");  
                $shipmentData["IsBillOfLadingAvailable"] = $this->Value($package, array('IsBillOfLadingAvailable'), ""); 
            $shipmentDataList[] = $shipmentData;
        }else{
            echo "No Shipment Data" . "\n\r";
        }
         return $shipmentDataList;
    }

    public function getNonPartneredSmallParcelData($smallParcelData){
        $shipmentDataList = array();
        $partneredEstimate = $this->Value($smallParcelData, array('PartneredEstimate', 'Amount', 'Value'), 0);
        if(isset($smallParcelData["PackageList"]["member"]["0"])){
            echo "SP Multi Item Shipment" . "\n\r\n\r";
            foreach($smallParcelData["PackageList"]["member"] as $package){
                $shipmentData = array();
                    $shipmentData["Length"] = $this->Value($package, array('Dimensions', 'Length'), ""); 
                    $shipmentData["Width"] = $this->Value($package, array('Dimensions', 'Width'), ""); 
                    $shipmentData["Height"] = $this->Value($package, array('Dimensions', 'Height'), ""); 
                    $shipmentData["Unit"] = $this->Value($package, array('Dimensions', 'Unit'), ""); 
                    $shipmentData["Weight"] = $this->Value($package, array('Weight', 'Value'), ""); 
                    $shipmentData["Weight_Unit"] = $this->Value($package, array('Weight', 'Unit'), ""); 
                    $shipmentData["CarrierName"] = $this->Value($package, array('CarrierName'), ""); 
                    $shipmentData["TrackingId"] = $this->Value($package, array('TrackingId'), ""); 
                    $shipmentData["PackageStatus"] = $this->Value($package, array('PackageStatus'), ""); 
                    $shipmentData["PartneredEstimate"] = $partneredEstimate;
                    $shipmentData["IsStacked"] = $this->Value($package, array('IsStacked'), "");  
                    $shipmentData["TotalWeight"] = $this->Value($package, array('TotalWeight'), "");  
                    $shipmentData["SellerDeclaredValue"] = $this->Value($package, array('SellerDeclaredValue'), "");  
                    $shipmentData["BoxCount"] = $this->Value($package, array('BoxCount'), "");  
                    $shipmentData["FreightReadyDate"] = $this->Value($package, array('FreightReadyDate'), "");  
                    $shipmentData["PreviewPickupDate"] = $this->Value($package, array('PreviewPickupDate'), "");  
                    $shipmentData["PreviewDeliveryDate"]  = $this->Value($package, array('PreviewDeliveryDate'), "");  
                    $shipmentData["PreviewFreightClass"] = $this->Value($package, array('PreviewFreightClass'), "");  
                    $shipmentData["AmazonReferenceId"]  = $this->Value($package, array('AmazonReferenceId'), "");  
                    $shipmentData["IsBillOfLadingAvailable"] = $this->Value($package, array('IsBillOfLadingAvailable'), "");
                $shipmentDataList[] = $shipmentData;
            }
        }
        elseif(isset($smallParcelData["PackageList"]["member"])){
            $package = $smallParcelData["PackageList"]["member"];
            echo "SP Single Item Shipment" . "\n\r\n\r";
            $shipmentData = array();
                $shipmentData["Length"] = $this->Value($package, array('Dimensions', 'Length'), ""); 
                $shipmentData["Width"] = $this->Value($package, array('Dimensions', 'Width'), ""); 
                $shipmentData["Height"] = $this->Value($package, array('Dimensions', 'Height'), ""); 
                $shipmentData["Unit"] = $this->Value($package, array('Dimensions', 'Unit'), ""); 
                $shipmentData["Weight"] = $this->Value($package, array('Weight', 'Value'), ""); 
                $shipmentData["Weight_Unit"] = $this->Value($package, array('Weight', 'Unit'), ""); 
                $shipmentData["CarrierName"] = $this->Value($package, array('CarrierName'), ""); 
                $shipmentData["TrackingId"] = $this->Value($package, array('TrackingId'), ""); 
                $shipmentData["PackageStatus"] = $this->Value($package, array('PackageStatus'), ""); 
                $shipmentData["PartneredEstimate"] = $partneredEstimate;
                $shipmentData["IsStacked"] = $this->Value($package, array('IsStacked'), "");  
                $shipmentData["TotalWeight"] = $this->Value($package, array('TotalWeight'), "");  
                $shipmentData["SellerDeclaredValue"] = $this->Value($package, array('SellerDeclaredValue'), "");  
                $shipmentData["BoxCount"] = $this->Value($package, array('BoxCount'), "");  
                $shipmentData["FreightReadyDate"] = $this->Value($package, array('FreightReadyDate'), "");  
                $shipmentData["PreviewPickupDate"] = $this->Value($package, array('PreviewPickupDate'), "");  
                $shipmentData["PreviewDeliveryDate"]  = $this->Value($package, array('PreviewDeliveryDate'), "");  
                $shipmentData["PreviewFreightClass"] = $this->Value($package, array('PreviewFreightClass'), "");  
                $shipmentData["AmazonReferenceId"]  = $this->Value($package, array('AmazonReferenceId'), "");  
                $shipmentData["IsBillOfLadingAvailable"] = $this->Value($package, array('IsBillOfLadingAvailable'), "");
            $shipmentDataList[] = $shipmentData;
            echo "passed";
        }
        else{
            echo "No Shipment Data" . "\n\r";
        }
        return $shipmentDataList;
    }
    public function invokeGetTransportContent(FBAInboundServiceMWS_Interface $service, $request)
    {
        try {
            $response = $service->GetTransportContent($request);

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

    function getAllAvailable($array){
        $keys = array_keys($array);
        $test = array();
        foreach($keys as $key){
            echo "finding $key";
            $test[$key] = $this->Value($array, $key, "");
        }
        print_r("---------");
        print_r($test);
        print_r("---------");
    }



    /* Checks if you have array, then uses the keys to dive into array for values */
    function Value($array, $key, $default = false){
        if (is_array($array) === true){
            settype($key, 'array');
            foreach ($key as $value){
                if (array_key_exists($value, $array) === false){
                    return $default;
                }
                // print_r("\n\r" . $value ."\n\r");
                $array = $array[$value];
            }
            return $array;
        }
        return $default;
    }


}


