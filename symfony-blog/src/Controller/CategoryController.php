<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}")
     * @param CategoryRepository $categoryRepository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        CategoryRepository $categoryRepository,
        $id,
        ArticleRepository $articleRepository)
    {
        $category = $categoryRepository->find($id);

        $articles = $articleRepository->findBy(
            ['category'=>$category],
            ['publicationDate'=>'DESC'],
            3
        );

        //dump($articles);

        return $this->render('category/index.html.twig', [
            'category' => $category,
            'article'=> $articles
        ]);
    }

    /**
     * @Route("/categories/list")
     */
    public function list(CategoryRepository $categoryRepository){
        $category = $categoryRepository->findAll();

        return $this->render(
          'category/list.html.twig', ['category'=> $category]
        );
    }

    public function menu(CategoryRepository $categoryRepository){
        $categories = $categoryRepository->findAll();

        return $this->render(
          'category/menu.html.twig',
          ['categories' => $categories]
        );
    }
}
