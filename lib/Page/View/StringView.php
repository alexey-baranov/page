<?php

namespace Page\View {
    /**
     * Description of StringView
     *
     * @author alexey_baranov
     */
    class StringView extends HtmlElementView {
        static $showCount=0;
        /**
         * Вызывается перед escapeHTMLString($this->getModel()
         * через call_user_func($_beforeEscapeFilter, $model)
         * 
         * function tolowerFilter($model){
         *    return lower($model);  //переделывает все в нижний регистр
         * }
         *
         * @var arr
         */
        protected static $_beforeEscapeFilters= array();
        protected static $_afterEscapeFilters= array();
        /**
         * @see StringView::$_beforeEscape
         * @param string $value
         */
        static function addBeforeEscapeFilter($value){
            self::$_beforeEscapeFilters[]= $value;
        }
        static function addAfterEscapeFilter($value){
            self::$_afterEscapeFilters[]= $value;
        }
        function show() {
            self::$showCount++;
            
            if ($this->getFullIo()=="p_node_text"){
                $x=1;
                $x++;
            }
            $model= $this->getModel();
            
            foreach(StringView::$_beforeEscapeFilters as $eachFilter){
                $model= call_user_func($eachFilter, $this, $model);
            }
            
            $model= $this->escapeHTMLString($model);
            
            foreach(StringView::$_afterEscapeFilters as $eachFilter){
                $model= call_user_func($eachFilter, $this, $model);
            }
            
//            $escapdModelWithCallButtons= preg_replace('/((\+7|\b8)\s*(\(|-)?\d{3,4}(\)|-)?\s*\d[\d\-]{5,7}\d\b)|(\b\d[\d\-]{4,7}\d\b)/', "<span class='phoneNumber' title='Звонить на \\0' onclick=\"call('\\0'); return false;\">\\0</span>", $escapedModel);
            echo "<span {$this->getAttributesHtml()}>{$model}</span>";
        }
    }
}