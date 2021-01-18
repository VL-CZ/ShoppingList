<?php
require_once __DIR__ . '/../models/ResponseModel.php';

class ErrorController
{
    /**
     * GET: Error/Error
     * display error page
     */
    public function getError()
    {
        return new HtmlResponseModel('error', []);
    }
}