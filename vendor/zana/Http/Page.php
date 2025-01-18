<?php
namespace Zana\Http;

use Exception;
use Zana\Router\Router;
use Zana\Config\Config;

/**
 * Class Page
 * @package Zana\Http
 */
class Page
{
    /**
     * @var string
     */
    protected $view;
    /**
     * @var string
     */
    protected $template;
    /**
     * @var array mixed
     */
    protected $vars = [];
    /**
     * @var string
     */
    protected $output;
    protected $outputFormat;
    protected $module;
    protected $controller;

    /**
     * Page constructor.
     * @param mixed $content
     * @param int $outputFormat
     * @throws Exception
     */
    public function __construct($content = null, $outputFormat = PageFormat::HTML)
    {
        // Page initialization
        $this->outputFormat = $outputFormat;
    }

    /**
     * @param string $var
     * @param mixed $value
     * @return $this
     * @throws HttpException
     */
    public function addVar($var, $value): self
    {
        if (!is_string($var) || is_numeric($var) || empty($var)) {
            throw new HttpException("Invalid variable name", HttpException::INVALID_VAR_NAME);
        }
        $this->vars[$var] = $value;
        return $this;
    }

    /**
     * @param $vars[]
     * @return $this
     */
    public function addVars($vars = []): self
    {
        $this->vars = array_merge($this->vars, $vars);
        return $this;
    }

    /**
     * @return string
     * @throws HttpException
     */
    public function getGeneratedPage()
    {
        if (is_null($this->view) && is_null($this->template) || $this->outputFormat != PageFormat::HTML) {
            return $this->output;
        } elseif (is_null($this->view) && !is_null($this->template)) {
            if (!file_exists($this->template)) {
                throw new HttpException("Template <b>{$this->template}</b> not found", HttpException::TEMPLATE_NOT_FOUND);
            }
            extract($this->vars);
            ob_start();
            require $this->template;
        } elseif (!is_null($this->view) && is_null($this->template)) {
            if (!file_exists($this->view)) {
                throw new HttpException("View <b>{$this->view}</b> not found", HttpException::VIEW_NOT_FOUND);
            }
            extract($this->vars);
            ob_start();
            require $this->view;
        } elseif (!is_null($this->view) && !is_null($this->template)) {
            if (!file_exists($this->view) || !file_exists($this->template)) {
                throw new HttpException("Template <b>{$this->template}</b> and view <b>{$this->view}</b> not found", HttpException::TEMPLATE_N_VIEW_NOT_FOUND);
            }
            extract($this->vars);
            ob_start();
            require $this->view;
            $content = ob_get_clean();
            ob_start();
            require $this->template;
            echo '<div class="text-center p-3">Powered by <a class="link-underline link-underline-opacity-0" href="https://github.com/jeffersonmwanaut/Zana">Zana</a></div>';
            
        }
        return ob_get_clean();
    }

    /**
     * @param string $view
     * @return $this
     * @throws HttpException
     */
    public function setView($view): self
    {
        if (!is_string($view) || empty($view)) {
            throw new HttpException("Invalid view", HttpException::INVALID_VIEW);
        }
        $this->view = Config::get('path')['root'] . '/src/' . $this->module . '/view/' . $this->controller . '/' . $view . '.php';
        return $this;
    }

    /**
     * @param string $template
     * @return $this
     * @throws HttpException
     */
    public function setTemplate($template): self
    {
        if (!is_string($template) || empty($template)) {
            throw new HttpException("Invalid template", HttpException::INVALID_VIEW);
        }

        if(in_array($template, ['base.template', 'zana.template'])) {
            $templateRoot = Config::get('path')['root'] . '/vendor/zana/template';
        } else {
            $templateRoot = Config::get('path')['root'] . '/template';
        }
        $this->template = $templateRoot . '/' . $template . '.php';
        return $this;
    }

    /**
     * Input page content
     * @param mixed $content
     * @param int $outputFormat
     * @return Page
     * @throws HttpException
     */
    public function write($content, $outputFormat = PageFormat::HTML): self
    {
        $this->outputFormat = $outputFormat;

        switch ($this->outputFormat) {
            case PageFormat::XML:
                $this->xml($content);
                break;
            case PageFormat::JSON:
                $this->json($content);
                break;
            case PageFormat::TEXT:
                $this->text($content);
                break;
            default:
                $this->html($content);
                break;
        }
        return $this;
    }

    /**
     * Add HTML code to the page
     * @param $html
     * @return $this
     */
    protected function html($html): self
    {
        $this->output = $html;
        return $this;
    }

    /**
     * @param mixed $param
     * @return Page
     */
    protected function json($param): self
    {
        $this->output = json_encode($param);
        return $this;
    }

    /**
     * Convert the given data to XML format.
     * @param mixed $param
     * @return Page
     * @throws HttpException
     */
    protected function xml($param): self
    {
        // Check if the parameter is null
        if ($param === null) {
            $this->output = '<?xml version="1.0" encoding="UTF-8"?><response></response>';
            return $this;
        }

        // Convert the parameter to XML
        $this->output = $this->arrayToXml($param);
        return $this;
    }

    /**
     * Convert an array or object to XML.
     * @param mixed $data
     * @param SimpleXMLElement|null $xmlData
     * @return string
     */
    private function arrayToXml($data, \SimpleXMLElement $xmlData = null): string
    {
        if ($xmlData === null) {
            $xmlData = new \SimpleXMLElement('<response/>');
        }

        foreach ($data as $key => $value) {
            // Handle numeric keys by converting them to a string
            if (is_numeric($key)) {
                $key = 'item' . $key; // Change numeric keys to item1, item2, etc.
            }

            // If the value is an array or object, recursively convert it
            if (is_array($value) || is_object($value)) {
                $subNode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subNode);
            } else {
                // Add the value as a child node
                $xmlData->addChild($key, htmlspecialchars((string)$value));
            }
        }

        return $xmlData->asXML();
    }

    /**
     * @param string $param
     * @return Page
     * @throws HttpException
     */
    protected function text($param): self
    {
        $this->output = $param;
        return $this;
    }

    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }
}
