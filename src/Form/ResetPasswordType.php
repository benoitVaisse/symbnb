<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->createAttr("Ancien mot de passe", "Entrer votre ancien mot de passe..."))
            ->add('newPassword', RepeatedType::class, [
                "type"=> PasswordType::class,
                "invalid_message"=> "Les 2 mot de passe doivent être identique !", 
                "first_options" => ["label"=>"Nouveau mot de passe",
                                    "attr"=>[
                                        "placeholder"=>"Nouveau mot de passe ..."
                                    ]
                                ],
                "second_options" => ["label"=>"Confirmer le nouveau mot de passe",
                                    "attr"=>[
                                        "placeholder"=>"Confirmer le Nouveau mot de passe ..."
                                    ]
                                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
