<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class SubmitJetOrderCancelCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:submit-jet-shipping-cancel-information';

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

        $apiConnection = new jetAPIUtil();

        $apiConnection->apiMethod = "PUT";
        $cancelledOrder = OrdersJet::where('cancel_status', '=', 'cancel')->where('export_status', '!=', '_CANCELLED_')->get();

        foreach($cancelledOrder as $shipLine) {

            date_default_timezone_set('America/Phoenix');
            $right_now = date("Y-m-d h:m:s");
            $msg = "";

            $shippingJSON = $this->getItemShippingLineJsonCancel($shipLine, $shipLine->order_items__merchant_sku, $shipLine->order_items__request_order_quantity);

            $apiConnection->apiUrl = '/api/orders/' . $shipLine->purchase_order_id . '/shipped';
            echo $apiConnection->apiUrl . "\n\r";
            $apiConnection->jsonPayload = $shippingJSON;
            echo $apiConnection->jsonPayload . "\n\r";
            $apiConnection->put();
            echo $apiConnection->httpStatus . "\n\r";
            echo $apiConnection->result;
            if ($apiConnection->httpStatus == '204') {
                $shipLine->export_status = '_CANCELLED_';
                $shipLine->save();

            // change POS status to Do Not Ship

                $part_ord_num = $shipLine->purchase_order_id;

                $query = "SELECT invoicenum FROM invoice WHERE partner_order_number = '$part_ord_num'";
                $invoice = DB::connection('mysql-walts2')->select($query); 
                $invoicenum = $invoice[0]->invoicenum;
                $query = "UPDATE invoice SET order_status = 'Do Not Ship' WHERE partner_order_number = '$part_ord_num' and invoicenum = '$invoicenum'";
                DB::connection('mysql-walts2')->update($query);
                $order_item_id = $shipLine->order_items__order_item_id;
                DB::table('shipping_transactions')->where('unique_item_id', $order_item_id)->update(array('order_status' => 'Do Not Ship', 'action' => 'Order cancelled via API', 'user' => 'Jet Batch API', 'updated_at' => $right_now));

            // email Allison/Shipping
                $data = array('Error' => 'This order or an item from this order was cancelled.');
                $data['invoice'] = $invoice[0]->invoicenum;
                Mail::send('admin.defaultEmail', $data, function($message) use ($data) {
                    //$message->to('tjones@walts.com', 'Troy Jones')
                    $message->to('asmock@walts.com', 'Allison Smock')
                    ->cc('shipping@walts.com', 'Walts Shipping')
                    ->subject("Jet Order Cancelled : Invoice = " . $data['invoice']);
                }); 


            // insert message into messages table
                $msg = "This order or an item from this order was cancelled.";                 
                $query = "insert into messages set msg = '$msg', 
                            msg_time = '$right_now'
                            , msg_from = 'AutoLoaded'
                            , msg_to = 'System'
                            , msg_type = 'shipping'
                            , invoice = '" . $invoice[0]->invoicenum . "'";                  
                $newTransaction = DB::connection('mysql-walts2')->insert($query);

                echo "success\n\r";
            } else {
                $shipLine->export_status = '_ERROR_';
                $shipLine->save();
                echo "fail\n\r";
            }
            sleep(1);
        }

    }

    public function getItemShippingLineJsonCancel($shipLine, $merchant_sku, $qty){
        return '{
                    "shipments": [{
                        "alt_shipment_id": "'.  $shipLine->id . '",
                        "shipment_items": [
                            {
                            "merchant_sku": "'. $merchant_sku . '",
                            "response_shipment_cancel_qty": '. $qty . '
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


