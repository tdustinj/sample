<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckAmazonFBAInboundShipmentsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-amazon-fba-inbound-warehouses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check that FBA Inbound shipments are still closed';

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
        $amazonDistributionCenters = \AmazonWarehouse::all();
        $amazonFcList = array();
        foreach($amazonDistributionCenters as $fc){
            $amazonFcList[$fc->code] = $fc;
        }
        $orderList = array();
        $orderListQuery = AmazonFBAInboundShipment::where('DestinationFulfillmentCenterId_Address', '=', 'Not in POS Amazon Warehouse List')->orderBy('id', 'DESC')->get();  
        foreach($orderListQuery as $order){
            $update = $this->getAmazonFC($order->DestinationFulfillmentCenterId, $amazonFcList);
            if($update != 'Not in POS Amazon Warehouse List'){
                $order->DestinationFulfillmentCenterId_Address = $update;
                $order->save();
            }
        }
    }

        
        public function getAmazonFC($fcId, $fcList){
            // echo $fcId;
            if(isset($fcList[$fcId])) {
                $fcInfo = $fcList[$fcId];
                //print_r($fcInfo);
                $fcAddress = $fcInfo->type . "\n\r" . $fcInfo->address . " " . $fcInfo->address_2 . "\n\r" . $fcInfo->city . ", " . $fcInfo->state . " " . $fcInfo->zip;
            }
            else{
                $fcAddress = 'Not in POS Amazon Warehouse List';
            }
            return $fcAddress;
        }
}



