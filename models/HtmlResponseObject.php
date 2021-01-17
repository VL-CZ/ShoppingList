<?php


class HtmlResponseObject
{
    private static $templatesFolder = __DIR__ . '/../templates/';
    private static $headerFile = '_header.php';
    private static $footerFile = '_footer.php';

    /**
     * template file to include
     */
    private $templateFile;

    /**
     * array of params for the template file
     */
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

    /**
     * render the page
     */
    public function render()
    {
        $this->renderHeader();

        // extract params to local variables
        extract($this->params, EXTR_SKIP);

        $templateFilePath = self::$templatesFolder . $this->templateFile . '.php';
        require $templateFilePath;

        $this->renderFooter();
    }

    private function renderHeader()
    {
        require self::$templatesFolder . self::$headerFile;
    }

    private function renderFooter()
    {
        require self::$templatesFolder . self::$footerFile;
    }
}