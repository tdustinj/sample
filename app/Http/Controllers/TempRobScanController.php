<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\TempRobScannedInventory;
use App\Models\Inventory;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderItem;

class TempRobScanController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');

    //  $this->middleware('log')->only('index');

    //$this->middleware('subscribed')->except('store');
  }

  public static function tempInsertItemIntoInventory(Request $request)
  {
    //  Log::debug('Inside TempInsert');
    $locationList = array();
    $locations = Location::all();
    foreach ($locations as $location) {
      $locationList["$location->location_name"] = $location->id;
    }
    // Log::debug('Made Locations Array');
    $productInfo = Product::where('upc', '=', $request->upc)->first();
    //  Log::debug('Fetched Product');
    if (sizeof($productInfo) > 0) {
      //   Log::debug('Found Product');

      $newInventoryRecord = new Inventory;

      $newInventoryRecord->fk_products_id = $productInfo->id;
      $newInventoryRecord->model_number = $productInfo->model_number;
      $newInventoryRecord->fk_brand_id = $productInfo->fk_brand_id;
      $newInventoryRecord->fk_category_id = $productInfo->fk_category_id;
      $newInventoryRecord->part_number = $productInfo->part_number;
      $newInventoryRecord->upc = $productInfo->upc;
      $newInventoryRecord->box_code = $productInfo->model_code;
      $newInventoryRecord->model_bar_code = $productInfo->model_number;
      $newInventoryRecord->ean = 'NA';
      $newInventoryRecord->asin = 'NA';
      if ($request->serial == "") {
        $newInventoryRecord->serial_tracked = false;
        $serial = "NRS-" . rand(100, 999) . "-" . time();
        $newInventoryRecord->serial_number = $serial;
      } else {
        $newInventoryRecord->serial_tracked = false;
        $newInventoryRecord->serial_number = $request->serial;
      }
      $upcSerialKey = $productInfo->upc . ':-:' . $newInventoryRecord->serial_number;
      $item = Inventory::where('key_upc_serial', '=', $upcSerialKey)->get();
      if (sizeof($item) > 0) {
        Log::debug('Found a match in inventory');
        throw new \Exception("Item already in inventory");
      }
      $newInventoryRecord->fk_location_id = "25"; //$locationList[$request->location];
      $newInventoryRecord->current_condition = $request->condition;;
      $newInventoryRecord->initial_purchase_condition = 'NEW';

      $newInventoryRecord->current_condition_notes = "";
      $newInventoryRecord->selling_status = 'AVAILABLE';

      $newInventoryRecord->fk_vendor_id = 0;
      $newInventoryRecord->fk_purchase_order_id = 0;
      $newInventoryRecord->ordered_at = new \DateTime();
      $newInventoryRecord->received_at = new \DateTime();
      $newInventoryRecord->received_by = 'Scan Project';
      $newInventoryRecord->invoice_cost = $productInfo->current_cost;
      //  Log::debug('WReady For Insert');
      try {
        $newInventoryRecord->save();
        //  Log::debug('We Were able to Save into Inventory Table');
        return true;
      } catch (\Exception $e) {
        $exceptionMessage = $e->getMessage();
        //  Log::debug($exceptionMessage);
        return false;
      }
    } else {
      //Log::debug('Fetched Product');

    }
  }

  public static function makeResponse($data)
  {
    return response()->json([
      'data' => $data
    ]);
  }

  public function deleteItem(Request $request)
  {
    $serials = strpos($request->serial, ':=:') === false ? array($request->serial) : explode(':=:', $request->serial);

    for ($i = 0; $i < count($serials); $i++) {
      try {
        //    Log::debug($serials[$i]);
        $scannedItem = Inventory::where(
          [
            ['session_name', '=', $request->sessionName],
            ['model_number', '=', $request->model],
            ['serial_number', '=', $serials[$i]]
          ]
        )->first();
        if ($scannedItem) {

          $purchaseOrderLine = PurchaseOrderItem::where([
            ['purchase_order_id', '=', $scannedItem->fk_purchase_order_id],
            ['products_id', '=', $scannedItem->fk_products_id]
          ])->first();

          if ($purchaseOrderLine) {
            $purchaseOrderLine->qty_received = $purchaseOrderLine->qty_received - 1;
            $purchaseOrderLine->save();
          }

          $scannedItem->delete();
        } else {
          throw new \Exception("Unable to Delete from Session");
        }
      } catch (\Exception $e) {
        return response()->json([
          'data' => array('error' => "Unable to delete", 'exceptionMessage' => $e->getMessage())
        ], 404);
      }
    }
    return response()->json([
      'data' => array('Serials Removed' => $serials),
    ], 200);
  }

  public function determineSerial($request, $index)
  {
    if ($request->serial[$index] == '') {
      $serial = "NRS-" . rand(100, 999) . "-" . time();
    } else {
      $serial = $request->serial[$index];
    }
    return $serial;
  }

  public function insertItem(Request $request)
  {
    try {
      $item = Product::where('upc', '=', $request->scan_code)->orWhere('master_carton_scan_code', $request->scan_code)->first();

      $item->load(['brand', 'category']);

      $boxQty = $item->box_qty;
      $combinedSerials = array();

      if ($request->scan_code == $item->upc and $request->serial == '') {
        $boxQty = 1;
      }

      for ($i = 0; $i < $boxQty; $i++) {
        $validSerial = true;
        if ($request->serial[$i] !== '') {
          $validSerial = $this->validateSerial($item, $request->serial[$i]);
        }

        $previousInventory = Inventory::where([
          ['upc', '=', $item->upc],
          ['serial_number', '=', $request->serial[$i]],
        ])->first();

        if (isset($previousInventory->id)) {
          throw new \Exception("Duplicate Serial Scanned");
        }

        if (!$validSerial) {
          throw new \Exception("Invalid Serial Scanned");
        }
      }

      for ($i = 0; $i < $boxQty; $i++) {
        $newScannedItem = new Inventory;
        $newScannedItem->upc = $request->upc;
        $newScannedItem->initial_purchase_condition = "NEW";
        $newScannedItem->current_condition = $request->condition;
        $newScannedItem->current_condition_notes = "";
        $newScannedItem->selling_status = 'AVAILABLE';
        $newScannedItem->session_name = $request->sessionName;
        $newScannedItem->section_name = $request->section;
        $newScannedItem->fk_products_id = $item->id;
        $newScannedItem->fk_location_id = Location::where('location_name', '=', $request->location)->get()[0]->id;
        $newScannedItem->model_number = $request->model;
        $newScannedItem->fk_brand_id = $item->brand->id;
        $newScannedItem->fk_vendor_id = $request->vendor_id;
        $newScannedItem->fk_category_id = $item->category->id;
        $newScannedItem->fk_purchase_order_id = $request->purchase_order_id;
        $newScannedItem->fk_purchase_order_item = $request->purchaseOrderItemId;
        $newScannedItem->serial_number = $this->determineSerial($request, $i);
        $newScannedItem->serial_tracked = $request->serial !== '' ? true : false;
        $newScannedItem->ordered_at = new \DateTime();
        $newScannedItem->received_at = new \DateTime();
        $newScannedItem->received_by = $request->user;
        /*
         * Need to do something with key_upc_serial array thing
         */
        $newScannedItem->save();
        $combinedSerials[] = $newScannedItem->serial_number;
      }

      return response()->json([
        'data' => 'success',
        'combinedSerials' => $combinedSerials
      ], 200);
    } catch (\Exception $e) {

      $error = 'Unknown';
      switch ($e->getCode()) {
        case 23000:
          $error = "Item Already Scanned This Session";
          break;
        default:
          $error = $e->getMessage();
          break;
      }
      return response()->json([
        'data' => array('error' => "Unable to Add", 'exceptionMessage' => $error)
      ], 404);
    }
  }

  public function sortSerials($serial)
  {
    $serialArray = explode(':=:', $serial);
    $keyedArray = array();
    //Log::debug("Original Serial " .$serial);
    foreach ($serialArray as $serial) {
      $keyedArray[$serial] = $serial;
    }
    $beforeSort = print_r($keyedArray, true);
    // Log::debug($beforeSort);
    ksort($keyedArray);

    $afterSort = print_r($keyedArray, true);
    // Log::debug($afterSort);
    $sortedSerialString = implode(":=:", $keyedArray);
    // Log::debug($sortedSerialString);
    return $sortedSerialString;
  }
  public static function validateSerial(Product $item, $serial)
  {
    /*
     * This allows for a brand by brand validation of a serial number
     * return true if valid and false if invalid
     * if the computation is complex a local static method will be called that will return trues or false.
     */
    $valid = false;

    $upcCheck = Product::where('upc', '=', $serial)->first();

    //first check to make sure it is not a upc that is in the system
    // check to make sure it is not another items upc
    if (isset($upcCheck->id)) {
      // the serial is

      return false;
    }


    //Log::debug($item->brand->brand_name);
    switch ($item->brand->brand_name) {

      case "SONY":

        if (substr($serial, 0, 2) == "S0") {
          $valid = true;
        } else {
          $valid = false;
        }
        // Log::debug("Sony");
        break;
      default:
        // Log::debug("Default");
        $valid = true;
    }

    return $valid;
  }
  public function validateProductIdentifier($productIdentifier)
  {
    // make sure we have not sent nothing
    if ($productIdentifier != '') {
      try {
        $item = Product::where('upc', '=', $productIdentifier)->orWhere('model_number', '=', $productIdentifier)->orWhere('model_code', '=', $productIdentifier)->orWhere('part_number', '=', $productIdentifier)->orWhere('master_carton_scan_code', '=', $productIdentifier)->first();
        if (isset($item->id)) {
          if ($productIdentifier == $item->master_carton_scan_code) {
            $scanCode = $item->master_carton_scan_code;
          } else {
            $scanCode = $item->upc;
          }
          return response()->json([
            'data' => $item->toArray(),
            'scan_code' =>  $scanCode
          ], 200);
        } else {
          throw new \Exception("UPC not found in Product Catalog");
        }
      } catch (\Exception $e) {
        return response()->json([
          'data' => array('error' => "Invalid UPC", 'exceptionMessage' => $e->getMessage())
        ], 404);
      }
    } else {
      return response()->json([
        'data' => array('error' => "No Matching product found", 'exceptionMessage' => 'Empty String Sent')
      ], 404);
    }
  }


  public function setMasterScanCodeAndQty($productId, $masterScanCode, $boxQty, $serialTracked)
  {
    // make sure we have not sent nothing
    // Log::debug("I am Here !!!!!!!!");
    if ($productId != '' and $masterScanCode != '' and $boxQty != '') {
      try {
        $item = Product::findOrFail($productId);


        $item->master_carton_scan_code = $masterScanCode;
        $item->box_qty = $boxQty;
        $item->serial_number = $serialTracked;

        $item->save();
        return response()->json([
          'data' => $item->toArray()
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'data' => array('error' => "Could Not Save Changes", 'exceptionMessage' => $e->getMessage())
        ], 404);
      }
    } else {
      return response()->json([
        'data' => array('error' => "No Matching product found", 'exceptionMessage' => 'Empty String Sent')
      ], 404);
    }
  }

  public function updateUPC(Request $request)
  {
    // {productId}/{upc}/{serialTracked}
    // make sure we have not sent nothing
    $validUpc = false;
    if (strlen($request->upc) == 12 and ctype_digit($request->upc)) {
      $validUpc = true;
    }

    if ($request->productId != '' and  $validUpc) {
      try {
        $item = Product::findOrFail($request->productId);

        $item->upc = $request->upc;
        $item->serial_number = $request->serialTracked;
        $item->part_number = $request->partNumber;
        $item->model_code = $request->modelCode;
        $item->save();

        return response()->json([
          'data' => $item->toArray()
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'data' => array('error' => "Could Not Save Changes to Upc", 'exceptionMessage' => $e->getMessage())
        ], 404);
      }
    } else {
      return response()->json([
        'data' => array('error' => "No Matching product found or Invalid UPC", 'exceptionMessage' => 'Empty String Sent')
      ], 404);
    }
  }



  public static function getLegacyLocationMap($location, $condition)
  {
    $location = strtoupper($location);
    $condition = strtoupper($condition);

    switch ($location) {
      case "CARVER":
        if ($condition == "A") {
          $mappedLocation = $location;
        } elseif ($condition == "B") {
          $mappedLocation = $location . "_B_STOCK";
        } elseif ($condition == "C") {
          $mappedLocation = $location . "_C_STOCK";
        } else {
          $mappedLocation = $location . "_NO_CONDITION_MAPPING";
        }
        break;
      case "RUBY":
        if ($condition == "A") {
          $mappedLocation = $location;
        } elseif ($condition == "B") {
          $mappedLocation = $location . "_B_STOCK";
        } elseif ($condition == "C") {
          $mappedLocation = $location . "_C_STOCK";
        } else {
          $mappedLocation = $location . "_NO_CONDITION_MAPPING";
        }
        break;
      default:
        $mappedLocation =  "UNKNOWN_" . $location;
        break;
    }
    return $mappedLocation;
  }

  public function compareSessionToActualLegacy($sessionName, $location, $brand)
  {

    $scannedItemsList = array();
    $legacyInventoryList = array();
    $simpleScanList = array();
    $noInventoryScannedList = array();
    $allItemsScanned = array();
    $report = array();
    $scannedItems = TempRobScannedInventory::where('session_name', '=', $sessionName)->get();
    $report['Session Name'] =  $sessionName;
    $report['Number Items Scanned'] =  count($scannedItems);
    if (sizeof($scannedItems) > 0) {
      // first lets make a list of scannedItems to check against
      foreach ($scannedItems as $item) {
        // we need to make a key with
        // model location condition
        $allItemsScanned[] = trim(strtoupper($item->inventory_model)) . ' , Location: ' . $item->location . ' , Condition: ' . $item->condition . ' , Scan Section: ' . $item->section_name;
        $key = trim(strtoupper($item->inventory_model)) . "::" . $item->location . "::" . $item->condition;
        // now lets add to the  list of legacy Inventory for this model if we have not already
        $simpleScanList[trim(strtoupper($item->inventory_model))] = $item;
        if (!array_key_exists($key, $legacyInventoryList)) {
          $legacyLocationConditionMap = $this->getLegacyLocationMap($item->location, $item->condition);
          $query = "select qty_on_hand, vendor, model from inventory where UPPER(model) = '" . trim(strtoupper($item->inventory_model)) . "' and vendor = '$legacyLocationConditionMap'";
          $currentLegacyInventory = DB::connection('oldpos')->select($query);
          if (sizeof($currentLegacyInventory)) {
            // we have a match from the old system (in therory we should only have 1 bucket so we will just loop thru results anyway. This will
            // show if we have a problem in the old system worst case after investigating inventory table
            foreach ($currentLegacyInventory as $bucket) {
              // print_r($bucket);
              $legacyInventoryList[$key] = array('qty' => $bucket->qty_on_hand);
            }
          }
        }
        if (array_key_exists($key, $scannedItemsList)) {
          // already have one in the list so just increment the total
          $scannedItemsList[$key]["qty"] += 1;
        } else {
          $scannedItemsList[$key]["qty"] = 1;
        }
      }
      //  echo "Scanned Items List";
      // print_r($scannedItemsList);
      // echo "Reported Inventory ";
      // print_r($legacyInventoryList);
      foreach ($scannedItemsList as $key => $item) {
        if (array_key_exists($key, $legacyInventoryList)) {
          if ($item["qty"] != $legacyInventoryList[$key]["qty"]) {
            $report['Exceptions'][] = "$key : Scanned -> " . $item["qty"] . " Wpos reported -> " . $legacyInventoryList[$key]["qty"];
          } else {
            $report['Accurate'][] =  "$key";
          }
        }
      }
      //$brand= "ONKYO";
      $query = "select qty_on_hand, vendor, model from inventory where vendor = '" . $location . "'  and  brand = '" . $brand . "' and qty_on_hand != 0";
      $currentLegacyInventory = DB::connection('oldpos')->select($query);
      foreach ($currentLegacyInventory as $item) {
        if (!array_key_exists(trim(strtoupper($item->model)), $simpleScanList)) {
          $noInventoryScannedList[trim(strtoupper($item->model))] = $item;
        }
      }

      ksort($noInventoryScannedList);
      $report['Inventory In Wpos With No Scans'] = array();
      foreach ($noInventoryScannedList as $item) {
        $report['Inventory In Wpos With No Scans'][] = trim(strtoupper($item->model)) . " qty in Wpos = " . $item->qty_on_hand;
      }
      $report["All Inventory Scaned  for this session"] = $allItemsScanned;
      $legInvList = array();
      foreach ($legacyInventoryList as $key =>  $item) {
        $legInvList[] = $key . " -> " . $item["qty"];
      }
      $report["LegacyInventoryList"] = $legInvList;
      $sInvList = array();
      foreach ($scannedItemsList as $key =>  $item) {
        $sInvList[] = $key . " -> " . $item["qty"];
      }
      $report["ScannedItemsList"] = $sInvList;
    } else {
      $report['Number Items Scanned'] = 0;
    }
    return response()->json([
      'data' => $report

    ], 200);
  }

  public function getBrands()
  {

    $brandList = Brand::where('active', 1)->orderBy('brand_name')->get();

    return response()->json([
      'data' => 'success',
      'brands' => $brandList->toArray()
    ], 200);
  }
  public function getSessions()
  {
    $sessionList = DB::table('temp_rob_scanned_inventories')->pluck('session_name');
    $reducedList = array();
    //$message = print_r($sessionList, true);
    //Log::debug($message);
    foreach ($sessionList as $key => $sessionName) {
      $reducedList[$sessionName] = $sessionName;
    }
    ksort($reducedList);
    return response()->json([
      'data' => 'success',
      'sessions' => $reducedList
    ], 200);
  }
  public function getLocations()
  {

    $locationList = array('CARVER', 'RUBY', 'AMAZON_FBA_INBOUND_WH', 'B2B_RESERVED', 'CARVER_B_STOCK', 'RUBY_B_STOCK', 'CARVER_C_STOCK', 'RUBY_C_STOCK', 'CARVER_DAMMAGED', "WAREHOUSE_DAMMAGED");

    return response()->json([
      'data' => 'success',
      'locations' => $locationList
    ], 200);
  }
}
