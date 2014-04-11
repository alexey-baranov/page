<?php

namespace Page\View {

    /**
     * Description of StringView
     *
     * @author alexey_baranov
     */
    class HiddenView extends EditorStringView {
        function initialize() {
            parent::initialize();
            //$this->attributes['style']="background: #55FF55; font-size: 12pt;";
            $this->attributes['title']= "{$this->getFullIo()} = {$this->getModel()}";
            $this->setClassName("hidden");
        }
        function show() {
            echo "
            <input type='hidden' {$this->getAttributesHtml()} />";
        }
    }
}