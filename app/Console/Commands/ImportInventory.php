<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;
use App\Models\Location;

class ImportInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importInventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Inventory';

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

        echo "Import Inventory";
        $locationList = array();
        $locations = Location::all();
        foreach($locations as $location){
            $locationList["$location->location_name"] = $location->id;
        }
        $inventoyBuckets = \DB::connection('oldpos')->select('select * from inventory where vendor not like "DISTRIBUTOR%" and qty_on_hand > 0 order by model');
        foreach ($inventoyBuckets as $bucket) {
          //  echo "Checking " . $bucket->model . "\n\r";
             $productInfo = \DB::table('product')->where('model_number', '=', $bucket->model)->get();

            // print_r($productInfo);
             if(sizeof($productInfo)) {
                for($i = 1; $i <= $bucket->qty_on_hand; $i++) {

                    $newInventoryRecord = new Inventory;

                    $newInventoryRecord->fk_products_id = $productInfo[0]->id;
                    $newInventoryRecord->model_number = $productInfo[0]->model_number;
                    $newInventoryRecord->fk_brand_id = $productInfo[0]->fk_brand_id;
                    $newInventoryRecord->fk_category_id = $productInfo[0]->fk_category_id;
                    $newInventoryRecord->part_number = $productInfo[0]->part_number;
                    $newInventoryRecord->upc = $productInfo[0]->upc;
                    $newInventoryRecord->box_code = $productInfo[0]->upc;
                    $newInventoryRecord->model_bar_code = $productInfo[0]->model_number;
                    $newInventoryRecord->ean = 'NA';
                    $newInventoryRecord->asin = 'NA';
                    $newInventoryRecord->serial_tracked = true;
                    $newInventoryRecord->serial_number = 'NOT_REAL:-' . random_int(100,10000);
                     switch($bucket->vendor){
                        case 'AMAZON_FBA_INBOUND' :
                            $newInventoryRecord->fk_location_id = $locationList["AMAZON_FBA_INBOUND"];
                            $newInventoryRecord->current_condition = 'NEW';
                            break;
                        case 'AMAZON_FBA_LOST' :
                            $newInventoryRecord->fk_location_id = $locationList["AMAZON_FBA_LOST"];
                            $newInventoryRecord->current_condition = 'NEW';
                            break;
                        case 'AMAZON_FBA_UNFULFILLABLE' :
                            $newInventoryRecord->fk_location_id = $locationList["AMAZON_FBA"];
                            $newInventoryRecord->current_condition = 'DAMMAGED';
                            break;
                        case 'AMAZON_FBA_WH' :
                            $newInventoryRecord->fk_location_id = $locationList["AMAZON_FBA"] ;
                            $newInventoryRecord->current_condition = 'NEW';
                            break;
                        case 'AMAZON_FBA_WH_DAMMAGED' :
                            $newInventoryRecord->fk_location_id = $locationList["AMAZON_FBA"];
                            $newInventoryRecord->current_condition = 'DAMMAGED';
                            break;
                        case 'DISPLAY' :
                            $newInventoryRecord->fk_location_id = $locationList["DISPLAY"];
                            $newInventoryRecord->current_condition = 'DISPLAY';
                            break;
                        case 'MISSING' :
                            $newInventoryRecord->fk_location_id = $locationList["OTHER"];
                            $newInventoryRecord->current_condition = 'OTHER';
                            break;
                        case 'RETURN' :
                            $newInventoryRecord->fk_location_id = $locationList["RETURN"];
                            $newInventoryRecord->current_condition = 'NA';
                            break;
                        case 'CARVER' :
                            $newInventoryRecord->fk_location_id = $locationList["CARVER"];
                            $newInventoryRecord->current_condition = 'NEW';
                            break;
                         case 'RUBY' :
                             $newInventoryRecord->fk_location_id = $locationList["RUBY"];
                             $newInventoryRecord->current_condition = 'NEW';
                             break;
                        case 'CARVER_B_STOCK' :
                            $newInventoryRecord->fk_location_id = $locationList["CARVER"];
                            $newInventoryRecord->current_condition = 'B_STOCK';
                            break;
                         case 'RUBY_B_STOCK' :
                             $newInventoryRecord->fk_location_id = $locationList["RUBY"];
                             $newInventoryRecord->current_condition = 'B_STOCK';
                             break;
                         case 'RUBY_C_STOCK' :
                             $newInventoryRecord->fk_location_id = $locationList["RUBY"];
                             $newInventoryRecord->current_condition = 'C_STOCK';
                             break;

                        default :
                            $newInventoryRecord->fk_location_id = $locationList["OTHER"];
                            $newInventoryRecord->current_condition = 'NA';
                    }

                    $newInventoryRecord->initial_purchase_condition = 'NEW';

                    $newInventoryRecord->current_condition_notes = $bucket->note;
                    $newInventoryRecord->selling_status = 'AVAILABLE';

                    $newInventoryRecord->fk_vendor_id = 0;
                    $newInventoryRecord->fk_purchase_order_id = 0;
                    $newInventoryRecord->ordered_at = new \DateTime();
                    $newInventoryRecord->received_at = new \DateTime();
                    $newInventoryRecord->received_by = 'Import';
                    $newInventoryRecord->invoice_cost = $productInfo[0]->current_cost;



                    try {
                        $newInventoryRecord->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        echo "could not insert " . $bucket->model . "\n\r";
                        echo $e->getMessage();
                    }
                }
            }
            else{
                echo "Failed to Import model $bucket->model not found in products db $bucket->vendor , $bucket->qty_on_hand"   . "\n\r";
            }
        }
        return true;
    }


}
