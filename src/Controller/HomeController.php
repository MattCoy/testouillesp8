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
     * @Route("/comment-allez-vous", name="exercice1")
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

        return $this->render("exercice3.html.twig", ['age'=>$age, 'nom'=>$nom]);

    }

    /*
     * Créer une page pour la route exercice/genre/prix/produit
	où genre et produit sont des placeholders qui doivent être uniquement constitués de lettres
	et prix est uniquement constitué de chiffres


	Si genre est différent de homme ou femme, renvoyer un message d'erreur de votre choix
	Sinon, afficher un page avec le texte :
	vous avez demandé : {produit}, prix {prix} € pour {genre}
	et afficher une photo qui doit être une photo de femme si genre est femme et homme sinon

	 */

    /**
     *@Route("exercice/{genre}/{prix}/{produit}", name="exoRecap", requirements={"genre"="[a-zA-Z]+", "prix"="[0-9]+", "produit"="[a-zA-Z]+"})
     */

    public function exoRecap($genre, $prix, $produit){

        $message = '';

        if(!in_array($genre, ['homme', 'femme'])){
            $message = "genre invalide";
        }

        return $this->render('exoRecap.html.twig',
            array('genre'=>$genre,
                'prix'=>$prix,
                'produit'=>$produit,
                'message'=> $message
            )
        );

    }


}