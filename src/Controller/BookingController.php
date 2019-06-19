<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * permet d'afficher la page et le formulaire de reservation d'un annonce
     * @Route("/ad/{slug}/book", name="ad_book")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function book(Ad $ad, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $booking->setAd($ad)
                    ->setUser($this->getUser());

            if(!$booking->isBookableDate()){
                $this->addFlash(
                "warning",
                "Vous ne pouvez pas reserver pour cette date : Elles sont deja prise, veuillez en choisir des nouvelles");
            }
            else
            {
                $manager->persist($booking);
                $manager->flush();
    
                return $this->redirectToRoute("booking_show" ,["id" => $booking->getId(), "success" => 1 ]);
            }



        }


        return $this->render('booking/book.html.twig',[
            "ad" => $ad,
            "form" => $form->createView()
        ]);
    }

    /**
     * permet d'affficher la page d'une reservation
     *
     * @Route("account/booking/{id}", name="booking_show")
     * @Security("is_granted('ROLE_USER') and booking.getUser() == user ")
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function bookingShow(Booking $booking, Request $request, ObjectManager $manager)
    {
        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setUser($user)
                    ->setAd($booking->getAd());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash("success", "Votre commentaire a bien été envoyé !!");
        }
        elseif ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash("danger", "Un Problème est survenue lors de l'envoi de votre commentaire");
        }



        return $this->render("/booking/show.html.twig", [
            "booking" =>$booking,
            "form" => $form->createView(),
            "user" => $user
        ]);
    }
}
