<?php


class JsonResponseObject
{
    public $ok;
    public $payload;
    public $error;

    /**
     * JsonResponseObject constructor.
     */
    public function __construct()
    {
    }

    /**
     * set payload of this object
     * @param $payload
     */
    public function setPayload($payload)
    {
        $this->ok = true;
        $this->payload = $payload;
    }

    /**
     * set error of this object
     * @param $error
     */
    public function setError($error)
    {
        $this->ok = false;
        $this->error = $error;
    }
}