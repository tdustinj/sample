<?php

namespace App\Listeners;

use App\Events\OrderItemsImport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\WorkOrderItem;
use App\Models\WorkOrder;
use App\Models\Product;
use App\Models\OrderImport;
// use App\Services\OrderManager\OrderManagerClient; 

class ImportItems
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
     * @param  OrderItemsImport  $event
     * @return void
     */
    public function handle(OrderItemsImport $event)
    {
        //
        print_r($event->workorderId);

        // print_r($event->items);
        // exit;

        foreach($event->items as $item) {
          print_r($item);
            $workorderItem = WorkOrderItem::where('order_manager_item_id', '=', $item->id)->first();
            if(!$workorderItem) {
              print_r("New item imported. \n");
              $workorderItem = new WorkOrderItem();
            }

            if (empty($item->members)) {
              $productToMap = Product::where('model_number', 'like', $item->platform_reference_sku)->first();
            } else {
              $productToMap = Product::where('external_data_source_id', '=', $item->members[0]->product_id)->first();
            }

            if($productToMap){
              print_r("Product found in Products Database. \n");
              $workorderItem->workorder_id = $event->workorderId;
              $workorderItem->platform_order_item_id = $item->platform_order_item_id;
              $workorderItem->product_id = $productToMap->id;
              $workorderItem->employee_id = 1;
              $workorderItem->model_number = $productToMap->model_number;
              $workorderItem->part_number = $productToMap->part_number;
              $workorderItem->fk_brand_id = $productToMap->fk_brand_id;
              $workorderItem->description = $productToMap->description;
              $workorderItem->upc = $productToMap->upc;
              $workorderItem->fk_category_id = $productToMap->fk_category_id;
              $workorderItem->item_class = $productToMap->item_class;
              $workorderItem->item_type = $productToMap->item_type;
              $workorderItem->serial_number_tracked = $productToMap->serial_number;
              $workorderItem->source_vendor = 'WAREHOUSE';
              $workorderItem->condition = $item->status_code;
              $workorderItem->tax_code = 'tbd';
              $workorderItem->tax_amount = ($item->item_sales_tax === null ? 0.00 : $item->item_sales_tax);
              $workorderItem->ext_price = 0.00;
              $workorderItem->unit_price = ($item->per_item_price === null ? 0.00 : $item->per_item_price);
              $workorderItem->total_item_price = $item->total_item_price;
              $workorderItem->number_units = $item->order_qty;
              $workorderItem->standard_gross_profit = 0;
              $workorderItem->final_gross_profit = 0;
              $workorderItem->order_manager_item_id = $item->id;
              $workorderItem->item_shipping_cost = ($item->item_shipping_cost === null ? 0.00 : $item->item_shipping_cost);
              foreach($item->shipping_details as $itemShippingInfo){
                $workorderItem->shipping_service = ($itemShippingInfo->shipping_method === null ? "" : $itemShippingInfo->shipping_method); 
              }

              try {
                $workorderItem->save();
                /* Set order as exported in OrderManager API */
                // ******* DEPRECATED *********
                // ----> Use OrderManagerClientContract type-hinting instead, injected into the constructor via Service Container bindings.
                // (See other uses of OrderManagerClient for more info)
                // $importOrderService = new OrderManagerClient();
                // ****************************
                // $setOrderManagerExported = $importOrderService->setOrderImported($fullorder->data->id, $this->orderImport->id);
                // $setOrderManagerExported = json_decode($setOrderManagerExported);

                // if($setOrderManagerExported->data == "success"){
                  /* Set order as imported in API-OSPOS order_import table */
                //   $this->orderImport->imported = true;
                //   $this->orderImport->import_failed = false;
                //   $this->orderImport->order_manager_updated = true;
                // }else {
                  /* Issue setting order as exported in Order Manager, in order_import set order_manager_updated = false; */
                  // $this->orderImport->order_manager_updated = false;
                // }

                // $this->orderImport->save();

              }catch(Exception $e){
                print_r("exception failure\n");
                print_r($e->getMessage());
                $orderImport = OrderImport::where('order_manager_id', '=', $event->workorderId)->first();
                $orderImport->imported = false;
                $orderImport->import_failed = true;
                $orderImport->save();
              }
          }else{
            /* 
             * 
              Should only be here if we could not find the product that was ordered. In which case we want to import all but the items and set a flag that we didn't get the full order, flag is imported = true && import_failed true. 
             *
             */
            $orderImport = OrderImport::where('order_manager_id', '=', $event->workorderId)->first();
            $orderImport->import_failed = true;
            $orderImport->save();

            print_r("Issue finding Product for Order. We can not get the Tax yet because we don't have the items. \n");
          }
        }
    }
}
