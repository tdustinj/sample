<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportActiveProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importActiveProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Active Products';

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

        echo "Import Products into products";
        $existingProducts = DB::table('product')->pluck('external_data_source_id');
        $existingProductList = array();
        foreach($existingProducts as $existingProduct){
            $existingProductList[$existingProduct] = $existingProduct;
        }
        print_r($existingProductList);
        $products = \DB::connection('wpos2')->select('select * from products where active = "true" order by id DESC ');
        foreach ($products as $productRec) {
            if (!array_key_exists($productRec->id, $existingProductList)) {
                $newProduct = new \App\Models\Product;

                $newProduct->upc = $productRec->upc;
                $newProduct->description = $productRec->description;
                $newProduct->fk_category_id = $productRec->categories_id;
                $newProduct->fk_brand_id = $productRec->brands_id;
                $newProduct->fk_manufacturer_id = $productRec->brands_id;
                $newProduct->model_number = $productRec->model;
                $newProduct->model_code = $productRec->model;
                $newProduct->part_number = $productRec->mpn;
                switch ($productRec->brands_id) {
                    case (114) :
                        $newProduct->serial_number = "false";
                        break;
                    default :
                        $newProduct->serial_number = "true";
                        break;
                }


                $newProduct->marketing_class = "STANDARD";
                $newProduct->qty_on_hand = 100;
                $newProduct->qty_on_order = 10;
                $newProduct->qty_committed = 0;
                $newProduct->qty_on_quoted = 0;
                $newProduct->item_class = $productRec->attributegroups_id;
                $newProduct->item_type = $productRec->shipping_type;
                $newProduct->box_qty = $productRec->master_box_qty;
                $newProduct->master_carton_scan_code = $productRec->master_carton_code;
                $newProduct->stock_class = 'STOCK';
                $newProduct->current_cost = $productRec->retail;
                $newProduct->current_rebate_credit = $productRec->retail;
                $newProduct->current_adj_cost = $productRec->retail;
                $newProduct->current_map = $productRec->retail;
                $newProduct->current_rebate = $productRec->retail;
                $newProduct->current_adj_map = $productRec->retail;
                $newProduct->msrp = $productRec->retail;
                $newProduct->map = $productRec->retail;
                $newProduct->minimum_price = $productRec->retail;
                $newProduct->spiff = 0;
                $newProduct->external_data_source = "wpos2_products";
                $newProduct->external_data_model = $productRec->model;
                $newProduct->external_data_source_id = $productRec->id;
                $newProduct->external_data_source_update_status = $productRec->updated_at;
                $newProduct->status = "ACTIVE";

                try {
                    $newProduct->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    echo "could not insert " . $productRec->model . "\n\r";
                    echo $e->getMessage();
                }
            }
            else{
                echo $productRec->model . " Already Imported \n\r";
            }
        }
        return true;
    }


}
