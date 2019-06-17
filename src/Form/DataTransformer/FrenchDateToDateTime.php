<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


    class FrenchDateToDateTime implements DataTransformerInterface {

        /**
         * transforme un objet date en date francaise
         *
         * @param [dateTime] $date
         * @return string
         */
        public function transform($date){

            if($date === null) {
                return '';
            }

            return $date->format("d/m/Y");
        }


        /**
         * transforme une date francaise en objet dateTime
         *
         * @param [string] $frenchDate
         * @return DateTime
         */
        public function reverseTransform($frenchDate){

            if($frenchDate === null){
                //exception
                throw new TransformationFailedException("vous devez donner une date");
            }

            $date = \DateTime::createFromFormat("d/m/Y", $frenchDate);

            if($date === false)
            {
                // exception
                throw new TransformationFailedException("vous devez donner une date");
            }

            return $date;


        }
    }

?>