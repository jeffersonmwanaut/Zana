<?php namespace Main\Controller;

use Zana\Controller;
use Zana\Router\Router;
use Zana\Config\Config;

use Zana\ABAC\Policy;

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
        $policyConfigFilename = ROOT . '/config/ABAC/policy.json';
        $policy = new Policy($policyConfigFilename);

        $access = [
            "subject"=> "user", 
            "action"=> "view", 
            "resource"=> "public", 
            "environment"=> "production" 
        ];

        if ($policy->checkAccess($access)) {
            echo "Access granted.";
        } else {
            echo "Access denied.";
        }

        return $this->page
            ->addVars([
                'dTitle' => "Website Under Construction"
            ])
            ->setView(ROOT . '/src/Main/view/website-under-construction.php')
            ->setTemplate(ROOT . '/template/base.template.php');
    }
}