<?php namespace Main\Controller;

use Zana\Controller;
use Zana\Router\Router;
use Zana\Router\Route;

class MainController extends Controller
{
    public function index()
    {
        $this->httpResponse->redirect(Router::generateUrl(Route::UNDER_CONSTRUCTION));
        //$this->httpResponse->redirect(Router::generateUrl(Route::UNDER_MAINTENANCE));
    }

    public function error404()
    {
        return $this->page
            ->addVars([
                'dTitle' => "404 Error: Page Not Found"
            ])
            ->setView('404')
            ->setLayout('base.layout');
    }

    public function underConstruction()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Under Construction"
            ])
            ->setView('under-construction')
            ->setLayout('base.layout');
    }

    public function navigateBack()
    {
        $previousUrl = \Zana\Http\PageStack::pop();
        $this->httpResponse->redirect($previousUrl);
    }

    public function underMaintenance()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Under Maintenance"
            ])
            ->setView('under-maintenance')
            ->setLayout('base.layout');
    }
}