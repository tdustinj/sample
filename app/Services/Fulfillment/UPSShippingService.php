<?php

namespace App\Services\Fulfillment;

use App\Jobs\UPSShipPackageCreate;

use App\Services\Fulfillment\FulfillmentGeneratorContract;
use App\Models\Fulfillment, App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Aws\S3\S3Client;

class UPSShippingService implements FulfillmentGeneratorContract
{
    public function __construct()
    {
    	$this->access = env('UPS_ACCESS_LISCENSE_NUMBER');
    	$this->userid = env('UPS_USER_ID');
    	$this->passwd = env('UPS_PASSWORD');
    	$this->operation = "ProcessRate";
    	$this->endpointurl = env('UPS_TEST_RATING');
        $this->shippingNumber = env('UPS_SHIPPING_NUMBER');
    	// $outputFileName = "XOLTResult.xml";
        $this->shipMethod = null;
        $this->rate = null;
        $this->arrivalDate = null;
        // $this->jsonHeader = {"UPSSecurity": { "UsernameToken": {
        // "Username": "Your User Id",
        // "Password": "Your Password" },
        // "ServiceAccessToken": {
        // "AccessLicenseNumber": "Your Access License"
        // } },;

    }

    public function configShipment($config){
    	$this->shipment = Fulfillment::where('fk_workorder_id', '=',$config->get('workorderId'))->where( 'id', '=',$config->get('fulfillmentId'))->with(['fulfillmentType:id,fulfillment_name', 'package.items.workorderItem:id,model_number,unit_price'])->get();
    	$this->contactInfo = Contact::where('id', '=', $this->shipment[0]->fk_ship_to_contact_id)->first();

        if(isset($config->shipMethod)){
            $this->shipMethod = $config->get('shipMethod');
            $this->rate = $config->get('rate');
            $this->arrivalDate = $config->get('arrivalDate');
        }
    }

    public function getQuote(){
    	$this->wsdl = dirname(__FILE__). '/../Soap/UPS/RateWS.wsdl';
        $request = $this->buildQuote();
            $printRequest = print_r($request, true);
            Log::debug($printRequest);
        $freight = false;
        if($freight){
            $this->operation = "ProcessFreightRate";
            $response = $this->getQuoteFreight();
            // return $response;
            $responses = array();
            if(isset($response->TotalShipmentCharge)){
                if($response->GuaranteedIndicator == ""){
                    $arrivalDate = "No Date Given";
                }else{
                    $arrivalDate = $response->GuaranteedIndicator;
                }
                $rateResponse = array(
                    'price' => $response->TotalShipmentCharge->MonetaryValue, 
                    'arrivalDate' => $arrivalDate, 
                    'serviceType' => $this->getServiceFreight($response->Service->Code),
                    'fullResponse'=> $response,
                );
                $responses[] = $rateResponse;
            }else{
                $responses[] = $response;

            }

            return $responses;
        }else{
        	try{
        	  	$mode = array('soap_version' => 'SOAP_1_1', 'trace' => 1); // use soap 1.1 client

        	  	//initialize soap client
        		$client = new \SoapClient($this->wsdl , $mode);
        		//set endpoint url
        		$client->__setLocation($this->endpointurl);
    			//create soap header
    			$usernameToken['Username'] = $this->userid;
    			$usernameToken['Password'] = $this->passwd;
    			$serviceAccessLicense['AccessLicenseNumber'] = $this->access;
    			$upss['UsernameToken'] = $usernameToken;
    			$upss['ServiceAccessToken'] = $serviceAccessLicense;
    			
    			$header = new \SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
    			$client->__setSoapHeaders($header);
        	  	//get response
        		$resp = $client->__soapCall($this->operation, array($request));

                $responses = array();
                foreach($resp->RatedShipment as $quote){
                    $arrivalDate = "";
                    if(isset($quote->GuaranteedDelivery)){
                        $arrvialDate = Carbon::now();
                        $arrivalDate = $arrvialDate->addDays($quote->GuaranteedDelivery->BusinessDaysInTransit); 
                        $arrivalDate = $arrivalDate->format('l jS \\of F Y'); 
                        if(isset($quote->GuaranteedDelivery->DeliveryByTime)){
                            $arrivalDate .= " at " . $quote->GuaranteedDelivery->DeliveryByTime;
                        }

                    }
                    $rateResponse = array('price' => $quote->TotalCharges->MonetaryValue, 'arrivalDate' => $arrivalDate, 'serviceType' => $this->getService($quote->Service->Code));
                    $responses[] = $rateResponse;
                }
        		return $responses;
            }
            catch (\SoapFault $exception) {
                // printFault($exception, $client);
                $apiResponse = array('error' => 'error in creation of SOAP Request:' . $exception . "YASHHHHH");
                $printResponse = print_r($apiResponse, true);
                Log::debug($printResponse);
                // print_r($client->__getLastRequestHeaders());
            }
        	catch(Exception $ex)
        	{
        		print_r ($ex);
        	}
        }
    }

