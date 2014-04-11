<?php

namespace Page\View {

    /**
     * Description of EditorIntView
     *
     * @author alexey_baranov
     */
    class EditorIntView extends HtmlElementView{
        function initialize() {
            parent::initialize();
            $this->attributes['size']= 5;
        }
        function show(){
            echo "<input type='number' value='{$this->getModel()}' {$this->getAttributesHtml()} />";
        }
        function init(){
            if (!isset($_REQUEST[$this->getFullIo()])){
                return;
            }
            elseif (trim($_REQUEST[$this->getFullIo()])===''){
                $this->setModel(null);
            }
            else{
                $this->setModel( (int)$_REQUEST[$this->getFullIo()] );
            }
        }
    }

}