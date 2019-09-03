<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    /**
     * fonction qui affiche la page d'accueil du site
     * @Route("/", name="home_page")
     * @return reponse
     */
    public function index(AdRepository $adRepo, UserRepository $userRepo, Request $request)
    {
        $local = "";
        $local = $request->getLocale();
        dump($local);

        $bestAds = $adRepo->findBestAds(3);
        $bestUsers = $userRepo->findBestUsers(2);
        dump($bestUsers);

        return $this->render('home_page/index.html.twig', [
            "bestAds" => $bestAds,
            "bestUsers" => $bestUsers
        ]);
    }

}
