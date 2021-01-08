<?php

class Router
{
    private $requestParamArrays = [];
    public static $homePageAddress = '?action=Items/Items';

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->requestParamArrays['GET'] = $_GET;
        $this->requestParamArrays['POST'] = $_POST;
        $this->requestParamArrays['DELETE'] = $_GET;
    }

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

    private function getMethodName($requestMethod, $pathItems)
    {
        $lastItem = array_pop($pathItems);
        return strtolower($requestMethod) . $lastItem;
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
                $methodArgs[$arg->name] = $argsArray[$arg->name];
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
            $responseModel = $this->getData($instance, $methodName, $argsArray);

            $data = $responseModel->getData();

            // no data
            if (is_null($data))
            {
                http_response_code(204);
            }
            // redirect response
            else if ($responseModel->isRedirect())
            {
                // redirect to selected page
                $this->redirectTo($data);
            }
            else
            {
                // return JSON data
                echo json_encode($data);
            }
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

    private function isHomePageRequest()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET);
    }

    private function redirectTo($page)
    {
        header("Location: $page", 303);
        exit();
    }

    public function dispatch()
    {
        $array = $_GET;
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // redirect to home
        if ($this->isHomePageRequest())
        {
            $this->redirectTo(self::$homePageAddress);
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
        $methodName = $this->getMethodName($requestMethod, $pathItems);

        if ($this->actionExists($fileName, $className, $methodName))
        {
            $instance = new $className();
            $argsArray = $this->requestParamArrays[$requestMethod];
            $this->returnResponse($argsArray, $instance, $methodName);
        }
        else
        {
            http_response_code(404);
        }
    }
}
