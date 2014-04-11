<?php

namespace Page\View {
    /**
     * Description of DateView
     *
     * @author alexey_baranov
     */
    class DateView extends HtmlElementView {
        const FORMAT_DEFAULT="d.m.Y H:i";  //ISO8601  Y-m-d\TH:i:sO ;

        /**
         *
         * @var string формат даты
         */
        public $_format= DateView::FORMAT_DEFAULT;
        
        public function getFormat() {
            return $this->_format;
        }

        public function setFormat($_format) {
            $this->_format = $_format;
            return $this;
        }

        function show() {
            /* @var $model \DateTime */
            $model = $this->getModel();
                
            $MODEL= $model?$model->format($this->_format):null;
            new StringView($MODEL, $this, "", true);
        }
    }
}