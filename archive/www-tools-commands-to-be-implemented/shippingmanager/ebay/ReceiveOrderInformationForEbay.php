<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use League\Csv\Reader;
use League\Csv\Writer;




class ReceiveOrderInformationForEbay extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:receive-order-info-ebay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Orders from ebay and receive the details';

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

        $apiCall = new ebayApiUtil();
            $apiCall->apiUrl        = 'https://api.ebay.com/sell/fulfillment/v1/order?filter=creationdate:%5B2017-11-21T15:05:43.026Z..%5D&limit=50&offset=0';
            $apiCall->apiMethod     = 'get';
            $apiCall->contentType   = 'application/json';
            $apiCall->oAuthRequired = true;
            $apiCall->callApi();
        $result = json_decode($apiCall->getResult());

        $httpHeaders = $apiCall->getHttpStatus();
        print_r($httpHeaders);
        print_r($httpHeaders['http_code']);

        //print_r($result);

        foreach($result->orders as $order){
            // print_r($order);
            
            // print_r('orderFulfillmentStatus: ' . $order->orderFulfillmentStatus . "\n");
            // print_r('orderPaymentStatus: ' . $order->orderPaymentStatus . "\n");
            // print_r('cancelState: ' . $order->cancelStatus->cancelState . "\n");

            // $orderAlreadyImported = OrdersEbay::where('extendedOrderID', '=', $order->orderId)->get();
            // print_r($orderAlreadyImported[0]->extendedOrderID . "\n");
            // print_r($order->orderId . "\n");
            // foreach($order->lineItems as $item){
            //     print_r("SKU: " . $item->sku . " with Qty: " . $item->quantity . "\n"); 
            // }
            // //print_r($order->orderId . "\n\n\n\n\n");

            // //$order->fulfillmentHrefs // ebay giving us the url to check information for shipping_fulfillment
            // foreach($order->fulfillmentHrefs as $fulfillmentHref){
            //     print_r("Fullfullment URL: " . $fulfillmentHref  . "\n");
            // }
            //print_r( "------End Order----- \n\n");

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