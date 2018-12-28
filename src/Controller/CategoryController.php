<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categorie/add", name="categorie_add")
     * $request va contenir les infos de la requête http et notamment $_GET ou $_POST
     */
    public function addCategorie(Request $request)
    {
        //je crée un nouvel objet Categorie
        $categorie = new Category();

        //je crée mon formulaire à partir de cette classe
        $form = $this->createForm(CategoryType::class, $categorie);

        //je demande à mon objet Form de prendre en charge les données envoyées (contenues dans la requête HTTP)
        $form->handleRequest($request);

        //si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() contient les données envoyées

            // ici on charge le formulaire de remplir notre objet catégorie avec ces données
            $categorie = $form->getData();

            // maintenant, on peut enregistrer cette nouvelle catégorie
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            //on crée un message flash
            $this->addFlash(
                'success',
                'Catégorie ajoutée !'
            );

            //on renvoie sur la liste des catégories par exemple
            return $this->redirectToRoute('categorie_last5');
        }

        //je passe en paramètre la "vue" du formulaire
        return $this->render('category/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/categorie/last5", name="categorie_last5")
     */
    public function showLast5Categories()
    {

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findLast5();

        return $this->render('category/last.five.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/categorie/update/{id}",
     *     name="categorie_update",
     *     requirements={"id":"\d+"}
     * )
     * $request va contenir les infos de la requête http et notamment $_GET ou $_POST
     */
    public function updateCategorie(Request $request, Category $categorie)
    {
        //je crée mon formulaire à partir de mon objet $categorie
        $form = $this->createForm(CategoryType::class, $categorie);

        //je demande à mon objet Form de prendre en charge les données envoyées (contenues dans la requête HTTP)
        $form->handleRequest($request);

        //si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            // ici on charge le formulaire de remplir notre objet catégorie avec ces données
            $categorie = $form->getData();

            // maintenant, on peut enregistrer la categorie modifiée
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            //on crée un message flash
            $this->addFlash(
                'success',
                'Catégorie modifiée !'
            );

            //on renvoie sur la liste des catégories par exemple
            return $this->redirectToRoute('categorie_last5');
        }

        //je passe en paramètre la "vue" du formulaire
        return $this->render('category/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/categorie/delete/{id}",
     *     name="categorie_delete",
     *     requirements={"id":"\d+"}
     * )
     */
    public function deleteCategorie(Category $category)
    {
        // le ParamConverter convertit autoamtiquement l'id en objet Categorie

        //récupération du manager
        $entityManager = $this->getDoctrine()->getManager();

        //Je veux supprimer cette catégorie
        $entityManager->remove($category);

        //j'oexecute les requêtes
        $entityManager->flush();
        $this->addFlash(
            'warning',
            'Catégorie supprimée !'
        );

        //on redirige sur la liste des 5 dernières catégories
        return $this->redirectToRoute('categorie_last5');
    }
}