    public function confirmFulfillment(){
        UPSShipPackageCreate::dispatch($this->shipment, $this->contactInfo, $this->shipMethod, $this->getService($this->shipMethod, true), $this->rate);
        //should try and grab the arrival estimated date also.
        // $carbon = $this->arrivalDate->format('Y-M-D');
        $arrivalDate = str_replace("of ","", $this->arrivalDate);
        $arrivalDate = str_replace("at ","", $arrivalDate);
        $carbonDate = Carbon::parse( $arrivalDate );
        Log::debug($carbonDate);
        $this->shipment[0]->master_tracking_id = "Creating UPS Shipment";
        $this->shipment[0]->fulfillment_cost_total_quote = $this->rate;
        $this->shipment[0]->expected_delivery_date = ($this->arrivalDate == null ? 'No Estimate' : $carbonDate);
        $this->shipment[0]->expected_ship_date = Carbon::now('America/Phoenix');
        $this->shipment[0]->save();
    	return array('data'=>'Success', 'serviceType' => $this->getService($this->shipMethod, true));
    }

    public function createFulfillment($fulfillment, $contact, $serviceType, $service){
        $this->wsdl = dirname(__FILE__). '/../Soap/UPS/ShipAccept/Ship.wsdl';
        
        $request = $this->buildShip($fulfillment, $contact, $serviceType, $service);
        try{
            $mode = array('soap_version' => 'SOAP_1_1', 'trace' => 1); // use soap 1.1 client

            // initialize soap client
            $client = new \SoapClient($this->wsdl , $mode);

            //set endpoint url
            $client->__setLocation("https://wwwcie.ups.com/webservices/Ship");

            //create soap header
            $usernameToken['Username'] = $this->userid;
            $usernameToken['Password'] = $this->passwd;
            $serviceAccessLicense['AccessLicenseNumber'] = $this->access;
            $upss['UsernameToken'] = $usernameToken;
            $upss['ServiceAccessToken'] = $serviceAccessLicense;
      
            $header = new \SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
            $client->__setSoapHeaders($header);

            $resp = $client->__soapCall('ProcessShipConfirm',array($request));
            print_r($resp);
            if($resp->Response->ResponseStatus->Description == "Success"){
                return array(
                        'status' => $resp->Response->ResponseStatus->Description, 
                        'totalCharges' => $resp->ShipmentResults->ShipmentCharges->TotalCharges->MonetaryValue, 
                        'shipmentID' => $resp->ShipmentResults->ShipmentIdentificationNumber,
                        'shipDigest' => $resp->ShipmentResults->ShipmentDigest,
                       );
            }
            
            // print_r($resp);
        }
        catch(\SoapFault $ex)
        {
            print_r($ex->detail);
            $responseLog = print_r($ex->detail, true);
            Log::debug($responseLog);
        }

    }

