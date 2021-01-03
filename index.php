<?php

require_once __DIR__ . '/router.php';

try
{
    $router = new Router();
    $router->dispatch();
}
catch (Exception $e)
{
    http_response_code(404);
}