<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Service\EmailService;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * affiche toutes les annonces du site
     * @Route("/ads", name="ads_liste")
     * 
     * @return Response
     */
    public function index(AdRepository $adRepo, ObjectManager $manager)
    {
        // $em = $this->getDoctrine()->getRepository(Ad::class);
        $ads = $adRepo->findAll();
        return $this->render('ad/ads_liste.html.twig', [
            "ads"=>$ads 
            ]);
    }

    /**
     * affiche un formulaire pour creer une nouvelle annonce
     *@Route("/ad/creation", name="ad_create")
     *@IsGranted("ROLE_USER", statusCode=404, message="Veuillez vous Connecter pour créer une Annonce")
     * @return Response
     */
    public function createAd(objectManager $manager, Request $request, EmailService $mailService)
    {
        $ad = new Ad();
        // $img = new Image();
        // $img->setUrl("url numero 1")
        //     ->setCaption("description 1");
        
        // $ad->addImage($img);
        $user = $this->getUser();
        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            foreach($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $ad->setUser($user);
            $manager->persist($ad);
            $manager->flush();

            $mailService->sendAdminNewAd($ad, $user);

            $this->addFlash(
                "success",
                "l'annonce <strong>".$ad->getTitle()."</strong> a bien été ajoutée !"
            );
            return $this->redirectToRoute("ad_one", ["slug" => $ad->getSlug()]);
        }

        return $this->render("/ad/formulaire_creation.html.twig",[
            "formAd" => $form->createView() 
        ]);
    }

    /**
     * fonction qui permet d'afficher l'annonce sélectionné
     * @Route("/ad/{slug}", name="ad_one")
     *
     * @param Ad $ad
     * @return Response
     */
    public function showOne(Ad $ad)
    {
        return $this->render('ad/ad_one.html.twig', [
            "ad"=>$ad
            ]);
    }


    /**
     * permet d'afficher le formulaire d'édition d'une annonce
     * @Route("/ad/{slug}/edit", name="ad_edit")
     * @Security ("is_granted('ROLE_USER') and user == ad.getUser()")
     * @return Response
     */
    public function editAd(Ad $ad, Request $request, ObjectManager $manager)
    {

        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            foreach($ad->getImages() as $image)
            {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "Les modifications de l'annonce <strong>".$ad->getTitle()."</strong> ont bien été enregistrées !"
            );
            return $this->redirectToRoute("ad_one", ["slug" => $ad->getSlug()]);
        }

        return $this->render("/ad/formulaire_edition.html.twig", [
            "formAd"=>$form->createView(),
        ]);
    }

    /**
     * permet de supprimer une annonce
     * @Route("/ad/{slug}/delete", name="ad_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getUser()")
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function deleteAd(Ad $ad, ObjectManager $manager)
    {
        $user = $this->getUser();

        $this->addFlash("success", "L'annonce <strong>".$ad->getTitle()."</strong> a bien été surpprimée");

        $manager->remove($ad);
        $manager->flush();

        return $this->render("/user/user.html.twig",[
            "user" => $user
        ]);
    }
}