    public function createFulfillmentAccept($shipmentDigest){
        $this->wsdl = dirname(__FILE__). '/../Soap/UPS/ShipAccept/Ship.wsdl';
        
        $request = $this->buildShipAccept($shipmentDigest);
        // print_r($request);
        try{
            $mode = array('soap_version' => 'SOAP_1_1', 'trace' => 1); // use soap 1.1 client

            // initialize soap client
            $client = new \SoapClient($this->wsdl , $mode);

            //set endpoint url
            $client->__setLocation("https://wwwcie.ups.com/webservices/Ship");

            //create soap header
            $usernameToken['Username'] = $this->userid;
            $usernameToken['Password'] = $this->passwd;
            $serviceAccessLicense['AccessLicenseNumber'] = $this->access;
            $upss['UsernameToken'] = $usernameToken;
            $upss['ServiceAccessToken'] = $serviceAccessLicense;
      
            $header = new \SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
            $client->__setSoapHeaders($header);

            $resp = $client->__soapCall('ProcessShipAccept',array($request));
            // print_r($resp);

            if($resp->Response->ResponseStatus->Description == "Success"){
                $response = array();
                
                if(is_array($resp->ShipmentResults->PackageResults)){

                    $count = 0;
                    foreach($resp->ShipmentResults->PackageResults as $upsPackage){
                        $fp = fopen(dirname(__FILE__) . "/../Soap/UPS/ShipLabelsPackages/packageLabel" . $count . ".gif", 'wb');
                        $imageSalt = imagecreatefromstring(base64_decode($upsPackage->ShippingLabel->GraphicImage));
                        imagepng($imageSalt, $fp);
                        fclose($fp);

                        $im = imagecreatefrompng(dirname(__FILE__) . "/../Soap/UPS/ShipLabelsPackages/packageLabel" . $count . ".gif");
                        $image = imagerotate($im, 270, 0);
                        imagepng($image, dirname(__FILE__) . "/../Soap/UPS/ShipLabelsPackages/packageLabel" . $count . ".gif");

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

                        $fileName ='testUPSShippingLabel' . $count;
                        $result = $client->putObject(array(
                            'Bucket' => $bucket,
                            'Key'    => $fileName,
                            'SourceFile' => dirname(__FILE__) . "/../Soap/UPS/ShipLabelsPackages/packageLabel" . $count . ".gif",
                            'ACL'    => 'public-read'));

                        $response['packageContainer'][] = array('trackingNumber' => $upsPackage->TrackingNumber, 'shippingLabelURL' => $result['ObjectURL']);
                        $response['totalCharges'] = $resp->ShipmentResults->ShipmentCharges->TotalCharges;
                        $response['masterTracking'] = $resp->ShipmentResults->ShipmentIdentificationNumber;
                        $count++;
                    }
                }else{
                    $fp = fopen(dirname(__FILE__) . "/../Soap/UPS/ShipLabelsPackages/packageLabel.gif", 'wb');
                    $imageSalt = imagecreatefromstring(base64_decode($resp->ShipmentResults->PackageResults->ShippingLabel->GraphicImage));
                    imagepng($imageSalt, $fp);
                    fclose($fp);

                    $im = imagecreatefrompng(dirname(__FILE__) . "/../Soap/UPS/ShipLabelsPackages/packageLabel.gif");
                    $image = imagerotate($im, 270, 0);
                    imagepng($image, dirname(__FILE__) . "/../Soap/UPS/ShipLabelsPackages/packageLabel.gif");

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

                    $fileName ='testUPSShippingLabel';
                    $result = $client->putObject(array(
                        'Bucket' => $bucket,
                        'Key'    => $fileName,
                        'SourceFile' => dirname(__FILE__) . "/../Soap/UPS/ShipLabelsPackages/packageLabel.gif",
                        'ACL'    => 'public-read'));
                    print_r($result['ObjectURL']);
                    $response[] = array('trackingNumber'=> $resp->ShipmentResults->ShipmentIdentificationNumber, 'totalCharges' => $resp->ShipmentResults->ShipmentCharges->TotalCharges, 'shippingLabelURL' => $result['ObjectURL']);

                }
                return $response;
            }else{
                throw new Exception("Error Processing Request", 1);
                
            }
            
            // print_r($resp);
        }
        catch(\SoapFault $ex)
        {   
            print_r($ex->getMessage());
            // $responseLog = print_r($ex->detail, true);
            // Log::debug($responseLog);
        }

    }

