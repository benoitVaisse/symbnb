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
    protected function createAttr($label, $placeholder, $require = true, $class="")
    {
        return ["label"=> $label,
                "attr"=> [
                    "class" => $class,
                    "placeholder"=> $placeholder,
                ],
                "required"=> $require
        ];
    }

    /**
     * permet de creer les attribut de nos champs de type dateTime
     *
     * @param string $label
     * @param string $placeholder
     * @param string $widget
     * @param string $class
     * @return array
     */
    protected function createAttrDate($label, $placeholder, $widget=false ,$class="")
    {
        return ["label"=> $label,
                "attr"=> [
                    "class" => $class,
                    "placeholder"=> $placeholder,
                ],
                "widget"=> $widget
        ];
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function ckEditorOption()
    {
        return ['config_name' => 'full_config',
        ];
    }
}




?>