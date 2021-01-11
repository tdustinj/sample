<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportActiveBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importActiveBrands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Active Brands';

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

        echo "Import Brands";
        $brands = \DB::connection('wpos2')->select('select * from brands ');
        foreach ($brands as $brand) {

            $newBrand = new \App\Models\Brand;

            $newBrand->brand_name = $brand->name;
            $newBrand->parent_company = $brand->name;
            $newBrand->id = $brand->id;


            try {
                $newBrand->save();
            } catch (\Illuminate\Database\QueryException $e) {
                echo "could not insert " . $brand->name . "\n\r" . $e->getMessage();
            }
        }
        return true;
    }


}
