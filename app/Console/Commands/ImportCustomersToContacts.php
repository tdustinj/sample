<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use App\Models\Contact;
use DB;


class ImportCustomersToContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importCustomersToContacts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Import Customers from Old POS';

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
        // This command will hit walts2 customers database and pull all customer records
        echo "Import customers into contacts";
        $customerCount = DB::connection('oldpos')->select('select count(*) as rec_count from customers');
        $numCustomerRecs = $customerCount[0]->rec_count;



        $customerRecords = array();
        for($i= 0; $i < $numCustomerRecs; $i = $i + 1000){
            $customers = DB::connection('oldpos')->select('select * from customers where 1 limit ' .$i .' ,1000');
            foreach($customers as $customerRec){

                $customerSince = new \DateTime($customerRec->customer_since);

                $newContact = new Contact;


                $newContact->id = $customerRec->id;

                    $newContact->created_at = $customerSince;
                    $newContact->updated_at = $customerSince;
                    $newContact->first_name = $customerRec->FirstName;
                    $newContact->last_name = $customerRec->LastName;

                    $newContact->personal_email = $customerRec->Email;
                    $newContact->work_email = $customerRec->Email_2;
                    $newContact->third_party_email = $customerRec->Email;
                    $newContact->home_phone = $customerRec->HomePhone;
                    $newContact->work_phone = $customerRec->WorkPhone;
                    $newContact->mobile_phone = $customerRec->MobilePhone;
                    $newContact->address = $customerRec->Address;
                    $newContact->address2 = $customerRec->Address2;
                    $newContact->city = $customerRec->City;
                    $newContact->state = $customerRec->State;
                    $newContact->zip = $customerRec->Zip;
                    $newContact->country = 'US';
                    $newContact->source = $customerRec->FoundAboutUs;
                    $state = strtoupper($customerRec->State);
                    if($state == 'AZ' or $state == 'ARIZONA') {
                        $newContact->tax_zone = true;
                    }
                    else{
                        $newContact->tax_zone = false;
                    }
                    try {
                        $newContact->save();
                    }
                    catch(\Illuminate\Database\QueryException $e)
                    {
                        echo "could not insert " . $customerRec->id . "\n\r";
                    }
            }
        }


    }
}
