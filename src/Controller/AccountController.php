<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodepassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre compte a bien été créé, vous pouvre maintenant vous connecter !!");

            return $this->redirectToRoute("account_login");
        }


        return $this->render("/account/registration.html.twig", [
            "form"=>$form->createView(),
        ]);
    }
}
