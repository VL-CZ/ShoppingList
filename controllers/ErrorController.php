<?php


class ErrorController
{
    /**
     * GET: Error/Error
     * display error page
     */
    public function getError()
    {
        require __DIR__ . '/../templates/error.php';
        exit();
    }
}