<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportInventoryToOldPos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:exportInventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Inventory';

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

        echo "Export Inventory";

        $inventoyBuckets = \DB::connection('oldpos')->select('select * from inventory where qty_on_hand > 0 order by model');
        foreach ($inventoyBuckets as $bucket) {

             $productInfo = \DB::table('product')->where('model', '=', $bucket->model)->get();

            if(sizeof($productInfo)) {
                for($i = 1; $i <= $bucket->qty_on_hand; $i++) {

                    $newInventoryRecord = new \App\Models\Inventory;

                    $newInventoryRecord->products_id = $productInfo[0]->id;
                    $newInventoryRecord->model_number = $productInfo[0]->model;
                    $newInventoryRecord->fk_brand_id = $productInfo[0]->fk_brand_id;
                    $newInventoryRecord->fk_category_id = $productInfo[0]->fk_category_id;
                    $newInventoryRecord->part_number = $productInfo[0]->part_number;
                    $newInventoryRecord->upc = $productInfo[0]->upc;
                    $newInventoryRecord->box_code = $productInfo[0]->upc;
                    $newInventoryRecord->model_bar_code = $productInfo[0]->model;
                    $newInventoryRecord->ean = 'NA';
                    $newInventoryRecord->asin = 'NA';
                    $newInventoryRecord->serial_tracked = true;
                    $newInventoryRecord->serial_number = 'NOT_REAL:-' . random_int(100,10000);
                     switch($bucket->vendor){
                        case 'AMAZON_FBA_INBOUND' :
                            $newInventoryRecord->location_id = 'AMAZON_FBA_INBOUND';
                            $newInventoryRecord->current_condition = 'NEW';
                            break;
                        case 'AMAZON_FBA_LOST' :
                            $newInventoryRecord->location_id = 'AMAZON_FBA_LOST';
                            $newInventoryRecord->current_condition = 'NEW';
                            break;
                        case 'AMAZON_FBA_UNFULFILLABLE' :
                            $newInventoryRecord->location_id = 'AMAZON_FBA';
                            $newInventoryRecord->current_condition = 'DAMMAGED';
                            break;
                        case 'AMAZON_FBA_WH' :
                            $newInventoryRecord->location_id = 'AMAZON_FBA' ;
                            $newInventoryRecord->current_condition = 'NEW';
                            break;
                        case 'AMAZON_FBA_WH_DAMMAGED' :
                            $newInventoryRecord->location_id = 'AMAZON_FBA';
                            $newInventoryRecord->current_condition = 'DAMMAGED';
                            break;
                        case 'DISPLAY' :
                            $newInventoryRecord->location_id = 'DISPLAY';
                            $newInventoryRecord->current_condition = 'DISPLAY';
                            break;
                        case 'MISSING' :
                            $newInventoryRecord->location_id = 'OTHER';
                            $newInventoryRecord->current_condition = 'OTHER';
                            break;
                        case 'RETURN' :
                            $newInventoryRecord->location_id = 'RETURN';
                            $newInventoryRecord->current_condition = 'NA';
                            break;
                        case 'WAREHOUSE' :
                            $newInventoryRecord->location_id = 'WAREHOUSE';
                            $newInventoryRecord->current_condition = 'NEW';
                            break;
                        case 'WAREHOUSE_LDO' :
                            $newInventoryRecord->location_id = 'WAREHOUSE';
                            $newInventoryRecord->current_condition = 'LDO';
                            break;

                        default :
                            $newInventoryRecord->location_id = $bucket->vendor;
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
                        echo "could not insert " . $bucket->model_number . "\n\r";
                        echo $e->getMessage();
                    }
                }
            }
            else{
                echo "Failed to Import model $bucket->model_number not found in products db $bucket->vendor , $bucket->qty_on_hand"   . "\n\r";
            }
        }
        return true;
    }


}
