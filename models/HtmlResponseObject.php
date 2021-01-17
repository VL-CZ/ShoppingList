<?php


class HtmlResponseObject
{
    private static $templatesFolder = __DIR__ . '/../templates/';
    private $templateFile;
    private $params;

    /**
     * HtmlResponseObject constructor.
     * @param $templateFile
     * @param $params
     */
    public function __construct($templateFile, $params)
    {
        $this->templateFile = $templateFile;
        $this->params = $params;
    }

    public function render()
    {
        // extract params to local variables
        extract($this->params, EXTR_SKIP);

        $templateFilePath = self::$templatesFolder . $this->templateFile . '.php';
        require $templateFilePath;
        exit();
    }
}