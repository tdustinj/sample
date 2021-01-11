<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckAmazonFBAInboundShipmentsStatusCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:check-amazon-fba-inbound-shipments-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check that FBA Inbound shipments are not sitting for 10 days or more';

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
        /* Grabbing All Shipments that are not closed or working, 
         * Check to see if we have a last status, if not we save the last status because the record must have been created today. 
         * else of the last_status is equal to the current ShipmentStatus then add a day to the last_status_days.
         * and if the last_status does not equal the currect ShipmentStatus then update to last_status and reset the last_status_days counter.
         *
         * The last_status_days is going to be use for the notification rules below:
         *
         * Working - No notification or view required
         * Ready to Ship - Notification required at 10 days if no status change
         * Shipped - Notification required at 20 days if no status change
         * In Transit- Notification required at 20 days if no status change
         * Delivered- Notification required at 10 days if no status change
         * Checked in - Notification required at 7 days if no status change
         * Receiving - Notification required at 7 days if no status change
         * Closed - No notification or view required
         * 
        */ 
        
        $shipments = AmazonFBAInboundShipment::where('ShipmentStatus', '!=', 'CLOSED')->where('ShipmentStatus', '!=', 'WORKING')->get(); //->pluck('id', 'ShipmentId');
        foreach($shipments as $order){
            if($order->last_status == ""){
                // print_r($order->ShipmentId . " Need to add Status\n\r");
                $order->last_status = $order->ShipmentStatus;
            }elseif($order->last_status == $order->ShipmentStatus){
                // print_r($order->ShipmentId . " Was still the same\n\r");
                $days = $order->last_status_days;
                $days = $days + 1;
                $order->last_status_days = $days;
            }elseif($order->last_status != $order->ShipmentStatus){
                // print_r($order->ShipmentId . " Changed ShipmentStatus\n\r");
                $order->last_status = $order->ShipmentStatus;
                $order->last_status_days = 0;
            }

            try{
                $order->save();
            }catch(PDOException $exception) {
                echo "\n\r" . "____ Saving ____" . "\n\r";
                // echo "\n\r" . "Error: " . $exception->getCode() . "\n\r";
                print_r($exception->getMessage());
            }
        }
    }//Close fire()

    

}//close class



