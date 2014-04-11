<?php

namespace Page\View {

    /**
     * Description of EditorStringView
     *
     * @author alexey_baranov
     */
    class ButtonView extends HtmlElementView {
        /**
         * Обработчик нажания кнопки
         * @param \Page\MulticastDelegate array($obj, $method)
         */
        public $click;
        
        function __construct($model, $parent= null, $io="p", $action= false) {
            parent::__construct($model, $parent, $io, $action);
            $this->click= new \Page\MulticastDelegate();
        }
        function setModel($model) {
            $this->attributes['value'] = $model;
            return parent::setModel($model);
        }

        function show() {
            $x=2;
            echo "
            <input type='button' {$this->getAttributesHtml()}>";
        }

        function action() {
            $isActionCalled= false;
            
            if (isset($_REQUEST[$this->getFullIo()])){
                $isActionCalled= true;
            }
            else{
                $pageView= $this->getParentOfTypeOrNull("Page\View\PageView");
                if ($pageView && $pageView->getSubmitInputEmulator()==$this->getFullIo()) {
                    $isActionCalled= true;
                }
            }
            if ($isActionCalled && $this->click) {
                if ($this->click instanceof \Page\MulticastDelegate){
                    $this->click->call($this, null);
                }
                else {
                    call_user_func($this->click, $this, null); //1- колбэк[$obj, $method], 2- параметр $sender, 3- arg
                }
                die;
                //throw new \Page\Exception("В качестве обработчика передан несуществующий метод " . get_class($this->click[0]) . "->" . $this->click[1]);
            }
        }

    }
}