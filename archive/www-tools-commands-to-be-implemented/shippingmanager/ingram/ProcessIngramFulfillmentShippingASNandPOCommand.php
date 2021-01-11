<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProcessIngramFulfillmentShippingASNandPOCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:process-ingram-fulfillment-shipping-asn-and-po-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Ingram Orders to Original Platform';

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

        $unprocessedItems = ShippingTransaction::where('fullfillment_warehouse', '=', 'Ingram Mico')->where('fulfillment_shipment_status', '=', 'SHIPPED')->where('platform_updated_status', '=', 'UPDATED')->where('status', '=', 1)->get();
        //$unprocessedItems = ShippingTransaction::where('fullfillment_po_number', '=', 10103)->get();

        if(sizeof($unprocessedItems)) {
            echo "We have unprocessed orders \n\r";
            foreach ($unprocessedItems as $shipment) {

                date_default_timezone_set('America/Phoenix');
                $right_now = date("Y-m-d h:m:s");

                $query =" select * from purchase_order where ponum = '$shipment[fullfillment_po_number]'";
                $purchase_order = DB::connection('mysql-walts2')->select($query);    

                //dd($purchase_order);            

                if (($purchase_order) && ($purchase_order[0]->status != 'finished')) {

                  $query = "select * from transaction_purchase where ponum = $shipment[fullfillment_po_number] and item = '$shipment[description]'";
                  $transaction_purchase = DB::connection('mysql-walts2')->select($query);

                  if (($transaction_purchase) && ($transaction_purchase[0]->qty_received == 0) && ($transaction_purchase[0]->status != 'receiving')) {
                    $IngramOrder = OrdersIngramMicroFulfillmentnt::where('CustomerPO', '=', $shipment['fullfillment_po_number'])->get();
                    $msg = "";
                    if ($IngramOrder) {
                      $receive_and_finish = $this->receive_finish_po($transaction_purchase[0], $IngramOrder[0]['Quantity']);
                      if ($receive_and_finish) {
                        // change statuses in shipping transaction tbl
                        $shipment['fulfillment_shipment_status'] = 'PO_FINISHED';
                        $msg = "PO " . $shipment['fullfillment_po_number'] . " received and finished";

                        print "Received and finished!\n\r";
                      } else {
                        // change statuses
                        print "There was a problem finishing this PO.\n\r";
                        $shipment['fulfillment_shipment_status'] = 'PO_FINISH_ERROR';
                        $msg = "PO " . $shipment['fullfillment_po_number'] . " ERROR ON RECEIVE AND FINISH";                    
                      }
                     
                    } else {
                        echo "No matching PO found in Ingram Orders table.  PO field may have been blank.\n\r";
                        $shipment['fulfillment_shipment_status'] = 'PO_FINISH_ERROR';
                        $msg = "PO " . $shipment['fullfillment_po_number'] . " not found in Ingram Orders table.";                     
                    }

                  } else {
                      echo "There was a problem with this PO, or it may have been received already.\n\r";
                      $shipment['fulfillment_shipment_status'] = 'PO_RECEIVE_ERROR';
                      $msg = "PO " . $shipment['fullfillment_po_number'] . " ERROR ON RECEIVE PO"; 
                  } 

                } else {
                    echo "This PO is finished already.\n\r";
                    $shipment['fulfillment_shipment_status'] = 'PO_FINISHED';
                    $msg = "PO " . $shipment['fullfillment_po_number'] . " already finished";                   
                }


        
                $shipment['updated_at'] = $right_now;
                $shipment['user'] = "Ingram Batch Run API";                  
                $query = "insert into messages set msg = '$msg', 
                            msg_time = '$right_now'
                            , msg_from = 'AutoLoaded'
                            , msg_to = 'System'
                            , msg_type = 'shipping'
                            , invoice = '" . $shipment['invoice_num'] . "'";                  
                $newTransaction = DB::connection('mysql-walts2')->insert($query);
                $shipment->save(); 

                //die;
            }
        }

    }


    function receive_finish_po($transaction_purchase, $qty) {

        //username and password of account
        $username = "tjones@walts.com";
        $password = "Rln263!cf";

        //login form action url
        $url="https://wpos.walts.com/pos/index.php";
        $postinfo = "username=".$username."&password=".$password;
        $cookie_file_path = "/cookie.txt";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        //set the cookie the site has for certain features, this is optional
        curl_setopt($ch, CURLOPT_COOKIE, "cookiename=PHPSESSID");
        curl_setopt($ch, CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
        curl_exec($ch);

        $cost = $transaction_purchase->cost; 
        $qty_rec_so_far = $transaction_purchase->qty_received; 
        $qty_ordered = $transaction_purchase->qty_ordered; 
        $upc = ""; 
        $note = "";
        $po_number = $transaction_purchase->ponum;
        $rec_location = "INGRAM_DROP_SHIP";

        $tid = $transaction_purchase->tid; 
        $item = $transaction_purchase->item;

        $postinfo = "cost=" . $cost . "&qty_rec_so_far=" . $qty_rec_so_far . "&qty_ordered=" . $qty_ordered . "&upc=" . $upc . "&note=" . $note . "&po_number=" . $po_number . "&rec_location=" . $rec_location . "&tid=" . $tid . "&item=" . $item;

        //page with the content I want to grab
        curl_setopt($ch, CURLOPT_URL, "https://wpos.walts.com/pos/receive_po_api.php");
        //do stuff with the info with DomDocument() etc
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);        
        $html = curl_exec($ch);
        curl_close($ch);

        echo $html;

        return 1;
                
    }




}
