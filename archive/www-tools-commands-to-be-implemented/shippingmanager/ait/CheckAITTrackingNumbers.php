<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckAITTrackingNumbers extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:check-ait-tracking-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check AIT Tracking Numbers for Delivered Status';

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
    public function fire(){
        $aitTrackingNumbers = ShippingTransaction::where('ship_company', '=', 'AIT')->where('action', '!=', 'Delivered')->where('fulfillment_shipment_status', '=', 'SHIPPED')->where('order_status', '=', 'Shipped')->where('status', '=', 1)->get();
        $apiConnection = new aitAPIUtil();

        foreach($aitTrackingNumbers as $item){

            $apiConnection->track($item['bol_tracking']);
            $xml = $apiConnection->result;
            if(isset($xml->StatusInfo->StsDesc) && trim($xml->StatusInfo->StsDesc) == "DELIVERED") {
                date_default_timezone_set('America/Phoenix');
                $right_now = date("Y-m-d h:m:s");
                $today = date("Y-m-d");
                $item->action = "Delivered";
                $item->order_status = "Delivered";
                $item->status = "0";
                $item->user = "AIT API";
                $item->updated_at = $right_now;
                $item->actual_eta = substr($xml->StatusInfo->StsDateTime, 0, 10);
                $item->save();
                echo $item->invoice_num;
                echo " Delivered!\n\r";  
                
                $msg = "Tracked Item Delivered via AIT API";
                $query = "insert into messages set msg = '$msg', 
                            msg_time = '$right_now'
                          , msg_from = 'AutoLoaded'
                          , msg_to = 'System'
                          , msg_type = 'shipping'
                          , invoice = '" . $item->invoice_num . "'";
                $newMsg = DB::connection('mysql-walts2')->insert($query);                
            }  

        } // end foreach
        

    } //Close fire()

    

}//close class



