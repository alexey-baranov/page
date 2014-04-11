<?php

namespace Page {

    /**
     * Description of Core_Exception
     *
     * @author alexey_baranov
     */
    class Exception extends \Exception {
        /**
         * @var string
         */
        protected $_instruction;

        function __construct($message, $code=null, $prev=null, $instruction=null) {
            parent::__construct($message, $code, $prev);
            $this->_instruction = $instruction;
        }

        function getInstruction() {
            return $this->_instruction;
        }

        function setInstruction($value) {
            $this->_instruction = $value;
        }
    }

}