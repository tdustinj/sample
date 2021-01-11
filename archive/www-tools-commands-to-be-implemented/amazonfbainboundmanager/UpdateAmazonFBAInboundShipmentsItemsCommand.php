<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateAmazonFBAInboundShipmentsItemsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-amazon-fba-inbound-shipments-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the List of Amazon FBA Inbound Shipments Items ';

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
        $nonFinishedFBAOrders = AmazonFBAInboundShipment::where('ShipmentStatus', '!=', 'CANCELLED')->where('ShipmentStatus', '!=', 'DELETED')->where('ShipmentStatus', '!=', 'CLOSED')->get();
        foreach($nonFinishedFBAOrders as $fbaShipment) {
            $results = $this->ListInboundShipmentItems($fbaShipment->ShipmentId);
        }
       // print_r($results);
    }


    public function ListInboundShipmentItems($shipmentId)
    {

        $serviceUrl = "https://mws.amazonservices.com/FulfillmentInboundShipment/2010-10-01";
        $mwsApi = new AmazonMwsAPIUtil($serviceUrl);
        $service = new FBAInboundServiceMWS_Client($mwsApi->AWS_ACCESS_KEY_ID, $mwsApi->AWS_SECRET_ACCESS_KEY, $mwsApi->APPLICATION_NAME,
            $mwsApi->APPLICATION_VERSION, $mwsApi->config);



        $request = new \FBAInboundServiceMWS_Model_ListInboundShipmentItemsRequest();
        $request->setSellerId($mwsApi->MERCHANT_ID);


        //$statusList = new \FBAInboundServiceMWS_Model_ListInboundShipmentItemsRequest();
        //$statusList->setShipmentId($shipmentId);
        $request->setShipmentId($shipmentId);
        $result = $this->invokeListInboundShipmentItems($service, $request);
        $xmlObj = simplexml_load_string($result);

        $json = json_encode($xmlObj);
        // return $json;
        $result = json_decode($json, TRUE);

        if(isset($result["ListInboundShipmentItemsResult"]["ItemData"]["member"][0])) {
            foreach ($result["ListInboundShipmentItemsResult"]["ItemData"]["member"] as $shipment) {
                echo "============================ Multi Shipment Item Information ===========================================  " . "\n\r";
                print_r($result);
               // $fbaShipmentItem = new AmazonFBAInboundShipmentItems();
                $key = $shipment["ShipmentId"] . '-' . $shipment["SellerSKU"];
                echo $key;
                $fbaShipmentItem = AmazonFBAInboundShipmentItems::where('id_sku', '=',$key)->get();
                //dd($fbaShipmentItem);
                $fbaShipmentItem[0]->QuantityShipped = $shipment["QuantityShipped"];
                $fbaShipmentItem[0]->QuantityReceived = $shipment["QuantityReceived"];
                $fbaShipmentItem[0]->QuantityInCase = $shipment["QuantityInCase"];

                try {
                    $fbaShipmentItem[0]->save();
                } catch (PDOException $exception) {
                    // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                     print_r($exception->getMessage());
                }
            }
        }
        else{

                echo "============================ Single Shipment Item Information ===========================================  " . "\n\r";

            if(isset($result["ListInboundShipmentItemsResult"]["ItemData"]["member"])) {
                $shipment = $result["ListInboundShipmentItemsResult"]["ItemData"]["member"];
                $key = $shipment["ShipmentId"] . '-' . $shipment["SellerSKU"];
                echo $key;
                $fbaShipmentItem = AmazonFBAInboundShipmentItems::where('id_sku', '=', $key)->get();

                $fbaShipmentItem[0]->QuantityShipped = $shipment["QuantityShipped"];
                $fbaShipmentItem[0]->QuantityReceived = $shipment["QuantityReceived"];
                $fbaShipmentItem[0]->QuantityInCase = $shipment["QuantityInCase"];

                try {
                    $fbaShipmentItem[0]->save();
                } catch (PDOException $exception) {
                    // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                    print_r($exception->getMessage());
                }
            }
        }
        return $result;
        sleep(1);

    }

    public function invokeListInboundShipmentItems(FBAInboundServiceMWS_Interface $service, $request)
    {
        try {
            $response = $service->ListInboundShipmentItems($request);

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


}


