<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use App\Models\Quote;
use DB;


class ImportInvoicesAsQuotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importInvoicesAsQuotes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Import Invoices as Quotes';

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
    public function handle()
    {



        $invoiceCount = DB::connection('oldpos')->select('select max(invoicenum) as rec_count from invoice where 1');
        $numInvoiceRecords = $invoiceCount[0]->rec_count;
        $start = DB::table('quote')
            ->select(DB::raw('max(id) as last_imported_invoicenum'))
            ->get();

        if(sizeof($start) AND ( $start[0]->last_imported_invoicenum != null)) {
            $startImportAt = $start[0]->last_imported_invoicenum;
        }
        else{
            $startImportAt = 10001;
        }
        echo $numInvoiceRecords;
        echo "\n\r Starting at $startImportAt \n\r";
        for ($i = $startImportAt; $i < $numInvoiceRecords; $i = $i + 1000) {
            echo "i=" . $i . "\n\r";
            echo "select * from invoice where 1 limit $i , 1000". "\n\r";
            $invoices = DB::connection('oldpos')->select('select * from invoice where 1  limit ' .  $i . ' , 1000');
            foreach ($invoices as $invoice) {
                $invoiceTypes = explode(':',ImportInvoicesAsQuotes::getQuoteTypeQuoteClass($invoice->invoice_type, $invoice->bill_id));

                $createDate = new \DateTime($invoice->create_date);
                if($invoice->finished_date != '0000-00-00')
                {
                    $finishedDate = new \DateTime($invoice->finished_date);
                }
                else{
                    $finishedDate = new \DateTime($invoice->create_date);
                }

                $newQuote = new Quote();


                $newQuote->id = $invoice->invoicenum;

                $newQuote->created_at = $createDate;
                $newQuote->updated_at = $finishedDate;
                $newQuote->sold_contact_id = $invoice->sold_id;
                $newQuote->ship_contact_id = $invoice->ship_id;
                $newQuote->bill_contact_id = $invoice->bill_id;
                $newQuote->sold_account_id = 0;
                $newQuote->quote_type = $invoiceTypes[1];
                $newQuote->quote_class = $invoiceTypes[0];
                $newQuote->quote_status = 'Not Converted';
                $newQuote->primary_sales_id = 1;//$invoice->employee_id_1;
                $newQuote->second_sales_id = 2;//$invoice->employee_id_2;
                $newQuote->third_sales_id = 3;//$invoice->employee_id_3;
                $newQuote->product_total = $invoice->invoice_total - $invoice->tax; // estimate to be converted with transaction serial importing
                $newQuote->labor_total = 0; // to be update with labor detail conversion
                $newQuote->shipping_total = 0;
                if($invoice->tax > 0){
                    $newQuote->taxable = true;
                    $newQuote->tax_zone = 'AZ';
                }
                else{
                    $newQuote->taxable = false;
                    $newQuote->tax_zone = 'NA';
                }
                $newQuote->tax_total = $invoice->tax;
                $newQuote->total = $invoice->invoice_total;
                $newQuote->notes = "<invoiceComments>" . $invoice->comments . "</invoiceComments>
                    <invoiceWorkDone>" . $invoice->work_done . "</invoiceWorkDone>
                    <invoiceComplaint>" . $invoice->complaint . " " . $invoice->complaint2 . "</invoiceComplaint>
                    <invoiceEstimate>" . $invoice->estimate . "</invoiceEstimate>";
                $newQuote->lead_source = $invoice->referal;
                $newQuote->primary_development = $invoiceTypes[2];
                $newQuote->primary_product_interest = 'NA';
                $newQuote->primary_feature_interest = 'NA';
                $newQuote->demo_affinity = 'NA';
                if($invoice->finished_date == "0000-00-00" ){
                    $newQuote->approval_status = "Not Approved";
                }
                else{
                    $newQuote->approval_status = "Approved";
                }

                $newQuote->approval_status_notes = $invoice->comments;

                try {
                    $newQuote->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    echo "could not insert " . $invoice->invoicenum . "\n\r" . $e->getMessage();
                }
            }
        }


    }
    function getQuoteTypeQuoteClass($invoiceType, $bill_id){
        $invoiceAttributes = '';
        switch($invoiceType){
            case 'Pre Installation Walk' :
                $invoiceAttributes = 'B2C:Installation';
                break;
            case 'B2B Local Resale' :
                $invoiceAttributes =  'B2B:Carry Out Sale';
                break;
            case 'B2B Out Of State' :
                $invoiceAttributes =  'B2B:Shipping Sale';
                break;
            case 'B2B Sale In State' :
                $invoiceAttributes =  'B2B:Carry Out Sale';
                break;
            case 'Carry In Service' :
                $invoiceAttributes =  'B2C:Service';
                break;
            case 'Carry Out Sale' :
                $invoiceAttributes =  'B2C:Carry Out Sale';
                break;
            case 'Chat Sale In State' :
                $invoiceAttributes =  'B2C:Delivery Sale';
                break;
            case 'Chat Sale Out Of State' :
                $invoiceAttributes =  'B2C:Shipping Sale';
                break;
            case 'Delivery Sale' :
                $invoiceAttributes =  'B2C:Delivery Sale';
                break;
            case 'Installation' :
                $invoiceAttributes =  'B2C:Installation';
                break;
            case 'Installation Contractor' :
                $invoiceAttributes =  'B2B:Installation';
                break;
            case 'Marketplace In State' :
                $invoiceAttributes =  'B2C:Shipping Sale';
                break;
            case 'Marketplace Sale' :
                $invoiceAttributes =  'B2C:Shipping Sale';
                break;
            case 'Phone Sale Out Of State' :
                $invoiceAttributes =  'B2C:Shipping Sale';
                break;
            case 'Return Authorization Sale' :
                $invoiceAttributes =  'B2C:Delivery Sale';
                break;
            case 'Sales Call' :
                $invoiceAttributes =  'B2C:Installation';
                break;
            case 'Sales Contact' :
                $invoiceAttributes =  'B2C:Installation';
                break;
            case 'Service Call' :
                $invoiceAttributes =  'B2B:Service';
                break;
            case 'Walts.com Sale In State' :
                $invoiceAttributes =  'B2C:Shipping Sale';
                break;
            case 'Walts.com Sale Out Of State' :
                $invoiceAttributes =  'B2C:Shipping Sale';
                break;
            default :
                $invoiceAttributes = 'B2C:Delivery Sale';

        }
        switch($bill_id){
            case 13091 :
                $invoiceAttributes .= ':3PMP';
                break;
            case 56511 :
                $invoiceAttributes .= ':3PMP';
                break;
            case 64744 :
                $invoiceAttributes .= ':3PMP';
                break;
            case 78597 :
                $invoiceAttributes .= ':3PFN'; // 3rd party like Kibo
                break;
            case 85113 :
                $invoiceAttributes .= ':WALTS.COM';
                break;
            case 109219 :
                $invoiceAttributes .= ':3PMP';
                break;
            case 115666 :
                $invoiceAttributes .= ':3PMP';
                break;
            case  119819:
                $invoiceAttributes .= ':3PFN';
                break;
            case 116988 :
                $invoiceAttributes .= ':3PMP';
                break;
            case 141780 :
                $invoiceAttributes .= ':3PMP';
                break;
            case 131282 :
                $invoiceAttributes .= ':3PMP';
                break;
            case 132839 :
                $invoiceAttributes .= ':WALTS.COM';
                break;
            case 135516 :
                $invoiceAttributes .= ':WALTS.COM';
                break;
            case 132923 :
                $invoiceAttributes .= ':3PMP';
                break;
            case 140659 :
                $invoiceAttributes .= ':3PMP';
                break;
            default :
                $invoiceAttributes .= ':STORE';

        }
        return $invoiceAttributes;
    }
}
