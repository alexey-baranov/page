<?php

namespace Page\View {

    /**
     * Description of EditorStringView
     *
     * @author alexey_baranov
     */
    class EditorStringView extends HtmlElementView {

        function setModel($model) {
            $this->attributes['value'] = $model;
            return parent::setModel($model);
        }

        function show() {
            echo "
            <input type='text' {$this->getAttributesHtml()} />";
        }

        function init() {
            if (isset($_REQUEST[$this->getFullIo()])) {
                $this->setModel( trim($_REQUEST[$this->getFullIo()]) );
            }
        }
    }
}