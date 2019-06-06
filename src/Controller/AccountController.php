<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * permet de ce connecter
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        if($error != null)
        {
            $this->addFlash("danger","L'email ou le password est invalide");
        }

        return $this->render('account/login.html.twig',[
            "username"=>$username
        ]);
    }


    /**
     * permet de ce déconnecter
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
        //// logout
    }
}
