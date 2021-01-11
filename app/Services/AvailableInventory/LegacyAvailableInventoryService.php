<?php

namespace App\Services\AvailableInventory;

use App\Services\AvailableInventory\CalculatesAvailableInventoryContract;
use GuzzleHttp;

class LegacyAvailableInventoryService implements CalculatesAvailableInventoryContract
{
    // todo: this will need to be refactored after the switch if not sooner.
    // todo: consider adding a column(s) to listingmanager with product ids from walts2.products and ospos.products so we do not need to resolve


    protected $productIds;  // product ids of ospos.products

    protected $resolvedProductIds; // product ids and models of walts3.products ,
    // we need both as the walts2.inventory does not always have valid product ids.

    protected $baseUrl;


	public function __construct()
	{

		$this->baseUrl = env('INVENTORY_MANAGEMENT_SYSTEM_API_ENDPOINT', 'https://wpos.walts.com/pos');
        $this->productIds = null;
        $this->resolvedProductIds = null;
	}



    public function setProductIds($productIds){
        /*
         * This Method sets the ProductId always from ospos.product.id and for the time being the legacy walts3.product.id
        */
        $this->productIds = $this->resolveLegacyProductIds($productIds);

    }

    private function resolveLegacyProductIds($productIds){
        // stubbed out for now.
        /*
         *  This method will get the  call api-ospos to get the product ids
         *
         */

        return array('LT-55MA877', 'OLED55E7P', 'OLED65C7P', 'TX-NR676');

    }
    public function getAvailableInventoryListBySource(){
        /*
        * Gets Totals for One Bucket /Location/Conditions and Committed
         *
         */
        $fulUrl = '';

        $client = new GuzzleHttp\Client();



                $fullUrl = $this->baseUrl .  '/getInventoryTotalsByLocation.php';
                $response = $client->post($fullUrl
                    , ['auth' => ['apiWPOS@walts.com', 'tan37rrt']
                        ,'verify' => false,
                    ]);




        $inventoryList = json_decode($response->getBody());
        return $inventoryList;

    }

    public function getAvailableInventoryListByAggregation(){
        /*
	    * Gets Totals for All Buckets/Locations/Conditions and Committed
	     *
	     */


        $client = new GuzzleHttp\Client();



                $fullUrl = $this->baseUrl .  '/getInventoryTotalsByLocation.php';

                $response = $client->post($fullUrl, [
                    'form_params' => [
                        'productIds' => json_encode($this->productIds)]
                ]);




       // $inventoryList = json_decode($response->getBody()->getContents());
        $inventoryList = $response->getBody()->getContents();
        //print_r(GuzzleHttp\json_decode($inventoryList));
        return $inventoryList;


    }

    public function calculateSellableInventoryByAggregation($aggregation){

    }

    public function calculateCommitedInventoryByAggregation($aggregation){

    }
}

?>