<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get vendor list
        try {
            $vendor = Vendor::where('vendor_type', '=', 'Product')->get();
            return response()->json([
                'data' => $vendor->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Vendors Found", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $vendor = new Vendor();
            $vendor->vendor_type = $request->vendorType ;
            $vendor->company_name = $request->companyName ;
            $vendor->rep = $request->rep ;
            $vendor->dealer_number = $request->dealerNumber ;
            $vendor->order_desk_name = null;
            $vendor->address = $request->address ;
            $vendor->address2 = $request->address2 ;
            $vendor->city = $request->city ;
            $vendor->state = $request->state ;
            $vendor->zip = $request->zip ;
            $vendor->country = $request->country;
            //$vendor->default_cogs_account = $request->defaultCogsAccount ;
            $vendor->rep_email = $request->repEmail ;
            /*
             *$vendor->order_portal_url = $request->orderPortUrl ;
             *$vendor->rebate_portal_url = $request->rebatePortalUrl ;
             *$vendor->spiff_submission_url = $request->spiffSubmissionUrl ;
             */
            $vendor->rep_phone = $request->repPhone ;
            $vendor->program_name = $request->programName ;
            /*
            *$vendor->order_desk_phone = $request->orderDeskPhone ;
            *$vendor->fax_phone = $request->faxPhone ;
            *$vendor->minimum_order_free_freight = $request->minimumOrderFreeFreight ;
            *$vendor->fiscal_year_start = $request->fiscalYearStart ;
              *$vendor->payment_discount_15_day = $request->paymentDiscount15Day ;
              *$vendor->payment_discount_30_day = $request->paymentDiscount30Day ;
              *$vendor->payment_discount_60_day = $request->paymentDiscount60Day;
              *$vendor->payment_discount_90_day = $request->paymentDiscount90Day ;
              *$vendor->payment_discount_120_day = $request->paymentDiscount120Day ;
              *$vendor->flooring_company = $request->flooringCompany ;
              *$vendor->standard_flooring_term_days = $request->standardFlooringTermsDays ;
             */

            $vendor->save();
            return response()->json([
              'data' => $vendor->toArray()
            ], 200);
        }
        catch(exception $e)
        {
          return response()->json([
            'data' => array('error'=> "Unable to Store Inventory", 'exceptionMessage' => $e->getMessage())
          ], 404);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      try {
        $vendor = Vendor::findOrFail($id);
        return response()->json([
          'data' => $vendor->toArray()
        ], 200);
      }

      catch(\Exception $e)
      {
        return response()->json([
          'data' => array('error'=> "Vendor Not Found for id: $id", 'exceptionMessage' => $e->getMessage())
        ], 404);
      }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      try {

        $vendor = Purchase::findOrFail($id);
        $vendor->vendor_type = $request->vendorType ;
        $vendor->company_name = $request->companyName ;
        $vendor->rep = $request->rep ;
        $vendor->dealer_number = $request->dealerNumber ;
        $vendor->order_desk_name = $request->orderDeskName ;
        $vendor->address = $request->address ;
        $vendor->address2 = $request->address2 ;
        $vendor->city = $request->city ;
        $vendor->state = $request->state ;
        $vendor->zip = $request->zip ;
        $vendor->country = $request->country;
        $vendor->default_cogs_account = $request->defaultCogsAccount ;
        $vendor->rep_email = $request->repEmail ;
        $vendor->order_portal_url = $request->orderPortUrl ;
        $vendor->rebate_portal_url = $request->rebatePortalUrl ;
        $vendor->spiff_submission_url = $request->spiffSubmissionUrl ;
        $vendor->rep_phone = $request->repPhone ;
        $vendor->order_desk_phone = $request->orderDeskPhone ;
        $vendor->fax_phone = $request->faxPhone ;
        $vendor->minimum_order_free_freight = $request->minimumOrderFreeFreight ;
        $vendor->fiscal_year_start = $request->fiscalYearStart ;
        $vendor->program_name = $request->programName ;
        $vendor->dfi_program_discount = $request->dfiProgramDiscount ;
        $vendor->vir_percent = $request->virPercent ;
        $vendor->accrued_mdf_percent = $request->accruedMdfDiscount ;
        $vendor->accrued_coop_percent = $request->accruedCoopDiscount ;
        $vendor->default_payment_method = $request->defaultPaymentMethod ;
        $vendor->credit_line = $request->creditLine ;
        $vendor->payment_discount_prepay = $request->paymentDiscountPrepay ;
        $vendor->payment_discount_15_day = $request->paymentDiscount15Day ;
        $vendor->payment_discount_30_day = $request->paymentDiscount30Day ;
        $vendor->payment_discount_60_day = $request->paymentDiscount60Day;
        $vendor->payment_discount_90_day = $request->paymentDiscount90Day ;
        $vendor->payment_discount_120_day = $request->paymentDiscount120Day ;
        $vendor->flooring_company = $request->flooringCompany ;
        $vendor->standard_flooring_term_days = $request->standardFlooringTermsDays ;
        $vendor->save();
        return response()->json([
          'data' => $vendor->toArray()
        ], 200);
      }
      catch(exception $e)
      {
        return response()->json([
          'data' => array('error'=> "Unable to Update Vendor", 'exceptionMessage' => $e->getMessage())
        ], 404);
      }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
      //
    }

    /*
     * mysql> describe vendor;
    +-----------------------------+------------------+------+-----+---------------------+----------------+
      | Field                       | Type             | Null | Key | Default             | Extra          |
      +-----------------------------+------------------+------+-----+---------------------+----------------+
      | id                          | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
      | company_name                | varchar(255)     | NO   |     | NULL                |                |
      | rep                         | varchar(255)     | NO   |     | NULL                |                |
      | dealer_number               | varchar(255)     | NO   |     | NULL                |                |
      | order_desk_name             | varchar(255)     | YES  |     | NULL                |                |
      | address                     | varchar(255)     | NO   |     | NULL                |                |
      | address2                    | varchar(255)     | YES  |     | NULL                |                |
      | city                        | varchar(255)     | NO   |     | NULL                |                |
      | state                       | varchar(255)     | NO   |     | NULL                |                |
      | zip                         | varchar(255)     | NO   |     | NULL                |                |
      | country                     | varchar(255)     | NO   |     | US                  |                |
      | vendor_type                 | varchar(255)     | NO   |     | PRODUCT             |                |
      | default_cogs_account        | varchar(255)     | NO   |     | PRODUCT             |                |
      | rep_email                   | varchar(255)     | NO   |     | NULL                |                |
      | order_desk_email            | varchar(255)     | YES  |     | NULL                |                |
      | order_portal_url            | varchar(255)     | YES  |     | NULL                |                |
      | rebate_portal_url           | varchar(255)     | YES  |     | NULL                |                |
      | spiff_submission_url        | varchar(255)     | YES  |     | NULL                |                |
      | rep_phone                   | varchar(255)     | NO   |     | NULL                |                |
      | order_desk_phone            | varchar(255)     | YES  |     | NULL                |                |
      | fax_phone                   | varchar(255)     | YES  |     | NULL                |                |
      | minimum_order_free_freight  | decimal(10,2)    | NO   |     | 0.00                |                |
      | fiscal_year_start           | varchar(255)     | NO   |     | 01-01               |                |
      | program_name                | varchar(255)     | NO   |     | NULL                |                |
      | dfi_program_discount        | decimal(10,2)    | NO   |     | 0.00                |                |
      | vir_percent                 | decimal(10,2)    | NO   |     | 0.00                |                |
      | accrued_mdf_percent         | decimal(10,2)    | NO   |     | 0.00                |                |
      | accrued_coop_percent        | decimal(10,2)    | NO   |     | 0.00                |                |
      | default_payment_method      | varchar(255)     | NO   |     | Open Account        |                |
      | credit_line                 | decimal(10,2)    | NO   |     | 0.00                |                |
      | payment_discount_prepay     | decimal(10,2)    | NO   |     | 0.00                |                |
      | payment_discount_15_day     | decimal(10,2)    | NO   |     | 0.00                |                |
      | payment_discount_30_day     | decimal(10,2)    | NO   |     | 0.00                |                |
      | payment_discount_60_day     | decimal(10,2)    | NO   |     | 0.00                |                |
      | payment_discount_90_day     | decimal(10,2)    | NO   |     | 0.00                |                |
      | payment_discount_120_day    | decimal(10,2)    | NO   |     | 0.00                |                |
      | flooring_company            | varchar(255)     | NO   |     | Wells Fargo Finance |                |
      | standard_flooring_term_days | int(11)          | NO   |     | 30                  |                |
      | created_at                  | timestamp        | YES  |     | NULL                |                |
      | updated_at                  | timestamp        | YES  |     | NULL                |                |
      +-----------------------------+------------------+------+-----+---------------------+----------------+

     */
}
