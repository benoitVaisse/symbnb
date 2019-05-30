<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * fonction qui affiche la page d'accueil du site
     * @Route("/", name="home_page")
     * 
     * @return reponse
     */
    public function index()
    {
        return $this->render('home_page/index.html.twig', []);
    }

    /**
     * 
     *
     * @return reponse
     */
    public function showAd()
    {

    }
}
