<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ResetPassword;
use App\Form\EditProfileType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use Symfony\Component\Form\FormError;
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

    /**
     * permet d'afficher le formualire d"édition du profile utilisateur et de le traiter
     *
     * @Route("/account/edit", name="account_edit")
     * @param Request $request
     * @param ObjectManager $manager
     * @return void
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(EditProfileType::class,$user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();
        }

        return $this->render("/account/editProfile.html.twig", [
            "form"=>$form->createView()
        ]);
    }


    /**
     * permet de modifier le mot de passe de l'utilisateur
     *
     * @Route("/account/reset-password", name="account_reset_password")
     * 
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function resetPassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = $this->getUser();
        $resetPassword = new ResetPassword();
        $form = $this->createForm(ResetPasswordType::class, $resetPassword);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
            
            if($encoder->isPasswordValid($user, $resetPassword->getOldPassword()))
            {
                $newHash = $encoder->encodePassword($user, $resetPassword->getNewPassword());
                $user->setHash($newHash);
                $manager->persist($user);
                $manager->flush();

            }
            else
            {
                $form->get('oldPassword')->addError(new FormError("Vous n'avez pas renseigner correctement l'ancien mot de passe"));
            }
        }

        return $this->render("/account/resetPassword.html.twig", [
            "form"=>$form->createView(),
        ]);

    }
}
