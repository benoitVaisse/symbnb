<?php

namespace App\Controller;

use App\Entity\Ad;
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
        return $this->render('booking/book.html.twig');
    }
}
