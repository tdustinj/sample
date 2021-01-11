<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class SubmitJetOrderShippingInformationCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:submit-jet-shipping-information';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit Jet Order Shipping Updates';

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
       // https://merchant-api.jet.com/api/orders/{jet_defined_order_id}/shipped
        if(App::environment('production')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://cronitor.link/P5QGIx/run");
            $response = curl_exec($curl);
        }

        $apiConnection = new jetAPIUtil();
        // contains recompiled orders

        $apiConnection->apiMethod = "PUT";
        $shipmentList = ShippingTransaction::where('platform_updated_status', '=', 'NOT_UPDATED')->where('fulfillment_shipment_status', '=', 'SHIPPED')->where('partner', '=', 'jet')->get();

        foreach($shipmentList as $shipLine) {
            //$cleanedOrderId = explode("-!-", $shipLine->partner_order_number);
            // echo  "\n\r" . "Shipping order " . print_r($order) ;
            //print_r($shipLine); die;
            date_default_timezone_set('America/Phoenix');
            $right_now = date("Y-m-d h:m:s");
            $msg = "";
            $unique_item_id = explode("-", $shipLine->unique_item_id, 2);
            $unique_item_id = $unique_item_id[0];
            $orderInfo = OrdersJet::where('order_items__order_item_id', '=', $unique_item_id)->get();
            // print_r($orderInfo);
            //$shippingJSON = '{"shipments": [';  // , $orderInfo[0]->order_detail__request_shipping_carrier
            // $shippingJSON = $this->getItemShippingLineJson($shipLine, $orderInfo[0]->order_items__merchant_sku, $orderInfo[0]->order_items__request_order_quantity);
            $shippingJSON = $this->getItemShippingLineJson($shipLine, $orderInfo[0]->order_items__merchant_sku, 1);
            //$shippingJSON .= ']}';
            print_r($shippingJSON);
            // exit;
            $apiConnection->apiUrl = '/api/orders/' . $orderInfo[0]->purchase_order_id .'/shipped';
            // $apiConnection->apiUrl = '/api/orders/' . $shipLine->partner_order_number . '/shipped';
            echo $apiConnection->apiUrl . "\n\r";
            // exit;
            $apiConnection->jsonPayload = $shippingJSON;
            echo $apiConnection->jsonPayload . "\n\r";

            $apiConnection->put();
            echo $apiConnection->httpStatus . "\n\r";
            echo $apiConnection->result;
            if ($apiConnection->httpStatus == '204') {
                //$shipLine->platform_updated_status = 'UPDATED';
                //$shipLine->save();
                echo "success\n\r";
                        
                DB::table('shipping_transactions')->where('invoice_num', $shipLine->invoice_num)->where('bol_tracking', $shipLine->bol_tracking)->update(array('platform_updated_status' => 'UPDATED', 'updated_at' => $right_now, 'user' => 'Jet Batch Run API'));  
                $msg = "Updated Jet with shipping info";

            } else {
                //echo "Error ack order $shipLine->partner_order_number" . ' ' . $apiConnection->httpStatus . "\n\r";
                //$shipLine->status = 'Error Sending Shipping Information to Jet';
               // $shipLine->save();
                echo "fail\n\r";
                $msg = 'Error Sending Shipping Information to Jet';
                DB::table('shipping_transactions')->where('invoice_num', $shipLine->invoice_num)->where('bol_tracking', $shipLine->bol_tracking)->update(array('platform_updated_status' => 'SHIPPING CONFIRMATION ERROR', 'updated_at' => $right_now, 'user' => 'Jet Batch Run API'));

                if(App::environment('production')) {
                    $data = "Error with invoice: " . $shipLine->invoice_num;
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, "https://cronitor.link/P5QGIx/fail?msg=" . $data);
                    $response = curl_exec($curl);
                }   
            }
                $query = "insert into messages set msg = '$msg', 
                            msg_time = '$right_now'
                            , msg_from = 'AutoLoaded'
                            , msg_to = 'System'
                            , msg_type = 'shipping'
                            , invoice = '" . $shipLine->invoice_num . "'";                  
                $newTransaction = DB::connection('mysql-walts2')->insert($query);
            echo $orderInfo[0]->order_items__merchant_sku;
            sleep(1);
        }
        // --data-urlencode

        if(App::environment('production')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://cronitor.link/P5QGIx/complete");
            $response = curl_exec($curl);
        }
    }

    public function getItemShippingLineJson($shipLine, $merchant_sku, $qty){


        $jetShipCompanies = array('FedEx', 'FedEx SmartPost', 'FedEx Freight', 'UPS', 'UPS Freight', 'UPS Mail Innovations', 'UPS SurePost', 'OnTrac', 'OnTrac Direct Post', 'DHL', 'DHL Global Mail', 
        'USPS', 'CEVA', 'Laser Ship', 'LaserShip', 'Spee Dee', 'A&M Trucking', 'A Duie Pyle', 'A1', 'ABF', 'APEX', 'Averitt', 'Dynamex', 'Eastern Connection', 'Ensenda', 'Estes', 'GSO', 'Land Air Express', 
        'Lone Star', 'Meyer', 'New Penn', 'Pilot', 'Prestige', 'RBF', 'Reddaway', 'RL Carriers', 'Roadrunner', 'SAIA Freight', 'Southeastern Freight', 'UDS', 'UES', 'YRC', 'GSO', 'A&M Trucking', 'Other', 
        'Old Dominion', 'Parcel', 'ConveyDecisioning', 'JetExpress', 'Bekins / Home Direct', 'Seko Worldwide', 'Mail Express', 'Dynamex', 'Newgistics', 'Delivered by Walmart', 'NonstopDelivery', 'MPX', 
        'Cagney Global', 'Simmons Carrier', 'DeliveryCourier', 'AGS', 'Watkins & Shepard', 'WN Direct', 'Royal Mail International', 'Doorman');
        $ship_company = $shipLine->ship_company;
        if (!in_array($ship_company, $jetShipCompanies)) {
            $ship_company = "Other";
        }


        return '{
                    "shipments": [{
                        "alt_shipment_id": "'.  $shipLine->id . '",
                        "shipment_tracking_number": "'.  $shipLine->bol_tracking . '",
                        "response_shipment_date": "'.  date('Y-m-d\TH:i:s', strtotime($shipLine->ship_timestamp)) . '.0000000-07:00' . '",
                        "response_shipment_method": "'.  $shipLine->ship_method . '",
                        "expected_delivery_date": "'.  date('Y-m-d\TH:i:s', strtotime($shipLine->expected_eta)). '.0000000-07:00' . '",
                        "ship_from_zip_code": "85286",
                        "carrier_pick_up_date": "'.  date('Y-m-d\TH:i:s', strtotime($shipLine->ship_timestamp)). '.0000000-07:00' . '",
                        "carrier": "'. $ship_company . '",
                        "shipment_items": [
                            {
                            "alt_shipment_item_id": "'.  $shipLine->unique_item_id . '",
                            "merchant_sku": "'. $merchant_sku . '",
                            "response_shipment_sku_quantity": '. $qty . '
                        }]
                    }]
                }';
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


