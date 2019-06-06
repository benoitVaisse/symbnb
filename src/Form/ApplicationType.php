<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;



class ApplicationType extends AbstractType
{
    
    /**
     * permet de creer les attribut de nos champs
     *
     * @param string $label
     * @param string $placeholder
     * @param string $class
     * @return array
     */
    protected function createAttr($label, $placeholder, $class="")
    {
        return ["label"=> $label,
                "attr"=> [
                    "class" => $class,
                    "placeholder"=> $placeholder,
                ]
        ];
    }
}




?>