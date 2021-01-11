<?php
namespace App\Listeners;

use TaxJar\Client as TaxJarClient;
use TaxJar\Exception as TaxJarException;
use App\Models\PlatformStateTaxMap;
use App\Models\State;
use App\Events\OrderImported;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateWorkorderTax
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
     * @param  OrderImported  $event
     * @return void
     */
    public function handle(OrderImported $event)
    {
        // print_r($event->workorder);
        // print_r($event->contact);
        // print_r($event->workorderItems);
        // print_r($event->platform);

        /* Here is where we will take the Order Information to use to find out if the platform is going to Collect And Remit Tax. 
        * - Need to find a way to get that information out of the order. 
        * - If the Order does not provide the info, use the PlatformStateTaxMap table as the template.
        * - We know that Jet and Newegg will ALWAYS Collect And Remit Tax for us.
        */
        
        /* Get state_code foreign key to use for getting PlatformStateTaxMap */
        $stateCode = $this->get_state_code($event->contact[0]->state);
        list($stateFound, $stateCode) = $this->get_state_code($event->contact[0]->state);
        if(sizeof($event->workorderItems) >= 1 && $stateFound && $stateCode != ""){
            $platformStateTaxMap = PlatformStateTaxMap::where('platform_code', '=', $event->platform)->where('state_code', '=', $stateCode)->first();

            /* TAXJAR */
            $lineItems = array();
            foreach($event->workorderItems as $item){
                $lineItems['line_items'] = [
                      [
                        'id' => $item->id,
                        'quantity' => $item->number_units,
                        'unit_price' => $item->unit_price
                      ]
                    ];
            }

            $client = TaxJarClient::withApiKey(env('TAXJAR_KEY'));
            try {
                $tax = $client->taxForOrder([
                  'from_country' => 'US',
                  'from_zip' => '85284',
                  'from_state' => 'AZ',
                  'from_city' => 'Tempe',
                  'from_street' => '860 W Carver Rd',
                  'to_country' => 'US',
                  'to_zip' => $event->contact[0]->zip,
                  'to_state' => $stateCode,
                  'to_city' => $event->contact[0]->city,
                  'to_street' => $event->contact[0]->address,
                  'amount' => $event->workorder->total,
                  'shipping' => 0,
                  'line_items' => $lineItems['line_items']
                ]);
                // print_r($tax);
                if($tax->amount_to_collect > 0){
                    $tax_total = 0;
                    if(is_object($tax->breakdown) && isset($tax->breakdown->shipping)){
                        foreach($event->workorderItems as $item) {
                            /* To get the shipping tax, grab the tax rate from TaxJar and use the item_shipping_cost provided by the platform, then store it in computed_shipping_tax. */
                            $tax_total += $item->tax_amount;
                            $shippingTaxRate = $tax->breakdown->shipping->combined_tax_rate;
                            $shippingTaxCollectable = $shippingTaxRate * $item->item_shipping_cost;
                            $item->computed_shipping_tax = $shippingTaxCollectable;
                            $item->item_shipping_tax_rate = $shippingTaxRate;
                            foreach($tax->breakdown->line_items as $line_item){
                                if($line_item->id == $item->id){
                                    $item->computed_tax = $line_item->tax_collectable;
                                }
                            }
                            $item->save();
                        }
                    }
                    //overwrite if total is 0
                    $event->workorder->tax_total = ($event->workorder->tax_total == 0.00 ? $tax_total : 0.00);
                    $event->workorder->tax_rate = $tax->rate;
                    $event->workorder->computed_tax = $tax->amount_to_collect;
                    $event->workorder->save();
                    
                }
            } catch(TaxJarException $e){
                print_r($e->getMessage());
            }
            // $computedTax =

            if (isset($platformStateTaxMap->collectAndRemitTax)) {
                // We must now use Tax Jar to find out the tax we need to apply and collect for our safety from the IRS(BIGBROTHER).
                // Set the workorder to taxable (or whatever we are going to call it).
                

            } else {
                // The platform should be collecting the Tax for us. We can sit back and relax, just set the workorder to non-taxable (or whatever we are going to call it).
                // We still want to get the computed tax, just to make sure that we do not have a large discrepency from the platform. 

                foreach($event->workorderItems as $item){
                    // print_r($item);
                }
            }
        }else{
            print_r("could not get taxes for this order, state: " . $event->contact[0]->state);
        }
        /* Workorder 
        [id] => 36678
        [order_manager_id] => 36678
        [sold_contact_id] => 4437
        [ship_contact_id] => 4437
        [bill_contact_id] => 1
        [sold_account_id] => 1
        [workorder_type] => Ship
        [workorder_class] => online
        [workorder_status] => Shipped
        [requirement_status] => none
        [quote_id] => 1
        [invoice_id] => 
        [primary_sales_id] => 1
        [second_sales_id] => 1
        [third_sales_id] => 1
        [product_total] => 128.49
        [labor_total] => 0
        [shipping_total] => 0
        [tax_total] => 0
        [total] => 128.49
        [viewed] => 0
        [customer_contacted] => 0
        [shipping_confirmed] => 0
        [is_amazon_prime] => 0
        [updated_at] => 2018-12-12 16:18:50
        [created_at] => 2018-12-12 16:18:50
        */
        /* Contact 
        [id] => 4437
        [created_at] => 2018-12-12 16:18:50
        [updated_at] => 2018-12-12 16:18:50
        [primary_email] => r9pn25hpnyssvsq@marketplace.amazon.com
        [secondary_email] => 
        [third_party_email] => 
        [secondary_phone] => 
        [tertiary_phone] => 
        [primary_phone] => 
        [first_name] => Gerald
        [middle_initial] => 
        [last_name] => Maher
        [address] => 390 Triangle K Rd
        [address2] => 
        [city] => Walla walla
        [state] => Washington
        [zip] => 99362
        [country] => USA
        [source] => 
        [account_id] => 
        [tax_zone] => NOT_SET
        */
        /* Workorder_Items
         * - Has tax per Item usually.
       
        [id] => 4026
        [workorder_id] => 36678
        [created_at] => 2018-12-12 16:18:50
        [updated_at] => 2018-12-12 16:18:50
        [product_id] => 1014
        [employee_id] => 1
        [model_number] => UBD-M7500
        [part_number] => UBD-M7500/ZA
        [fk_brand_id] => 32
        [description] => Samsung UBD-M7500 Ultra High Definition 4K Universal Blu-Ray Player
        [upc] => 887276235523
        [fk_category_id] => 28
        [item_class] => 8
        [item_type] => Non Freight
        [serial_number_tracked] => true
        [source_vendor] => WAREHOUSE
        [condition] => shipped
        [tax_code] => tbd
        [tax_amount] => 10.50
        [ext_price] => 0.00
        [unit_price] => 128.49
        [number_units] => 1.00
        [standard_gross_profit] => 0.00
        [final_gross_profit] => 0.00
        [number_units_fulfilled] => 0
        [number_serial_numbers_assigned] => 0
        [shipping_service] => Standard
        [order_manager_item_id] => 36981
        */
    
    }

    private function get_state_code($stateCode){
        $stateCode = trim($stateCode);
        $stateCode = preg_replace("/[^A-Za-z0-9 ]/", '', $stateCode);
        $stateCode = preg_replace("/\s{2,}/", " ", $stateCode);

        $stateCodeFound = false;
        $sC = "";
        //print_r("\n" . $stateCode . "\n");
        if (strlen($stateCode) <= 2){
            $stateCode = strtoupper($stateCode);
            $stateCode = State::where('state_code', '=', $stateCode)->first();
        } else {
            $stateCode = ucfirst($stateCode);
            $stateCode = State::where('state_name', '=', $stateCode)->first();
        }
        if(isset($stateCode->state_code)){
            $stateCodeFound = true;
            $sC = $stateCode->state_code;
            // return $stateCode->state_code;
        }
        // return $stateCode->state_code;

        return array($stateCodeFound, $sC);
    }
}
