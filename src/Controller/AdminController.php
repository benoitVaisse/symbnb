<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\AnnonceType;
use App\Form\AdminBookingType;
use App\Form\AdminCommentType;
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
    //---------------------------------------- Dashboard -------------------------------------------------------------//


    /**
     * affiche le dashboard de l'admin
     * @Route("/admin/dashboard", name="admin_dashboard")
     *
     * @return Response
     */
    public function dashboard()
    {
        return $this->render("/admin/dashboard.html.twig");
    }

    //--------------------------------------------------- section pour que l'admin puisse ce connecter et ce deconnecter  ------------------------------------------------------//

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
     * @Route("/admin/ads/{page}", name="admin_ads", requirements={"page"= "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(AdRepository $adRepo, $page = 1)
    {
        $limit = 10;
        // $ads = $adRepo->findAll();
        $start = ($limit * $page) - $limit;

        $ads = $adRepo->findBy([], ["id"=>"ASC"], $limit, $start);
        $nbPage = ceil(count($adRepo->findAll()) / $limit ); 

        return $this->render('admin/ads/ads.html.twig', [
            "ads"=>$ads ,
            "nbPage" => $nbPage,
            "page"=>$page
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
     * @return Response
     */
    public function adminComments(CommentRepository $repoComments){

        $comments = $repoComments->findAll();

        return $this->render("/admin/comments/comments.html.twig", [
            "comments"=>$comments,
        ]);

    }


    /**
     * Permet a l'admin de modifier le contenu d'un commentaire
     *
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     * 
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function editComment(Comment $comment, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                "success",
                "le commentaire a bien été modifié "
            );
        }

        return $this->render("admin/comments/edit.html.twig", [
            "comment" => $comment,
            "form"=>$form->createView()
        ]);

    }

    /**
     * permet a la'dmin de supprimer un comentaire
     * 
     * @Route("/ad/comment/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function deleteComment(Comment $comment, ObjectManager $manager)
    {
        $this->addFlash(
            "success",
            "le Commentaire numero {$comment->getId()} a bien été supprimé"
        );
        
        $manager->remove($comment);
        $manager->flush();


        return $this->redirectToRoute("admin_comments");
    }


    //----------------------------------------------------- section admin qui gère les Réservations ----------------------------------------------------------//

    /**
     * affiche toutes les réservations effectués sur le site
     * @Route("/admin/bookings", name="admin_bookings")
     *
     * @return Response
     */
    public function adminBookings()
    {
        $repo = $this->getDoctrine()->getRepository(Booking::class);
        $bookings = $repo->findAll();

        return $this->render("admin/booking/bookings.html.twig", [
            "bookings" => $bookings
        ]);
    }


    /**
     * permet de modifier une réservation
     *
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function editBooking(Booking $booking, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                "success",
                "Le Réservation numéro {$booking->getId()} a bien été modifiée"
            );

            return $this->redirectToRoute("admin_bookings");
        }

        return $this->render("admin/booking/edit.html.twig", [
            "form"=>$form->createView(),
            "booking"=>$booking
        ]);
    }

}
