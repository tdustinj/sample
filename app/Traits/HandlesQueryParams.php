<?php

namespace App\Traits;

trait HandlesQueryParams
{
	protected $queryParams = array();

	public function addQueryParameter(array $queryParamArg)
	{
		if (!$this->_isAssociativeArray($queryParamArg)) {
			throw new \Exception('Query parameter argument must be an associative array: ["somekey" => "someValue"]');
		}

		$this->queryParams = array_merge($this->queryParams, $queryParamArg);
		return $this;
	}

	public function getQueryString()
	{
		return (empty($this->queryParams) ? '' : '?' . http_build_query($this->queryParams));
	}

	protected function _isAssociativeArray($arg)
	{
		return is_array($arg) && (array_keys($arg) !== range(0, sizeof($arg) - 1));
	}
}

?>