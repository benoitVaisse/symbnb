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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditProfileType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->createAttr("Pénom", "Renseigner votre prénom ..."))
            ->add('lastName', TextType::class, $this->createAttr("Pénom", "Renseigner votre nom ..."))
            ->add('email', EmailType::class, $this->createAttr("Email", "renseigner votre Email ..."))
            ->add('picture', UrlType::class, $this->createAttr("Url avatar", "https:// ......"))
            ->add('introduction', TextType::class, $this->createAttr("Introduction", "Votre introduction...."))
            ->add('description', TextareaType::class, $this->createAttr("Description", "Votre description détaillée ..."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
