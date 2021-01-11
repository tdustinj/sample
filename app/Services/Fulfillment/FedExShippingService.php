<?php

namespace App\Services\Fulfillment;

use App\Services\Fulfillment\FulfillmentGeneratorContract;
use Illuminate\Support\Facades\Log;
use App\Models\Fulfillment, App\Models\Contact;
use Aws\S3\S3Client;

class FedExShippingService implements FulfillmentGeneratorContract
{

    protected $config;

	public function __construct()
	{
     $this->config = null;
     $this->fulfillmentInfo = null;
     $this->jobIdMultipleShipment = null;
     $this->serviceType = null;
     $this->shippingLabelURLS = null;
	}
    public function configShipment($config, $quote = true){
	    /*
	     *
	     * This will contain the logic to load up the data structure
	     */

        $shipment = Fulfillment::where('fk_workorder_id', '=',$config->get('workorderId'))->where( 'id', '=',$config->get('fulfillmentId'))->with(['fulfillmentType:id,fulfillment_name', 'package.items.workorderItem:id,model_number'])->get();
        $contactInfo = Contact::where('id', '=', $shipment[0]->fk_ship_to_contact_id)->first();
        $fulfillment = array('shipment'=> $shipment, 'contact'=>$contactInfo);
        $this->fulfillmentInfo = $fulfillment;

        require_once (dirname(__FILE__). '/../Soap/Fedex/fedex-common.php5');
        if($quote == true){
            $rateOptions = array(
                             'STANDARD_OVERNIGHT',
                             'FEDEX_GROUND',
                             'FEDEX_2_DAY',
                             'FEDEX_ONE_RATE',
                             'GROUND_HOME_DELIVERY',
                             'FEDEX_EXPRESS_SAVER'
                             // 'FEDEX_NEXT_DAY_AFTERNOON',
                             // 'FEDEX_NEXT_DAY_EARLY_MORNING',
                             // 'FEDEX_NEXT_DAY_END_OF_DAY',
                             // 'FEDEX_NEXT_DAY_FREIGHT',
                             // 'FEDEX_NEXT_DAY_MID_MORNING',
                             // 'FEDEX_FREIGHT_ECONOMY',
                             // 'SAME_DAY',
                             // 'SMART_POST',
                             // 'FEDEX_CUSTOM_CRITICAL_AIR_EXPEDITE'
                            );
            $this->buildQuoteRates($rateOptions, $shipment, $contactInfo);
        }else{
              $shipMethod = $config->get('shipMethod');
              if(sizeof($shipment[0]->package) > 1){
                $this->buildMultiplePackageShipment($shipMethod, $shipment, $contactInfo);
                return 'multiple';
              }else{
                $isOneRate = $this->checkForOneRate($shipMethod);
                $this->buildSinglePackageShipment($shipMethod, $shipment, $contactInfo, $isOneRate);
                return 'single';
              }
        }
    }

