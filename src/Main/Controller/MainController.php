<?php namespace Main\Controller;

use Zana\Controller;
use Zana\Router\Router;
use Zana\Router\Route;

class MainController extends Controller
{
    public function index()
    {
        $this->httpResponse->redirect(Router::generateUrl(Route::UNDER_CONSTRUCTION));
    }

    public function error404()
    {
        return $this->page
            ->addVars([
                'dTitle' => "404 Error: Page Not Found"
            ])
            ->setView('404')
            ->setTemplate('base.template');
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

    public function navigateBack()
    {
        $previousUrl = \Zana\Http\PageStack::pop();
        $this->httpResponse->redirect($previousUrl);
    }
}