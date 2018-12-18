<?php
/**
 * Created by PhpStorm.
 * User: Matthieu
 * Date: 18/12/2018
 * Time: 09:14
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{

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