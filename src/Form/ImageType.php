<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class, ["label"=>"Url",
                                            "attr"=>[
                                                "placeholder" =>"Veuillez mettre l'Url de l'image"
                                            ]
                                            ])
            ->add('caption', TextType::class, ["label"=>"Légende",
            "attr"=>[
                "placeholder" =>"Veuillez mettre une legende a l'image"
            ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
