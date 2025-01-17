<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                "widget"=> "single_text",
                "label"=>"Jour d'arrivé"
            ])
            ->add('endDate', DateType::class, [
                "widget"=> "single_text",
                "label"=>"Jour de départ"
            ])
            ->add('comment', TextareaType::class,[
                "label"=> "Commentaire"
            ])
            ->add('user', EntityType::class, [
                "class" => User::class,
                "choice_label"=> function($user){
                    return $user->getFirstName() ." ".strtoupper($user->getLastName());
                },
                "label"=> "Voyageur"
            ])
            ->add('ad', EntityType::class, [
                "class" => Ad::class,
                "choice_label" => function($ad){
                    return "id : {$ad->getId()} | Titre : {$ad->getTitle()}";
                },
                "label" => "Annonce"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
