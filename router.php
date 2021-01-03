<?php

class Router
{
    private function validatePathItems($pathItems)
    {
        $pattern = '/^[a-zA-Z_]+$/';
        if (count($pathItems) < 2)
            return false;

        foreach ($pathItems as $pathItem)
        {
            if (!preg_match($pattern, $pathItem))
                return false;
        }
        return true;
    }

    private function getFileName($pathItems)
    {
        array_pop($pathItems);
        $folder = __DIR__ . '/controllers/';
        $file = implode('/', $pathItems);
        $completePath = $folder . $file . 'Controller.php';

        return $completePath;
    }

    private function getClassName($pathItems)
    {
        array_pop($pathItems);
        $param = array_pop($pathItems);
        return $param . 'Controller';
    }

    private function getMethodName($isGetRequest, $pathItems)
    {
        $lastItem = array_pop($pathItems);
        if ($isGetRequest)
            return "get$lastItem";
        else
            return "post$lastItem";
    }

    private function getData($instance, $methodName, $argsArray)
    {
        $reflectionMethod = new ReflectionMethod($instance, $methodName);
        $arguments = $reflectionMethod->getParameters();
        $methodArgs = [];

        foreach ($arguments as $arg)
        {
            if (isset($argsArray[$arg->name]))
            {
                $methodArgs[$arg->name] = $_REQUEST[$arg->name];
            }
            else
            {
                http_response_code(400);
                die();
            }
        }

        return call_user_func_array(array($instance, $methodName), $methodArgs);
    }

    private function returnResponse($argsArray, $instance, $methodName)
    {
        try
        {
            $data = $this->getData($instance, $methodName, $argsArray);
            if (is_null($data))
                http_response_code(204);
            else
                echo json_encode($data);
        }
        catch (Exception $exception)
        {
            http_response_code(500);
        }
    }

    private function classMethodExists($className, $methodName)
    {
        if (class_exists($className))
        {
            $instance = new $className();
            return method_exists($instance, $methodName);
        }
        else
        {
            return false;
        }
    }

    private function actionExists($fileName, $className, $methodName)
    {
        if (file_exists($fileName))
        {
            require $fileName;
            return $this->classMethodExists($className, $methodName);
        }
        else
        {
            return false;
        }
    }

    public function dispatch()
    {
        $array = $_GET;
        $isGetRequest = true;

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $isGetRequest = false;
        }

        $action = $array['action'];
        $pathItems = explode('/', $action);

        // path not valid
        if (!isset($array['action']) || !$this->validatePathItems($pathItems))
        {
            http_response_code(400);
            die();
        }

        $fileName = $this->getFileName($pathItems);
        $className = $this->getClassName($pathItems);
        $methodName = $this->getMethodName($isGetRequest, $pathItems);

        if ($this->actionExists($fileName, $className, $methodName))
        {
            $instance = new $className();
            $argsArray = $isGetRequest ? $_GET : $_POST;
            $this->returnResponse($argsArray, $instance, $methodName);
        }
        else
        {
            http_response_code(404);
        }
    }
}
