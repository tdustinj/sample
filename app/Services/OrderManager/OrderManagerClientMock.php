<?php

namespace App\Services\OrderManager;

use App\Services\OrderManager\OrderManagerClientContract;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7;
use App\Events\OrderDoesNotExist;

class OrderManagerClientMock implements OrderManagerClientContract
{
    protected $baseUrl;

	public function __construct()
	{
		$this->baseUrl = '';
	}

    public function getAvailableOrdersToImport($platform = "all", $offset, $limit)
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
    }

    public function setOrderImported($orderManagerOrderId, $importOrderId)
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
    }

    public function getFullOrder($unifiedOrderId)
    {
        throw new Exception('Mock service -- ' . self::class . ' -- not implemented');
    }
}

?>