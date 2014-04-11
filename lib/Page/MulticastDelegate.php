<?php

/*
 * To change $this template, choose Tools | Templates
 * and open the template as the editor.
 */

namespace Page {

    /**
     * Description of MulticastDelegate
     *
     * @author alexey_baranov
     */
    class MulticastDelegate{

        /**
         * каждый делегат это массив [объект, метод]
         * @var array
         */
        protected $_delegates = array();

        function clear() {
            $this->_delegates = array();
        }

        function add($delegate) {
            foreach ($this->_delegates as $eachDelegate) {
                if ($eachDelegate == $delegate) {
                    return;
                }
            }
            $this->_delegates[] = $delegate;
        }

        function set($delegate) {
            $this->clear();
            $this->add($delegete);
        }

        function remove($delegate) {
            foreach ($this->_delegates as $EACH_DELEGATE=> $eachDelegate) {
                if ($eachDelegate == $delegate) {
                    unset($this->_delegates[$EACH_DELEGATE]);
                    return;
                }
            }
        }

        /**
         * вызывает делегаты, передавая $this и все параметры
         * 
         */
        function call() {
            foreach ($this->_delegates as $eachDelegate) {
                $args= func_get_args();
                //$args= array_merge(array($this), $args); сендером будет объетк $sqlTable
                call_user_func_array($eachDelegate, $args);
            }
        }
    }

}
