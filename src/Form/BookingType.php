<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, $this->createAttrDate(false, "la date a laquelle vous comptez arriver", "single_text"))
            ->add('endDate', DateType::class, $this->createAttrDate(false, "la date a laquelle vous partez", "single_text"))
            ->add('comment' ,TextareaType::class, $this->createAttr(false, "Laissez nous un petit commentaire si vous le souhaitez ..... On ce charge du reste  ;)"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
