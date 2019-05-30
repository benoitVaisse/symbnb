<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * affiche toutes les annonces du site
     * @Route("/ads", name="ads_liste")
     */
    public function index(AdRepository $adRepo, ObjectManager $manager)
    {
        // $em = $this->getDoctrine()->getRepository(Ad::class);
        $ads = $adRepo->findAll();
        return $this->render('ad/ads_liste.html.twig', [
            "ads"=>$ads 
            ]);
    }
}
