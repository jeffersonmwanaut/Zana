<?php namespace Main\Controller;

use Zana\Controller;
use Zana\Router\Router;
use Zana\Config\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MainController extends Controller
{
    /**
     * Redirect to the doc if not implemented
     */
    public function index()
    {
        $this->httpResponse->redirect(URL_ROOT . '/' . Router::generateUrl('_WEBSITE_UNDER_CONSTRUCTION'));
    }

    public function websiteUnderConstruction()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Website Under Construction"
            ])
            ->setView(ROOT . '/src/Main/view/website-under-construction.php')
            ->setTemplate(ROOT . '/template/base.template.php');
    }
}