<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AutoShipWithWaltsInvoiceCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:auto-ship-with-walts-invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set all Unshipped Orders to Walts and Invoice for Tracking number if after a certain time of day';

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
        $shipCutOffDateTime = $today ;
        $elevenPmToday = $today . " 22:00:00"; // right now set for 10:00pm
        $afterElevenPM = false;
        $now = new DateTime();
        $now = $now->format("Y-m-d H:i:s");



        if($now > $elevenPmToday){
            $afterElevenPM = true;
        }
        echo $elevenPmToday;
      //  $unshippedItems = ShippingTransaction::where('platform_updated_status', '=', 'NOT_UPDATED')->where('status', '=', 1)->where('bol_tracking', 'like', '____-__-__ %')->where('fulfillment_shipment_status', '=', 'NOT_SHIPPED')->where('partner', '!=', 'walts.com')->where('partner', '!=', '')->where('ship_by_date' ,'<=', $shipCutOffDateTime)->where('ship_by_date' ,'!=', '0000-00-00')->get();
        $unshippedItems = ShippingTransaction::where('platform_updated_status', '=', 'NOT_UPDATED')->where('status', '=', 1)->where('fulfillment_shipment_status', '=', 'NOT_SHIPPED')->where('partner', '!=', 'walts.com')->where('partner', '!=', '')->where('ship_by_date' ,'<=', $shipCutOffDateTime)->where('ship_by_date' ,'!=', '0000-00-00')->get();


        if(sizeof($unshippedItems)) {
            echo "We have Shipments that have not shipped yet and it is after 11:30 \n\r";
            foreach ($unshippedItems as $shipment) {
                 //print_r($shipment);
                 echo "Would set this one to walts invoice number : \n\r";
                 echo "Invoice: " . $shipment->invoice_num
                     . " Tracking : " . $shipment->bol_tracking
                     . " Fulfillment Shipment Status:" . $shipment->fulfillment_shipment_status
                     . " Status : " . $shipment->status
                     . " Platform Updated Status : " . $shipment->platform_updated_status
                     . " Customer Tracking Sent  : " . $shipment->customer_tracking_sent
                     . " Ship By Date : " . $shipment->ship_by_date
                     . "\n\r";

                 //$shipped_via_walts = 1;
                  $shipment->fulfillment_shipment_status = "SHIPPED";
                //$shipment->platform_updated_status = "NOT_UPDATED";
                $shipment->bol_tracking = $shipment->invoice_num;
                $shipment->ship_company = 'Walts';
                $shipment->ship_method = "Other";
                $shipment->date_shipped = $now;
              //  $shipment->save();


                }


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


