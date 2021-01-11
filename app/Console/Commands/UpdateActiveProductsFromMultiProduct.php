<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class UpdateActiveProductsFromMultiProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updateActiveProductsFromMultiProduct';

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

        echo "Update Products with Critical Changes";
        $products = \DB::connection('wpos2')->select('select * from products where active = "true" order by id DESC ');
        foreach ($products as $productRec) {
            // first check if product exists
            print_r($productRec);
            $aProduct = Product::where('external_data_source_id', '=', $productRec->id)->first();
            if(isset($aProduct->id)) {
                $aProduct->upc = $productRec->upc;

                $aProduct->box_qty = $productRec->master_box_qty;
                $aProduct->master_carton_scan_code = $productRec->master_carton_code;
                $aProduct->status = $productRec->active;

                try {
                    $aProduct->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    echo "could not save " . $productRec->model . "\n\r";
                    echo $e->getMessage();

                }
            }
        }
        return true;
    }


}
