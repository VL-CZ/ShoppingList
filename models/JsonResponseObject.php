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

    public function setPayload($payload)
    {
        $this->ok = true;
        $this->payload = $payload;
    }

    public function setError($error)
    {
        $this->ok = false;
        $this->error = $error;
    }
}