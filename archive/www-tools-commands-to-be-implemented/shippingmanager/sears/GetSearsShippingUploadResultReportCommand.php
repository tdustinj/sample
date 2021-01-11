<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetSearsShippingUploadResultReportCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:get-sears-shipping-upload-result-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the result of the shipping upload report';

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

        $today = new DateTime();
        $today->modify('-7 day');
        $fromDate = $today->format('Y-m-d');
        $toDate = date("Y-m-d");

        $searsReportsToProcess = SearsReport::where('report_status', '=', 'Sent')->where('report_type', '=', 'Shipping ASN')->where('processing_status', '!=', 'processed')->get();
        https://seller.marketplace.sears.com/SellerPortal/api/reports/v1/processing- report/{document-id}?sellerId={sellerId}
        $apiCall = new searsAPIUtil();
        $apiCall->apiMethod = 'GET';
      foreach($searsReportsToProcess as $report) {
          $apiCall->apiUrl = "/reports/v1/processing-report/" . $report->report_id;


          $apiCall->callApi();
          //  print_r($apiCall);
          $xml = simplexml_load_string($apiCall->result);
          print_r($xml);
          if (sizeof($xml)) {

              $json = json_encode($xml);
              $asnResults = json_decode($json, true);

              print_r($asnResults);
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


