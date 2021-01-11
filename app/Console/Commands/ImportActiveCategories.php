<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportActiveCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importActiveCategories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Active Categories';

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

        echo "Import categories";
        $categories = \DB::connection('wpos2')->select('select * from categories');
        foreach ($categories as $category) {

            $newCategory = new \App\Models\Category;

            $newCategory->category_name = $category->label;
            $newCategory->id = $category->id;



            try {
                $newCategory->save();
            } catch (\Illuminate\Database\QueryException $e) {
                echo "could not insert " . $category->label . "\n\r";
            }
        }
        return true;
    }


}
