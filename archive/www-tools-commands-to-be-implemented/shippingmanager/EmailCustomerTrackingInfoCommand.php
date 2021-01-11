<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EmailCustomerTrackingInfoCommand extends Command
{
    protected $name = 'command:email-customer-tracking-info';
    protected $description = 'Email tracking info to customers';
    public function __construct()
    {
        parent::__construct();
    }

    public function fire() {
        echo "Program ran.\n";
        // get all orders that have been shipped but not emailed
        $shippedOrders = ShippingTransaction::where('fulfillment_shipment_status', '=', 'SHIPPED')->where('customer_tracking_sent', '=', 'NOT_EMAILED')->get();
        //$shippedOrders = ShippingTransaction::where('invoice_num', '=', 199144)->get();
        //dd($shippedOrders);
        $groupedByTracking = array();

        foreach ($shippedOrders as $order) {    // combine invoices by tracking so we don't send multiple emails for the same invoice/tracking number
            $tracking = $order['bol_tracking'];   
            $groupedByTracking[$tracking] = $order; 
        }
        date_default_timezone_set('America/Phoenix');
        $right_now = date("Y-m-d h:m:s");
        foreach ($groupedByTracking as $item) {
            $msg = "Shipping info email sent to customer";
            if(!Swift_Validate::email($item['customer_email'])){ //if email is not valid
                $msg = "**ERROR** Send tracking email failed!  Bad email address!";
            } else {
                $this->sendTrackingInfo($item);
            }  

            DB::table('shipping_transactions')->where('invoice_num', $item['invoice_num'])->where('bol_tracking', $item['bol_tracking'])->update(array('customer_tracking_sent' => 'EMAILED'));
            DB::table('orders_amazon_fulfillment')->where('InvoiceNumber', $item['invoice_num'])->update(array('status' => 'CUSTOMER_EMAILED'));
            $query = "insert into messages set msg = '$msg', 
                        msg_time = '$right_now'
                      , msg_from = 'AutoLoaded'
                      , msg_to = 'System'
                      , msg_type = 'shipping'
                      , invoice = '" . $item['invoice_num'] . "'";
            $newTransaction = DB::connection('mysql-walts2')->insert($query);
            echo $query . "\n\r";  

        }

    }

    //################################################### FUNCTIONS #####################################################


    function sendTrackingInfo($shipped_item) {

        $subjectLine = 'Shipping Info from Walts TV & Home Theater!'; 
        if ($shipped_item->partner == 'walmart.com') {       
          $subjectLine = "[Important] " . $subjectLine;  // walmart will only relay messages if they have [Important] on the subject line
        }         
        $customer_city = $shipped_item['customer_city'] . " " . $shipped_item['customer_state'] . ", " . $shipped_item['customer_zip'];
        $data = array('customer_name' => ucwords($shipped_item['customer_name']),
                    'customer_address' => ucwords($shipped_item['customer_address']),
                    'customer_city' => ucwords($shipped_item['customer_city']),
                    'customer_phone' => ucwords($shipped_item['customer_phone']),
                    'customer_email' => $shipped_item['customer_email'],
                    'ship_company' => $shipped_item['ship_company'],
                    'partner_order_number' => $shipped_item['partner_order_number'],
                    'invoice_num' => $shipped_item['invoice_num'],
                    'subjectLine' => $subjectLine
                );

        Mail::send('admin.shippingEmail', $data, function($message) use ($data) {
            $message->to($data['customer_email'], $data['customer_name'])
            //->cc('asmock@walts.com', 'Allison Smock')
            //->cc('tjones@walts.com', 'Troy Jones')
            ->subject($data['subjectLine']);
        }); 
        echo "sent\n";

    }


    // function sendBadEmailAddressNotice($shipped_item) {
        

    //     $data = array('customer_name' => $shipped_items[0]['customer_name'],
    //                 'customer_address' => $shipped_items[0]['customer_address'],
    //                 'customer_city' => $shipped_items[0]['customer_city'],
    //                 'customer_state' => $shipped_items[0]['customer_state'],
    //                 'customer_zip' => $shipped_items[0]['customer_zip'],
    //                 'customer_email' => $customer_email
    //             );
    //     foreach ($shipped_items as $item) {
    //         $data['items'][] = $item;
    //     }

    //     Mail::send('admin.shippingEmail', $data, function($message) use ($data) {
    //         $message->to('asmock@walts.com', 'Allison Smock')
    //         //$message->to('tjones@walts.com', 'Troy Jones')
    //         ->subject('Bad Shipping Email Address: ' . $data['customer_email']);
    //     }); 
    //     echo "sent\n";

    // }


}


