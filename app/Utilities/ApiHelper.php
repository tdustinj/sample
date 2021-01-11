<?php

namespace App\Utilities;

use App\Traits\HandlesQueryParams;

abstract class ApiHelper
{
  use HandlesQueryParams;

  protected $baseUrl;
  protected $apiUrlPath;
  protected $method;
  protected $contentType;
  protected $result;
  protected $httpStatusCode;

  public function setBaseUrl($urlArg)
  {
    $this->baseUrl = $urlArg;
    return $this;
  }

  public function getBaseUrl()
  {
    return $this->baseUrl;
  }

  public function setApiUrlPath($urlArg)
  {
    // trim whitespace
    $urlArg = trim($urlArg);
    // strip forward slash at end if it exists
    if (substr($urlArg, -1) == '/') {
      $urlArg = substr($urlArg, 0, -1);
    }
    // adds a forward slash to the beginning if urlArg doesn't start with it
    if (substr($urlArg, 0, 1) != '/') {
      $urlArg = '/' . $urlArg;
    }
    $this->apiUrlPath = $urlArg;
    return $this;
  }

  public function getApiUrlPath()
  {
    return $this->apiUrlPath;
  }

  public function getFullUrl()
  {
    return $this->baseUrl . $this->apiUrlPath . $this->getQueryString();
  }

  public function setMethod($methodArg)
  {
    $this->method = $methodArg;
    return $this;
  }

  public function getMethod()
  {
    return $this->method;
  }

  public function setContentType($contentTypeArg)
  {
    $this->contentType = $contentTypeArg;
    return $this;
  }

  public function getContentType()
  {
    return $this->contentType;
  }

  public function getResult()
  {
    return $this->result;
  }

  public function getHttpStatusCode()
  {
    return $this->httpStatusCode;
  }
}

?>