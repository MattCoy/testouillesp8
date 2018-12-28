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
     */
    public function updateCategorie(Category $category)
    {
        /* Ici on utilise une fonctionnalité très utile de symfony : le ParamConverter
        Alors que l'on devrait récupérer en argument $id, l'id de la catégorie,
        on indique à Symfony que l'on veut récupérer l'entité Categorie:
        le ParamConverter va comprendre cela et va lui même faire la requête find($id),
        ce qui nous évite d'écrire:
        $article = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->find($id);
        Si aucun article n'est trouvé, une erreur 404 est générée.
        */

        //Ensuite je peut modifier ma catégorie
        $category->setLibelle('catégorie modifiée');

        //récupération du manager
        $entityManager = $this->getDoctrine()->getManager();

        //ici pas besoin de faire $entityManager->persist($categorie);
        //car doctrine a déjà en mémoire cette entité, puisqu'elle l'a récupéré dans la base

        $entityManager->flush();
        //à ce moment, doctrine sait que $categorie existe déjà dans la base et va donc faire un update au lieu d'un insert !

        //On va utiliser une autre fonctionnalité très utile de Symfony : les messages flashs:
        // Ce sont des messages stockés en session et qui sont ensuite effaçés dès qu'ils sont affichés,
        //de sorte qu'il n'apparaissent qu'une seule fois
        $this->addFlash(
            'success',
            'Catégorie modifiée !'
        );

        //on redirige sur la liste des 5 dernières catégories
        return $this->redirectToRoute('categorie_last5');
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
