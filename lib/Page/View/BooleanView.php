<?php

namespace Page\View {
    /**
     * Description of StringView
     *
     * @author alexey_baranov
     */
    class BooleanView extends HtmlElementView {
        function show() {
            $CKECKED= $this->getModel()? "checked='checked'":"";
            echo "
            <input type='checkbox' $CKECKED disabled {$this->getAttributesHtml()}></input>";
        }
    }
}