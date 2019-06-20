<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{

    // section pour que l'admin puisse ce connecter et ce deconnecter  //

    /**
     * permet a l'adminnistrateur de ce connecter
     * @Route("/admin/login", name="admin_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        if($error != null)
        {
            $this->addFlash("danger","L'email ou le password est invalide");
        }

        return $this->render("admin/account/login.html.twig");
    }


    /**
     * permet a l'admin de ce déconnecter
     *
     * @Route("/admin/logout", name="admin_logout")
     * @return void
     */
    public function logout(){
        //.....
    }

    // Section du controlle rpour les annonces gérées par l'adminitrateur //

    /**
     * @Route("/admin/ads", name="admin_ads")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AdRepository $adRepo)
    {
        $ads = $adRepo->findAll();
        return $this->render('admin/ads/ads.html.twig', [
            "ads"=>$ads
        ]);
    }
}
