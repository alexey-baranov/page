<?php

namespace Page\View {

    /**
     * Description of EditorTextView
     *
     * @author alexey_baranov
     */
    class EditorPasswordView extends EditorStringView{
        function show() {
            echo "
            <input type='password' {$this->getAttributesHtml()} ></input>";
        }
    }

}