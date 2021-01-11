<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetSearsShippingMethodListCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:get-sears-shipping-method-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the List Of Shipping Method List for use in Creating Shipping Methods';

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
       // https:// seller.marketplace.sears.com/SellerPortal/api/oms/shipping-carrier/v1?sellerId={sellerid}
        $apiCall = new searsAPIUtil();
        $apiCall->apiMethod = 'GET';
        $apiCall->apiUrl = "/oms/shipping-carrier/v1";
      //  echo $apiCall->apiUrl;

        // $apiCall->apiUrl = "/oms/shippingcarrier/v1";




        $apiCall->callApi();
      //  print_r($apiCall);
        $xml = simplexml_load_string($apiCall->result);
        print_r($xml);
       if(sizeof($xml)) {

            $json = json_encode($xml);
           $shipMethodArray = json_decode($json, true);

           foreach ($shipMethodArray["shipping-carrier-details"] as $shippingOption) {
              // print_r($shippingOption);
               if (isset($shippingOption["shipping-methods"]["shipping-method-details"][0])) {
                   // we have multiple shipping methods for the same carrier

                   foreach ($shippingOption["shipping-methods"]["shipping-method-details"] as $subOption) {
                      // print_r($shippingOption);
                       $option = new SearsShippingCarrier();

                       $option->shipping_carrier = $shippingOption["shipping-carrier"];
                       $option->shipping_carrier_description = $shippingOption["shipping-carrier-description"];

                       $option->shipping_method = $subOption["shipping-method"];
                       $option->shipping_method_description = $subOption["shipping-method-description"];
                       $option->unique_key = $option->shipping_carrier . "_" . $option->shipping_method;
                       try {
                           $option->save();
                       }
                       catch (PDOException $exception) {
                           // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                           // print_r($exception->getMessage());
                       }
                   }
               } else {
                   // only one shipping method for this carrier
                   $option = new SearsShippingCarrier();

                   $option->shipping_carrier = $shippingOption["shipping-carrier"];
                   $option->shipping_carrier_description = $shippingOption["shipping-carrier-description"];

                   $option->shipping_method = $shippingOption["shipping-methods"]["shipping-method-details"]["shipping-method"];
                   $option->shipping_method_description = $shippingOption["shipping-methods"]["shipping-method-details"]["shipping-method-description"];
                   $option->unique_key = $option->shipping_carrier . "_" . $option->shipping_method;
                   try {
                       $option->save();
                   }
                   catch (PDOException $exception) {
                       // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                       // print_r($exception->getMessage());
                   }

               }
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


}