    public function getValidationServices(){
        $path_to_wsdl =  dirname(__FILE__) . "/../Soap/Fedex/ValidationAvailabilityAndCommitmentService_v8.wsdl";

        ini_set("soap.wsdl_cache_enabled", "0");

        $client = new \SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

        /* Production Eventually */
        $request['WebAuthenticationDetail'] = array(
             'UserCredential' =>array(
                 'Key' => getProperty('key'), 
                 'Password' => getProperty('password')
             )
        );
        $request['ClientDetail'] = array(
             'AccountNumber' => getProperty('shipaccount'), 
             'MeterNumber' => getProperty('meter')
        );
        $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Service Availability Request v5.1 using PHP ***');
        $request['Version'] = array(
            'ServiceId' => 'vacs', 
            'Major' => '8',
            'Intermediate' => '0', 
            'Minor' => '0'
        );

        $destination = $this->fulfillmentInfo['contact']->zip; 
        
        $request['Origin'] = array(
            'PostalCode' => '85284', // Origin details
            'CountryCode' => 'US'
        );
        $request['Destination'] = array(
            'PostalCode' => $destination, // Destination details
            'CountryCode' => 'US'
         );
        $request['ShipDate'] = getProperty('serviceshipdate');
        // $request['CarrierCode'] = 'FDXE'; // valid codes FDXE-Express, FDXG-Ground, FDXC-Cargo, FXCC-Custom Critical and FXFR-Freight
        // $request['Service'] = 'PRIORITY_OVERNIGHT'; // valid code STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
        $request['Packaging'] = 'YOUR_PACKAGING';

        try {
            if(setEndpoint('changeEndpoint')){
                $newLocation = $client->__setLocation(setEndpoint('endpoint'));
            }
            
            $response = $client ->serviceAvailability($request);
            $printLog = print_r($response, true);
            Log::debug($printLog);

            if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
                // echo 'The following service type(s) are available.'. Newline;
                // echo '<table border="1">';
                // foreach ($response->Options as $optionKey => $option){
                //     echo '<tr><td><table>';
                //     if(is_string($option)){
                //         echo '<tr><td>' . $optionKey . '</td><td>' . $option . '</td></tr>';
                //     }else{           
                //         foreach($option as $subKey => $subOption){
                //             echo '<tr><td>' . $subKey . '</td><td>' . $subOption . '</td></tr>';
                //         }
                //     }
                //     echo '</table></td></tr>';
                // }
                // echo'</table>';
                
                // printSuccess($client, $response);
            }else{
                // printError($client, $response);
            } 
            
            // writeToLog($client);    // Write to log file   
        } catch (SoapFault $exception) {
            // printFault($exception, $client);
        }
        return $request;
        
    }

    public function getQuote(){
        $responses = array();

        $getRated = $this->config;
        Log::debug($getRated);
        foreach($getRated as $option => $request){
            // $responses[$option] = $request;
            $path_to_wsdl = dirname(__FILE__) . "/../Soap/Fedex/RateService_v22_php/RateService_v22.wsdl";
            ini_set("soap.wsdl_cache_enabled", "0"); 
            $client = new \SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
            try {
                $newLocation = $client->__setLocation(setEndpoint('prod'));            
                $response = $client->getRates($request);
                $printy = print_r($response, true);
                Log::debug($printy);
                if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){      
                    // printSuccess($client, $response);
                    // print_r($response);

                   // $printy = print_r($response, true);
                   // Log::debug($printy);
                    $noServicesValid = false;
                    if($response->HighestSeverity == 'WARNING'){
                        if(is_array($response->Notifications)){
                            foreach($response->Notifications as $error){
                                if($error->Code == 556 && $error->Message == "There are no valid services available. "){
                                    $noServicesValid = true;
                                    $rateResponse = array('errors' => $error->Message);
                                }
                            }
                        }else if(is_object($response->Notifications)){
                            // print_r($response->Notifications);
                            $errors[] = $response->Notifications->Message . $response->Notifications->Code;
                            if($response->Notifications->Code == 556 && $response->Notifications->Message == "There are no valid services available. "){
                                    $noServicesValid = true;
                                    $rateResponse = array('errors' => $response->Notifications->Message);
                                }
                        }
                    }
                    if(!$noServicesValid){
                        if(is_array($response->RateReplyDetails)){
                            foreach($response->RateReplyDetails as $serviceTypeOption){

                                if(isset($serviceTypeOption->RatedShipmentDetails->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount)){
                                    $price = $serviceTypeOption->RatedShipmentDetails->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount;
                                    // print_r($serviceTypeOption);
                                }else{
                                    $price = $serviceTypeOption->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount;
                                    // print_r($serviceTypeOption);
                                }



                                if(isset($serviceTypeOption->DeliveryTimestamp)){
                                    // $year = substr($dateTime, 0,4);
                                    $month = substr($serviceTypeOption->DeliveryTimestamp, 5,5);
                                    // $day = substr($dateTime, 8,2);
                                    $hour = substr($serviceTypeOption->DeliveryTimestamp, 11,5);
                                    // $minute = substr($dateTime, 14,2);
                                    $arrivalDate = $serviceTypeOption->DeliveryDayOfWeek . " " . $month . " at " . $hour;
                                    $rateResponse = array('price' => $price, 'arrivalDate' => $arrivalDate, 'serviceType' => $serviceTypeOption->ServiceType . " - " . $serviceTypeOption->RatedShipmentDetails->ShipmentRateDetail->SpecialRatingApplied);
                                }else{
                                    $arrivalDate = $serviceTypeOption->TransitTime;
                                    // if($option == "SMART_POST"){
                                    //     $arrivalDate = $arrivalDate . " to " . serviceTypeOption->CommitDetails->MaximumTransitTime;
                                    // }
                                    // $rateResponse = $response;
                                    $rateResponse = array('price' => $price, 'arrivalDate' => $arrivalDate, 'serviceType' => $serviceTypeOption->ServiceType);
                                }

                                $responses[] = $rateResponse;

                            }
                        }else{
                            if(isset($response->RateReplyDetails->RatedShipmentDetails->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount)){
                                $price = $response->RateReplyDetails->RatedShipmentDetails->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount;
                                // print_r($response->RateReplyDetails);
                            }else{
                                $price = $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount;
                                // print_r($response->RateReplyDetails);
                            }
                            
                            if(isset($response->RateReplyDetails->DeliveryTimestamp)){
                                // $year = substr($dateTime, 0,4);
                                $month = substr($response->RateReplyDetails->DeliveryTimestamp, 5,5);
                                // $day = substr($dateTime, 8,2);
                                $hour = substr($response->RateReplyDetails->DeliveryTimestamp, 11,5);
                                // $minute = substr($dateTime, 14,2);
                                $arrivalDate = $response->RateReplyDetails->DeliveryDayOfWeek . " " . $month . " at " . $hour;
                                $rateResponse = array('price' => $price, 'arrivalDate' => $arrivalDate, 'serviceType' => $option);
                            }else{
                                $arrivalDate = $response->RateReplyDetails->TransitTime;
                                if($option == "SMART_POST"){
                                    $arrivalDate = $arrivalDate . " to " . $response->RateReplyDetails->CommitDetails->MaximumTransitTime;
                                }
                                // $rateResponse = $response;
                                $rateResponse = array('price' => $price, 'arrivalDate' => $arrivalDate, 'serviceType' => $option);
                            }
                        }
                    }


                    // foreach($response->RateReplyDetails as $serviceTypeOption){

                    //     if(isset($serviceTypeOption->RatedShipmentDetails->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount)){
                    //         $price = $serviceTypeOption->RatedShipmentDetails->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount;
                    //         // print_r($serviceTypeOption);
                    //     }else{
                    //         $price = $serviceTypeOption->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetChargeWithDutiesAndTaxes->Amount;
                    //         // print_r($serviceTypeOption);
                    //     }



                    //     if(isset($serviceTypeOption->DeliveryTimestamp)){
                    //         // $year = substr($dateTime, 0,4);
                    //         $month = substr($serviceTypeOption->DeliveryTimestamp, 5,5);
                    //         // $day = substr($dateTime, 8,2);
                    //         $hour = substr($serviceTypeOption->DeliveryTimestamp, 11,5);
                    //         // $minute = substr($dateTime, 14,2);
                    //         $arrivalDate = $serviceTypeOption->DeliveryDayOfWeek . " " . $month . " at " . $hour;
                    //         $rateResponse = array('price' => $price, 'arrivalDate' => $arrivalDate, 'serviceType' => $serviceTypeOption->ServiceType);
                    //     }else{
                    //         $arrivalDate = $serviceTypeOption->TransitTime;
                    //         // if($option == "SMART_POST"){
                    //         //     $arrivalDate = $arrivalDate . " to " . serviceTypeOption->CommitDetails->MaximumTransitTime;
                    //         // }
                    //         // $rateResponse = $response;
                    //         $rateResponse = array('price' => $price, 'arrivalDate' => $arrivalDate, 'serviceType' => $serviceTypeOption->ServiceType);
                    //     }

                    //     $responses[] = $rateResponse;

                    // }


                    // $responses[$option] = $rateResponse;
                }else{
                    // printError($client, $response);
                    // print_r($response);
                    $errors = array();
                    if(is_array($response->Notifications)){
                        foreach($response->Notifications as $error){
                            $errors[] = $error->Message;
                        }
                    }else if(is_object($response->Notifications)){
                        // print_r($response->Notifications);
                        $errors[] = $response->Notifications->Message . $response->Notifications->Code;
                    }
                    $rateResponse = array('errors' => $errors);
                    // $responses[$option] = $rateResponse;
                }

                // $responses[] = $response; 
                $responses[] = $rateResponse;
                // writeToLog($client);    // Write to log file   
            } catch (\SoapFault $exception) {
               $responses["failed"] = $exception; 
            }
        }
        
        return $responses;
    }

    public function confirmFulfillment(){
        $path_to_wsdl = dirname(__FILE__) . "/../Soap/Fedex/ShipService_v21.wsdl";
        //The WSDL is not included with the sample code.
        //Please include and reference in $path_to_wsdl variable.

        define('SHIP_LABEL', 'shipgroundlabel.png');  // PDF label file. Change to file-extension .png for creating a PNG label (e.g. shiplabel.png)
        // define('SHIP_CODLABEL', 'CODgroundreturnlabel.pdf');  // PDF label file. Change to file-extension ..png for creating a PNG label (e.g. CODgroundreturnlabel.png)
        ini_set("soap.wsdl_cache_enabled", "0");
        $client = new \SoapClient($path_to_wsdl, array('trace' => 1));
        $request = $this->config;
        // $printSent = print_r($request, true);
        //     Log::debug($printSent);
        try {
            if(setEndpoint('changeEndpoint')){
                $newLocation = $client->__setLocation(setEndpoint('endpoint'));
            }   
            $response = $client->processShipment($request); // FedEx web service invocation

            if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR'){
                // // $fp = fopen(dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/" . $labelName, 'wb'); 
                // $fp = fopen(dirname(__FILE__) . "../Soap/Fedex/ShipLabels/shipgroundlabel.png", 'wb');
                // $imageSalt = imagecreatefromstring($response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
                // imagepng($imageSalt, $fp);
                // fclose($fp);

                // $im = imagecreatefrompng(dirname(__FILE__) . "../Soap/Fedex/ShipLabels/shipgroundlabel.png");
                // // imageflip($im, IMG_FLIP_VERTICAL);
                // $image = imagerotate($im, 180, 0);
                // imagepng($image, dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/shipgroundlabel.png");
                $this->singleLabelSave($response);

            }else{
                // printError($client, $response);
                $apiResponse = array('error' => 'error in creation of Fedex Shipment', 'results' => $response, 'request' => $request, 'headers' => $client->__getLastRequestHeaders());
            }

            // writeToLog($client);    // Write to log file
        } catch (\SoapFault $exception) {
            // printFault($exception, $client);
            $apiResponse = array('error' => 'error in creation of SOAP Request:' . $exception . "YASHHHHH");
            $printResponse = print_r($apiResponse, true);
            Log::debug($printResponse);
            // print_r($client->__getLastRequestHeaders());
        }

        if($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR'){
            $warnings = false;
            $trackingNumber = $response->CompletedShipmentDetail->MasterTrackingId->TrackingNumber;

            if($response->HighestSeverity == "WARNING"){
                $warnings = true;
                if($response->Notifications->Code == 7000){
                    $netCharges = 0.00;
                    $warningCodes = $response->Notifications->Code . " : " . $response->Notifications->Message;
                }else{
                    $warningCodes = $response->Notifications->Code . " : " . $response->Notifications->Message;
                }
            }else{
                if(is_array($response->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails)){
                    $netCharges = $response->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails[0]->TotalNetChargeWithDutiesAndTaxes->Amount;
                }elseif(is_object($response->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails) ){
                    $netCharges = $response->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails->TotalNetChargeWithDutiesAndTaxes->Amount;
                } 
            }

            $printLog = print_r($response, true);
            Log::debug($printLog);

            $bucket = 'test-api.packing-list';
            $awsAccessKey = env('AWS_S3_ACCESS_KEY_ID');
            $awsSecretKey = env('AWS_S3_SECRET_ACCESS_KEY');
            $region = env('AWS_S3_DEFAULT_REGION');

            $client = S3Client::factory(array(
                'credentials' => array(
                    'key'    => $awsAccessKey,
                    'secret' => $awsSecretKey
                ),
                'region' => $region,
                'version' => '2006-03-01'
            )); 

            $fileName = $this->fulfillmentInfo['shipment'][0]->fk_workorder_id . "-" . $trackingNumber .".pdf";
            $result = $client->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $fileName,
                'SourceFile' => dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/shipgroundlabel.pdf",
                'ACL'    => 'public-read'));


            // $apiResponse = array('trackingNumbers' => array($trackingNumber) , 'shippingLabelURLS' => array($result['ObjectURL']), 'totalCharge' => $netCharges);
            if($warnings){
                $apiResponse = array('trackingNumber' => $trackingNumber, 'shippingLabelURLS' => array($result['ObjectURL']), 'totalCharge' => $netCharges, 'warning' => $warningCodes);
            }else{
                $apiResponse = array('trackingNumber' => $trackingNumber, 'shippingLabelURLS' => array($result['ObjectURL']), 'totalCharge' => $netCharges);
            }

            $fulfillmentInfo = $this->fulfillmentInfo['shipment'];
                $fulfillmentInfo[0]->master_tracking_id = $apiResponse['trackingNumber'];
                $fulfillmentInfo[0]->fulfillment_cost_total_actual = $apiResponse['totalCharge'];
                $fulfillmentInfo[0]->expected_ship_date = "2018-07-26 16:44:00";
                $fulfillmentInfo[0]->expected_delivery_date = "2018-07-29 16:44:00";
                    $fulfillmentInfo[0]->package[0]->tracking_number = $apiResponse['trackingNumber'];
                    $fulfillmentInfo[0]->package[0]->save();
            $fulfillmentInfo[0]->save();

        }
        return $apiResponse;
    }

    public function confirmMultipleFulfillment(){
        function isSuccess($client, $response){
            if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR'){
                return true;
            }else return false;
        }

        function processCreateOpenShipmentResponseSuccess($client, $response){
            $individualPackagesTracking = array();
            foreach($response->CompletedShipmentDetail->CompletedPackageDetails as $packageDetails){
               $individualPackagesTracking[] = $packageDetails->TrackingIds->TrackingNumber;
            }
            $response = array('JobId'=> $response->JobId, 'Index'=> $response->Index, 'MasterTrackingId'=> $response->CompletedShipmentDetail->MasterTrackingId->TrackingNumber, 'individualPackagesTracking'=> $individualPackagesTracking);
            return $response;
        }

        $path_to_wsdl =  dirname(__FILE__) . "/../Soap/Fedex/OpenShipService/OpenShipService_v13.wsdl";
        define('SHIP_LABEL', 'openShipLabel');
        ini_set("soap.wsdl_cache_enabled", "0");
        $client = new \SoapClient($path_to_wsdl, array('trace' => 1));

        if(setEndpoint('changeEndpoint')){
            $newLocation = $client->__setLocation(setEndpoint('endpoint'));
            // $newLocation = $client->__setLocation('https://wsbeta.fedex.com:443/web-services/openship');
        }
        /*
            To OpenShip methods are being ran in the following order:
                CreateOpenShipment
                getCreateOpenShipmentResults
                getModifyOpenShipmentResults
                getConfirmOpenShipmentResults
                ModifyOpenShipment
                retrieveOpenShipment
                AddPackagesToOpenShipment
                ModifyPackageInOpenShipment
                ValidateOpenShipment
                retrievePackageInOpenShipment
                ConfirmOpenShipment
        */
        //Custom Walts Open Shipment Proccess
        try{
            $responseCreateOpenShipment = $client->createOpenShipment($this->config);
            if(isSuccess($client, $responseCreateOpenShipment)){
                // echo "We made our open shipment, now lets grab our master tracking ID, JobId, and Index";
                $this->jobIdMultipleShipment = processCreateOpenShipmentResponseSuccess($client, $responseCreateOpenShipment);
                $responseValidateOpenShipment = $client->validateOpenShipment($this->buildValidateOrConfirmOpenShipmentRequest($this->jobIdMultipleShipment["Index"]));
                if(isSuccess($client, $responseValidateOpenShipment)){
                    // echo "We have a valid shipment so lets go ahead and confirm it now.";
                    // $responseConfirmOpenShipment = $client->confirmOpenShipment($this->buildValidateOrConfirmOpenShipmentRequest($this->jobIdMultipleShipment["Index"]));
                    $responseConfirmOpenShipment = $client->confirmOpenShipment($this->buildValidateOrConfirmOpenShipmentRequest($this->jobIdMultipleShipment["Index"]));
                    if(isSuccess($client, $responseConfirmOpenShipment)){
                        //Successfully confirmed the shipment, so save the shipping labels. and Return the urls to the images with the netCharges, and tracking numbers.
                        $this->printAllLabels($responseConfirmOpenShipment);
                        $trackingNumbers = array();
                        foreach($responseConfirmOpenShipment->CompletedShipmentDetail->CompletedPackageDetails as $package){
                            $trackingNumbers[] = $package->TrackingIds->TrackingNumber;
                        }
                        // print_r($responseConfirmOpenShipment);
                        $trackingNumbers['masterTrackingId'] = $responseConfirmOpenShipment->CompletedShipmentDetail->MasterTrackingId->TrackingNumber;
                        // if(is_array($responseConfirmOpenShipment->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails)){
                        //     $netCharges = $responseConfirmOpenShipment->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails[0]->TotalNetChargeWithDutiesAndTaxes;
                        // }else{
                        //     $netCharges = $responseConfirmOpenShipment->CompletedShipmentDetail->ShipmentRating->ShipmentRateDetails->TotalNetChargeWithDutiesAndTaxes;
                        // }
                        // $apiResponse = array('trackingNumbers' => $trackingNumbers, 'shippingLabelURLS' => $this->shippingLabelURLS, 'totalCharge' => $netCharges);
                        $apiResponse = array('trackingNumbers' => $trackingNumbers, 'totalCharge' => "faked for now", 'shippingLabelURLS' => $this->shippingLabelURLS);

                        $fulfillmentInfo = $this->fulfillmentInfo['shipment'];
                            $fulfillmentInfo[0]->master_tracking_id = $apiResponse['trackingNumbers']['masterTrackingId'];
                            $fulfillmentInfo[0]->fulfillment_cost_total_actual = 0.00;
                            $fulfillmentInfo[0]->expected_ship_date = "2018-07-26 16:44:00";
                            $fulfillmentInfo[0]->expected_delivery_date = "2018-07-29 16:44:00";
                            $packageCount = 0;
                            foreach($fulfillmentInfo[0]->package as $package){
                                $package->tracking_number = $apiResponse['trackingNumbers'][$packageCount];
                                $package->save();
                                $packageCount++;
                            }
                        $fulfillmentInfo[0]->save();
                        return $apiResponse;
                    }else{
                        echo "Failed to confirm OpenShipment";
                        return $responseConfirmOpenShipment;
                    }
                }else{
                   echo "Failed validation of open shipment"; 
                   return $responseValidateOpenShipment;
                }
            }else{
                echo "Failed initial opening of open shipment";
                return $responseCreateOpenShipment;
            }
        } catch (\SoapFault $exception) {
            printFault($exception, $client);
        }
    }
    private function buildQuoteRates($rateOptions, $shipment, $contactInfo){
         $rates = array();
         foreach($rateOptions as $option){
            $request = array();         
             /* Sandbox 
            $request['WebAuthenticationDetail'] = array(
                'UserCredential' =>array(
                    'Key' => 'e40kth3RbUVKf3Jp', 
                    'Password' => 'YRb9E5BkZJb48DuP5pwmLbCmB'
                )
            );
            $request['ClientDetail'] = array(
                'AccountNumber' => '510087720', 
                'MeterNumber' => '119023931'
            );
            $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request using PHP ***');
            $request['Version'] = array(
                 'ServiceId' => 'crs', 
                 'Major' => '22', 
                 'Intermediate' => '0', 
                 'Minor' => '0'
             );
            */




             /* Production Eventually */
             $request['WebAuthenticationDetail'] = array(
                 'UserCredential' =>array(
                     'Key' => getProperty('key'), 
                     'Password' => getProperty('password')
                 )
             );
             $request['ClientDetail'] = array(
                 'AccountNumber' => getProperty('shipaccount'), 
                 'MeterNumber' => getProperty('meter')
             );
             $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request using PHP ***');
             $request['Version'] = array(
                 'ServiceId' => 'crs', 
                 'Major' => '22', 
                 'Intermediate' => '0', 
                 'Minor' => '0'
             );
             $request['ReturnTransitAndCommit'] = true;
             $request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
             $request['RequestedShipment']['ShipTimestamp'] = date('c');
             $request['RequestedShipment']['Recipient'] = $this->addRecipient($contactInfo);
             $request['RequestedShipment']['Shipper'] = $this->addShipper();
             $request['RequestedShipment']['ShippingChargesPayment'] = $this->addShippingChargesPayment();
             $request['RequestedShipment']['PackageCount'] = sizeof($shipment[0]->package);
             // $request['RequestedShipment']['VariableOptionsServiceOptionType'] = array($option, 'FEDEX_ONE_RATE');
             // $request['RequestedShipment']['VariableOptionsServiceOptionType'] = array($rateOptions);

             if($option == 'FEDEX_ONE_RATE'){
                $request['RequestedShipment']['PackagingType'] = 'FEDEX_PAK';
                $request['RequestedShipment']['VariableOptionsServiceOptionType'] = array('2_DAY');
                // $request['RequestedShipment']['ServiceType'] = '2_DAY';
                $request['RequestedShipment']['SpecialServicesRequested'] = array(
                    'SpecialServiceTypes' => array('FEDEX_ONE_RATE')
                );
                $request['RequestedShipment']['RequestedPackageLineItems'] = array();
                foreach($shipment[0]->package as $package){
                    $request['RequestedShipment']['RequestedPackageLineItems'][] = $this->addMultiplePackageLineItem1($shipment[0], $package, false);
                }
             }else{
                $request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                $request['RequestedShipment']['ServiceType'] = $option; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, FEDEX_2_DAY, GROUND_HOME_DELIVERY ...
                $request['RequestedShipment']['RequestedPackageLineItems'] = array();
                foreach($shipment[0]->package as $package){
                    $request['RequestedShipment']['RequestedPackageLineItems'][] = $this->addMultiplePackageLineItem1($shipment[0], $package, true);
                }
             }


             if($option == 'GROUND_HOME_DELIVERY'){
                 $request['RequestedShipment']['Recipient'] = $this->addRecipient($contactInfo, true);
             }else{
                 $request['RequestedShipment']['Recipient'] = $this->addRecipient($contactInfo);
             }
             

             if($option == 'SMART_POST'){
                 $request['RequestedShipment']['SmartPostDetail'] = $this->addSmartPostDetail();
             }
            
             // $request['RequestedShipment']['RequestedPackageLineItems'] = array();

             // foreach($shipment[0]->package as $package){
             //     $request['RequestedShipment']['RequestedPackageLineItems'][] = $this->addMultiplePackageLineItem1($shipment[0], $package, true);
             // }
             $rates[$option] = $request;
         }
        $this->config = $rates;
    }
    private function buildMultiplePackageShipment($serviceType, $shipment, $contactInfo){
        $this->serviceType = $serviceType;
        $request = $this->buildMultiplePackageShipmentHeader();

        $packageCount = sizeof($shipment[0]->package);
        $request['RequestedShipment'] = array(
                    'ShipTimestamp' => date('c'),
                    'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
                    'ServiceType' => $serviceType, // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
                    'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
                    'Shipper' => $this->addShipper(),
                    // 'Recipient' => $this->addRecipient($customer_info[0]),
                    'ShippingChargesPayment' => $this->addShippingChargesPayment(true),
                    'LabelSpecification' => $this->addLabelSpecification(),
                    // 'RateRequestTypes' => array('LIST'), // valid values ACCOUNT and LIST    
                    'PackageCount' => $packageCount,
                    'PackageDetail' => 'INDIVIDUAL_PACKAGES',                                        
                    'RequestedPackageLineItems' => array(
                        // '0' => $this->addPackageLineItem1($customer_info[0], $packages, $signatureRequired)
                    )
                );
        if($serviceType == 'SMART_POST'){
            $request['RequestedShipment']['SmartPostDetail'] = $this->addSmartPostDetail();
        }

        if($request['RequestedShipment']['ServiceType'] == 'GROUND_HOME_DELIVERY'){
            $request['RequestedShipment']['Recipient'] = $this->addRecipient($contactInfo, true);
        }else{
            $request['RequestedShipment']['Recipient'] = $this->addRecipient($contactInfo);
        }

        foreach($shipment[0]->package as $package){
            $request['RequestedShipment']['RequestedPackageLineItems'][] = $this->addMultiplePackageLineItem1($contactInfo, $package, false);
        }



        $this->config = $request;
    }
    private function buildSinglePackageShipment($serviceType, $shipment, $contactInfo, $isOneRate){
        $packagingType = 'YOUR_PACKAGING';
        if($isOneRate){
            Log::debug("true");
            $serviceType = str_replace(" - FEDEX_ONE_RATE","", $serviceType);
            $packagingType = 'FEDEX_PAK';
        }

        $path_to_wsdl = dirname(__FILE__) . "/../Soap/Fedex/ShipService_v21.wsdl";
        //The WSDL is not included with the sample code.
        //Please include and reference in $path_to_wsdl variable.

        // define('SHIP_LABEL', 'shipgroundlabel.png');  // PDF label file. Change to file-extension .png for creating a PNG label (e.g. shiplabel.png)
        // define('SHIP_CODLABEL', 'CODgroundreturnlabel.pdf');  // PDF label file. Change to file-extension ..png for creating a PNG label (e.g. CODgroundreturnlabel.png)
        ini_set("soap.wsdl_cache_enabled", "0");
        $client = new \SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
       
        /* SandBox Keys */ 
        $request['WebAuthenticationDetail'] = array(
            // 'ParentCredential' => array(
            //     'Key' => 'xxx',
            //     'Password' => 'xxx'
            // ),
            'UserCredential' =>array(
                'Key' => 'e40kth3RbUVKf3Jp', 
                'Password' => 'YRb9E5BkZJb48DuP5pwmLbCmB'
            )
        );
        $request['ClientDetail'] = array(
            'AccountNumber' => '510087720', 
            'MeterNumber' => '119023931'
        );
        

        // 112549603
        // 112549603

        /* Production Eventually 
        $request['WebAuthenticationDetail'] = array(
            'UserCredential' =>array(
                'Key' => getProperty('key'), 
                'Password' => getProperty('password')
            )
        );
        $request['ClientDetail'] = array(
            'AccountNumber' => getProperty('shipaccount'), 
            'MeterNumber' => getProperty('meter')
        );*/
        $request['TransactionDetail'] = array('CustomerTransactionId' => '*** Ground Domestic Shipping Request using PHP ***');
        $request['Version'] = array(
            'ServiceId' => 'ship', 
            'Major' => '21', 
            'Intermediate' => '0', 
            'Minor' => '0'
        );
        $request['RequestedShipment'] = array(
            'ShipTimestamp' => date('c'),
            'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
            'ServiceType' => $serviceType, // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
            'PackagingType' => $packagingType, // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
            'Shipper' => $this->addShipper(),
            'ShippingChargesPayment' => $this->addShippingChargesPayment(true),
            'LabelSpecification' => $this->addLabelSpecification(), 
            'PackageCount' => 1,
            // 'PackageDetail' => 'INDIVIDUAL_PACKAGES',                                        
        );

        $request['RequestedShipment']['RequestedPackageLineItems'] = array(
            '0' => $this->addPackageLineItem1($shipment[0], $shipment[0]->package[0], false)
        );

        if($isOneRate){
            $request['RequestedShipment']['SpecialServicesRequested'] = array(
                                'SpecialServiceTypes' => array('FEDEX_ONE_RATE')
                            );
            // $request['RequestedShipment']['RequestedPackageLineItems'] = array();
        }

        // $request['RequestedShipment']['TotalInsuredValue']= array(
        //     'Amount'=> 100,   //$shipment[0]->package[0]
        //     'Currency'=>'USD'
        // );

        if($serviceType == 'SMART_POST'){
            $request['RequestedShipment']['SmartPostDetail'] = $this->addSmartPostDetail();
        }

        if($request['RequestedShipment']['ServiceType'] == 'GROUND_HOME_DELIVERY'){
            $request['RequestedShipment']['Recipient'] = $this->addRecipient($contactInfo, true);
        }else{
            $request['RequestedShipment']['Recipient'] = $this->addRecipient($contactInfo);
        }

        // Log::debug($request);
        $this->config = $request;
    }
    private function calcTotalPackages(){
    }
    private function addShipper(){
        $shipper = array(
            'Contact' => array(
                'PersonName' => 'Walts TV',
                'CompanyName' => 'Walts TV',
                'PhoneNumber' => '4802109645' //add correct shipping number
            ),
            'Address' => array(
                'StreetLines' => array('860 W. Carver Road', 'Suite 101'),
                'City' => 'Tempe',
                'StateOrProvinceCode' => 'AZ',
                'PostalCode' => '85284',
                'CountryCode' => 'US'
            )
        );
        return $shipper;
    }
    private function addRecipient($customer, $residential = false){
        $recipient = array(
            'Contact' => array(
                'PersonName' => $customer->first_name . " " . $customer->last_name,
                'CompanyName' => '',
                'PhoneNumber' => '4802680565' //$customer->mobile_phone
            ),
            'Address' => array(
                'StreetLines' => array($customer->address),
                'City' => $customer->city,
                'StateOrProvinceCode' => $customer->state,
                'PostalCode' => $customer->zip,
                'CountryCode' => 'US',
                'Residential' => 'TRUE'
            )
        );
        return $recipient;                                      
    }
    private function addShippingChargesPayment($test = false){
        $shipaccount = getProperty('shipaccount');
        if(!$test){
            $shippingChargesPayment = array(
                'PaymentType' => 'SENDER',
                'Payor' => array(
                    'ResponsibleParty' => array(
                        // 'AccountNumber' => getProperty('shipaccount'),
                        'AccountNumber' => $shipaccount,
                        'Contact' => null,
                        'Address' => array(
                            'CountryCode' => 'US'
                        )
                    )
                )
            );
        }else{
            $shippingChargesPayment = array(
                'PaymentType' => 'SENDER',
                'Payor' => array(
                    'ResponsibleParty' => array(
                        'AccountNumber' => '510087720',
                        'Contact' => null,
                        'Address' => array(
                            'CountryCode' => 'US'
                        )
                    )
                )
            );
        }
        return $shippingChargesPayment;
    }
    private function addLabelSpecification(){
        // $labelSpecification = array(
        //     'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
        //     'ImageType' => 'ZPLII',  // valid values DPL, EPL2, PDF, ZPLII and PNG
        //     'LabelStockType' => 'STOCK_4X6' // PAPER_8.5X11_TOP_HALF_LABEL
        // );

        $labelSpecification = array(
            'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
            'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
            'LabelStockType' => 'STOCK_4X6' // PAPER_8.5X11_TOP_HALF_LABEL
        );
        return $labelSpecification;
    }
    private function addSpecialServices(){
        $specialServices = array(
            // 'SpecialServicesRequested' => array(
            //     'SignatureOptionDetail' => 'ADULT',
            // )
            'SpecialServiceTypes' => array('SIGNATURE_OPTION'),
            'SignatureOptionDetail' => array(
                'OptionType' => ('DIRECT'),
            ),
            

            // 'SpecialServiceTypes' => array('COD'),
            // 'CodDetail' => array(
            //     'CodCollectionAmount' => array(
            //         'Currency' => 'USD', 
            //         'Amount' => 150
            //     ),
            //     'CollectionType' => 'ANY' // ANY, GUARANTEED_FUNDS
            // )

            // 'SpecialServiceTypes' => array('COD'),
            // 'CodDetail' => array(
            //     'CodCollectionAmount' => array(
            //         'Currency' => 'USD', 
            //         'Amount' => 150
            //     ),
            //     'CollectionType' => 'ANY' // ANY, GUARANTEED_FUNDS
            // )
        );
        return $specialServices; 
    }
    private function addPackageLineItem1($invoice, $dimensions, $signatureRequired){
        // print_r($signatureRequired);
        if($signatureRequired){
            $packageLineItem = array(
                'SequenceNumber'=>1,
                'GroupPackageCount'=>1,
                'Weight' => array(
                    'Value' => $dimensions->package_weight,
                    'Units' => 'LB'
                ),
                'Dimensions' => array(
                    'Length' => $dimensions->package_length,
                    'Width' => $dimensions->package_width,
                    'Height' => $dimensions->package_height,
                    'Units' => 'IN'
                ),
                'CustomerReferences' => array(
                    // '0' => array(
                    //     'CustomerReferenceType' => 'CUSTOMER_REFERENCE', // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                    //     'Value' => 'Walts'
                    // ), 
                    '0' => array(
                        'CustomerReferenceType' => 'INVOICE_NUMBER', 
                        'Value' => $invoice->fk_workorder_id
                    ),
                    '1' => array(
                        'CustomerReferenceType' => 'P_O_NUMBER', 
                        'Value' => $invoice->fulfillment_partner_order_id
                    )
                ),
                'SpecialServicesRequested' => $this->addSpecialServices()

            );
        }else{
            $packageLineItem = array(
                'SequenceNumber'=>1,
                'GroupPackageCount'=>1,
                'Weight' => array(
                    'Value' => $dimensions->package_weight,
                    'Units' => 'LB'
                ),
                'Dimensions' => array(
                    'Length' => $dimensions->package_length,
                    'Width' => $dimensions->package_width,
                    'Height' => $dimensions->package_height,
                    'Units' => 'IN'
                ),
                'CustomerReferences' => array(
                    // '0' => array(
                    //     'CustomerReferenceType' => 'CUSTOMER_REFERENCE', // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                    //     'Value' => 'Walts'
                    // ), 
                    '0' => array(
                        'CustomerReferenceType' => 'INVOICE_NUMBER', 
                        'Value' => $invoice->fk_workorder_id
                    ),
                    '1' => array(
                        'CustomerReferenceType' => 'P_O_NUMBER', 
                        'Value' => $invoice->fulfillment_partner_order_id
                    )
                ),
            );
        }
        return $packageLineItem;
    }
    private function addMultiplePackageLineItem1($invoice, $dimensions, $signatureRequired){
        // print_r($signatureRequired);
        if($signatureRequired){
            $packageLineItem = array(
                'SequenceNumber'=>1,
                'GroupPackageCount'=>1,
                'InsuredValue'=>array(
                    'Amount'=>8,
                    'Currency'=>'USD'
                 ),
                'Weight' => array(
                    'Value' => $dimensions->package_weight,
                    'Units' => 'LB'
                ),
                'Dimensions' => array(
                    'Length' => $dimensions->package_length,
                    'Width' => $dimensions->package_width,
                    'Height' => $dimensions->package_height,
                    'Units' => 'IN'
                ),
                'CustomerReferences' => array(
                    // '0' => array(
                    //     'CustomerReferenceType' => 'CUSTOMER_REFERENCE', // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                    //     'Value' => 'Walts'
                    // ), 
                    '0' => array(
                        'CustomerReferenceType' => 'INVOICE_NUMBER', 
                        'Value' => $invoice->fk_workorder_id
                    ),
                    '1' => array(
                        'CustomerReferenceType' => 'P_O_NUMBER', 
                        'Value' => $invoice->fulfillment_partner_order_id
                    )
                ),
                'SpecialServicesRequested' => $this->addSpecialServices()

            );
        }else{
            $packageLineItem = array(
                'SequenceNumber'=>1,
                'GroupPackageCount'=>1,
                'InsuredValue'=>array(
                    'Amount'=>8,
                    'Currency'=>'USD'
                 ),
                'Weight' => array(
                    'Value' => $dimensions->package_weight,
                    'Units' => 'LB'
                ),
                'Dimensions' => array(
                    'Length' => $dimensions->package_length,
                    'Width' => $dimensions->package_width,
                    'Height' => $dimensions->package_height,
                    'Units' => 'IN'
                ),
                'CustomerReferences' => array(
                    // '0' => array(
                    //     'CustomerReferenceType' => 'CUSTOMER_REFERENCE', // valid values CUSTOMER_REFERENCE, INVOICE_NUMBER, P_O_NUMBER and SHIPMENT_INTEGRITY
                    //     'Value' => 'Walts'
                    // ), 
                    '0' => array(
                        'CustomerReferenceType' => 'INVOICE_NUMBER', 
                        'Value' => $invoice->fk_workorder_id
                    ),
                    '1' => array(
                        'CustomerReferenceType' => 'P_O_NUMBER', 
                        'Value' => $invoice->fulfillment_partner_order_id
                    )
                ),
            );
        }
        return $packageLineItem;
    }
    private function addSmartPostDetail(){
        $smartPostDetail = array(
            'Indicia' => 'PARCEL_SELECT',
            'AncillaryEndorsement' => 'CARRIER_LEAVE_IF_NO_RESPONSE',
            'SpecialServices' => 'USPS_DELIVERY_CONFIRMATION',
            'HubId' => getProperty('hubid')
            //'CustomerManifestId' => 'XXX'
        );
        return $smartPostDetail;
    }
    private function buildValidateOrConfirmOpenShipmentRequest($index){
        $request = $this->buildMultiplePackageShipmentHeader();
        $request['Index'] = $index;
        return $request;
    }
    private function buildMultiplePackageShipmentHeader(){
        $request['WebAuthenticationDetail'] = array(
            'UserCredential' =>array(
                'Key' => 'e40kth3RbUVKf3Jp', 
                'Password' => 'YRb9E5BkZJb48DuP5pwmLbCmB'
            )
        );
        $request['ClientDetail'] = array(
            'AccountNumber' => '510087720', 
            'MeterNumber' => '119023931'
        );
 
        
        $request['TransactionDetail'] = array('CustomerTransactionId' => '*** OpenShip Request using PHP ***');
        $request['Version'] = array(
            'ServiceId' => 'ship', 
            'Major' => '13', 
            'Intermediate' => '0', 
            'Minor' => '0'
        );
        if($this->serviceType == 'SMART_POST'){
            $request['Actions'] = 'CONFIRM';
        }
        return $request;
    }
    private function singleLabelSave($response){
        $packageDetails = $response->CompletedShipmentDetail->CompletedPackageDetails;
        if(is_array($packageDetails)){
           
        }else if(is_object($packageDetails)){
            // $fp = fopen(dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/shipgroundlabel.png", 'wb');
            // $imageSalt = imagecreatefromstring($response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
            // imagepng($imageSalt, $fp);
            // fclose($fp);
            // $im = imagecreatefrompng(dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/shipgroundlabel.png");

            $fp = fopen(dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/shipgroundlabel.pdf", 'wb');
            $imageSalt = $response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image;
            fwrite($fp, $imageSalt);
            fclose($fp);

            
            // imageflip($im, IMG_FLIP_VERTICAL);
            // $image = imagerotate($im, 180, 0);

            // imagepng($im, dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/shipgroundlabel.png");
        }
    }
    private function printAllLabels($response){
        $packageDetails = $response->CompletedShipmentDetail->CompletedPackageDetails;
        if(is_array($packageDetails)){
            $count = 0;
            foreach($packageDetails as $packageDetail){
                $this->printLabel($packageDetail, $count, $packageDetail->TrackingIds->TrackingNumber);
                $count++;
            }
        }else if(is_object($packageDetails)){

            // printLabel($packageDetails);
        }
    }
    private function printLabel($packageDetail, $i, $trackingNumber){
        $labelName = SHIP_LABEL . "_" . $i . ".png";
        $fp = fopen(dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/" . $labelName, 'wb'); 

        $imageSalt = imagecreatefromstring($packageDetail->Label->Parts->Image);
        imagepng($imageSalt, $fp);
        fclose($fp);

        $fileLocation = dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/" . $labelName;
        $im = imagecreatefrompng($fileLocation);
        // imageflip($im, IMG_FLIP_VERTICAL);
        $image = imagerotate($im, 180, 0);
        imagepng($image, $fileLocation);



        // $labelName = SHIP_LABEL . "_" . $i . ".png";
        // $fp = fopen('fedex_shipping_labels/' . $labelName, 'wb');  
        // fwrite($fp, ($packageDetail->Label->Parts->Image)); 
        // fclose($fp);


        // $im = imagecreatefrompng('fedex_shipping_labels/' . $labelName);
        // imageflip($im, IMG_FLIP_HORIZONTAL);
        // imagepng($im);
        // imagedestroy($im);
        $this->storeS3Labels($labelName, $trackingNumber);
    }
    private function storeS3Labels($image, $trackingNumber){
        $fileName = $this->fulfillmentInfo['shipment'][0]->fk_workorder_id . "-" . $trackingNumber;

       $bucket = 'test-api.packing-list';
       $awsAccessKey = env('AWS_S3_ACCESS_KEY_ID');
       $awsSecretKey = env('AWS_S3_SECRET_ACCESS_KEY');
       $region = env('AWS_S3_DEFAULT_REGION');

       $client = S3Client::factory(array(
           'credentials' => array(
               'key'    => $awsAccessKey,
               'secret' => $awsSecretKey
           ),
           'region' => $region,
           'version' => '2006-03-01'
       )); 


       $result = $client->putObject(array(
           'Bucket' => $bucket,
           'Key'    => $fileName,
           'SourceFile' => dirname(__FILE__) . "/../Soap/Fedex/ShipLabels/" . $image,
           'ACL'    => 'public-read'));
       $this->shippingLabelURLS[] = $result['ObjectURL'];
    }

    private function checkForOneRate($serviceType){
        if (strpos($serviceType, 'FEDEX_ONE_RATE') !== false) {
            return true;
        }else{
            return false;
        }
    }
}

?>