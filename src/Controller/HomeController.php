<?php
/**
 * Created by PhpStorm.
 * User: Matthieu
 * Date: 18/12/2018
 * Time: 09:14
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/")
     * @return Response
     */
    public function home(){

        //on crée une variable
        $nom = 'Toto';
        //on la passe en paramètre de notre méthode render()
        return $this->render('index.html.twig', array('nom' => $nom));

    }
    //...

    /**
     * @Route("/bonjour")
     * @return Response
     */
    public function bonjour()
    {

        return new Response('<html><body><strong>Bonjour !</strong></body></html>');

    }

    /**
     * @Route("/comment-allez-vous")
     * @return Response
     */
    public function cava(){

        return new Response('<html><body><strong>Bien merci!</strong></body></html>');

    }

}