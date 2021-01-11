<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateShippingTransactionsWithTrackingFromIngramASNCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:update-shipping-transactions-with-tracking-from-ingram-asn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Shipping Transactions with drop ship ingram tracking information';

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
    public function fire()
    {
        date_default_timezone_set('America/Phoenix');
        $right_now = date("Y-m-d h:m:s");
        $date_shipped = date("Y-m-d");
        // Fetch SHIPPED MCO's and update shipping_transactions_table
        $asnList = IngramASNTransaction::where('status', '=', 'NEW')->get();
        if (sizeof($asnList)) {
            $i = 1;
            foreach ($asnList as $asn) {
                $xml = simplexml_load_string($asn->xml_body);
                $json = json_encode($xml);
                $result = json_decode($json, TRUE);
                print_r($result);
                $fulfillMentOrder = OrdersIngramMicroFulfillmentnt::where('CustomerPO', '=', $result["DespatchAdviceHeader"]["CustomerPO"])->get();
                if (sizeof($fulfillMentOrder)) {
                    $fulfillMentOrder[0]->ShippingTrackingNumber = $result["LineHeader"]["LineItem"]["PackageHeader"]["IdentificationHeader"]["Identification"]["@attributes"]["TrackingNumber"];
                    if(isset($result["ConsignmentHeader"]["CarrierName"])) {
                        $fulfillMentOrder[0]->ShippingCarrier = $result["ConsignmentHeader"]["CarrierName"];
                    }
                    else{
                        $fulfillMentOrder[0]->ShippingCarrier = 'NOT PROVIDED IN ASN';
                    }
                    $fulfillMentOrder[0]->ingram_ship_status = 'ship_complete';
                    $fulfillMentOrder[0]->ShipDate = $asn->created_at;
                    $str_length = strlen($result["DespatchAdviceHeader"]["DespatchAdviceNumber"]) - 6;

                    $fulfillMentOrder[0]->ingram_invoice_num = substr($result["DespatchAdviceHeader"]["DespatchAdviceNumber"], 0, $str_length);

                    echo $i . ". " . $result["LineHeader"]["LineItem"]["PackageHeader"]["IdentificationHeader"]["Identification"]["@attributes"]["TrackingNumber"] . " "
                        . $fulfillMentOrder[0]->ShippingCarrier. "\n\r";

                    $SerialNumber = 'NA';
                    if (!is_array($result["LineHeader"]["LineItem"]["Product"]["SerialNumberHeader"]["SerialNumber"])) {
                        $SerialNumber = $result["LineHeader"]["LineItem"]["Product"]["SerialNumberHeader"]["SerialNumber"];
                    }
                    else{
                        print_r($result["LineHeader"]["LineItem"]["Product"]);
                    }

                    // $fulfillMentOrder[0]->shipping_transactions_table = 'NOT_UPDATED';
                    //now we have to update the original in OLDPOS
                    // gget the OriginalTID from the Ingram Order that is the tid in transaction_serial
                    $tid = $fulfillMentOrder[0]->OriginalTid;
                    $updateQuery = "update transaction_serial set serial = '$SerialNumber' where tid = '$tid' and serial = '' limit 1";
                    echo $updateQuery . "\n\r";
                    DB::connection('mysql-walts2')->update($updateQuery);
                    $fulfillMentOrder[0]->save();
                    $asn->status = 'processed';
                    $asn->save();
                    $i++;

                }
                else{
                    echo "Ignoring this one" ;
                    print_r($result);
                    $asn->status = 'ignored';
                    $asn->save();

                }
            }

        }


    }














    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('example', InputArgument::OPTIONAL, 'An example.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example.', null),
        );
    }

//To remove all the hidden text not displayed on a webpage
    public function strip_html_tags($str)
    {
        $str = preg_replace('/(<|>)\1{2}/is', '', $str);
        $str = preg_replace(
            array(// Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
            ),
            "", //replace above with nothing
            $str);
        $str = $this->replaceWhitespace($str);
        $str = strip_tags($str);
        return $str;
    }

//To replace all types of whitespace with a single space
    public function replaceWhitespace($str)
    {
        $result = $str;
        foreach (array(
                     "  ", " \t", " \r", " \n",
                     "\t\t", "\t ", "\t\r", "\t\n",
                     "\r\r", "\r ", "\r\t", "\r\n",
                     "\n\n", "\n ", "\n\t", "\n\r",
                 ) as $replacement) {
            $result = str_replace($replacement, $replacement[0], $result);
        }
        return $str !== $result ? $this->replaceWhitespace($result) : $result;
    }


}


