<?php

namespace Page\View {

    /**
     * Моделью вьюшки является строка - код выбранного элемента
     *
     * @author alexey_baranov
     */
    class ComboboxView extends HtmlElementView {
        /**
         * array опции
         */
        public $options=array();
        
        /**
         * Выбирает опцию только если есть совпадение опции с модельи
         * в противном случае комбобокс будет пустой 
         * 
         * @var bool
         */
        public $explicitSelection= false;
                
        function show() {
            $explicitSelected= false;
            echo "
            <select {$this->getAttributesHtml()}>";
            foreach($this->options as $EACH_OPTION => $eachOption){
                if ($this->_model===(string)$EACH_OPTION){
                    $explicitSelected= true;
                }
                echo "<option value='{$this->escapeHTMLString($EACH_OPTION)}'".($this->_model===(string)$EACH_OPTION?"selected":"").">{$this->escapeHTMLString($eachOption)}</option>
                ";
            }
            echo "</select>";
            if ($this->explicitSelection && !$explicitSelected):
            ?>
                <script>
                    $(document).ready(function(){
                        $("#<?= $this->getFullIo() ?>").prop("selectedIndex", -1);
                    })
                </script>
            <?php
            endif;
        }

        function init() {
            $modelView= new EditorStringView(null, $this, "", View::ACTION_INIT);
            if ($modelView->getModel()!==null){
                $this->setModel( $modelView->getModel() );
            }
        }
    }
}