<?php

namespace App\Console\Commands;

use App\Models\TempRobScannedInventory;
use Illuminate\Console\Command;
use App\Models\Inventory;
use App\Models\Location;

class SyncInventoyTempRobToOldInventoryScanned extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:syncInventoryTempRobToNewScan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a one time command to sync scanned inventory with new scanned inventory';

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

    //

    public function handle()
    {
/*	olddb->walts2:inventory_new_received
        id
model
upc
serial_number
po_number
transaction_purchase_tid
combo_key
date_received
user_name */


/*
 *  oregonDb->ospos:temp_rob_scanned_inventories
 *
id
upc
serial
fk_products_id
condition
inventory_model
created_at
updated_at
location
session_name
session_upc_serial_combo_key
section
section_name
added_to_old_pos
 */
        $dedupedInventory = array();
        $tempScannedInventory = TempRobScannedInventory::all();
        foreach($tempScannedInventory as $item){
                 $comboKey = $item->upc . '-' . $item->serial . '!' . $item->condition;
                 $dedupedInventory[$comboKey] = $item;
                // echo $comboKey . "\n\r";

                }
                ksort($dedupedInventory);
         foreach($dedupedInventory as $key => $item){
            $insertKey = $item->upc . "-" . $item->serial;
            echo $insertKey . "\n\r";

         }



    }


}
