<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use App\Models\QuoteItem;
use DB;


class ImportLaborLinesToQuoteItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importLaborLinesToQuoteItem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Import Labor Lines';

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

        $transactionCount = DB::connection('oldpos')->select('select count(*) as rec_count from transaction_labor');
        $numTransactionRecords = $transactionCount[0]->rec_count;

        echo "Importing $numTransactionRecords" . "\n\r";
        for ($i = 0; $i < $numTransactionRecords; $i = $i + 1000) {
            echo "select * from transaction_labor where 1 limit 1000," .$i . "\n\r";
        $transSerial = DB::connection('oldpos')->select('select * from transaction_labor where 1 limit '. $i . ', 1000' );
             foreach($transSerial as $line){

                $createDate = new \DateTime($line->sold_date);

                $newLine = new QuoteItem();

                 $newLine->quote_id = $line->invoice;
                 $newLine->product_id = $line->pid;

                     $newLine->tax_code = 'GEN_LABOR';


                 $newLine->tax_amount =  0;
                 $newLine->model_number = $line->tech;
                 $newLine->part_number = $line->tech;
                 $newLine->upc = '0';
                 $newLine->category = 'Labor';
                 $newLine->item_class= 'Labor';
                 $newLine->item_type= 'Labor';
                 $newLine->employee_id = 1;
                 $newLine->brand = 'Walts';
                 $newLine->description = $line->description;

                 $newLine->serial_number = $line->tid;
                 $newLine->quote_date = $createDate;
                 $newLine->source_vendor = $line->tech;
                 $newLine->condition = 'NEW';
                 $newLine->ext_price = $line->hours * $line->rate ;
                 $newLine->unit_price = $line->rate ;
                 $newLine->number_units = $line->hours ;
                 $gp = ($line->hours * $line->rate) - ($line->hours * (.5 * $line->rate));
                 $newLine->standard_gross_profit =  $gp ;
                 $newLine->final_gross_profit = $gp ;

                    $newLine->created_at = $createDate;

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
