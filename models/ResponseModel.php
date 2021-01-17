<?php
require_once __DIR__ . '/JsonResponseObject.php';
require_once __DIR__ . '/HtmlResponseObject.php';

abstract class ResponseModel
{
    protected $data;

    /**
     * ResponseModel constructor.
     */
    protected function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * check if the response is redirect
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * check if the response is JSON
     */
    public function isJson()
    {
        return false;
    }

    /**
     * check if the response is HTML page
     */
    public function isHtml()
    {
        return false;
    }

    /**
     * get data for response
     */
    public function getData()
    {
        return $this->data;
    }
}

class RedirectResponseModel extends ResponseModel
{
    /**
     * RedirectResponseModel constructor.
     */
    public function __construct($targetPage)
    {
        parent::__construct($targetPage);
    }

    public function isRedirect()
    {
        return true;
    }
}

class JsonResponseModel extends ResponseModel
{
    /**
     * RedirectResponseModel constructor.
     */
    protected function __construct()
    {
        parent::__construct(new JsonResponseObject());
    }

    public function isJson()
    {
        return true;
    }
}

class JsonOkResponseModel extends JsonResponseModel
{
    /**
     * JsonOkResponseModel constructor.
     */
    public function __construct($payload)
    {
        parent::__construct();
        $this->data->setPayload($payload);
    }
}

class JsonErrorResponseModel extends JsonResponseModel
{
    /**
     * JsonErrorResponseModel constructor.
     */
    public function __construct($error)
    {
        parent::__construct();
        $this->data->setError($error);
    }
}

class HtmlResponseModel extends ResponseModel
{
    /**
     * HtmlResponseModel constructor.
     * @param $templateFile
     * @param $params
     */
    public function __construct($templateFile, $params)
    {
        parent::__construct(new HtmlResponseObject($templateFile, $params));
    }

    public function isHtml()
    {
        return true;
    }
}