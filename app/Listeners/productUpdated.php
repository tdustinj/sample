<?php

namespace App\Listeners;

use GuzzleHttp;
use App\Models\Product;

class productUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        try {
            echo "\n\r Product Updated ";
            // print_r($event->message);
            $product = Product::where('id', '=', $event->message->products_id)->get();
             echo "Checking id : " .$product[0]->external_data_source_id . "  \n\r";

            $client = new GuzzleHttp\Client();
            $response = $client->get('https://test.wpos2.walts.com/api/v1/products/full/' . $product[0]->external_data_source_id
                , ['auth' => ['apiWPOS@walts.com', 'tan37rrt']
                 ,'verify' => false,
                ]);
           // echo $response->getStatusCode(); // 200
            $updatedProduct = json_decode($response->getBody());
            print_r($updatedProduct);

        }
        catch(\Exception $e)
        {
            print_r($e->getMessage());
        }

    }
}
