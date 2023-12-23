<?php namespace Main\Controller;

use Zana\Controller;
use Zana\Router\Router;

class MainController extends Controller
{
    public function index()
    {
        $this->httpResponse->redirect(URL_ROOT . '/' . Router::generateUrl('_UNDER_CONSTRUCTION'));
    }

    public function underConstruction()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Under Construction"
            ])
            ->setView(ROOT . '/src/Main/view/under-construction.php')
            ->setTemplate(ROOT . '/template/base.template.php');
    }
}