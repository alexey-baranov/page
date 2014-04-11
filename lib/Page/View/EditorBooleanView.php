<?php

namespace Page\View {

    /**
     * Description of EditorStringView
     *
     * @author alexey_baranov
     */
    class EditorBooleanView extends HtmlElementView {
        /**
         *
         * @var type Текст для чекбокса
         */
        public $label;
        
        function setModel($m) {
            if ($m){
                $this->attributes["checked"]="checked";
            }
            else {
                unset($this->attributes["checked"]);
            }
            parent::setModel($m);
        }
        function show() {
            $off= "off";
            new HiddenView($off, $this, "off", true);
            echo "
            <input type='checkbox' {$this->getAttributesHtml()} />"; //checked выводится тут же
            if ($this->label){
                echo "
                    <label for='{$this->getFullIo()}'>{$this->label}</label>
                    ";
            }
        }

      	public function init() {
            $offView= new HiddenView(null, $this, "off", View::ACTION_INIT);
            
            if (isset($_REQUEST[$this->getFullIo()]) && ($_REQUEST[$this->getFullIo()] == 'on' || $_REQUEST[$this->getFullIo()] == 1)){
                $this->setModel(true);
            }
            else if ($offView->getModel()!==null){
                $this->setModel(false);
            }
            else{
                $x= 1;
            }
        }
    }
}