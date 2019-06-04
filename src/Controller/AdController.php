<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @return Response
     */
    public function createAd(objectManager $manager, Request $request)
    {
        $ad = new Ad();
        // $img = new Image();
        // $img->setUrl("url numero 1")
        //     ->setCaption("description 1");
        
        // $ad->addImage($img);
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
     *@Route("/ad/{slug}/edit", name="ad_edit")
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
}
