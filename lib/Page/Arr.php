<?php
/**
 * Created by PhpStorm.
 * User: alexey_baranov
 * Date: 16.12.2010
 * Time: 18:24:32
 * To change this template use File | Settings | File Templates.
 */
namespace Page{
    class Arr extends \Doctrine\Common\Collections\ArrayCollection{
        /**
         * @param string $separator по умолчанию ","
         * @return string
         */
        function join($separator=","){
            return \join($separator, $this->toArray());
        }
        
        /**
         * Возвращает все элементы указанного класса 
         *
         * @param string $TYPE 
         * @return Arr
         */
        function ofType($TYPE) {
            $result= new Arr();
            foreach ($this as $EACH_ELEMENT => $eachElement) {
                if (get_class($eachElement)==$TYPE || is_subclass_of($eachElement, $TYPE)){
                    $result->set($EACH_ELEMENT, $eachElement);
                }
            }
            return $result;
        }
    }
}