<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateShippingInformationForNeweggBusinessOrdersCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-shipping-information-for-newegg-business-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Tracking Information To Newegg Business for Orders';

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

        $fulfillmentInfoItems = array();
        $fulfilledItems = ShippingTransaction::where('platform_updated_status', '=', 'NOT_UPDATED')->where('fulfillment_shipment_status', '=', 'SHIPPED')->where('partner', '=', 'newegg_business')->where('status', '=', 1)->get();
        //$fulfilledItems = ShippingTransaction::where('invoice_num', '=', 195657)->get();
        //dd($fulfilledItems);
        if(sizeof($fulfilledItems)) {
            echo "We have Shipments that have not been sent \n\r";
            $header_array =array('Content-Type:application/xml',
               'Accept:application/xml',
               'Authorization: 677c46162b58d0294776e60c205dce2a',
               'SecretKey: fe6706b3-0ce0-44fe-bb1d-ac58d0cf2450');  
            $groupedByInvoice = array();
            foreach ($fulfilledItems as $item) {    // we have to combine items based on invoice
                $order = OrdersNeweggBusiness::where('OrderNumber', '=', $item['partner_order_number'])->where('SellerPartNumber', '=', $item['description'])->get();
                if(sizeof($order)) {
                    $item['NeweggItemNumber'] = $order[0]['NeweggItemNumber'];
                    $invoice = $item['invoice_num'];   
                    $groupedByInvoice[$invoice][] = $item;                     
                } else {
                    echo "No matching order found in Newegg Orders Table\n\r";
                }

            }

            foreach ($groupedByInvoice as $shipment) {
                $msg = "";
                if($shipment[0]['NeweggItemNumber']) {  // means we successfully did a lookup in the above code so should be good
                    date_default_timezone_set('America/Phoenix');
                    $right_now = date("Y-m-d h:m:s");                    
                    $orderXml = $this->getOrderRequestXml($shipment);    

                    $request = "https://api.newegg.com/marketplace/b2b/ordermgmt/orderstatus/orders/". $shipment[0]['partner_order_number'] . "?sellerid=V17E";

                    $header_array = array('Content-Type:application/xml',
                        'Accept:application/xml',
                        'Authorization: 677c46162b58d0294776e60c205dce2a',
                        'SecretKey: fe6706b3-0ce0-44fe-bb1d-ac58d0cf2450');

                    $session = curl_init($request);

                    $putString = stripslashes($orderXml);
                    $putData = tmpfile();
                    fwrite($putData, $putString);
                    fseek($putData, 0);

                    curl_setopt($session, CURLOPT_HEADER, 1);
                    curl_setopt($session, CURLOPT_HTTPHEADER, $header_array);
                    curl_setopt($session, CURLOPT_PUT, true);
                    curl_setopt($session, CURLOPT_HEADER, false);
                    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($session, CURLOPT_POSTFIELDS, $orderXml);
                    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($session, CURLOPT_INFILE, $putData);
                    curl_setopt($session, CURLOPT_INFILESIZE, strlen($putString));
                    $response = curl_exec($session);

                    $xml = simplexml_load_string($response, null, LIBXML_NOCDATA);

                    print_r($xml);

                    // if successful update
                    if ($xml->IsSuccess == 'true') {
                        echo "success\n\r";
                        $msg = "Updated Newegg Business with shipping info";
                        DB::table('shipping_transactions')->where('invoice_num', $shipment[0]->invoice_num)->where('bol_tracking', $shipment[0]['bol_tracking'])->update(array('platform_updated_status' => 'UPDATED', 'updated_at' => $right_now, 'user' => 'Newegg Business Batch Run API'));                        
                        $invoicenum = $shipment[0]->invoice_num;
                        $updateQuery = "update invoice set shipping_confirmed = 'YES' where invoicenum = '$invoicenum'";
                                        echo $updateQuery . "\n\r";
                                        DB::connection('mysql-walts2')->update($updateQuery);                     
                    } else {
                        echo "fail\n\r";
                        $msg = "ERROR: " . $xml->Message;
                        DB::table('shipping_transactions')->where('invoice_num', $shipment[0]->invoice_num)->where('bol_tracking', $shipment[0]['bol_tracking'])->update(array('platform_updated_status' => 'SHIPPING CONFIRMATION ERROR', 'updated_at' => $right_now, 'user' => 'Newegg Business Batch Run API'));                        
                    }   
                    //die;                
                    $query = "insert into messages set msg = '$msg', 
                                msg_time = '$right_now'
                              , msg_from = 'AutoLoaded'
                              , msg_to = 'System'
                              , msg_type = 'shipping'
                              , invoice = '" . $shipment[0]->invoice_num . "'";
                    $newTransaction = DB::connection('mysql-walts2')->insert($query);
                        //$response = $this->sendShippingInfo($xml);

                    

                } else {
                    echo "No matching order found in Newegg Business Orders Table\n\r";
                }

            }
        } else {
            echo "No shipments waiting for processing.\n\r";
        }


    }


