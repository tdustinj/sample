<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class SubmitJetShippingExceptionsTransactionsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:submit-jet-shipping-exception-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit Jet Shipping Exception Transactions';

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
        $shippingExceptionsTransactions = TransactionListingsJet::where('platform', '=', 'jet')->where('transaction_type', '=', 'shipping_exception')->where('platform_update_status', '=', 'Not_Sent')->get();
        $apiConnection = new jetAPIUtil();
        $apiConnection->apiMethod = "PUT";
    
        foreach($shippingExceptionsTransactions as $shipExceptionTransaction) {

            $apiConnection->apiUrl = "/api/merchant-skus/" . $shipExceptionTransaction->products_id . "/shippingexception";
            echo $apiConnection->apiUrl . "\n\r";
            $apiConnection->jsonPayload = $this->shippingExceptionJSON();
            echo $apiConnection->jsonPayload . "\n\r";
            $apiConnection->put();
            echo $apiConnection->httpStatus . "\n\r";
            echo $apiConnection->result;
            switch($apiConnection->httpStatus){
                case "200" :
                    $this->saveShippingException($shipExceptionTransaction->products_id);
                    $jetProduct = JetProduct::where('products_id', '=', $shipExceptionTransaction->products_id)->get();
                    echo "success\n\r";
                    $shipExceptionTransaction->platform_update_status = 'Success';
                    $shipExceptionTransaction->save();
                    break;
                case "201" :
                    $this->saveShippingException($shipExceptionTransaction->products_id);
                    $jetProduct = JetProduct::where('products_id', '=', $shipExceptionTransaction->products_id)->get();
                    echo "success\n\r";
                    $shipExceptionTransaction->platform_update_status = 'Success';
                    $shipExceptionTransaction->save();
                  
                    break;
                case "202" :
                    $this->saveShippingException($shipExceptionTransaction->products_id);
                    $jetProduct = JetProduct::where('products_id', '=', $shipExceptionTransaction->products_id)->get();
                    // $jetProduct->save();
                    echo "success\n\r";
                    $shipExceptionTransaction->platform_update_status = 'Success';
                    $shipExceptionTransaction->save();
                  
                    break;
                case "204" :
                    $this->saveShippingException($shipExceptionTransaction->products_id);
                    $jetProduct = JetProduct::where('products_id', '=', $shipExceptionTransaction->products_id)->get();
                    // $jetProduct->save();
                    echo "success\n\r";
                    $shipExceptionTransaction->platform_update_status = 'Success';
                    $shipExceptionTransaction->save();
                   
                    break;
                case "400" :
                    $shipExceptionTransaction->platform_update_status = 'Error';
                    $shipExceptionTransaction->save();
                   
                    break;
                case "401" :
                    $shipExceptionTransaction->platform_update_status = 'Error';
                    $shipExceptionTransaction->save();

                    break;
                case "403" :
                    $shipExceptionTransaction->platform_update_status = 'Error';
                    $shipExceptionTransaction->save();

                    break;
                case "404" :
                    $shipExceptionTransaction->platform_update_status = 'Error';
                    $shipExceptionTransaction->save();
                   
                    break;
                case "405" :
                    $shipExceptionTransaction->platform_update_status = 'Error';
                    $shipExceptionTransaction->save();

                    break;
                case "500" :
                    // $jetProduct->result = $apiConnection->result;
                   
                    break;
                case "503" :
                    // $jetProduct->result = $apiConnection->result;
                   
                    break;

                default :
                    $jetProduct->result = $apiConnection->result;
                    $jetProduct->error = $apiConnection->httpStatus;
                   
                    break;
            }
                sleep(1);
        }
    }

    public function saveShippingException($products_id){
        $jetProduct = JetProduct::where('products_id', '=', $products_id)->get();
        $jetProduct[0]->shipping_exception = 'true';
        if($jetProduct[0]->save()){
            echo "Transaction Updated\n\r";
        }else{
            echo "Error Transaction saving\n\r";
        }

    }

    public function shippingExceptionJSON(){
        return '{
                  "fulfillment_nodes": [
                    {
                      "fulfillment_node_id": "69173ca6a20e4c5988e3facb75e7a31d",
                      "shipping_exceptions": [
                        {
                          "shipping_method": "Freight",
                          "override_type": "Override charge",
                          "shipping_charge_amount": 0,
                          "shipping_exception_type": "exclusive"
                        }
                      ]
                    }
                  ]
                }';
    }

}
    


