<?php

class Router
{
    private $requestParamArrays = [];
    public static $homePageAddress = '?action=List/Items';
    public static $errorPageAddress = '?action=Error/Error';

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->requestParamArrays['GET'] = $_GET;
        $this->requestParamArrays['POST'] = $_POST;
        $this->requestParamArrays['DELETE'] = $_GET;
    }

    /**
     * check if URL path is valid
     * @param $pathItems
     * @return bool
     */
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

    /**
     * get name of the file which contains the Controller
     * @param $pathItems - array of URL items
     * @return string
     */
    private function getFileName($pathItems)
    {
        array_pop($pathItems);
        $folder = __DIR__ . '/controllers/';
        $file = implode('/', $pathItems);
        return $folder . $file . 'Controller.php';
    }

    /**
     * get name of the Controller class
     * @param $pathItems - array of URL items
     * @return string
     */
    private function getClassName($pathItems)
    {
        array_pop($pathItems);
        $param = array_pop($pathItems);
        return $param . 'Controller';
    }

    /**
     * get name of the Controller method
     * @param $requestMethod - given HTTP method
     * @param $pathItems - array of URL items
     * @return string
     */
    private function getMethodName($requestMethod, $pathItems)
    {
        $lastItem = array_pop($pathItems);
        return strtolower($requestMethod) . $lastItem;
    }

    /**
     * call given instance method with args and return the result
     * @param $instance
     * @param $methodName
     * @param $argsArray
     * @return false|mixed
     * @throws ReflectionException
     */
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

    /**
     * @param $argsArray
     * @param $instance
     * @param $methodName
     */
    private function returnResponse($argsArray, $instance, $methodName)
    {
        try
        {
            // get response model
            $responseModel = $this->getData($instance, $methodName, $argsArray);

            // get the data from response model
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
            else if ($responseModel->isJson())
            {
                // return JSON data
                echo json_encode($data);
            }
            else
                throw new Exception("Invalid return type");
        }
        catch (Exception $exception)
        {
            $this->redirectTo(self::$errorPageAddress);
        }
    }

    /**
     * check if selected class has given method
     * @param $className
     * @param $methodName
     * @return bool
     */
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

    /**
     * check if given file contains given class that has given method
     * @param $fileName
     * @param $className
     * @param $methodName
     * @return bool
     */
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

    /**
     * check if the current request is homepage request
     * @return bool
     */
    private function isHomePageRequest()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET);
    }

    /**
     * redirect to location
     * @param $page
     */
    private function redirectTo($page)
    {
        header("Location: $page", 303);
        exit();
    }

    /**
     * dispatch
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // check if it's homepage request
        if ($this->isHomePageRequest())
        {
            $this->redirectTo(self::$homePageAddress);
        }

        $action = $_GET['action'];
        $pathItems = explode('/', $action);

        // path not valid
        if (!isset($_GET['action']) || !$this->validatePathItems($pathItems))
        {
            http_response_code(400);
            die();
        }

        // extract fileName, controller className and methodName from the URL
        $fileName = $this->getFileName($pathItems);
        $className = $this->getClassName($pathItems);
        $methodName = $this->getMethodName($requestMethod, $pathItems);

        // check if the controller action exists
        if ($this->actionExists($fileName, $className, $methodName))
        {
            $instance = new $className();
            $argsArray = $this->requestParamArrays[$requestMethod];

            // return the response
            $this->returnResponse($argsArray, $instance, $methodName);
        }
        else
        {
            http_response_code(404);
        }
    }
}
