<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/add", name="addArticle")
     */
    public function addArticle()
    {

        $entityManager = $this->getDoctrine()->getManager();
        $article = new Article();
        $article->setTitle('oula');
        $article->setContent('contenu top');
        $article->setDatePubli(new \DateTime(date('Y-m-d H:i:s')));
        $article->setAuthor('Matthieu');
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->render('article/add.html.twig', [
            'article' => $article,
        ]);
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
}
