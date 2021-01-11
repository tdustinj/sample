<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
// This is were new routes are added to support
// listing-manager, ordermanager, ospos-web, ospos-native
//
Route::post('auth', 'UserController@auth');
Route::post('auth/google', 'UserController@authGoogle');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user/all', 'UserController@allUsers');
    Route::resource('user', 'UserController');
    Route::get('users/all/admin', 'UserController@AdminAllUsers');
    Route::post('users/update', 'UserController@UpdateUser');

    // Route::get('user/all', 'UserController@allUsers');
    Route::get('testBigSearch/{query}', 'AccountController@testBigSearch');
    Route::resource('account', 'AccountController');
    Route::post('account/search', 'AccountController@search');
    Route::resource('contact', 'ContactController');
    Route::post('contact/search', 'ContactController@search');
    Route::get('contact/{id}', 'ContactController@show');
    Route::post('contact/quotes', 'ContactController@searchForQuotes');
    Route::post('contact/quotes/name', 'ContactController@searchForQuotesByName');
    Route::post('contact/workorder', 'ContactController@searchForWorkorders');
    Route::post('contact/workorder/name', 'ContactController@searchForWorkordersByName');
    Route::post('contact/name', 'ContactController@searchForContactsByName');
    // Added create contact route
    Route::post('contact/store', 'ContactController@store');
    // Added contact return 100
    Route::post('contact/contactHistory', 'ContactController@contactHistory');
    Route::post('contact/update', 'ContactController@update');
    Route::resource('communication', 'CommunicationController');
    Route::post('communication/search', 'CommunicationController@search');
    Route::resource('inventory', 'InventoryController');
    Route::get('inventory/list/{modelNumber}', 'InventoryController@list');
    Route::post('inventory/search', 'InventoryController@search');
    Route::get('inventory/getAssignable/{productId}', 'InventoryController@getAssignable');
    Route::get('inventory/assign/{inventoryId}/{workorderId}/{workorderItemId}', 'InventoryController@assignInventory');
    Route::get('inventory/release/{inventoryId}/{workorderId}/{workorderItemId}', 'InventoryController@releaseInventory');
    Route::get('inventory/assigned/{inventoryId}', 'InventoryController@getAssigned');
    Route::get('inventory/scanInventoryReceive/{upc}/{serial}/{location}/{condition}/{poLineItemId}', 'InventoryController@scanInventoryReceive');
    Route::get('inventory/scanSetup/{products_id}/{upc}/{model_bar_code}', 'InventoryController@scanSetup');
    Route::get('inventory/scanInventoryTransfer/{locationId}/{upc}/{serial}', 'InventoryController@scanInventoryTransfer');
    Route::post('inventory/scanAssignInventory', 'InventoryController@scanAssignInventory');
    Route::post('inventory/getAvailable', 'InventoryController@getAvailableInventory');
    Route::get('inventory/getInventoryList/{productId}', 'InventoryController@getInventoryList');

    Route::resource('inventoryConditions', 'InventoryConditionsController');
    Route::post('inventoryConditions/update', 'InventoryConditionsController@update');
    Route::get('inventoryConditions/destroy/{id}', 'InventoryConditionsController@destroy');

    Route::resource('product', 'ProductController');
    Route::post('product/search', 'ProductController@search');
    Route::post('product/getLegacyProductIds', 'ProductController@getLegacyProductIds');
    Route::get('payment/settings', 'PaymentController@paymentSettingOptions');
    Route::resource('payment', 'PaymentController');
    Route::post('payment/search', 'PaymentController@search');
    Route::resource('listing', 'ListingController');
    Route::post('listing/search', 'ListingController@search');
    Route::resource('workorder', 'WorkOrderController');
    Route::post('workorder/search', 'WorkOrderController@search');
    Route::resource('workorder/item', 'WorkOrderItemController');
    Route::post('workorder/store', 'WorkOrderController@store');
    Route::post('workorder/update/{id}', 'WorkOrderController@update');
    Route::post('workorder/item/search', 'WorkOrderItemController@search');
    Route::get('workorder/getFull/{id}', 'WorkOrderController@getFull');

    Route::resource('invoice', 'InvoiceController');
    Route::post('invoice/search', 'InvoiceController@search');
    Route::resource('invoice/item', 'InvoiceItemController');
    Route::post('invoice/item/search', 'InvoiceItemController@search');
    Route::resource('quote', 'QuoteController');
    Route::post('quote/store', 'QuoteController@store');
    Route::post('quote/search', 'QuoteController@search');
    Route::post('quote/update', 'QuoteController@update');
    Route::get('quote/updateTotals/{id}', 'QuoteController@updateTotals');
    Route::get('quote/getFull/{id}', 'QuoteController@getFull');
    Route::get('quote/convertToOrder/{id}', 'QuoteController@convertToOrder');

    Route::resource('order-types', 'OrderTypeController');
    Route::post('order-types/update', 'OrderTypeController@update');
    Route::get('order-types/destroy/{id}', 'OrderTypeController@destroy');

    Route::resource('locations', 'LocationController');
    Route::post('locations/update', 'LocationController@update');
    Route::get('locations/destroy/{id}', 'LocationController@destroy');

    Route::resource('quote/item', 'QuoteItemController');
    Route::post('quote/item/search', 'QuoteItemController@search');
    Route::post('quote/item/update', 'QuoteItemController@update');
    Route::resource('report', 'ReportController');
    Route::post('report/search', 'ReportController@search');
    Route::resource('requirement', 'RequirementController');
    Route::post('requirement/search', 'RequirementController@search');
    Route::resource('setting', 'SettingController');
    Route::post('setting/search', 'SettingController@search');
    Route::get('setting/optionName/{optionName}', 'SettingController@getOptionNameSet');
    Route::resource('support', 'SupportController');
    Route::post('support/search', 'SupportController@search');

    Route::get('brand/all', 'BrandController@allBrands');
    Route::resource('brand', 'BrandController');
    Route::post('brand/search', 'BrandController@search');

    Route::get('category/all', 'CategoryController@allCategories');
    Route::resource('category', 'CategoryController');
    Route::get('category/allCategoriesFull', 'CategoryController@allCategoriesFull');
    Route::post('category/search', 'CategoryController@search');

    Route::resource('purchase', 'PurchaseController');
    Route::post('purchase/search', 'PurchaseController@search');
    Route::post('purchase/receive/{id}', 'PurchaseController@receive');
    Route::get('purchase/getFull/{id}', 'PurchaseController@getFull');
    Route::get('purchase/print/{id}', 'PurchaseController@printPurchaseOrder');

    Route::resource('vendor', 'VendorController');
    Route::post('vendor/search', 'VendorController@search');

    Route::resource('purchase/orderItem', 'PurchaseOrderItemController');
    Route::post('purchase/orderItem/search', 'PurchaseOrderItemController@search');
    Route::post('purchase/orderItem/bulkReceive/{id}', 'PurchaseOrderItemController@bulkReceive');
    Route::post('purchase/orderItem/receive/{id}', 'PurchaseOrderItemController@receive');

    Route::resource('fulfillment', 'FulfillmentController');
    Route::post('fulfillment/search', 'FulfillmentController@search');
    Route::get('fulfillment/getFull/{id}', 'FulfillmentController@getFull');
    Route::get('fulfillment/getFulfillments/{workorderId}', 'FulfillmentController@getFulfillments');
    Route::resource('fulfillment-package', 'FulfillmentPackageController');
    Route::resource('fulfillment-package-product', 'FulfillmentPackageProductController');
    Route::resource('fulfillment-type', 'FulfillmentTypeController');
    Route::post('fulfillment-type/update', 'FulfillmentTypeController@update');
    Route::get('fulfillment-type/destroy/{id}', 'FulfillmentTypeController@destroy');

    // Fulfillment Shipping BoardAPI
    Route::get('/fulfillment-tracker', 'FulfillmentTrackingController@index');
    Route::get('/fulfillment-tracker/deliver/{tid}', 'FulfillmentTrackingController@deliver');
    Route::get('/fulfillment-tracker/remove/{tid}', 'FulfillmentTrackingController@remove');
    Route::post('/fulfillment-tracker', 'FulfillmentTrackingController@index');

    // Order Status Board API
    Route::get('/order-status-board', 'OrderStatusBoardController@index');
    Route::post('/order-status-board', 'OrderStatusBoardController@toggleViewed');

    Route::post('fulfillment-get-quote', 'FulfillmentController@getFulfillment');
    Route::post('fulfillment-create', 'FulfillmentController@createFulfillment');

    Route::post('attachments/attach', 'AttachmentController@attach');
    Route::get('/attachments/getAttachmentsList/{workorderId}', 'AttachmentController@getAttachmentsList');
    Route::delete('/attachments/delete/{workorderId}', 'AttachmentController@delete');


    Route::get('robinventory/scanner/validate/{productIdentifer}', 'TempRobScanController@validateProductIdentifier');
    Route::post('robinventory/scanner/insertItem', 'TempRobScanController@insertItem');
    Route::get('robinventory/scanner/setMasterScanCodeAndQty/{productId}/{masterScanCode}/{boxQty}/{serialTracked}', 'TempRobScanController@setMasterScanCodeAndQty');
    Route::post('robinventory/scanner/updateUPC', 'TempRobScanController@updateUPC');
    Route::post('robinventory/scanner/deleteItem', 'TempRobScanController@deleteItem');

    Route::get('robinventory/scanner/getSessions', 'TempRobScanController@getSessions');
    Route::get('robinventory/scanner/getBrands', 'TempRobScanController@getBrands');
    Route::get('robinventory/scanner/getLocations', 'TempRobScanController@getLocations');

    Route::get('robinventory/scanner/compare/{sessionName}/{location}/{brand}', 'TempRobScanController@compareSessionToActualLegacy');
    Route::get('order-imports/issues', 'OrderImportController@orderImportIssues');
    Route::get('order-imports/issues/{id}', 'OrderImportController@getOrderManagerOrder');
    Route::get('order-imports/count', 'OrderImportController@orderImportIssuesCount');

    Route::get('platformStateTaxMap/all', 'PlatformStateTaxMapController@getAllTaxInfo');
    Route::get('platformStateTaxMap/all/states', 'PlatformStateTaxMapController@getAllTaxInfoByState');
    Route::get('platformStateTaxMap/all/platforms', 'PlatformStateTaxMapController@getAllTaxInfoByPlatform');
    Route::get('states/all', 'StateController@getAllStates');

    Route::resource('platforms', 'PlatformController');
    Route::get('platforms/all', 'PlatformController@getAllPlatforms');
    Route::post('platforms/update', 'PlatformController@update');
    Route::get('platforms/destroy/{platformCode}', 'PlatformController@destroy');

    Route::resource('platformStateTaxMap', 'PlatformStateTaxMapController');
    Route::put('platformStateTaxMap/{platform_code}/{state_code}/{collectAndRemitTax}', 'PlatformStateTaxMapController@update');
    Route::resource('workorder-notes', 'WorkorderNotesController');
    Route::resource('quote-note', 'QuoteNoteController');
    Route::get('note-types/all', 'NoteTypesController@noteTypes');
    Route::resource('note-types', 'NoteTypesController');
    Route::post('note-types/update', 'NoteTypesController@update');
    Route::get('note-types/destroy/{noteTypeCode}', 'NoteTypesController@destroy');

    Route::resource('quote/laborLines', 'QuoteLaborLineController');
    Route::post('quote/laborLines/update', 'QuoteLaborLineController@update');
    Route::get('quote/laborLines/delete/{id}', 'QuoteLaborLineController@destroy');
    Route::resource('workorder/laborLines', 'WorkOrderLaborLineController');
    Route::post('workorder/laborLines/update', 'WorkOrderLaborLineController@update');
    Route::get('workorder/laborLines/delete/{id}', 'WorkOrderLaborLineController@destroy');

    Route::get('/report', 'ReportController@index');
    Route::post('/report/inventory', 'ReportControllerController@inventoryReport');
    Route::post('/report/quote', 'ReportControllerController@quoteReport');
    Route::post('/report/order', 'ReportControllerController@orderReport');
    Route::post('/report/invoice', 'ReportControllerController@invoiceReport');
    Route::post('/report/purchase', 'ReportControllerController@purchaseReport');
    Route::post('/report/tax', 'ReportControllerController@taxReport');

    Route::get('/salesReport/totalsByInvoiceType', 'SalesReportController@totalsByInvoiceType');
    Route::get('/salesReport/openOrders', 'SalesReportController@openOrders');
    Route::get('/salesReport/openOrders/partner', 'SalesReportController@openPartnerOrders');
    Route::get('/salesReport/openOrders/nonPartner', 'SalesReportController@openNonPartnerOrders');
    Route::get('/salesReport/openInstalls', 'SalesReportController@openInstalls');

    Route::get('purchaseOrder/{id}/pdf', 'PurchaseController@pdf');
    Route::get('purchaseOrder/{id}/email-preview', 'PurchaseController@preview');
    Route::post('purchaseOrder/{id}/email', 'PurchaseController@email');
});
