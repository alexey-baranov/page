<?php

namespace Page {

    class Hyperlink {
        const TARGET_BLANK= "_blank";
        const TARGET_SELF= "_self";
        
        protected $_href;
        protected $_target;
        
        function __construct($href, $target= self::TARGET_SELF) {
            $this->_href = $href;
            $this->_target = $target;
        }

        
        public function getTarget() {
            return $this->_target;
        }

        public function setTarget($target) {
            $this->_target = $target;
        }

        public function getHref() {
            return $this->_href;
        }

        public function setHref($href) {
            $this->_href = $href;
        }

        }

}