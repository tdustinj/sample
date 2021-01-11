<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateShippingInformationForAmazonOrdersCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-shipping-information-for-amazon-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Tracking Information To amazon for Amazon MFN Orders';

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


        // This request Amazon FBA Inventory then creates a new amazon_reports request
        define('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');
        $today = date("Y-m-d") ;
        $elevenPmToday = $today . " 18:00:00"; // right now set for 6:00pm
        $afterElevenPM = false;
        $now = new DateTime();
        $now = $now->format("Y-m-d H:i:s");



        if($now > $elevenPmToday){
            $afterElevenPM = true;
        }



        $serviceUrl = "https://mws.amazonservices.com/Products/2011-10-01";
        $config = array(
            'ServiceURL' => $serviceUrl,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'MaxErrorRetry' => 3,
        );
        $AWS_ACCESS_KEY_ID = $_ENV['AWS_ACCESS_KEY_ID'];
        $AWS_SECRET_ACCESS_KEY = $_ENV['AWS_SECRET_ACCESS_KEY'];
        $APPLICATION_NAME = $_ENV['APPLICATION_NAME'];
        $APPLICATION_VERSION = $_ENV['APPLICATION_VERSION'];
        $AWS_MARKETPLACE_ID = $_ENV['AWS_MARKETPLACE_ID'];
        $MERCHANT_ID = $_ENV['MERCHANT_ID'];
        $MERCHANT_TOKEN = $_ENV['MERCHANT_TOKEN'];


        $fulfillmentInfoItems = array();
        $fulfilledItems = ShippingTransaction::where('platform_updated_status', '=', 'NOT_UPDATED')->where('fulfillment_shipment_status', '=', 'SHIPPED')->where('partner', '=', 'Amazon')->where('status', '=', 1)->get();
        //$fulfilledItems = ShippingTransaction::where('invoice_num', '=', 184086)->get();

       // foreach($fulfilledItems as $amazonShipment){
       //     echo "Shiping Transaction : Invoice " . $amazonShipment->invoice_num . " , Amazon Order Id " . $amazonShipment->partner_order_number
       //         . " , tracking num " . $amazonShipment->bol_tracking . ", Date" . $amazonShipment->updated_at .  "\n\r";
       // }


        if(sizeof($fulfilledItems)) {
            echo "We have Shipments that have not been sent \n\r";
            foreach ($fulfilledItems as $shipment) {
                /*if ((!$shipment->bogus_ship_notified and $shipment->order_status != "Shipped") and ($shipment->ship_by_date == $today) and $afterElevenPM) {
                    // generate bogus shipping information
                    $freight = $this->isFreight($shipment->ship_method);
                    $shipment->bogus_ship_notified = 1;
                    $fulfillmentInfo["loop"] = "Do not Have";
                    $fulfillmentInfo["amazonOrderId"] = $shipment->partner_order_number;
                    if($freight) {
                        $fulfillmentInfo["carrierCode"] = "Other";
                        $fulfillmentInfo["carrierName"] = "XPO";
                        $fulfillmentInfo["shippingMethod"] = $shipment->ship_method;
                        $fulfillmentInfo["shippingTrackingNumber"] = $shipment->invoice_num;
                    }
                    else{
                        $fulfillmentInfo["carrierCode"] = "Other";
                        $fulfillmentInfo["carrierName"] = "USPS";
                        $fulfillmentInfo["shippingMethod"] = $shipment->ship_method;
                        $fulfillmentInfo["shippingTrackingNumber"] = $shipment->invoice_num;
                    }
                    $fulfillmentInfo["amazonOrderItemCode"] = $shipment->unique_item_id;
                    $fulfillmentInfo["merchantFulfillmentItemId"] = $shipment->tid;
                    $fulfillmentInfo["quantity"] = 1;
                   // $fulfillmentInfo["shipment"] = $shipment;
                    $fulfillmentInfoItems[] = $fulfillmentInfo;
                    $shipment->order_status = "Third Party Conf. Track Pend.";

                }
                */
                if ($shipment->fulfillment_shipment_status == "SHIPPED" ) { //and $shipment->bogus_ship_notified != 1) {
                    $fulfillmentInfo["loop"] = "Clear to snd Update";
                    $fulfillmentInfo["amazonOrderId"] = $shipment->partner_order_number;
                    $fulfillmentInfo["carrierCode"] = $shipment->ship_company;
                    $fulfillmentInfo["carrierName"] = $shipment->ship_company;
                    $fulfillmentInfo["shippingMethod"] = $shipment->ship_method;
                    $fulfillmentInfo["shippingTrackingNumber"] = $shipment->bol_tracking;
                    $fulfillmentInfo["amazonOrderItemCode"] = $shipment->unique_item_id;
                    $fulfillmentInfo["merchantFulfillmentItemId"] = $shipment->tid;
                    $fulfillmentInfo["id"] = $shipment->id;
                    $amazon_date = new DateTime($shipment->date_shipped);
                    $amazon_date = $amazon_date->format('Y-m-d\TH:i:s\Z');

                    $fulfillmentInfo["updated_at"] = $amazon_date;
                    $fulfillmentInfo["quantity"] = 1;
                   // $fulfillmentInfo["shipment"] = $shipment;
                    $fulfillmentInfoItems[] = $fulfillmentInfo;
                  //  echo $this->getShippingInformationXml($fulfillmentInfo);

                }
                else{
                 echo "shipment " . $shipment->partner_order_number . " " . $shipment->order_status ."\n\r";
                }

            }
        }
        print_r($fulfillmentInfoItems);
        $feedXml ='<?xml version="1.0"
encoding="UTF-8"?>
<AmazonEnvelope
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">
    <Header>     
