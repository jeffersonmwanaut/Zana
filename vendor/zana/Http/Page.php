<?php
namespace Zana\Http;

use Exception;
use Zana\Router\Router;

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

    /**
     * Page constructor.
     * @param mixed $content
     * @param int $outputFormat
     * @throws Exception
     */
    public function __construct($content = null, $outputFormat = PageFormat::HTML)
    {
        switch ($outputFormat) {
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
    }

    /**
     * @param string $var
     * @param mixed $value
     * @return $this
     * @throws HttpException
     */
    public function addVar($var, $value)
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
    public function addVars($vars = [])
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
        if (is_null($this->view) && is_null($this->template)) {
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
    public function setView($view)
    {
        if (!is_string($view) || empty($view)) {
            throw new HttpException("Invalid view", HttpException::INVALID_VIEW);
        }
        $this->view = $view;
        return $this;
    }

    /**
     * @param string $template
     * @return $this
     * @throws HttpException
     */
    public function setTemplate($template = 'base.template')
    {
        if (!is_string($template) || empty($template)) {
            throw new HttpException("Invalid template", HttpException::INVALID_VIEW);
        }
        $this->template = $template;
        return $this;
    }

    /**
     * Input page content
     * @param mixed $content
     * @param int $outputFormat
     * @return Page
     * @throws HttpException
     */
    public function write($content, $outputFormat = PageFormat::HTML)
    {
        switch ($outputFormat) {
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
    protected function html($html)
    {
        $this->output = $html;
        return $this;
    }

    /**
     * @param mixed $param
     * @return Page
     */
    protected function json($param)
    {
        $this->output = json_encode($param);
        return $this;
    }

    /**
     * @param mixed $param
     * @return Page
     */
    protected function xml($param)
    {
        $this->output = $param;
        return $this;
    }

    /**
     * @param string $param
     * @return Page
     * @throws HttpException
     */
    protected function text($param)
    {
        $this->output = $param;
        return $this;
    }
}
