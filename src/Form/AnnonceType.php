<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends AbstractType
{

/**
 * permet de creer les attribut de nos champs
 *
 * @param string $label
 * @param string $placeholder
 * @param string $class
 * @return array
 */
    private function createAttr($label, $placeholder, $class="")
    {
        return ["label"=> $label,
                "attr"=> [
                    "class" => $class,
                    "placeholder"=> $placeholder,
                ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->createAttr("Titre de l'annonce", "Titre de l'annonce...", "form-control"))
            ->add('introduction',TextType::class,  $this->createAttr("Introduction de l'annonce", "Introduction de l'annonce...", "form-control" ) )
            ->add('content', TextareaType::class, $this->createAttr("Description de l'annonce", "Ici vous pouvez mettre la description de votre annonce et de votre appartement", "form-control") )
            ->add('coverImage', UrlType::class, $this->createAttr("Url de l'image", "Mettre l'url de l'image", "form-control"))
            ->add('rooms', IntegerType::class,$this->createAttr("Nombre de chambre", "Indiquez le nombre de chambre de votre appartement", "form-control"))
            ->add('price', MoneyType::class, $this->createAttr("Prix par nuit", "Veuillez indiquer le prix pour uen nuit", "form-control"))
            ->add('images', CollectionType::class, ["entry_type"=>ImageType::class,
                                                    "allow_add"=>true,
                                                    "allow_delete"=>true
                                                    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
