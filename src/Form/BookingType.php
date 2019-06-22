<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\FrenchDateToDateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transformer;

    public function __construct(FrenchDateToDateTime $transformer)
    {
        $this->transformer = $transformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->createAttr(false, "la date a laquelle vous comptez arriver", "single_text"))
            ->add('endDate', TextType::class, $this->createAttr(false, "la date a laquelle vous partez", "single_text"))
            ->add('comment' ,TextareaType::class, $this->createAttr(false, "Laissez nous un petit commentaire si vous le souhaitez ..... On ce charge du reste  ;)", false))
        ;

        $builder->get("startDate")->addModelTransformer($this->transformer);
        $builder->get("endDate")->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            "validation_groups"=> "reservation"
        ]);
    }
}
