<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use League\Csv\Reader;
use League\Csv\Writer;




class UpdateShippingInformationForEbay extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-shipping-info-ebay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update shipping information Ebay';

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
    {  /////// NEW CODE AREA FOR PETER
        // ShippingTransaction::where('shipped_via_walts', '=', 1)->where('ship_company', '=', 'Walts')->where('partner', '=', 'platform')->get();
        // use invoice number as the trackingNumber and Other as shipping method

        $fulfilledItems = ShippingTransaction::where('platform_updated_status', '=', 'NOT_UPDATED')->where('fulfillment_shipment_status', '=', 'SHIPPED')->where('partner', '=', 'ebay')->where('status', '=', 1)->get();
        if(sizeof($fulfilledItems) > 0){
            $sentOrders = array();
            $query = "select * from ship_company";
            $shipCompanies = DB::connection('mysql-walts2')->select($query);
            $shippingMap = array();
            foreach($shipCompanies as $ship_company){
                $shippingMap[$ship_company->ship_company] = $ship_company->ebay_shipping_carrier_code;
            }

            foreach($fulfilledItems as $lineItem){
                print_r($lineItem->partner_order_number . "\n");
                $ebayOrder = OrdersEbay::where('sellingManagerSalesRecordNumber', '=', $lineItem->partner_order_number)->get();
                $ebayOrderLines = OrdersEbay::where('parentId', '=', $ebayOrder[0]['id'])->get();

                $shippingLineItemId = '';
                $shippingOrderId = '';

                if(sizeof($ebayOrderLines)){
                    print_r($ebayOrderLines);
                    echo "we have a multiline, we need to get the sku to match to ebays to get the lineItemId to gurantee we get the correct one \n";
                    //Description has the sku to compare with.
                    $apiCall = new ebayApiUtil();
                        $apiCall->apiUrl        = 'https://api.ebay.com/sell/fulfillment/v1/order/' . $ebayOrderLines[0]['orderLineItemID'];
                        $apiCall->apiMethod     = 'get';
                        $apiCall->contentType   = 'application/json';
                        $apiCall->oAuthRequired = true;
                        $apiCall->callApi();
                    $result = json_decode($apiCall->getResult());
                    // print_r($result);
                    // exit;
                    // Search the results to match for $ebayOrderLines[0]['description'] to $result->lineItems[0]->sku
                    foreach($result->lineItems as $lineItemToMatch){ 
                        if($lineItemToMatch->sku === $ebayOrderLines[0]['description']){
                            $shippingLineItemId = $lineItemToMatch->lineItemId;
                            $shippingOrderId = $result->orderId;
                        }

                    }
                }else{
                    //Double checking that we have this order and that it is a single line item to get lineItemId.
                    echo "in the else \n\n";
                    $apiCall = new ebayApiUtil();
                        $apiCall->apiUrl        = 'https://api.ebay.com/sell/fulfillment/v1/order/' . $ebayOrder[0]['purchaseOrderID'] ;
                        $apiCall->apiMethod     = 'get';
                        $apiCall->contentType   = 'application/json';
                        $apiCall->oAuthRequired = true;
                        $apiCall->callApi();

                    $result = json_decode($apiCall->getResult());
                    $shippingLineItemId = $result->lineItems[0]->lineItemId;
                    $shippingOrderId = $result->orderId;
                    // print_r($result);
                    // exit;
                    //PeterTODO:: May be able to check if item is already fulfilled 
                }

                $key = array_search($lineItem->ship_company, $shippingMap);
                if ($key !== false) {
                    $shipCompanyMapped = $shippingMap[$lineItem->ship_company];
                }else{
                    $shipCompanyMapped = "Other";
                }

                $time = gmdate("TH:i:s\Z");
                $time = substr($time, 3);
                
                //Create the shipping JSON from shipping_transaction
                $shippingInfo = array("lineItems"=>[ array(
                                            "lineItemId"=>$shippingLineItemId,
                                            "quantity"=>1
                                            )],
                                        "shippedDate"=> $lineItem->date_shipped . 'T' . $time,
                                        "shippingCarrierCode"=> $shipCompanyMapped,
                                        "trackingNumber"=>$lineItem->bol_tracking
                                    );
                $shippingInfo = json_encode($shippingInfo);
                print_r($shippingInfo);

                $status = '';
                //Update the shipping fulfillment for the order
                $apiCall = new ebayApiUtil();
                    $apiCall->apiUrl        = 'https://api.ebay.com/sell/fulfillment/v1/order/' . $shippingOrderId . '/shipping_fulfillment';
                    $apiCall->apiMethod     = 'post';
                    $apiCall->contentType   = 'application/json';
                    $apiCall->bodyData      = $shippingInfo;
                    $apiCall->oAuthRequired = true;
                $apiCall->callApi();
                $result = $apiCall->getResult();
                $returnHeaders = $apiCall->getHttpStatus(); //use to to check for http/1.1 201 created and grab location: as well.

                print_r($returnHeaders['http_code']);
                // print_r($returnHeaders);

                if($returnHeaders['http_code'] === 201){
                    $lineItem->user = "ebayAPI";
                    $lineItem->action = 'shippingUpdated';
                    $lineItem->platform_updated_status = 'UPDATED';
                    $lineItem->save();
                    $status = "success";
                    $right_now = date("Y-m-d h:m:s"); 
                    $msg = "Updated Ebay with shipping info";
                    $query = "insert into messages set msg = '$msg', 
                                  msg_time = '$right_now'
                                , msg_from = 'AutoLoaded'
                                , msg_to = 'System'
                                , msg_type = 'shipping'
                                , invoice = '" . $lineItem->invoice_num . "'";
                    $newMsg = DB::connection('mysql-walts2')->insert($query);

                    $invoicenum = $lineItem->invoice_num;
                    $updateQuery = "update invoice set shipping_confirmed = 'YES' where invoicenum = '$invoicenum'";
                                    echo $updateQuery . "\n\r";
                                    DB::connection('mysql-walts2')->update($updateQuery);

                }else{
                    //error stuff!
                    echo "Error in Upload";
                    print_r($result);
                    $status = "possible error " . $returnHeaders['http_code'];
                }
                $sentOrders[$lineItem->partner_order_number] = $status; 
            }

            if(sizeof($sentOrders) > 0){
                print_r($sentOrders);
            }else{
                echo "Nothing sent \n";
            }
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
    /* Relist Ended Item */
    
    /* ReviseFixedPriceItem */
    public function getXMLHead()
    {

    
     $eBayAuthTokenProduction = $this->eBayAuthTokenProduction = $_ENV['EBAY_TOKEN_PRODUCTION'];
     $eBayAuthTokenSandbox = $this->eBayAuthTokenSandbox = $_ENV['EBAY_TOKEN_SANDBOX'];

     
     $xmlHead = '<?xml version="1.0" encoding="utf-8"?>
     <ReviseFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
     <RequesterCredentials>
     <eBayAuthToken>';
    
     $xmlHead .= $eBayAuthTokenProduction;
     

     $xmlHead .= '</eBayAuthToken>
     </RequesterCredentials>
     <ErrorLanguage>en_US</ErrorLanguage>
     <WarningLevel>High</WarningLevel>';
     
        

       
     //print_r($xmlHead);
       return $xmlHead;
    }
    public function getXmlTail()
    {

     $xmlTail = '</ReviseFixedPriceItemRequest>';
     //print_r($xmlTail);
     return $xmlTail;
    }
    public function getXmlElement($ebayProduct)
    {
        $xmlElement = '<xml>heres some data</xml>';
                    
        return $xmlElement;     
    }
    /* End Item */
   

    function libxml_display_error($error)
    {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Warning $error->code</b>: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Error $error->code</b>: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Fatal Error $error->code</b>: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .= " in <b>$error->file</b>";
        }
        $return .= " on line <b>$error->line</b>\n";

        return $return;
    }

    function libxml_display_errors() {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            print $this->libxml_display_error($error);
        }
        libxml_clear_errors();
    }
}