function getOrderRequestXml($shipment) {

    $xml = '
        <UpdateOrderStatus>
            <Action>2</Action>
            <Value>
                <Shipment>
                    <Header>
                        <SellerID>V17E</SellerID>
                        <SONumber>' . $shipment[0]['partner_order_number'] . '</SONumber>
                    </Header>';
                       $xml .= $this->createPackageList($shipment);
                $xml .= '
                </Shipment>
            </Value>
        </UpdateOrderStatus>';       
      
    return $xml;

}

function createPackageList($shipment) {
    $TrackingNumbers = array();
    $itemCount = count($shipment);
    foreach ($shipment as $packages) {    // we have to combine items based on tracking number and item
        $trackingItem = $packages['bol_tracking'];   
        $TrackingNumbers[$trackingItem][] = $packages; 
    }                
    // now we have items of the invoice grouped by tracking number and item count
    $packageList = '
                        <PackageList>
                            ';
    foreach ($TrackingNumbers as $package) {
        $ship_service = "ground";
        if (($package[0]['ship_method'] == 'SHIPPING_SMALL_STANDARD') || ($package[0]['ship_method'] == 'SHIPPING_SMALL_2DAY') || ($package[0]['ship_method'] == 'SHIPPING_SMALL_1DAY')) { 
            $ship_service = "air";
        }
        if (($package[0]['bol_tracking'] == $package[0]['invoice_num']) && ($package[0]['shipped_via_walts'])) {

            $packageList .= '<Package>
                                    <TrackingNumber>' . $package[0]['invoice_num'] . '</TrackingNumber>
                                    <ShipCarrier>Other</ShipCarrier>
                                    <ShipService>Standard</ShipService>';                          
                                $packageList .= $this->createItemList($package); 
        } else {
            $packageList .= '<Package>
                                    <TrackingNumber>' . $package[0]['bol_tracking'] . '</TrackingNumber>
                                    <ShipCarrier>' . $package[0]['ship_company'] . '</ShipCarrier>
                                    <ShipService>' . $ship_service . '</ShipService>';                          
                                $packageList .= $this->createItemList($package);             
        }
        $packageList .= '
                            </Package>
                        ';
    }

    $packageList .= '
                        </PackageList>';

    return $packageList;
}


function createItemList($items) {

    $uniqueItemsArray = array();    // to compare against previously added items
    $uniqueItems = array();         // final structure
    foreach ($items as $item) {    // we have to combine items based on our seller part number or (description)
        $itemsku = $item['description'];   

        if ( ! in_array( $itemsku, $uniqueItemsArray, true ) ) {
            $uniqueItems[$itemsku] = $item;
            $uniqueItems[$itemsku]['count'] = 1; 
            $uniqueItemsArray[] = $itemsku;    
        } else {
            $uniqueItems[$itemsku]['count'] = $uniqueItems[$itemsku]['count'] + 1;
        }

    }                
    // now we have items of the invoice grouped by sku
    $itemList = '
                                <ItemList>';                          
    foreach ($uniqueItems as $obj) {
        //dd($obj);
        $itemList .= '
                                    <Item>
                                        <SellerPartNumber>' . $obj['description'] . '</SellerPartNumber>
                                        <NeweggItemNumber>' . $obj['NeweggItemNumber'] . '</NeweggItemNumber>
                                        <ShippedQty>' . $obj['count'] . '</ShippedQty>
                                    </Item>';
    }

    $itemList .= '
                                </ItemList>';

    return $itemList;
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


