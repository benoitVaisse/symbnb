<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->createAttr("Prénom", "Votre prénom ..."))
            ->add('lastName', TextType::class, $this->createAttr("Nom", "Votre nom de famille ...."))
            ->add('email', EmailType::class, $this->createAttr("Email", "Votre adresse web ..."))
            ->add('picture', UrlType::Class, $this->createAttr("Avatar", "Lien de l'image de votre avatar"))
            ->add('hash', PasswordType::class, $this->createAttr("Password", "Votre mot de passe ......"))
            ->add("confirmHash", PasswordType::class, $this->createAttr("Confirmer votre password", "confirmation du mot de passe ..."))
            ->add('introduction', TextType::class, $this->createAttr("introduction","Une petite introduction pour vous présenter brièvement..."))
            ->add('description', TextareaType::class, $this->createAttr("Description", "Présenté vous en détails...."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
