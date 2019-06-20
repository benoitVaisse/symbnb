<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    // section pour que l'admin puisse ce connecter et ce deconnecter  //

    /**
     * permet a l'adminnistrateur de ce connecter
     * @Route("/admin/login", name="admin_login")
     * @return Response
     */
    public function login()
    {

        return $this->render("admin/login.html.twig", []);
    }


    /**
     * permet a l'admin de ce déconnecter
     *
     * @return void
     */
    public function logout(){
        //.....
    }

    // Section du controlle rpour les annonces gérées par l'adminitrateur //

    /**
     * @Route("/admin/ads", name="admin_ads")
     */
    public function index(AdRepository $adRepo)
    {
        $ads = $adRepo->findAll();
        return $this->render('admin/ads/ads.html.twig', [
            "ads"=>$ads
        ]);
    }
}
