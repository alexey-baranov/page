<?php

namespace Page\View {

    /**
     * Description of HTMLElementView
     *
     * @author alexey_baranov
     */
    class ExceptionView extends View {

        protected $_messageView;
        protected $_instructionView;
        protected $_stackTraceView;

        function initialize() {
            /* @var $model \Exception */
            $model = $this->getModel();


            $this->_messageView = new StringView($model->getMessage(), $this, "message");
            $this->_instructionView = method_exists($model, "getInstruction") ? new StringView($model->getInstruction(), $this, "instruction") : null;
            //$this->_stackTraceView= new PreView($model->getStack);
            $this->_stackTraceView = new PreView($model->getTraceAsString(), $this, "stackTrace");
            parent::initialize();
        }

        function show() {
            /* @var $model \Exception */
            $model = $this->getModel();
            
            $modelClass= get_class($model);
            echo "
                <div id='{$this->getFullIo()}' class='ui-state-error ui-corner-all' style='padding: 1em; margin:1em 0;'>
                    <div class='errorMessage'><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span><span onclick='$(\"#{$this->getFullIo()}_stackTrace\").toggle();'>{$modelClass}: {$this->_messageView->getHtml()}</span></div>";
            if ($this->_instructionView) {
                echo "
                    <div class='errorInstruction'>{$this->_instructionView->getHtml()}</div>";
            }
            echo "
            <div id='{$this->getFullIo()}_stackTrace' class='errorStackTrace' style='display:none'>";
            new StringView($model->getFile(), $this, "file", true);
            new StringView($model->getLine(), $this, "line", true);
            echo "{$this->_stackTraceView->getHtml()}
                </div>
                </div>";
        }

    }

}