<?php

namespace Page\View {

    /**
     * Description of EditorStringView
     *
     * @author alexey_baranov
     */
    class SubmitView extends ButtonView {
        function show() {
            echo "
            <input type='submit' {$this->getAttributesHtml()}>";
        }
    }
}