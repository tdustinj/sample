<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateShippingTransactionsWithTrackingFromIngramDropShipOrdersCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-shipping-transactions-with-tracking-from-mingram-drop-ship-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Shipping Transactions with drop ship ingram tracking information';

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
        date_default_timezone_set('America/Phoenix');
        $right_now = date("Y-m-d h:m:s");
        $date_shipped = date("Y-m-d");
        // Fetch SHIPPED MCO's and update shipping_transactions_table
        $shippedOrders = OrdersIngramMicroFulfillmentnt::where('ingram_ship_status', '=', 'ship_complete')->where('shipping_transactions_table', '!=', 'UPDATED')->get();
        if (sizeof($shippedOrders)) {

            echo "Number up Orders to Update " . sizeof($shippedOrders) . "\n\r";
            foreach ($shippedOrders as $order) {
                $shippingTransaction = ShippingTransaction::where('unique_item_id', '=', $order->TransactionID)->where('fullfillment_po_number', '=', $order->CustomerPO)->get();
                $order->shipping_transactions_table = "UPDATED";
               // echo "Have a Match for " . $order->CustomerPO . "\n\r";
                //print_r($shippingTransaction);
                if (sizeof($shippingTransaction) == 1) {
                    $shippingCompany = 'NA';
                    if($order->ShippingCarrier == 'AIRD Next Day Air'){
                        $shippingCompany = "AGS";
                    }
                    else {
                        $shippingCompany = $order->ShippingCarrier;
                    }
                    if ($order->ShippingTrackingNumber != $shippingTransaction[0]->bol_tracking OR strtoupper($order->ShippingCarrier) != strtoupper($shippingCompany)) {
                        echo "Need To update Order on Invoice   " . $shippingTransaction[0]->invoice_num . "\n\r";
                        echo "From " . $shippingTransaction[0]->bol_tracking . "  To " . $order->ShippingTrackingNumber . "\n\r";
                        echo "From " . $shippingTransaction[0]->ship_company . "  To " . $order->ShippingCarrier . "\n\r";
                        $shippingTransaction[0]->bol_tracking = $order->ShippingTrackingNumber;

                        if($order->ShippingCarrier == 'AIRD Next Day Air') {
                            $shippingTransaction[0]->ship_company = "AGS";
                        } elseif ($order->ShippingCarrier != 'NOT PROVIDED IN ASN') {
                              $shippingTransaction[0]->ship_company = $order->ShippingCarrier;
                        }
                        // if($shippingTransaction[0]->ship_company != '') {
                        //     $shippingTransaction[0]->fulfillment_shipment_status = 'SHIPPED';
                        //     $shippingTransaction[0]->order_status = 'Shipped';                                                        
                        // }   
                        $shippingTransaction[0]->fulfillment_shipment_status = 'SHIPPED';
                        $shippingTransaction[0]->order_status = 'Shipped';  
                        date_default_timezone_set('America/Phoenix');
                        $shipdate = date("Y-m-d"); 
                        $datetime1 = new DateTime($shipdate);
                        $ship_timestamp = gmdate("Y-m-d h:m:s");  
                        $expected_eta = date('Y-m-d', strtotime($ship_timestamp. ' + 3 days'));
                        $actual_eta = date('Y-m-d', strtotime($ship_timestamp. ' + 4 days'));

                        $shippingTransaction[0]->ship_timestamp = $ship_timestamp; 

                        $shippingTransaction[0]->distributor_invoice_num = $order->ingram_invoice_num;
                        $shippingTransaction[0]->date_shipped = $order->ShipDate;
                        $shippingTransaction[0]->expected_eta = $expected_eta;
                        $shippingTransaction[0]->actual_eta = $actual_eta;
                        $shippingTransaction[0]->user = "Ingram Batch Run API";
                        // $shippingTransaction[0]->amazon_shipment_id = $order->amazon_shipment_id;
                        $msg = "Your Order Has Shipped " . $order->EndUserPO;
                        $query = "insert into messages set msg = '$msg',
                                      msg_time = '$right_now'
                                    , msg_from = 'AutoLoaded'
                                    , msg_to = 'System'
                                    , msg_type = 'shipping'
                                    , invoice = '" . $shippingTransaction[0]->invoice_num . "'";
                          $newTransaction = DB::connection('mysql-walts2')->insert($query);
                       // echo $query . "\n\r";
                        $shippingTransaction[0]->platform_updated_status = "NOT_UPDATED";
                        $shippingTransaction[0]->customer_tracking_sent = "NOT_EMAILED";
                        $shippingTransaction[0]->save();
                        $order->shipping_transactions_table = "UPDATED";
                        $order->save();
                    } else {
                        echo "Order already updated \n\r";
                        $order->shipping_transactions_table = "UPDATED";
                        $order->save();

                    }

                }
                else{
                    echo "No Matching Shipping Transaction Found for unique_item_id = " . $order->TransactionID . "\n\r";
                    // Lets See if we can find a duplicate at this point
                    $possibleDupes = OrdersIngramMicroFulfillmentnt::where('EndUserPO', '=', $order->EndUserPO)->get();
                    foreach($possibleDupes as $dupOrder){
                       echo "Possible Dup for Order Enduser PO =" . $dupOrder->EndUserPO . "\n\r";
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


