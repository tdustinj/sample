<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use App\Models\TempRobScannedInventory;

class CompareSessionToActual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:compareSession';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comparision';

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

    //

    public function handle()
    {
//$this->compareSessionToActualLegacy("onkyoRob8-21");
$this->getUniqueByModel("VSXLX102");
    }

        public static function getLegacyLocationMap($location, $condition){
        $location = strtoupper($location);
        $condition = strtoupper($condition);

        switch($location){
            case "CARVER" :
                if($condition == "A"){
                    $mappedLocation = $location;
                }elseif( $condition == "B"){
                    $mappedLocation = $location . "_B_STOCK";
                }elseif($condition == "C"){
                    $mappedLocation = $location . "_C_STOCK";
                }
                else{
                    $mappedLocation = $location . "_NO_CONDITION_MAPPING";
                }
                break;
            case "RUBY" :
                if($condition == "A"){
                    $mappedLocation = $location;
                }elseif( $condition == "B"){
                    $mappedLocation = $location . "_B_STOCK";
                }elseif($condition == "C"){
                    $mappedLocation = $location . "_C_STOCK";
                }
                else{
                    $mappedLocation = $location . "_NO_CONDITION_MAPPING";
                }
                break;
            default :
                $mappedLocation =  "UNKNOWN_" . $location;
                break;
        }
        return $mappedLocation;

    }
        public function compareSessionToActualLegacy($sessionName)
    {

        $scannedItemsList = array();
        $legacyInventoryList = array();
        $simpleScanList = array();
        $noInventoryScannedList = array();
           $scannedItems = TempRobScannedInventory::where('session_name', '=', $sessionName)->get();
           echo "Number Of Scanned Items = " . count($scannedItems) . "\n\r" ;
           if(sizeof($scannedItems) > 0) {
                // first lets make a list of scannedItems to check against
                foreach ($scannedItems as $item) {
                    // we need to make a key with
                    // model location condition
                    $key = $item->inventory_model . "::" . $item->location . "::" . $item->condition;
                    // now lets add to the  list of legacy Inventory for this model if we have not already
                    $simpleScanList[$item->inventory_model] = $item;
                    if (!array_key_exists($key, $legacyInventoryList)) {
                        $legacyLocationConditionMap = $this->getLegacyLocationMap($item->location, $item->condition);
                        $query = "select qty_on_hand, vendor, model from inventory where model = '$item->inventory_model' and vendor = '$legacyLocationConditionMap'";
                        $currentLegacyInventory = DB::connection('oldpos')->select($query);
                        if (sizeof($currentLegacyInventory)) {
                            // we have a match from the old system (in therory we should only have 1 bucket so we will just loop thru results anyway. This will
                            // show if we have a problem in the old system worst case after investigating inventory table
                            foreach ($currentLegacyInventory as $bucket) {
                               // print_r($bucket);
                                $legacyInventoryList[$key] = array('qty' => $bucket->qty_on_hand);
                            }
                        }
                    }
                    if (array_key_exists($key, $scannedItemsList)) {
                        // already have one in the list so just increment the total
                        $scannedItemsList[$key]["qty"] += 1;
                    } else {
                        $scannedItemsList[$key]["qty"] = 1;
                    }
                }
              //  echo "Scanned Items List";
            // print_r($scannedItemsList);
            // echo "Reported Inventory ";
            // print_r($legacyInventoryList);
            foreach($scannedItemsList as $key => $item){
                if(array_key_exists($key,$legacyInventoryList)){
                    if($item["qty"] != $legacyInventoryList[$key]["qty"]){
                        echo "Exception found for $key : Scanned -> " . $item["qty"] . " Wpos reported -> " . $legacyInventoryList[$key]["qty"] . "\n\r";

                    }
                    else{
                        echo "$key is accurate \n\r";
                    }
                }
            }

                $query = "select qty_on_hand, vendor, model from inventory where vendor = 'CARVER'  and  brand like 'onkyo%' and qty_on_hand != 0";
                $currentLegacyInventory = DB::connection('oldpos')->select($query);
                foreach($currentLegacyInventory as $item){
                    if(!array_key_exists($item->model, $simpleScanList)){
                        $noInventoryScannedList[$item->model] = $item;
                    }
                }

              ksort($noInventoryScannedList);
                echo "The Following Items Show Inventory + or - but were not found in scan \n\r";
                foreach($noInventoryScannedList as $item){
                    echo $item->model . " qty in wpos = " . $item->qty_on_hand  . "\n\r";
                }
            }






            else {
                echo "No Scanned Items found for session $sessionName";

            }
            /*
             * Now wee need to do comparision of two lists and return things items that do not match
             */





    }
    public function getUniqueByModel($model)
    {


         $nonDuplicateList = array();
        $scannedItems = TempRobScannedInventory::where('inventory_model', '=', $model)->where('condition', 'a')->get();

        if(sizeof($scannedItems) > 0) {
            // first lets make a list of scannedItems to check against
            foreach ($scannedItems as $item) {
                $key = $item->upc . '-' . $item->serial;
               $nonDuplicateList[$key] = $item->inventory_model;
            }

        }

       print_r($nonDuplicateList);
        echo "Number Of Scanned Items = " . count($scannedItems) . "\n\r" ;
        echo "Number Of Unique Items = " . count($nonDuplicateList) . "\n\r";








    }


}
