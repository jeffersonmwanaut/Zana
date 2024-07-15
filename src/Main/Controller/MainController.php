<?php namespace Main\Controller;

use Zana\Controller;
use Zana\Router\Router;

class MainController extends Controller
{
    public function index()
    {
        $this->httpResponse->redirect(Router::generateUrl('_UNDER_CONSTRUCTION'));
    }

    public function underConstruction()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Under Construction"
            ])
            ->setView('under-construction')
            ->setTemplate('base.template');
    }
}