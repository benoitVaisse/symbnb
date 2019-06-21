<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
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

    //------------------------------------------ Section du controller pour les annonces gérées par l'adminitrateur ---------------------------------------------//

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


    /**
     * permet a l'admin de modifier une annonce
     * @Route("/admin/ad/{id}/edit", name="admin_ad_edit")
     * @param Ad $ad
     * @return void
     */
    public function editAd(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce {$ad->getTitle()} a bien été modifier "
            );
        }


        return $this->render("admin/ads/edit.html.twig", [
            "ad"=>$ad,
            "form"=>$form->createView(),
        ]);
    }

    
    /**
     * permet de supprimer une annonces
     *
     * @Route("admin/ad/{id}/delete", name="admin_ad_delete")
     * 
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return void
     */
    public function deleteAd(Ad $ad, ObjectManager $manager)
    {
        if(count($ad->getBookings()) > 0)
        {
            $this->addFlash(
                "warning",
                "Vous ne pouvez pas supprimer l'annonce <strong> {$ad->getTitle()} </strong> car elle a déja eu des reservations "
            );
        }
        else{
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimé"
            );
        }

        return $this->redirectToRoute("admin_ads");

    }



    //---------------------------------------------------------- Section qui gère les commentaires pour l'admin -----------------------------------------------------------//


    /**
     * permet de lister la liste de tous les commenaires
     *
     * @Route("/admin/comments", name="admin_comments")
     * @param CommentRepository $repoComments
     * @return void
     */
    public function adminComments(CommentRepository $repoComments){

        $comments = $repoComments->findAll();

        return $this->render("/admin/comments/comments.html.twig", [
            "comments"=>$comments,
        ]);

    }

}
