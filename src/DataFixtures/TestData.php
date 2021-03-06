<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

//notre classe, le nom est au choix doit hériter de Doctrine\Bundle\FixturesBundle\Fixture
class TestData extends Fixture
{
    //pour pouvoir utiliser l'encode de password, on crée une propriété
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        //lors de l'instanciation de la classe, on va stocker l'encoder dans la propriété
        $this->encoder = $encoder;
    }

    //elle doit implémenter la méthode load()
    //on récupère l'ObjectManager qui va nous permettre de persister nos objets
    public function load(ObjectManager $manager)
    {
        //on va créer 10 catégories
        for($i=1; $i <= 10; $i++){

            $categorie = new Category();
            $categorie->setLibelle('catégorie ' . $i);
            $manager->persist($categorie);

        }

        //on ajoute 5 utilisateurs AVANT d'ajouter les articles
        for($i=1; $i<=5; $i++){
            $user = new User();
            $user->setUsername('toto' . $i);
            $user->setEmail('toto' . $i . '@toto.to');
            $roles = $i === 1 ? ['ROLE_USER', "ROLE_ADMIN"] : ['ROLE_USER'];
            $user->setRoles($roles);
            //on définit un mot de passe
            $plainPassword = 'toto' . $i;
            //on l'encode
            $encoded = $this->encoder->encodePassword($user, $plainPassword);
            //on maj le user
            $user->setPassword($encoded);

            //on crée un tableau qui contient les utilisateurs
            $auteurs[] = $user;

            $manager->persist($user);
        }

        //on crée 30 articles

        for($i=1; $i <= 30; $i++){

            $article = new Article();

            $article->setTitle('Titre ' . $i);

            $article->setContent('Un contenu vraiment très intéressant numéro ' . $i);

            //on va générer des dates aléatoirement :

            //Generate a timestamp using mt_rand.
            $timestamp = mt_rand(1, time());

            //Format that timestamp into a readable date string.
            $randomDate = date("Y-m-d H:i:s", $timestamp);

            //create DateTime Object
            $article->setDatePubli(new \DateTime($randomDate));

            //on utilise le tableau d'utilisateurs créé plus haut
            $article->setUser($auteurs[array_rand($auteurs)]);

            $manager->persist($article);

        }

        //ne pas oublier de faire flush
        $manager->flush();
    }
}