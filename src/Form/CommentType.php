<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class,[
                "label"=> "Note de votre séjour",
                "attr"=> [
                    "min" => 0,
                    "max" => 5,
                    "step" => 1,
                    "placeholder" => "donner la note de votre séjour"
                ]
            ])
            ->add('content', TextareaType::class, [
                "label"=> "Donner un Commentaire sur votre séjour",
                "attr" => [
                    "placeholder" => "Donner un commentaire détaillé pour pouvoir aider les futurs voyageurs"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
