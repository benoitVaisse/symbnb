<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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


    /**
     * permet d'afficher le formulaire d'inscription et de le traiter
     *
     * @Route("/inscription", name="account_registration")
     * 
     * @param Request $request
     * @return Response
     */
    public function registration(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);


        return $this->render("/account/registration.html.twig", [
            "form"=>$form->createView(),
        ]);
    }
}
