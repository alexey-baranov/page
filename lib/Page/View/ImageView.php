<?php

namespace Page\View {

    /**
     * Description of EditorStringView
     *
     * @author alexey_baranov
     */
    class ImageView extends HtmlElementView {
        /**
         * $var array Обработчик нажания кнопки [$obj, $method]
         */
        public $click;
        
        function show() {
            $x=1;
            echo "
            <input type='image' {$this->getAttributesHtml()} />";
        }

        function action() {
            $actionCalled= false;
            
            if (isset($_REQUEST[$this->getFullIo()."_x"])){
                $actionCalled= true;
            }
            else{
                $pageView= $this->getParentOfTypeOrNull("Page\View\PageView");
                if ($pageView && $pageView->getSubmitInputEmulator()==$this->getFullIo()) {
                    $actionCalled= true;
                }
            }
            if ($actionCalled && $this->click) {
                $result= call_user_func($this->click, $this, null); //1- колбэк[$obj, $method], 2- параметр $sender, 3- arg
                if (!$result){
                    die;
                }
                //throw new \Page\Exception("В качестве обработчика передан несуществующий метод " . get_class($this->click[0]) . "->" . $this->click[1]);
            }            
        }
    }

}