<DocumentVersion>1.01</DocumentVersion>      
<MerchantIdentifier>Walts TV</MerchantIdentifier>
    </Header><MessageType>OrderFulfillment</MessageType>';

        $mwsConnection = new AmazonMarketPlaceWebServiceClient();
        $marketplaceIdArray = array("Id" => array($mwsConnection->AWS_MARKETPLACE_ID));

        if(sizeof($fulfillmentInfoItems)) {

              foreach ($fulfillmentInfoItems as $shippingLine) {

                //dd($shippingLine);

                $feedXml .= $this->getShippingInformationXml($shippingLine);

              }
             $feedXml .= '</AmazonEnvelope>';

            $feedHandle = @fopen('php://memory', 'rw+');
            fwrite($feedHandle, $feedXml);
            rewind($feedHandle);

            $request = new MarketplaceWebService_Model_SubmitFeedRequest();
            $request->setMerchant($mwsConnection->MERCHANT_ID);
            $request->setMarketplaceIdList($marketplaceIdArray);
            $request->setFeedType('_POST_ORDER_FULFILLMENT_DATA_');
            $request->setContentMd5(base64_encode(md5(stream_get_contents($feedHandle), true)));
            rewind($feedHandle);
            $request->setPurgeAndReplace(false);
            $request->setFeedContent($feedHandle);
            echo $feedXml;


            $feedSubmissionId = $mwsConnection->invokeSubmitFeed($mwsConnection->service, $request, $feedXml);
            // if exception feedId = -1
            //  var_dump($feedSubmissionId);
            date_default_timezone_set('America/Phoenix');
            if($feedSubmissionId) {
                foreach ($fulfillmentInfoItems as $shippingLine) {
                    echo "Updating " . $shippingLine['amazonOrderItemCode'] . "\n\r";
                    $right_now = date("Y-m-d h:m:s");

                    $shippingTransactions = ShippingTransaction::where('unique_item_id', '=', $shippingLine['amazonOrderItemCode'])->get();
                    //print_r($shippingTransaction); die;
                   foreach($shippingTransactions as $shippingTransaction) {
                       $shippingTransaction->platform_updated_status = 'UPDATED';
                       $shippingTransaction->updated_at = $right_now;
                       $shippingTransaction->action = 'Shipping Info Sent via API';
                       $shippingTransaction->user = 'Amazon API';
                       $shippingTransaction->submission_id = $feedSubmissionId;

                       $shippingTransaction->save();
                       echo "Updating " . $shippingTransaction->unique_item_id . "\n\r";

                       DB::table('orders_amazon_fulfillment')->where('MerchantFulfillmentOrderItemID', $shippingLine['amazonOrderItemCode'])->update(array('status' => 'PLATFORM_UPDATED'));

                      $msg = "Updated Amazon with shipping info";
                      $query = "insert into messages set msg = '$msg', 
                                  msg_time = '$right_now'
                                , msg_from = 'AutoLoaded'
                                , msg_to = 'System'
                                , msg_type = 'shipping'
                                , invoice = '" . $shippingTransaction['invoice_num'] . "'";
                      $newMsg = DB::connection('mysql-walts2')->insert($query);
                      $invoicenum = $shippingTransaction['invoice_num'];
                      $updateQuery = "update invoice set shipping_confirmed = 'YES' where invoicenum = '$invoicenum'";
                                          echo $updateQuery . "\n\r";
                                          DB::connection('mysql-walts2')->update($updateQuery);                      

                   }
                }
            }
            else{
                echo "Error";
            }

        }
        else {
            echo "No Pending Amazon Shipping Notifications Needed to Be sent";
        }




    }



     function isFreight($shipMethod){
         $isFreight = true;
        switch($shipMethod){

            case "SHIPPING_STERLING" :
                $isFreight = true;
                break;
            case "SHIPPING_SMALL_2DAY" :
                $isFreight = false;
                break;
            case "SHIPPING_SMALL_STANDARD" :
                $isFreight = false;
                break;
            case "SHIPPING_SMALL_1DAY" :
                $isFreight = false;
                break;
            case "SHIPPING_STANDARD" :
                $isFreight = true;
                break;
            case "SHIPPING_STANDARD_PLUS" :
                $isFreight = true;
                break;
            case "SHIPPING_SPEEDY" :
                $isFreight = true;
                break;

        }
        return $isFreight;
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

  function getShippingInformationXml($fulfillmentInfo){
        $xml = '<Message>     
<MessageID>' . $fulfillmentInfo["id"] .'</MessageID> 
<OrderFulfillment>
  <AmazonOrderID>' . $fulfillmentInfo["amazonOrderId"] . '</AmazonOrderID>
  <FulfillmentDate>'.  $fulfillmentInfo["updated_at"].'</FulfillmentDate>
  <FulfillmentData>
    <CarrierName>' . $fulfillmentInfo["carrierName"] . '</CarrierName>
    <ShippingMethod>' . $fulfillmentInfo["shippingMethod"] . '</ShippingMethod>
    <ShipperTrackingNumber>' . $fulfillmentInfo["shippingTrackingNumber"] . '</ShipperTrackingNumber>
  </FulfillmentData>
  <Item>
    <AmazonOrderItemCode>' . $fulfillmentInfo["amazonOrderItemCode"] . '</AmazonOrderItemCode>
   <MerchantFulfillmentItemID>' . $fulfillmentInfo["merchantFulfillmentItemId"] . '</MerchantFulfillmentItemID>
    <Quantity>' . $fulfillmentInfo["quantity"] . '</Quantity>
  </Item>
</OrderFulfillment>
</Message>';

    return $xml;
  }
}


