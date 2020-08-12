<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{
    /**
     * @Route("/admin/article")
     */
    public function index(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy(
            [],
            ['publicationDate'=>'DESC']
        );
        return $this->render('admin_article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ArticleRepository $articleRepository
     * @param $id
     * @Route("/admin/article/edition/{id}", defaults={"id": null})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository,
        $id)
    {
        if(is_null($id)) { // création
            $article = new Article();
            $article->setPublicationDate(new \DateTime());
        } else{ // modification
            $article = $articleRepository->find($id);
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute('app_adminarticle_index');
            }
        }

        return $this->render(
            'admin_article/edit.html.twig',
            [
                // passage du form au template
                'form'=> $form->createView()
            ]
        );
    }

    /**
     * @param Article $article
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("admin/suppression-article/{id}", requirements={"id": "\d+"})
     */
    public function delete(Article $article, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_adminarticle_index');
    }
}
