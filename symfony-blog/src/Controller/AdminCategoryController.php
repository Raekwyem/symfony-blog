<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/category")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        // c'est un findAll avec un tri
        $categories = $categoryRepository->findBy([], ['name'=>'ASC']);

        return $this->render('admin_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * defaults : l'id est optionnel dans l'url et vaut null si elle n'est pas présente
     * requirements: l'id doit être nu nombre (\d+ en expression régulière)
     * @Route("/admin/edition-categorie/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        $id){

        if(is_null($id)) { // création
            $category = new Category();
        } else{ // modification
            $category = $categoryRepository->find($id);
        }

        // création du formulaire lié à l'entité Category
        $form = $this->createForm(CategoryType::class, $category);

        // traitement de la requête par le formulaire
        $form->handleRequest($request);

        // si le form a été envoyé
        if($form->isSubmitted()) {
            // si la validation des annotations @Assert dans l'entité passe
            if ($form->isValid()) {
                // enregistrement de la catégorie de bdd
                $entityManager->persist($category);
                $entityManager->flush();

                return $this->redirectToRoute('app_admincategory_index');
            }
        }

        return $this->render(
            'admin_category/edit.html.twig',
            [
                // passage du form au template
                'form'=> $form->createView()
            ]
        );

    }

    /**
     * @Route("admin/suppression-categorie/{id}", requirements={"id": "\d+"})
     */
    public function delete(Category $category, EntityManagerInterface $entityManager){
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_admincategory_index');
    }
}
