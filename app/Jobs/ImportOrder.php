<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Services\OrderManager\OrderManagerClientContract; 
use App\Models\OrderImport;
use App\Models\Contact;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\Product;
use App\Events\OrderImported;
use App\Events\AmazonFBAOrderImported;
use Exception;


class ImportOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $echoMe;

    protected $orderImport;
    

    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrderImport $orderImport)
    {
        $this->orderImport = $orderImport;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrderManagerClientContract $orderManagerClient)
    {
        $order = $orderManagerClient->getFullOrder($this->orderImport->order_manager_id);
        //$order = $orderManagerClient->getFullOrder(58835);
        //var_dump($order); die;

        // This logic is that we only want to import orders with a status of returned or shipped if they are amazonfba orders
        if($order->data->status_code != 'cancelled' || (($order->data->status_code == 'returned' || $order->data->status_code == 'shipped') && $order->data->platform_code == 'amazon' && $order->data->fulfillment_channel_code == 'fba')) {

            $order_exists = WorkOrder::where('platform_order_id', '=', $order->data->platform_order_id)->first();
            //var_dump($order_exists); die;
            if (!$order_exists) {
                $isReturn = false; $isFBA = false;
                if ($order->data->fulfillment_channel_code == 'fba') {
                  $isFBA = true;
                  if ($order->data->status_code == 'returned') {
                    $isReturn = true;
                  }
                } 
                if ($order->data->status_code == 'returned' && $order->data->fulfillment_channel_code == 'fba') {
                  $isReturn = true;
                }
                var_dump($order);
                $customer_id = $this->insert_customer($order->data);         // Create new customer record
                $workOrder = $this->create_workOrder($order, $customer_id, $isFBA, $isReturn);     // Create new work order
                echo $workOrder->id . " work order created\n";
                $getTaxInfo = $this->add_workOrderItems($order->data, $workOrder->id, $isFBA, $isReturn);       // Add all the orders to tranaction serial for the new invoice
                if ($getTaxInfo) {
                  event(new OrderImported($workOrder));
                }
                /* Set order as exported in OrderManager API */
                $setOrderManagerExported = $orderManagerClient->setOrderImported($order->data->id, $workOrder->id);
                $setOrderManagerExported = json_decode($setOrderManagerExported);
                var_dump($setOrderManagerExported);
                if ($setOrderManagerExported->data == "success") {
                  /* Set order as imported in API-OSPOS order_import table */
                  $this->orderImport->import_failed = false;
                  $this->orderImport->order_manager_updated = true;
                } else {
                  /* Issue setting order as exported in Order Manager, in order_import set order_manager_updated = false; */
                  $this->orderImport->order_manager_updated = false;
                }      
                if ($isFBA) {
                  event(new AmazonFBAOrderImported($workOrder));
                }
            } else {
                echo "Exists already.\n";
                $setOrderManagerExported = $orderManagerClient->setOrderImported($order->data->id, $order_exists["id"]);   
                $setOrderManagerExported = json_decode($setOrderManagerExported);
                var_dump($setOrderManagerExported);    
                $this->orderImport->imported = true;
                $this->orderImport->order_manager_updated = true;                               
            }

        } else {
          echo "Order either cancelled, unrecognized or a non Amazon returned or shipped.\n";
          $this->orderImport->cancelled = true;         
        } 
        $this->orderImport->save(); 
        
    }


    private function insert_customer($order) {

          /* Create new Contact info */
          $contact = new Contact();
            $arr =  explode(" ", $order->shipping_details[0]->ship_to_customer->name);
            if (sizeof($arr) === 2){
              $contact->first_name  = $this->normalizeString($arr[0]);
              $contact->last_name  = $this->normalizeString($arr[1]);
            } elseif (sizeof($arr) === 3){
              $contact->first_name  = $this->normalizeString($arr[0]);
              $contact->middle_initial = $this->normalizeString($arr[1]);
              $contact->last_name  = $this->normalizeString($arr[2]);
            } else {
              $contact->first_name  = $this->normalizeString($arr[0]);
              $contact->last_name  = $this->normalizeString($arr[sizeof($arr)-1]);
            }
          $contactInDatabase = Contact::where('first_name', '=', $contact->first_name)
                                      ->where('last_name', '=', $contact->last_name)
                                      ->where('city', '=', $this->normalizeString($order->shipping_details[0]->ship_to_customer->city))
                                      ->where('state', '=', $order->shipping_details[0]->ship_to_customer->state)
                                      ->where('country', '=', $order->shipping_details[0]->ship_to_customer->country)
                                      ->where('zip', '=', $order->shipping_details[0]->ship_to_customer->postal_code)
                                      ->where('address', '=', $order->shipping_details[0]->ship_to_customer->address_line_1)
                                      ->where('address2', '=', $order->shipping_details[0]->ship_to_customer->address_line_2)
                                      ->first();
          if (isset($contactInDatabase->first_name)) {
            print_r("\nWe have this contact already with this name and matching address details. \n");
            $contact = $contactInDatabase;
          } else {
            print_r("\nWe do not have this contact. Add to contact database. \n");
            $contact->city  = $this->normalizeString($order->shipping_details[0]->ship_to_customer->city);
            $contact->state  = $order->shipping_details[0]->ship_to_customer->state;
            $contact->country  = $order->shipping_details[0]->ship_to_customer->country;
            $contact->zip  = $order->shipping_details[0]->ship_to_customer->postal_code;
            $contact->address  = $order->shipping_details[0]->ship_to_customer->address_line_1;
            $contact->address2 = $order->shipping_details[0]->ship_to_customer->address_line_2;
            if ($order->platform_code == "newgg_business" || (isset($order->platform_order_types[0]->order_type_code) && $order->platform_order_types[0]->order_type_code == "amazon_business")) {
                $contact->is_business = true;
            }
            $contact->primary_phone = $order->primary_phone;
            $contact->secondary_phone = $order->primary_phone;//can't be null because I messed up the fields
            $contact->primary_email  = $order->primary_email;
          }
          $contact->save();

          return $contact->id; //Return id for use in workorder.

    }

    private function create_workOrder($fullorder, $shipToCustomerId, $isFBA, $isReturn) {

        $workOrder = new WorkOrder();
        $workOrderInDatabase = WorkOrder::where('platform_order_id', '=', $fullorder->data->platform_order_id)->first();
        // if($workOrderInDatabase){
        //    print_r("We already have this order, lets just update it. \n");
        //    $workOrder = $workOrderInDatabase;
        // }
        //$workOrder->id = $fullorder->data->id; ???
        $workOrder->platform_order_id = $fullorder->data->platform_order_id;
        $workOrder->order_placed_date = $fullorder->data->order_date;
        $workOrder->order_manager_id = $fullorder->data->id;
        $workOrder->sold_contact_id = $shipToCustomerId;
        $workOrder->ship_contact_id = $shipToCustomerId;
        $workOrder->bill_contact_id = $this->getPlatformContactId($fullorder->data->platform_code); // Needs to be marketplace bill to address. $fullorder->data->platform_code; 
        $workOrder->sold_account_id = 1;
        $workOrder->order_type = 'Ship';
        $workOrder->order_class = 'online';
        //$workOrder->status = $this->normalizeString($fullorder->data->status_code); 
        //need mapping function to handle the way we have different statuses based on platform and ship method
        // $workOrder->status = $this->normalizeString($fullorder->data->status_code);
        if ($isFBA) {
          $workOrder->platform = 'AmazonFBA';
          $workOrder->status = $this->normalizeString($fullorder->data->status_code); // if it's FBA then use their status code
        } else {
          $workOrder->platform = $this->normalizeString($fullorder->data->platform_code);
          // the flag for amazon_prime is in the first array of platform_order_types unless it's also a business order, then it's in the second array
          if ((isset($orderInfo->platform_order_types[0]->order_type_code) and $orderInfo->platform_order_types[0]->order_type_code == 'amazon_prime') || 
              (isset($orderInfo->platform_order_types[1]->order_type_code) and $orderInfo->platform_order_types[1]->order_type_code == 'amazon_prime')) {
              $workOrder->is_amazon_prime = 1; 
              $workOrder->status = 'PRS Walts Parcel';
          } else {
              $workOrder->is_amazon_prime = 0;
              $workOrder->status = 'Shipping FAQ';
          }          
        }      
       
        $workOrder->requirement_status = 'none';
        $workOrder->quote_id = 1;      //May need to change to be nullable field.
        $workOrder->invoice_id = null;
        $workOrder->primary_sales_id = $this->getPlatformContactId($fullorder->data->platform_code); //Should be Marketplace? can not be null
        $workOrder->second_sales_id = 1; //Should be Marketplace?
        $workOrder->third_sales_id = 1; //Should be Marketplace?
        $workOrder->product_total = $fullorder->data->total_price;
        $workOrder->labor_total = 0;
        $workOrder->shipping_total = $this->getTotals($fullorder->data->order_items, 'item_shipping_cost'); //getTotals loops thru order_items and returns totals for whatever element name you pass
        $workOrder->shipping_tax_total = $this->getTotals($fullorder->data->order_items, 'item_shipping_tax');
        $workOrder->tax_total = $this->getTotals($fullorder->data->order_items, 'item_sales_tax');
        $workOrder->total = $fullorder->data->total_price;

        if (isset($fullorder->data->shipping_details[0]->earliest_delivery_date)) {
            $workOrder->expected_delivery_date = substr($fullorder->data->shipping_details[0]->earliest_delivery_date, 0, 10);
        } elseif (isset($fullorder->data->shipping_details[0]->latest_delivery_date)) {
            $workOrder->expected_delivery_date = substr($fullorder->data->shipping_details[0]->latest_delivery_date, 0, 10);
        } elseif (isset($fullorder->order_detail__request_delivery_by)) {
            $workOrder->expected_delivery_date = substr($fullorder->order_detail__request_delivery_by, 0, 10);  
        } else {
            $workOrder->expected_delivery_date = "TBD";
        }

        $workOrder->viewed = 0;
        $workOrder->customer_contacted = 0;
        $workOrder->shipping_confirmed = 0;
        
        $workOrder->save();
        $workOrderId = $workOrder->id; //Grab the workOrder Id for using as Key in workorder_items.   
        
        return $workOrder;   

    }

    private function add_workOrderItems($order, $workOrderId, $isFBA, $isReturn) {
        $getTaxInfo = true; $loopCounter = 1;
        foreach($order->order_items as $item) {
            //print_r($item);
            // $workorderItem = WorkOrderItem::where('order_manager_item_id', '=', $item->id)->first();
            // if(!$workorderItem) {
            //   print_r("New item imported. \n");
            //   $workorderItem = new WorkOrderItem();
            // }
            $this->orderImport->imported = true; // if we've made it this far the workorder is in the system so might as well flag it as imported
            $productToMap = "";
            $cleanedProduct = $item->platform_reference_sku;
            $workorderItem = new WorkOrderItem();
            if (empty($item->members)) {
              if (strpos($cleanedProduct, "-B-STOCK")) {
                $cleanedProduct = str_replace("-B-STOCK", "", $cleanedProduct);
              } elseif (strpos($cleanedProduct, "-BSTOCK")) {
                $cleanedProduct = str_replace("-BSTOCK", "", $cleanedProduct);
              } elseif (strpos($cleanedProduct, "-SUB-NEW")) {
                $cleanedProduct = str_replace("-SUB-NEW", "", $cleanedProduct);
              }
              $productToMap = Product::where('model_number', 'like', $cleanedProduct)->first();
              echo $cleanedProduct . "\n";
            } else {
              $productToMap = Product::where('external_data_source_id', '=', $item->members[0]->product_id)->first();
            }

            if ($productToMap) {
              print_r("Product found in Products Database. \n");
              $workorderItem->workorder_id = $workOrderId;
              if ($order->platform_code == 'ebay' || $order->platform_code == 'sears') {
                $workorderItem->platform_order_item_id = $order->platform_order_id . "-" . $loopCounter;
              } elseif ($order->platform_code == 'walmart') {
                $order_item_id = str_replace("__line", "-", $item->platform_order_item_id);
                $workorderItem->platform_order_item_id = $order_item_id;
              } else {
                $workorderItem->platform_order_item_id = $item->platform_order_item_id;
              }
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
              $workorderItem->condition = $item->condition_code;
              $workorderItem->tax_code = 'tbd';
              $workorderItem->tax_amount = ($item->item_sales_tax === null ? 0.00 : $item->item_sales_tax);
              $workorderItem->ext_price = 0.00;
              $workorderItem->unit_price = ($item->per_item_price === null ? 0.00 : $item->per_item_price);
              $workorderItem->total_item_price = $item->total_item_price;
              $workorderItem->number_units = $item->order_qty;
              
              if ($order->shipping_details[0]->shipping_service_level) {
                $workorderItem->shipping_service = $order->shipping_details[0]->shipping_service_level;
              } elseif ($order->shipping_details[0]->shipping_method) {
                $workorderItem->shipping_service = $order->shipping_details[0]->shipping_method;
              } else {
                $workorderItem->shipping_service = "Unavailable";
              }

              $workorderItem->ship_method_requested = ($order->shipping_details[0]->shipping_method ? $order->shipping_details[0]->shipping_method : "Unavailable");

              if ($order->shipping_details[0]->est_delivery_date) {
                $workorderItem->expected_delivery_date = $order->shipping_details[0]->est_delivery_date;
              } elseif ($order->shipping_details[0]->earliest_delivery_date) {
                $workorderItem->expected_delivery_date = $order->shipping_details[0]->earliest_delivery_date;
              } elseif ($order->shipping_details[0]->latest_delivery_date) {
                $workorderItem->expected_delivery_date = $order->shipping_details[0]->latest_delivery_date;
              } else {
                $today = date("Y-m-d");
                $workorderItem->expected_delivery_date = date('Y-m-d', strtotime($today . ' +7 Weekday'));
              }   

              if ($order->shipping_details[0]->est_shipping_date) {
                $workorderItem->expected_ship_date = $order->shipping_details[0]->est_shipping_date;
              } elseif ($order->shipping_details[0]->earliest_shipping_date) {
                $workorderItem->expected_ship_date = $order->shipping_details[0]->earliest_shipping_date;
              } elseif ($order->shipping_details[0]->latest_shipping_date) {
                $workorderItem->expected_ship_date = $order->shipping_details[0]->latest_shipping_date;
              } else {
                $today = date("Y-m-d");
                $workorderItem->expected_ship_date = date('Y-m-d', strtotime($today . ' +1 Weekday'));
              }

              $workorderItem->standard_gross_profit = 0;
              $workorderItem->final_gross_profit = 0;
              $workorderItem->order_manager_item_id = $item->id;
              $workorderItem->item_shipping_cost = ($item->item_shipping_cost === null ? 0.00 : $item->item_shipping_cost);
              // foreach($item->shipping_details as $itemShippingInfo){
              //   $workorderItem->shipping_service = ($itemShippingInfo->shipping_method === null ? "" : $itemShippingInfo->shipping_method); 
              // }
              //$workorderItem->shipping_service = ($item->shipping_method === null ? "" : $item->shipping_method); 
              try {

                $workorderItem->save();

              } catch(Exception $e) {
                print_r("exception failure\n");
                print_r($e->getMessage());
                $this->orderImport->import_failed = true;
              }
                
            } else {
              /* 
               * 
                Should only be here if we could not find the product that was ordered. In which case we want to import all but the items and set a flag that we didn't get the full order, flag is imported = true && import_failed true. 
               *
               */
              $this->orderImport->import_failed = true;

              print_r("Issue finding Product for Order. We can not get the Tax yet because we don't have the items. \n");
              $getTaxInfo = false;

            }
            $loopCounter++;
        }

        return $getTaxInfo;

    }

    private function getTotals($orders, $key){
        $total = 0;
        foreach ($orders as $item) {
          $total += $item->$key;
        }
        return $total;
    }

    private function normalizeString($string){
      $normalizedString = strtolower($string);
      $normalizedString = ucfirst($normalizedString);

      return $normalizedString;
    }

    private function getPlatformContactId($platform_name){
      $platformContact = Contact::where('first_name', '=', $platform_name)->where('last_name', '=', $platform_name)->first();
      if($platformContact){
        return $platformContact->id;
      }
      return 1;
    }    

}

