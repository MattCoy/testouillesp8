<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/add", name="addArticle")
     */
    public function addArticle(Request $request)
    {

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        //si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            // ici on charge le formulaire de remplir notre objet article avec ces données
            $article = $form->getData();

            //l'utilisateur connecté est l'auteur de l'article
            $article->setUser($this->getUser());

            // maintenant, on peut enregistrer ce nouvel article
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            //on crée un message flash
            $this->addFlash(
                'success',
                'Article ajouté !'
            );

            //on renvoie sur la liste des catégories par exemple
            return $this->redirectToRoute('showAllArticles');
        }

        return $this->render('article/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/article/{id}",
     * name="showArticle",
     * requirements={"id":"\d+"}
     * )
     */
    public function show($id)
    {
         /*
          * $this->getDoctrine()
                ->getRepository(Article::class)
            est la classe qui nous permet de manipuler l'entité article (= table article dans la base)
            find($id) récupère une entrée par son id
            $article est un objet de classe Article
         */
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }
        return $this->render('article/article.html.twig',
            array('article'=>$article));
    }

    /**
     * @Route("/article/{slug}",
     * name="showArticleWithSlug",
     * requirements={"slug":"[\w-]+"}
     * )
     */
    public function showWithSlug(Article $article)
    {

        return $this->render('article/article.html.twig',
            array('article'=>$article));
    }

    /**
     * @Route("/articles",
     * name="showAllArticles",
     * )
     */
    public function showAll()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        return $this->render('article/articles.html.twig',
            array('articles'=>$articles));
    }

    /**
     * @Route("/articles-recents",
     *     name="articles_recents"
     * )
     */
    public function showRecents()
    {
        //on va appeler la méthode findAllPostedAfter() nouvellement créée dans notre repository
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAllPostedAfter('2000-01-01');
        //$articles est  un tableau de tableaux et non pas un tableau d'objets articles

        $articles2 = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAllPostedAfter2('2000-01-01');
        //articles2 est un tableau d'objets articles

        return $this->render('article/recents.html.twig', array(
            'articles' => $articles,
            'articles2' => $articles2
        ));

    }

    /**
     * @Route("/article/update/{id}",
     *     name="article_update",
     *     requirements={"id":"\d+"}
     * )
     */
    public function updateArticle(Article $article, Request $request)
    {
        // le ParamConverter convertit automatiquement l'id en objet Article

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        //si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            // ici on charge le formulaire de remplir notre objet article avec ces données
            $article = $form->getData();

            //message flash
            $this->addFlash(
                'success',
                'Article modifié !'
            );
            // maintenant, on peut enregistrer ce nouvel article
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            //on crée un message flash
            $this->addFlash(
                'success',
                'Article modifié !'
            );

            //on renvoie sur la liste des catégories par exemple
            return $this->redirectToRoute('showAllArticles');
        }

        return $this->render('article/add.html.twig', array(
            'form' => $form->createView(),
        ));

        //on redirige sur la liste des 5 derniers articles
        return $this->redirectToRoute('articles_showAll');
    }

    /**
     * @Route("/article/delete/{id}",
     *     name="article_delete",
     *     requirements={"id":"\d+"}
     * )
     */
    public function deleteArticle(Article $article)
    {
        // le ParamConverter convertit automatiquement l'id en objet Article

        //récupération du manager
        $entityManager = $this->getDoctrine()->getManager();

        //Je veux supprimer cet article
        $entityManager->remove($article);

        //j'execute les requêtes
        $entityManager->flush();
        $this->addFlash(
            'warning',
            'Article supprimé !'
        );

        //on redirige sur la liste des 5 derniers articles
        return $this->redirectToRoute('showAllArticles');
    }
}
