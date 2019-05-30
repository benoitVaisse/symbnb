<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * affiche toutes les annonces du site
     * @Route("/ads", name="ads_liste")
     * 
     * @return Response
     */
    public function index(AdRepository $adRepo, ObjectManager $manager)
    {
        // $em = $this->getDoctrine()->getRepository(Ad::class);
        $ads = $adRepo->findAll();
        return $this->render('ad/ads_liste.html.twig', [
            "ads"=>$ads 
            ]);
    }


    /**
     * fonction qui permet d'afficher l'annonce sélectionné
     * @Route("/ad/{slug}", name="ad_one")
     *
     * @param Ad $ad
     * @return Response
     */
    public function showOne(Ad $ad)
    {
        return $this->render('ad/ad_one.html.twig', [
            "ad"=>$ad
            ]);
    }
}