    private function buildShip($fulfillment, $contact, $serviceType, $serviceCode){
        $requestoption['RequestOption'] = 'nonvalidate';
        $request['Request'] = $requestoption;

        $shipment['Description'] = 'Walts Test UPS Shipping';
        $shipper['Name'] = 'Walts TV';
        $shipper['AttentionName'] = 'Walts TV';
        $shipper['TaxIdentificationNumber'] = '123456';
        $shipper['ShipperNumber'] = $this->shippingNumber; 
        $address['AddressLine'] = '860 W. Carver Road, Suite 101';
        $address['City'] = 'Tempe';
        $address['StateProvinceCode'] = 'AZ';
        $address['PostalCode'] = '85284';
        $address['CountryCode'] = 'US';
        $shipper['Address'] = $address;
        $phone['Number'] = '4802109645';
        // $phone['Extension'] = '1';
        $shipper['Phone'] = $phone;
        $shipment['Shipper'] = $shipper;

        $shipto['Name'] = $contact->first_name . " " . $contact->last_name;
        $shipto['AttentionName'] = $contact->first_name . " " . $contact->last_name;
        $addressTo['ResidentialAddressIndicator'] = 'true'; //Can be empty, is true if the tag is present.
        $addressTo['AddressLine'] = $contact->address;
        $addressTo['City'] = $contact->city;
        $addressTo['PostalCode'] = $contact->zip;
        $addressTo['StateProvinceCode'] = $contact->state;
        $addressTo['CountryCode'] = $contact->country;
        // $phone2['Number'] = $contact->mobile_phone;
        $phone2['Number'] = '4802680565';
        $shipto['Address'] = $addressTo;
        $shipto['Phone'] = $phone2;
        $shipment['ShipTo'] = $shipto;

        $shipfrom['Name'] = 'Walts TV';
        $shipfrom['AttentionName'] = '1160b_74';
        $addressFrom['AddressLine'] = '860 W. Carver Road, Suite 101';
        $addressFrom['City'] = 'Tempe';
        $addressFrom['StateProvinceCode'] = 'AZ';
        $addressFrom['PostalCode'] = '85284';
        $addressFrom['CountryCode'] = 'US';
        $phone3['Number'] = '4802109645';
        $shipfrom['Address'] = $addressFrom;
        $shipfrom['Phone'] = $phone3;
        $shipment['ShipFrom'] = $shipfrom;

        $shipmentcharge['Type'] = '01';
        // $creditcard['Type'] = '06';
        // $creditcard['Number'] = '4716995287640625';
        // $creditcard['SecurityCode'] = '864';
        // $creditcard['ExpirationDate'] = '12/2013';
        // $creditCardAddress['AddressLine'] = '2010 warsaw road';
        // $creditCardAddress['City'] = 'Roswell';
        // $creditCardAddress['StateProvinceCode'] = 'GA';
        // $creditCardAddress['PostalCode'] = '30076';
        // $creditCardAddress['CountryCode'] = 'US';
        // $creditcard['Address'] = $creditCardAddress;
        // $billshipper['CreditCard'] = $creditcard;
        $billshipper['AccountNumber'] = $this->shippingNumber;
        $shipmentcharge['BillShipper'] = $billshipper;
        $paymentinformation['ShipmentCharge'] = $shipmentcharge;
        $shipment['PaymentInformation'] = $paymentinformation;

        $service['Code'] = $serviceCode;
        $service['Description'] = $serviceType;
        $shipment['Service'] = $service;

        /*
        $internationalForm['FormType'] = '01';
        $internationalForm['InvoiceNumber'] = 'asdf123';
        $internationalForm['InvoiceDate'] = '20151225';
        $internationalForm['PurchaseOrderNumber'] = '999jjj777';
        $internationalForm['TermsOfShipment'] = 'CFR';
        $internationalForm['ReasonForExport'] = 'Sale';
        $internationalForm['Comments'] = 'Your Comments';
        $internationalForm['DeclarationStatement'] = 'Your Declaration Statement';
        $soldTo['Option'] = '01';
        $soldTo['AttentionName'] = 'Sold To Attn Name';
        $soldTo['Name'] = 'Sold To Name';
        $soldToPhone['Number'] = '1234567890';
        $soldToPhone['Extension'] = '1234';
        $soldTo['Phone'] = $soldToPhone;
        $soldToAddress['AddressLine'] = '34 Queen St';
        $soldToAddress['City'] = 'Frankfurt';
        $soldToAddress['PostalCode'] = '60547';
        $soldToAddress['CountryCode'] = 'DE';
        $soldTo['Address'] = $soldToAddress;
        $contact['SoldTo'] = $soldTo;
        $internationalForm['Contacts'] = $contact;
        $product['Description'] = 'Product 1';
        $product['CommodityCode'] = '111222AA';
        $product['OriginCountryCode'] = 'US';
        $unitProduct['Number'] = '147';
        $unitProduct['Value'] = '478';
        $uom['Code'] = 'BOX';
        $uom['Description'] = 'BOX';
        $unitProduct['UnitOfMeasurement'] = $uom;
        $product['Unit'] = $unitProduct;
        $productWeight['Weight'] = '10';
        $uomForWeight['Code'] = 'LBS';
        $uomForWeight['Description'] = 'LBS';
        $productWeight['UnitOfMeasurement'] = $uomForWeight;
        $product['ProductWeight'] = $productWeight;
        $internationalForm['Product'] = $product;
        $discount['MonetaryValue'] = '100';
        $internationalForm['Discount'] = $discount;
        $freight['MonetaryValue'] = '50';
        $internationalForm['FreightCharges'] = $freight;
        $insurance['MonetaryValue'] = '200';
        $internationalForm['InsuranceCharges'] = $insurance;
        $otherCharges['MonetaryValue'] = '50';
        $otherCharges['Description'] = 'Misc';
        $internationalForm['OtherCharges'] = $otherCharges;
        $internationalForm['CurrencyCode'] = 'USD';
        $shpServiceOptions['InternationalForms'] = $internationalForm;
        $shipment['ShipmentServiceOptions'] = $shpServiceOptions;
        */

        foreach($fulfillment[0]->package as $upsPackage){
            $package['Description'] = '';
            $packaging['Code'] = '02';
            $packaging['Description'] = 'Walts TV Product'; //Try to add in information for what shipped in it.
            $package['Packaging'] = $packaging;
            $unit['Code'] = 'IN';
            $unit['Description'] = 'Inches';
            $dimensions['UnitOfMeasurement'] = $unit;
            $dimensions['Length'] = $upsPackage->package_length;
            $dimensions['Width'] = $upsPackage->package_width;
            $dimensions['Height'] = $upsPackage->package_height;
            $package['Dimensions'] = $dimensions;
            $unit2['Code'] = 'LBS';
            $unit2['Description'] = 'Pounds';
            $packageweight['UnitOfMeasurement'] = $unit2;
            $packageweight['Weight'] = $upsPackage->package_weight;
            $package['PackageWeight'] = $packageweight;
            $shipment['Package'][] = $package;
        }

        $labelimageformat['Code'] = 'GIF';
        $labelimageformat['Description'] = 'GIF';
        $labelspecification['LabelImageFormat'] = $labelimageformat;
        $labelspecification['HTTPUserAgent'] = 'Mozilla/4.5';
        $shipment['LabelSpecification'] = $labelspecification;
        $request['Shipment'] = $shipment;
        // print_r($request);
        return $request;
    }

