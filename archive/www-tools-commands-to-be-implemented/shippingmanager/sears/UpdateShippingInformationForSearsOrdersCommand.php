<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateShippingInformationForSearsOrdersCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-shipping-information-for-sears-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Tracking Information To Sears for Orders';

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

        $today = new DateTime();
        $today->modify('-7 day');
        $fromDate = $today->format('Y-m-d');
        $toDate = date("Y-m-d");
       //https://seller.marketplace.sears.com/SellerPortal/api/oms/asn/v7?sellerId={sellerId}

        $fulfillmentInfoItems = array();
        $fulfilledItems = ShippingTransaction::where('platform_updated_status', '=', 'NOT_UPDATED')->where('fulfillment_shipment_status', '=', 'SHIPPED')->where('partner', '=', 'sears')->orderBy('id', 'DESC')->where('status', '=', 1)->get();
        print_r($fulfilledItems);
        if(sizeof($fulfilledItems)){

           $xmlPayload = '<?xml version="1.0" encoding="UTF-8"?>
<shipment-feed xmlns="http://seller.marketplace.sears.com/oms/v7"
               xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
               xsi:schemaLocation="http://seller.marketplace.sears.com/oms/v7 asn.xsd ">';
          foreach($fulfilledItems as $item) {
              $now = new DateTime($item->created_at);
              $shipdate = $now->format("Y-m-d");
              echo "shipDTE" . $shipdate;
              $shipInfo = $this->getCarrierInfo($item->ship_company, $item->ship_method);
              $orderLineInfo = $this->getOrderLine($item->unique_item_id, $item->partner_order_number);
              $shipCompany = $shipInfo["shipCompany"];
              $shipMethod = $shipInfo["shipMethod"];
              if (($item->bol_tracking == $item->invoice_num) && ($item->shipped_via_walts)) {
                    $shipCompany = "Other";
                    $shipMethod = "Standard";
              }
              if($orderLineInfo != false) {
                  $xmlPayload .= '<shipment>
        <header>
            <asn-number>' . $item->id . '</asn-number>
            <po-number>' . $item->partner_order_number . '</po-number>
            <po-date>' . $orderLineInfo->po_date . '</po-date>
        </header>
        <detail>
            <tracking-number>' . $item->bol_tracking . '</tracking-number>
            <ship-date>' . $shipdate . '</ship-date>
            <shipping-carrier>' . $shipCompany . '</shipping-carrier>
            <shipping-method>' . $shipMethod . '</shipping-method>
            <package-detail>
                <line-number>' . $orderLineInfo->po_line_po_line_header_line_number . '</line-number>
                <item-id>' . $orderLineInfo->po_line_po_line_header_item_id . '</item-id>
                <quantity>1</quantity>
            </package-detail>
        </detail>
    </shipment>';
                  $item->platform_updated_status = 'UPDATED';
              }
              else{
                  echo "No OrderLine Found for " . $item->partner_order_number . "\n\r";
                  $item->platform_updated_status = 'Error';
              }
             }
             $xmlPayload .= '</shipment-feed>';

            $apiCall = new searsAPIUtil();
            $apiCall->apiMethod = 'PUT';
            $apiCall->apiUrl = "/oms/asn/v7";

            $apiCall->xmlPayload = $xmlPayload;
            $feedHandle = @fopen('php://memory', 'rw+');
            fwrite($feedHandle, $xmlPayload);
            rewind($feedHandle);


            $apiCall->xmlPayload = $xmlPayload;
            $apiCall->contentLength = strlen($apiCall->xmlPayload);
            $apiCall->feedHandle = $feedHandle;
            $apiCall->callApi();
            print_r($xmlPayload);

            $xml = simplexml_load_string($apiCall->result);
            print_r($xml);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);

            $docId = $array['document-id'];
            if($docId != '') {
                $searsSubmissionReport = new SearsReport();
                $searsSubmissionReport->report_type = 'Shipping ASN';
                $searsSubmissionReport->report_status = 'Sent';
                $searsSubmissionReport->report_id = $docId;
                $searsSubmissionReport->request_body = $xmlPayload;
                $searsSubmissionReport->save();

                foreach ($fulfilledItems as $item) {
                    // we need to update shipping_transactions
                    $item->platform_updated_status = 'UPDATED';
                    $item->submission_id = $docId;

                    $item->save();

                    $right_now = date("Y-m-d h:m:s");
                    $msg = "Updated Sears with shipping info";
                    $query = "insert into messages set msg = '$msg', 
                                  msg_time = '$right_now'
                                , msg_from = 'AutoLoaded'
                                , msg_to = 'System'
                                , msg_type = 'shipping'
                                , invoice = '" . $item->invoice_num . "'";
                    $newMsg = DB::connection('mysql-walts2')->insert($query);

                    $invoicenum = $item->invoice_num;
                    $updateQuery = "update invoice set shipping_confirmed = 'YES' where invoicenum = '$invoicenum'";
                                    echo $updateQuery . "\n\r";
                                    DB::connection('mysql-walts2')->update($updateQuery); 

                }
            }
    }



    }

private function getCarrierInfo($shipCompany, $shipMethod){
    $shippingInfo = array('shipCompany'=> 'OTH', 'shipMethod' => 'Standard');
        $combinedShip = $shipCompany . '-' . $shipMethod;
    $shippingMap = SearsShippingCarrier::where('shipping_trans_action_method', '=', $combinedShip)->get();
    if(sizeof($shippingMap)){
         $shippingInfo["shipCompany"] = $shippingMap[0]->shipping_carrier;
         $shippingInfo["shipMethod"] = $shippingMap[0]->shipping_method;
    }
    return $shippingInfo;
}

private function getOrderLine($order_id_line_id_key, $po_number){

     $orderLine = OrdersSears::where('order_id_line_id_key', '=', $order_id_line_id_key)->where('po_number', '=',$po_number )->get();
    // echo $order_id_line_id_key . " " . $po_number . "\n\r";
     //print_r($orderLine->id);
     // if(isset($orderLine[0]->id)) {
     //     return $orderLine[0];
     // }
     // else{
     //     return false;
     // }
     // CHANGED ABOVE CODE TO THIS BECAUSE CMD WAS BOMBING ON EMPTY DATA SET - TROY 3/28/18
     if(sizeof($orderLine)) {
         return $orderLine[0];
     }
     else{
         return false;
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


