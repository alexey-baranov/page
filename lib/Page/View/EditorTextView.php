<?php

namespace Page\View {

    /**
     * Description of EditorTextView
     *
     * @author alexey_baranov
     */
    class EditorTextView extends EditorStringView{
        function show() {
            echo "
                <textarea {$this->getAttributesHtml()}>{$this->escapeHTMLString($this->getModel())}</textarea>";
        }
    }

}