    private function buildShipAccept($shipmentDigest){
        $requestoption['RequestOption'] = 'nonvalidate';
        $request['ShipmentDigest'] = $shipmentDigest; //base_64_encoded possibly
        $request['Request'] = $requestoption;
        
        return $request;
    }

    private function getQuoteFreight(){
        $mode = array('soap_version' => 'SOAP_1_1', 'trace' => 1);
        $this->wsdl = dirname(__FILE__). '/../Soap/UPS/Freight/FreightRate.wsdl';
        $request = $this->buildQuoteFreight();
        // initialize soap client
        $client = new \SoapClient($this->wsdl , $mode);

        //set endpoint url
        $client->__setLocation("https://wwwcie.ups.com/webservices/FreightRate");

        //create soap header
        $usernameToken['Username'] = $this->userid;
        $usernameToken['Password'] = $this->passwd;
        $serviceAccessLicense['AccessLicenseNumber'] = $this->access;
        $upss['UsernameToken'] = $usernameToken;
        $upss['ServiceAccessToken'] = $serviceAccessLicense;

        $header = new \SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);

        $client->__setSoapHeaders($header);

        //get response
        $resp = $client->__soapCall($this->operation ,array($request));
        //get status
        $responseLog = print_r($resp, true);
        Log::debug($responseLog);
        // echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";
        return $resp;
    }

    private function buildQuote(){
        // $fulfillment, $contact, $serviceType, $service_name
    	$option['RequestOption'] = 'Shop';
    	$request['Request'] = $option;

    	$pickuptype['Code'] = '01';
    	$pickuptype['Description'] = 'Daily Pickup';
    	$request['PickupType'] = $pickuptype;

    	$customerclassification['Code'] = '01';
    	$customerclassification['Description'] = 'Classfication';
    	$request['CustomerClassification'] = $customerclassification;

    	$shipper['Name'] = 'Walts TV';
    	$shipper['ShipperNumber'] = $this->shippingNumber;
    	$address['AddressLine'] = array
    	(
    	    '860 W. Carver Road', 
            'Suite 101',
    	);
    	$address['City'] = 'Tempe';
    	$address['StateProvinceCode'] = 'AZ';
    	$address['PostalCode'] = '85284';
    	$address['CountryCode'] = 'US';
    	$shipper['Address'] = $address;
    	$shipment['Shipper'] = $shipper;

    	$shipto['Name'] = $this->contactInfo->first_name . " " . $this->contactInfo->last_name;
    	$addressTo['AddressLine'] = $this->contactInfo->address;
    	$addressTo['City'] = $this->contactInfo->city;
    	$addressTo['StateProvinceCode'] = $this->contactInfo->state;
    	$addressTo['PostalCode'] = $this->contactInfo->zip;
    	$addressTo['CountryCode'] = $this->contactInfo->country;
    	$addressTo['ResidentialAddressIndicator'] = '';
    	$shipto['Address'] = $addressTo;
    	$shipment['ShipTo'] = $shipto;

    	$shipfrom['Name'] = 'Walts TV';
    	$addressFrom['AddressLine'] = array
    	(
    	    '860 W. Carver Road', 
            'Suite 101',
    	);
    	$addressFrom['City'] = 'Tempe';
    	$addressFrom['StateProvinceCode'] = 'AZ';
    	$addressFrom['PostalCode'] = '85248';
    	$addressFrom['CountryCode'] = 'US';
    	$shipfrom['Address'] = $addressFrom;
    	$shipment['ShipFrom'] = $shipfrom;

    	$service['Code'] = '03';
    	$service['Description'] = 'Service Code';
    	$shipment['Service'] = $service;

        foreach($this->shipment[0]->package as $upsPackage){
            $packaging1['Code'] = '02';
            $packaging1['Description'] = 'Rate';
            $package1['PackagingType'] = $packaging1;
            $dunit1['Code'] = 'IN';
            $dunit1['Description'] = 'inches';
            $dimensions1['Length'] = $upsPackage->package_length;
            $dimensions1['Width'] = $upsPackage->package_width;
            $dimensions1['Height'] = $upsPackage->package_height;
            $dimensions1['UnitOfMeasurement'] = $dunit1;
            $package1['Dimensions'] = $dimensions1;
            $punit1['Code'] = 'LBS';
            $punit1['Description'] = 'Pounds';
            $packageweight1['Weight'] = $upsPackage->package_weight;
            $packageweight1['UnitOfMeasurement'] = $punit1;
            $package1['PackageWeight'] = $packageweight1;

            $shipment['Package'][] = $package1;
        }


    	// $packaging1['Code'] = '02';
    	// $packaging1['Description'] = 'Rate';
    	// $package1['PackagingType'] = $packaging1;
    	// $dunit1['Code'] = 'IN';
    	// $dunit1['Description'] = 'inches';
    	// $dimensions1['Length'] = '5';
    	// $dimensions1['Width'] = '4';
    	// $dimensions1['Height'] = '10';
    	// $dimensions1['UnitOfMeasurement'] = $dunit1;
    	// $package1['Dimensions'] = $dimensions1;
    	// $punit1['Code'] = 'LBS';
    	// $punit1['Description'] = 'Pounds';
    	// $packageweight1['Weight'] = '1';
    	// $packageweight1['UnitOfMeasurement'] = $punit1;
    	// $package1['PackageWeight'] = $packageweight1;

    	// $packaging2['Code'] = '02';
    	// $packaging2['Description'] = 'Rate';
    	// $package2['PackagingType'] = $packaging2;
    	// $dunit2['Code'] = 'IN';
    	// $dunit2['Description'] = 'inches';
    	// $dimensions2['Length'] = '3';
    	// $dimensions2['Width'] = '5';
    	// $dimensions2['Height'] = '8';
    	// $dimensions2['UnitOfMeasurement'] = $dunit2;
    	// $package2['Dimensions'] = $dimensions2;
    	// $punit2['Code'] = 'LBS';
    	// $punit2['Description'] = 'Pounds';
    	// $packageweight2['Weight'] = '2';
    	// $packageweight2['UnitOfMeasurement'] = $punit2;
    	// $package2['PackageWeight'] = $packageweight2;

    	// $shipment['Package'] = array(	$package1 , $package2 );
    	$shipment['ShipmentServiceOptions'] = '';
    	$shipment['LargePackageIndicator'] = '';
    	$request['Shipment'] = $shipment;
    	return $request;
    }

    private function buildQuoteFreight(){
      //create soap request
      $option['RequestOption'] = 'RateChecking Option';
      $request['Request'] = $option;
      $shipfrom['Name'] = 'Good Incorporation';
      $addressFrom['AddressLine'] = '2010 WARSAW ROAD';
      $addressFrom['City'] = 'Roswell';
      $addressFrom['StateProvinceCode'] = 'GA';
      $addressFrom['PostalCode'] = '30076';
      $addressFrom['CountryCode'] = 'US';
      $shipfrom['Address'] = $addressFrom;
      $request['ShipFrom'] = $shipfrom;

      $shipto['Name'] = 'Sony Company Incorporation';
      $addressTo['AddressLine'] = '2311 YORK ROAD';
      $addressTo['City'] = 'TIMONIUM';
      $addressTo['StateProvinceCode'] = 'MD';
      $addressTo['PostalCode'] = '21093';
      $addressTo['CountryCode'] = 'US';
      $shipto['Address'] = $addressTo;
      $request['ShipTo'] = $shipto;

      $payer['Name'] = 'Payer inc';
      $addressPayer['AddressLine'] = '435 SOUTH STREET';
      $addressPayer['City'] = 'RIS TOWNSHIP';
      $addressPayer['StateProvinceCode'] = 'NJ';
      $addressPayer['PostalCode'] = '07960';
      $addressPayer['CountryCode'] = 'US';
      $payer['Address'] = $addressPayer;
      $shipmentbillingoption['Code'] = '10';
      $shipmentbillingoption['Description'] = 'PREPAID';
      $paymentinformation['Payer'] = $payer;
      $paymentinformation['ShipmentBillingOption'] = $shipmentbillingoption;
      $request['PaymentInformation'] = $paymentinformation;

      $service['Code'] = '309';
      $service['Description'] = 'UPS Freight LTL - Guaranteed';
      $request['Service'] = $service;

      $handlingunitone['Quantity'] = '20';
      $handlingunitone['Type'] = array
      (
          'Code' => 'PLT',
          'Description' => 'PALLET'
      );
      $request['HandlingUnitOne'] = $handlingunitone;

      $commodity['CommodityID'] = '';
      $commodity['Description'] = 'No Description';
      $commodity['Weight'] = array
      (
         'UnitOfMeasurement' => array
         (
             'Code' => 'LBS',
             'Description' => 'Pounds'
         ),
         'Value' => '750'
      );
      $commodity['Dimensions'] = array
      (
          'UnitOfMeasurement' => array
          (
              'Code' => 'IN',
              'Description' => 'Inches'
          ),
          'Length' => '23',
          'Width' => '17',
          'Height' => '45'
      );
      $commodity['NumberOfPieces'] = '45';
      $commodity['PackagingType'] = array
      (
           'Code' => 'BAG',
           'Description' => 'BAG'
      );
      $commodity['DangerousGoodsIndicator'] = '';
      $commodity['CommodityValue'] = array
      (
           'CurrencyCode' => 'USD',
           'MonetaryValue' => '5670'
      );
      $commodity['FreightClass'] = '60';
      $commodity['NMFCCommodityCode'] = '';
      $request['Commodity'] = $commodity;

      $shipmentserviceoptions['PickupOptions'] = array
      (
            'HolidayPickupIndicator' => '',
            'InsidePickupIndicator' => '',
            'ResidentialPickupIndicator' => '',
            'WeekendPickupIndicator' => '',
            'LiftGateRequiredIndicator' => ''
      );
      $shipmentserviceoptions['OverSeasLeg'] = array
      (
             'Dimensions' => array
             (
                  'Volume' => '20',
                  'UnitOfMeasurement' => array
                  (
                      'Code' => 'CF',
                      'Description' => 'String'
                  )
             ),
             'Value' => array
             (
                  'Cube' => array
                  (
                       'CurrencyCode' => 'USD',
                       'MonetaryValue' => '5670'
                  )
             ),
             'COD' => array
             (
                  'CODValue' => array
                  (
                       'CurrencyCode' => 'USD',
                       'MonetaryValue' => '363'
                  ),
                  'CODPaymentMethod' => array
                  (
                       'Code' => 'M',
                       'Description' => 'For Company Check'
                  ),
                  'CODBillingOption' => array
                  (
                       'Code' => '01',
                       'Description' => 'Prepaid'
                  ),
                  'RemitTo' => array
                  (
                       'Name' => 'RemitToSomebody',
                       'Address' => array
                       (
                             'AddressLine' => '640 WINTERS AVE',
                             'City' => 'PARAMUS',
                             'StateProvinceCode' => 'NJ',
                             'PostalCode' => '07652',
                             'CountryCode' => 'US'
                       ),
                       'AttentionName' => 'C J Parker'
                  )
              ),
              'DangerousGoods' => array
              (
                    'Name' => 'Very Safe',
                    'Phone' => array
                    (
                        'Number' => '453563321',
                        'Extension' => '1111'
                    ),
                    'TransportationMode' => array
                    (
                        'Code' => 'CARGO',
                        'Description' => 'Cargo Mode'
                    )
              ),
              'SortingAndSegregating' => array
              (
                    'Quantity' => '23452'
              ),
              'CustomsValue' => array
              (
                    'CurrencyCode' => 'USD',
                    'MonetaryValue' => '23457923'
              ),
              'HandlingCharge' => array
              (
                    'Amount' => array
                    (
                        'CurrencyCode' => 'USD',
                        'MonetaryValue' => '450'
                    )
              )
       );
      $request['ShipmentServiceOptions'] = $shipmentserviceoptions;
      return $request;
    }

    private function getService($code, $value = false){
        $codes = array(
            '11'=>'UPS Standard',
            '08'=>'UPS Worldwide Expedited',
            '07'=>'UPS Worldwide Express',
            '54'=>'UPS Worldwide Express Plus',
            '65'=>'UPS Worldwide Saver',
            '02'=>'UPS 2nd Day Air',
            '59'=>'UPS 2nd Day Air A.M.',
            '12'=>'UPS 3 Day Select',
            '03'=>'UPS Ground',
            '01'=>'UPS Next Day Air',
            '14'=>'UPS Next Day Air Early',
            '13'=>'UPS Next Day Air Saver',
            'M3'=>'UPS Priority Mail',
        );

        if(!$value){
            if(array_key_exists($code, $codes)){
                $serviceName = $codes[$code];
            }
        }else{
            $key = array_search($code, $codes);
            if ($key !== false) {
                $serviceName = $key;
            }
        }
        return $serviceName;
    }

    private function getServiceFreight($code, $value = false){
        $codes = array(
            '308' => 'UPS Freight LTL',
            '309' => 'UPS Freight LTL - Guaranteed',
            '334' => 'UPS Freight LTL - Guaranteed A.M.',
            '349' => 'UPS Standard LT',
        );

        if(!$value){
            if(array_key_exists($code, $codes)){
                $serviceName = $codes[$code];
            }
        }else{
            $key = array_search($code, $codes);
            if ($key !== false) {
                $serviceName = $key;
            }
        }
        return $serviceName;
    }


}
?>