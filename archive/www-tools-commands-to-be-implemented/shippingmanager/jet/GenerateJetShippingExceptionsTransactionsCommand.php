<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class GenerateJetShippingExceptionsTransactionsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:generate-jet-shipping-exception-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Jet Shipping Shipping Transactions';

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
        $shippingExceptions = listing::where('platform', '=', 'jet')->where('shipping_class', '=', 'Freight')->select('model', 'id', 'products_id', 'shipping_class', 'platform_reference_id')->get();
        $sortedExceptions = array();

        foreach($shippingExceptions as $jetProduct){
            $sortedExceptions[$jetProduct->products_id] = $jetProduct;
        }

        $allJetProducts = JetProduct::all()->toArray();

        foreach($allJetProducts as $jetProduct){
            if(array_key_exists($jetProduct['products_id'], $sortedExceptions)){
                if($jetProduct['shipping_exception'] == 'true'){
                    echo "All good";
                }else{
                    // print_r($jetProduct);
                    // echo "generating new shipping_exception transaction for $jetProduct['products_id']" . "\n\r";
                    $transaction = new TransactionListingsJet();
                        $transaction->listings_id = $jetProduct['id'];
                        $transaction->transaction_type = 'shipping_exception';
                        $transaction->platform = 'jet';
                        $transaction->platform_update_status  = 'Not_Sent';
                        $transaction->products_id = $jetProduct['products_id'];
                        $transaction->sku = $jetProduct['products_id'];
                        $transaction->model = $jetProduct['model'];

                    if($transaction->save()){
                        echo "Transaction Updated\n\r";
                    }else{
                        echo 'error trying to save';
                    }
                } 
            }
        }



    }

}


