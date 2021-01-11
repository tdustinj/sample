<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use App\Models\QuoteItem;
use DB;


class ImportDetailLinesToQuoteItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importDetailLinesToQuoteItem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Import Detail Lines';

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
    public function handle()
    {

        $transactionCount = DB::connection('oldpos')->select('select count(*) as rec_count from transaction_serial where 1');
        $numTransactionRecords = $transactionCount[0]->rec_count;

          //  $startImportAt = 10001;

        echo "Importing $numTransactionRecords" . "\n\r";
        for ($i = 0; $i < $numTransactionRecords; $i = $i + 1000) {
            echo "select * from transaction_serial where 1 limit 1000," .$i . "\n\r";
        $transSerial = DB::connection('oldpos')->select('select * from transaction_serial where 1 limit '. $i . ', 1000' );
             foreach($transSerial as $line){

                $createDate = new \DateTime($line->sold_date);

                $newLine = new QuoteItem();

                 $newLine->quote_id = $line->invoice;
                 $newLine->product_id = $line->pid;
                 if($line->taxable) {
                     $newLine->tax_code = 'GEN_PRODUCT';
                 }
                 else{
                     $newLine->tax_code = 'INSURANCE';
                 }

                 $newLine->tax_amount =  $line->qty * $line->sold_for * .0873;
                 $newLine->model_number = $line->model;
                 $newLine->part_number = $line->model;
                 $newLine->upc = $line->model;
                 $newLine->category = $line->brand . ":" .$line->model;
                 $newLine->item_class= $line->brand . ":" .$line->model;
                 $newLine->item_type= 'serial';
                 $newLine->employee_id = 1;
                 $newLine->brand = $line->brand;
                 $newLine->description = $line->description;

                 $newLine->serial_number = $line->serial;
                 $newLine->quote_date = $createDate;
                 $newLine->source_vendor = $line->sourced_via;
                 $newLine->condition = 'NEW';
                 $newLine->ext_price = $line->qty * $line->sold_for ;
                 $newLine->unit_price = $line->sold_for ;
                 $newLine->number_units = $line->qty ;
                 $gp = ($line->qty * $line->sold_for) - ($line->qty * $line->cost);
                 $newLine->standard_gross_profit =  $gp ;
                 $newLine->final_gross_profit = $gp ;

                   // $newLine->created_at = $createDate;

                    try {
                        $newLine->save();
                    }
                    catch(\Illuminate\Database\QueryException $e)
                    {
                        echo "could not insert " . $newLine->model_number . "\n\r" . $e->getMessage();
                    }
            }
        }


    }
}
