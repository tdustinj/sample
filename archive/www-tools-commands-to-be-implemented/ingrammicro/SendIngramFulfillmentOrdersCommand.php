<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendIngramFulfillmentOrdersCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:submit-ingram-fulfillment-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Ingram Orders to ';

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
        //Cronitor
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://cronitor.link/i1DIzP/run");
        $response = curl_exec($curl);
        //

        define('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');

        // first get the UNSENT amazon multi channel orders

        $unsentIngramFulfillmentOrders = OrdersIngramMicroFulfillmentnt::where('status', '=', 'NOT_SENT')->get();
        //$unsentIngramFulfillmentOrders = OrdersIngramMicroFulfillmentnt::where('CustomerPO', '=', 10408)->get();


        if (sizeof($unsentIngramFulfillmentOrders)) {
            foreach($unsentIngramFulfillmentOrders as $order){
                $orderXml = $this->getOrderRequestXml($order);
                //dd($orderXml);

                $response = $this->sendOrder($orderXml);
                print_r($response);
                $response =  html_entity_decode($response);
                   print_r($response);

                $xml =  simplexml_load_string($response);
                $json = json_encode($xml);
                $result = json_decode($json, TRUE);

                // todo Troy add information to transaction_shipping and orders_ingram_micro_fullfillmentf
                print_r($result);

                // Save xml response in table

                $order->response_xml = $response;
                $order->document_id = $result['TransactionHeader']['DocumentID'];

                // change order status to Sent
                if (!is_array($result['TransactionHeader']['ErrorStatus'])) {
                    $order->status = $result['TransactionHeader']['ErrorStatus'];
                    $order->save();
                    $this->sendErrorEmail($result['TransactionHeader']['ErrorStatus'], $order->CustomerPO);
                } else {
                    $order->BranchOrderNumber = $result['OrderInfo']['OrderNumbers']['BranchOrderNumber'];
                    $order->status = "SENT";
                    $order->save();

                }
                             
            }

        }

    }


    function sendErrorEmail($errorMsg, $po) {

        $data = array('Error' => $errorMsg);
        $data['po'] = $po;
        Mail::send('admin.defaultEmail', $data, function($message) use ($data) {
            //$message->to('tjones@walts.com', 'Troy Jones')
            $message->to('asmock@walts.com', 'Allison Smock')
            ->cc('tjones@walts.com', 'Troy Jones')
            ->subject("Ingram Micro Order Submit Error : PO = " . $data['po']);
        }); 
        echo "sent\n";

    }



    function getOrderRequestXml($order){
     $autoRelease = "H";
     $carrierCode = "AH";
        if($order->Freight == 'Non Freight'){
            $autoRelease = 'H'; // will need to be 1
            $carrierCode = "UG";
        }
        //Auto Realse 1 for Parcel
        // LTL H
        $xml = '<?xml version="1.0" encoding="UTF-8"?><OrderRequest>
  <Version>2.0</Version>
  <TransactionHeader>
    <SenderID>MD</SenderID>
    <ReceiverID>WALTS</ReceiverID>
    <CountryCode>MD</CountryCode>
    <LoginID>XmLuiDWtvS</LoginID>
    <Password>Hen18jWgQi</Password>
    <TransactionID>' . $order->TransactionID . '</TransactionID>
    </TransactionHeader>
  <OrderHeaderInformation>';
    $xml .=  '
    <AddressingInformation>
      <CustomerPO>' . $order->CustomerPO . '</CustomerPO>
      <ShipToAttention>' . $order->ShipToAttention . '</ShipToAttention>
      <EndUserPO>' . $order->OrderId_OrderItemId_Key . '</EndUserPO>
      <ShipTo>
        <Address>
          <ShipToAddress1>' . $order->ShipToAttention . ' ' . $order->ShipToAddress3 . '</ShipToAddress1>
          
          <ShipToAddress2>' . $order->ShipToAddress2 . '</ShipToAddress2>
         
          <ShipToAddress3>' . $order->ShipToAddress3 . '</ShipToAddress3>
          
          <ShipToCity>' . $order->ShipToCity . '</ShipToCity>
          <ShipToProvince>' . $order->ShipToProvince . '</ShipToProvince>
          <ShipToPostalCode>' . $order->ShipToPostalCode . '</ShipToPostalCode>
           <ShipToCountryCode>US</ShipToCountryCode>
               </Address>
       </ShipTo>
    </AddressingInformation>
    <ProcessingOptions>
    <CarrierCode>' . $carrierCode . '</CarrierCode>
      <AutoRelease>' . $autoRelease . '</AutoRelease>
      <ShipmentOptions>
        <BackOrderFlag>Y</BackOrderFlag>
        <SplitShipmentFlag>Y</SplitShipmentFlag>
        <SplitLine>Y</SplitLine>
        <ShipFromBranches></ShipFromBranches>
      </ShipmentOptions>
      </ProcessingOptions>
    </OrderHeaderInformation>
  <OrderLineInformation>
  <ProductLine>
     <SKU>' . $order->SKU . '</SKU>
      <Quantity>' . $order->Quantity . '</Quantity>
      <CustomerLineNumber>1</CustomerLineNumber>
      <UPC></UPC>
   </ProductLine>
    <CommentLine>
      <CommentText>Thank You from Walts!</CommentText>
    </CommentLine>
  </OrderLineInformation>
</OrderRequest>';
        return $xml;

    }
    function sendOrder($xml)
    {
        $request = 'https://newport.ingrammicro.com/';

        $session = curl_init($request);
        $putString = stripslashes($xml);
        $putData = tmpfile();
        fwrite($putData, $putString);
        fseek($putData, 0);
// Set the POST options.
        curl_setopt ($session, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml", "Accept: application/xml"));
        curl_setopt($session, CURLOPT_HEADER, false);
        //  curl_setopt($session, CURLOPT_HTTPHEADER, $header_array);
        curl_setopt($session, CURLOPT_POST, true);
       // curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($session, CURLOPT_INFILE, $putData);
        curl_setopt($session, CURLOPT_INFILESIZE, strlen($putString));
// Do the POST and then close the session
        $response = curl_exec($session);
        return $response;
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

/*
 * Name	Description	Required	Values
MarketplaceId	The marketplace the fulfillment order is placed against.	No	MarketplaceId values: see Amazon MWS endpoints and MarketplaceId values.
Type: xs:string

SellerFulfillmentOrderId	A fulfillment order identifier that you create to track your fulfillment order. The SellerFulfillmentOrderId must be unique for each fulfillment order that you create. If your system already creates unique order identifiers, then these might be good values to use for your SellerFulfillmentOrderId values.	Yes	Maximum: 40 characters
Type: xs:string

FulfillmentAction	Specifies whether the fulfillment order should ship now or have an order hold put on it.	No	FulfillmentAction values:
Ship - The fulfillment order ships now.
Hold - An order hold is put on the fulfillment order.
Default: Ship

Type: xs:string

DisplayableOrderId	A fulfillment order identifier that you create. This value displays as the order identifier in recipient-facing materials such as the outbound shipment packing slip. The value of DisplayableOrderId should match the order identifier that you provide to your customer. You can use the SellerFulfillmentOrderId for this value or you can specify an alternate value if you want your customer to reference an alternate order identifier.	Yes	An alpha-numeric or ISO 8859-1 compliant string from one to 40 characters in length. Cannot contain two spaces in a row. Leading and trailing white space is removed.
Type: xs:string

DisplayableOrderDateTime	The date of your fulfillment order. Displays as the order date in customer-facing materials such as the outbound shipment packing slip.	Yes	In ISO 8601 date time format.
Type: xs:dateTime

DisplayableOrderComment	Order-specific text that appears in customer-facing materials such as the outbound shipment packing slip.	Yes	Maximum: 1000 characters
Type: xs:string

ShippingSpeedCategory	The shipping method for your fulfillment order.	Yes	ShippingSpeedCategory values:
Standard - Standard shipping method.
Expedited - Expedited shipping method.
Priority - Priority shipping method.
ScheduledDelivery - Scheduled Delivery shipping method. For more information, see Scheduled Delivery.
Note: Shipping method service level agreements vary by marketplace. See the Amazon Seller Central website in your marketplace for shipping method service level agreements and fulfillment fees.
Type: xs:string

DestinationAddress	The destination address for the fulfillment order.	Yes	Type: Address
FulfillmentPolicy	Indicates how unfulfillable items in a fulfillment order should be handled.	No	FulfillmentPolicy values:
FillOrKill - If an item in a fulfillment order is determined to be unfulfillable before any shipment in the order moves to the Pending status (the process of picking units from inventory has begun), then the entire order is considered unfulfillable. However, if an item in a fulfillment order is determined to be unfulfillable after a shipment in the order moves to the Pending status, Amazon cancels as much of the fulfillment order as possible. See the FulfillmentShipment datatype for shipment status definitions.
FillAll - All fulfillable items in the fulfillment order are shipped. The fulfillment order remains in a processing state until all items are either shipped by Amazon or cancelled by the seller.
FillAllAvailable - All fulfillable items in the fulfillment order are shipped. All unfulfillable items in the order are cancelled by Amazon.
Default: FillOrKill

Type: xs:string

NotificationEmailList	A list of email addresses that you provide that are used by Amazon to send ship-complete notifications to your customers on your behalf.	No	Maximum: 64 characters per email address
Type: List of type: xs:string

CODSettings	The COD (Cash On Delivery) charges for a COD order.	No	The CODSettings request parameter is valid only in China (CN) and Japan (JP). Specifying CODSettings in marketplaces other than CN and JP returns an error.
Type: CODSettings

Items	A list of items to include in the fulfillment order preview, including quantity.	Yes	Type: List of CreateFulfillmentOrderItem
DeliveryWindow	Specifies the time range within which your Scheduled Delivery fulfillment order should be delivered.
Important:
The StartDateTime and EndDateTime values of the DeliveryWindow request parameter must be specified exactly as they were returned by your previous call to the GetFulfillmentPreview operation. If you specify StartDateTime and EndDateTime values that were not returned by a previous call to the GetFulfillmentPreview operation, the service returns an error.
It is possible that delivery windows that were available when you called the GetFulfillmentPreview operation will not be available when call the CreateFulfillmentOrder operation. If this happens the service returns an error. In this case you need to call the GetFulfillmentPreview operation again to get the currently-available delivery windows.
For more information, see Scheduled Delivery.

No. Required only if ShippingSpeedCategory = ScheduledDelivery.	The DeliveryWindow request parameter is valid only in Japan (JP). Specifying DeliveryWindow in marketplaces other than JP returns an error.
Type: DeliveryWindow


 *
 */
