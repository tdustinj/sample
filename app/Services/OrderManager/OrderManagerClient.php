<?php

namespace App\Services\OrderManager;

use App\Services\OrderManager\OrderManagerClientContract;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7;
use App\Events\OrderDoesNotExist;

class OrderManagerClient implements OrderManagerClientContract
{
    protected $baseUrl;


	public function __construct()
	{

		$this->baseUrl = env('ORDER_MANAGER_API_ENDPOINT', 'https://test-ordermanager.walts.com/api/export/orders/');

	}

    public function getAvailableOrdersToImport($platform = "all", $offset, $limit){
        //list of orders
        $guzzle = new Client([
                'base_uri' => 'https://test-ordermanager.walts.com/',
                'timeout' => 5.0
        ]);

        $response = $guzzle->request('GET', 'api/orders/available/' . $offset . '/' . $limit, [
                'headers' => [
                    'Client' => 'om-ospos@walts.com',
                    'Secret' => 'd1OD0CKDydk8MWk',
                ],
        ]);
        // var_dump($response);
        return $response->getBody()->getContents();

    }

    public function setOrderImported($orderManagerOrderId, $importOrderId){
        //set marked imported orders.
        $guzzle = new Client([
                'base_uri' => 'https://test-ordermanager.walts.com/',
                'timeout' => 5.0
        ]);
        
        $response = $guzzle->request('GET', 'api/orders/setOrderExported/' . $orderManagerOrderId . '/' . $importOrderId, [
                'headers' => [
                    'Client' => 'om-ospos@walts.com',
                    'Secret' => 'd1OD0CKDydk8MWk',
                ],
        ]);

        // print_r($response->getBody()->getContents());
        return $response->getBody()->getContents();
        // orders/setOrderExported/{OrderManagerOrderId}/{importOrderId}
    }

    public function getFullOrder($unifiedOrderId){
        $orderExists = true;
        //use that list to get full order.
        $guzzle = new Client([
                'base_uri' => 'https://test-ordermanager.walts.com/',
                'timeout' => 5.0,
                'http_errors'=> true
        ]);
        try{
            $response = $guzzle->request('GET', 'api/orders/getFullOrder/' . $unifiedOrderId, [
                    'headers' => [
                        'Client' => 'om-ospos@walts.com',
                        'Secret' => 'd1OD0CKDydk8MWk',
                    ],
            ]);
            $response = json_decode($response->getBody()->getContents());
        } catch (ServerException $e) {
            /* how to handle Guzzle exception -Aaron */
           $error = json_decode($e->getResponse()->getBody()->getContents());
           print_r($error->data);
           $response = $error->data;
           //Check if the error had to to do with the order not existing anymore.
           // $errorString = "Order with id of " . $unifiedOrderId . " does not exist.";
           // if($error->data->exceptionMessage === $errorString){
           //      $orderExists = false;
           //      $response = $error->data;
           //      event(new OrderDoesNotExist($unifiedOrderId));
           // }
        }

        // print_r($response->getStatusCode());
        // $response->getBody()->getContents();

        //return array($orderExists, $response);
        return $response;

    }
}

?>