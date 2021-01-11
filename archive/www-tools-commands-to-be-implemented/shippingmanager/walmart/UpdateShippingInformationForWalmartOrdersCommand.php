<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateShippingInformationForWalmartOrdersCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-shipping-information-for-walmart-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Tracking Information To walmart for Orders';

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


        define('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');


        $apiConnection = new walmartAPIUtil();
        $apiConnection->apiMethod = "POST";
        $apiConnection->hack = true;

        $fulfillmentInfoItems = array();
        $fulfilledItems = ShippingTransaction::where('platform_updated_status', '=', 'NOT_UPDATED')->where('fulfillment_shipment_status', '=', 'SHIPPED')->where('partner', '=', 'walmart.com')->where('status', '=', 1)->get();




        foreach($fulfilledItems as $shippingTransaction){
            //print_r($shippingTransaction);
        $apiConnection->apiUrl = '/v3/orders/' . $shippingTransaction->partner_order_number . '/shipping';
       //echo $apiConnection->apiUrl . "\n\r";
              $lineNumber = explode('-',$shippingTransaction->unique_item_id);
              $lNumber = '';
              if(sizeof($lineNumber) == 1){
                  $lNumber = 1;
              }
              else{
                  $lNumber = $lineNumber[1];
              }
              //$shippingUrl = $this->trackingUrl($shippingTransaction->bol_tracking,$shippingTransaction->ship_company );
            $shippingUrl = "https://www.walts.com/tracking/" . $shippingTransaction->invoice_num;
            $now = new DateTime($shippingTransaction->updated_at);
            $now = $now->format("Y-m-d\TH:i:s\Z");

              $ship_company = $shippingTransaction->ship_company;
              if (($shippingTransaction->bol_tracking == $shippingTransaction->invoice_num) && ($shippingTransaction->shipped_via_walts)) {
                    $ship_company = "Other";
              }            
               $apiConnection->xmlPayload = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                  <ns2:orderShipment
                  xmlns:ns2="http://walmart.com/mp/v3/orders"
                  xmlns:ns3="http://walmart.com/">
                  <ns2:orderLines>
                  <ns2:orderLine>
                  <ns2:lineNumber>' . $lNumber . '</ns2:lineNumber>
                  <ns2:orderLineStatuses>
                  <ns2:orderLineStatus>
                  <ns2:status>Shipped</ns2:status>
                  <ns2:statusQuantity>
                  <ns2:unitOfMeasurement>Each</ns2:unitOfMeasurement>
                  <ns2:amount>1</ns2:amount>
                  </ns2:statusQuantity>
                  <ns2:trackingInfo>
                  <ns2:shipDateTime>' . $now . '</ns2:shipDateTime>
                  <ns2:carrierName>
                  <ns2:otherCarrier>' .  $ship_company .'</ns2:otherCarrier>
                  </ns2:carrierName>
                  <ns2:methodCode>Standard</ns2:methodCode>
                  <ns2:trackingNumber>' . $shippingTransaction->bol_tracking . '</ns2:trackingNumber>
                  <ns2:trackingURL>'. $shippingUrl . '</ns2:trackingURL>
                  </ns2:trackingInfo>
                  </ns2:orderLineStatus>
                  </ns2:orderLineStatuses>
                  </ns2:orderLine>
                  </ns2:orderLines>
                  </ns2:orderShipment>';



                //  echo  $apiConnection->apiMethod . " " . $apiConnection->baseUrl . $apiConnection->apiUrl   . $priceTransaction->sku . "\n\r";
                $apiConnection->contentLength = strlen($apiConnection->xmlPayload);

               // echo $apiConnection->xmlPayload;


                $apiConnection->contentType = 'application/xml';

                $walmartReport = new WalmartReport();

                $walmartReport->report_type = "shipping_notification";
                $walmartReport->request_body = $apiConnection->xmlPayload;

              //  print_r($apiConnection->xmlPayload);

                $apiConnection->callApi();
               //echo "result:" . print_r($apiConnection->header);
                if ($apiConnection->httpStatus === 200) {
                    $nsFreeXml = $string = str_replace('<ns2:', '<', $apiConnection->result);
                    $nsFreeXml = $string = str_replace('</ns2:', '</', $nsFreeXml);

                    $nsFreeXml = simplexml_load_string($nsFreeXml);
                    //print_r($nsFreeXml);
                    $walmartReport->request_id = $nsFreeXml->feedId;
                    $walmartReport->processing_status = 'submitted';
                    $shippingTransaction->platform_updated_status = 'UPDATED';
                    $shippingTransaction->submission_id = $nsFreeXml->feedId;
                    $shippingTransaction->save();
                    $walmartReport->save();


                    $right_now = date("Y-m-d h:m:s");
                    $msg = "Updated Walmart with shipping info";
                    $query = "insert into messages set msg = '$msg', 
                                  msg_time = '$right_now'
                                , msg_from = 'AutoLoaded'
                                , msg_to = 'System'
                                , msg_type = 'shipping'
                                , invoice = '" . $shippingTransaction->invoice_num . "'";
                    $newMsg = DB::connection('mysql-walts2')->insert($query);

                    $invoicenum = $shippingTransaction->invoice_num;
                    $updateQuery = "update invoice set shipping_confirmed = 'YES' where invoicenum = '$invoicenum'";
                                    echo $updateQuery . "\n\r";
                                    DB::connection('mysql-walts2')->update($updateQuery); 

                    echo "Updated Walmart with shipping info\n\r";

                } else {

                    echo "error: invalid transaction the api reported the http status code of :" . $apiConnection->httpStatus . "\n\r";
                   }

            }


    }




    public function trackingUrl($trackingNumber, $shipCompany) {
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
            case "xpo" :
                $url = "https://app.ltl.xpo.com/appjs/tracking/#/tracking";
               break;
                default:
                 $url = "https://www.walts.com/tracking/";
        }
        return htmlspecialchars($url);
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


