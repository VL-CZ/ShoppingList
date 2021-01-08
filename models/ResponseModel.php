<?php


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

    public function isRedirect()
    {
        return false;
    }

    public function isJsonOk()
    {
        return false;
    }

    public function isJsonError()
    {
        return false;
    }

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

class JsonOkResponseModel extends ResponseModel
{
    /**
     * RedirectResponseModel constructor.
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }

    public function isJsonOk()
    {
        return true;
    }
}