<?php namespace Doc\Controller;

use Zana\Controller;

class DocController extends Controller
{
    public function index()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Zana - Accueil"
            ])
            ->setView(ROOT . '/src/Doc/view/default.php')
            ->setTemplate(ROOT . '/template/zana.template.php');
    }

    public function download()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Zana - Téléchargement"
            ])
            ->setView(ROOT . '/src/Doc/view/download.php')
            ->setTemplate(ROOT . '/template/zana.template.php');
    }

    public function structure()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Zana - Structure"
            ])
            ->setView(ROOT . '/src/Doc/view/structure.php')
            ->setTemplate(ROOT . '/template/zana.template.php');
    }

    public function example()
    {
        return $this->page
            ->addVars([
                'dTitle' => "Zana - Exemple"
            ])
            ->setView(ROOT . '/src/Doc/view/example.php')
            ->setTemplate(ROOT . '/template/zana.template.php');
    }
}