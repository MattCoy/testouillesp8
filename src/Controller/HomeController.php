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
     * @Route("/", name="home")
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
     * @Route("/bonjour", name="bonjour")
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

    /**
     * @Route("/exercice2/heure", name="exercice2")
     */
    public function quelleHeure(){
        $date = new \DateTime(date('Y-m-d H:i:s'));
        //on envoie une réponse
        return $this->render('exercice2.html.twig',
            array('maDate' => $date->format('H\hs'))
        );
    }

    /**
     * cette route va matcher /bonjour/nimportequeltexte
     *
     * @Route("/bonjour/{nom}", name="bonjourNom", requirements={"nom"="[a-z]+"})
     *
     * J'ai nommé ma route, ce qui me sera utile pour générer l'url ou faire des
    redirections
     */
    public function bonjour2($nom){
        //$nom est automatiquement envoyé en paramètre à notre méthode
        //et contiendra tout ce qui suit /bonjour/
        return $this->render('bonjour.html.twig', array('nom'=>$nom));
    }

    /**
     * @Route("/testRedirect")
     * cette route va rediriger vers home
     */
    public function testRedirect(){

        return $this->redirectToRoute("home");

    }

    /**
     * @Route("exercice3/{age}/{nom}", name="exercice3", requirements={"age"="\d+", "nom"="[a-zA-Z]+"})
     * @return Response
     */
    public function exercice3($age, $nom){

        return $this->render("exercice3.html.twig", ['age'=>$age, 'pseudo'=>$nom]);

    }

}