<?php

namespace App\Services\AvailableInventory;

use App\Services\AvailableInventory\CalculatesAvailableInventoryContract;
use GuzzleHttp;
use App\Models\Inventory;

class AvailableInventoryService implements CalculatesAvailableInventoryContract
{
    // todo: consider adding a column(s) to listingmanager with product ids from walts2.products and ospos.products so we do not need to resolve

	protected $productIds;  // product ids of ospos.products



    protected $baseUrl;


	public function __construct()
	{

		$this->baseUrl = env('INVENTORY_MANAGEMENT_SYSTEM_API_ENDPOINT', 'https://test-api-ospos.walts.com/pos/api/vi/inventory');
        $this->productIds = null;
	}

    public function setProductIds($productIds){
	    /*
	     * This Method sets the ProductIds always from ospos.product.id and for the time being the legacy walts3.product.id
	    */
	    $this->productIds = $productIds;


    }


    public function getAvailableInventoryListBySource(){
        /*
        * Gets Totals for One Bucket /Location/Conditions and Committed
         *
         */
        $fulUrl = '';

        $client = new GuzzleHttp\Client();



        $fullUrl = $this->baseUrl .  '/getInventoryTotalsByLocation';
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
        $inventoryItemsList = array();
        try {
            foreach($this->productIds as $key => $productId) {

                $inventoryItems = Inventory::where('products_id', '=', $productId)->load('brand', 'category','manufacturer')->get();
                //$inventoryItems = Inventory::where('products_id', '=', $productId)->whereNull('assigned_to_invoice')->whereNull('workorder_item_id')->get();

                foreach($inventoryItems as $inventoryItem) {
                   // print_r($inventoryItem);
                    $inventoryItemsList[$inventoryItem->id][] = $inventoryItem;
                }
            }
        }

        catch(\Exception $e)
        {
            return  array('error'=> "Inventory not found for productId: $productId", 'exceptionMessage' => $e->getMessage());

        }

        return $inventoryItemsList;


    }

    public function calculateSellableInventoryByAggregation($aggregation){

    }

    public function calculateCommitedInventoryByAggregation($aggregation){

    }
}

?>