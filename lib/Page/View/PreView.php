<?php

namespace Page\View {
    /**
     * Description of StringView
     *
     * @author alexey_baranov
     */
    class PreView extends HtmlElementView {
        function show() {
            echo "
            <pre {$this->getAttributesHtml()}>{$this->escapeHTMLString($this->getModel())}</pre>";
        }
    }
}