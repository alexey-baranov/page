<?php

namespace Page\View {

    /**
     * Моделью вьюшки является строка - код выбранного элемента
     *
     * @author alexey_baranov
     */
    class RadioView extends HtmlElementView {

        /**
         * array опции
         */
        public $options = array();

        function show() {
            if ($this->options) {

                $index = 0;
                foreach ($this->options as $EACH_OPTION => $eachOption) {
                    echo "
            <input id='{$this->getFullIo()}_{$index}' type='radio' name='{$this->getFullIo()}' value='{$this->escapeHTMLString($EACH_OPTION)}' {$this->getAttributesHtml()}>
            <label for='{$this->getFullIo()}_{$index}'>{$eachOption}</label>";
                }
            }
        }

        function init() {
            $modelView = new EditorStringView(null, $this, "", View::ACTION_INIT);
            if ($modelView->getModel() !== null) {
                $this->setModel($modelView->getModel());
            }
        }

    }

}