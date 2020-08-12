<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(ArticleRepository $articleRepository)
    {
        $article = $articleRepository->findAll();

        return $this->render(
            'index/index.html.twig',
            ['article'=>$article]
        );
    }

    /**
     * @Route("/faq")
     */
    public function faq(){
        return $this->render('index/faq.html.twig');
    }

    /**
     * @Route("/bonjour/{firstname}")
     */
    public function hello($firstname){
        return $this->render(
            'index/hello.html.twig',
            [
                'prenom' => $firstname
            ]
        );
    }
}
