<?php

namespace Page\View {
    /**
     * Description of PageView
     *
     * @author alexey_baranov
     */
    class PageView extends View {
        const METHOD_POST= "POST";
        const METHOD_GET= "GET";
        const SUBMIT_INPUT_EMULATOR= "submitInputEmulator";

        
        protected $_scripts = array();
        protected $_css = array();
        protected $_defaultFocusIo;
        protected $_defaultActionIo;
        protected $_method = PageView::METHOD_GET;
        
        /**
         * @var string шаблон формы
         */
        protected $_formTemplate;
        
        /**
         * Название инпута, как- будто нажатого для сабмита
         * Используется для эмулирования сабмита
         * @var HiddenView
         */
        protected $_submitInputEmulatorView;
        
        function getSubmitInputEmulator() {
            return $this->_submitInputEmulatorView->getModel();
        }
        
        public function getFormTemplate() {
            return $this->_formTemplate;
        }

        public function setFormTemplate($_formTemplate) {
            $this->_formTemplate = $_formTemplate;
        }
        
        function getDefaultFocusIo() {
            return $this->_defaultFocusIo;
        }

        /**
         *
         * @param string $DEFAULT_FOCUS
         * @return PageView
         */
        function setDefaultFocusIo($DEFAULT_FOCUS) {
            $this->_defaultFocusIo = $DEFAULT_FOCUS;
            return $this;
        }

        function getDefaultActionIo() {
            return $this->_defaultActionIo;
        }

        /**
         *
         * @param string $defaultActionIo
         * @return PageView
         */
        function setDefaultActionIo($defaultActionIo) {
            $this->_defaultActionIo = $defaultActionIo;
            return $this;
        }

        /**
         * GET по умолчанию
         *
         * @return метот GET или POST, по которому передается форма на сервер 
         *
         */
        function getMethod() {
            return $this->_method;
        }

        /**
         *
         * @param string $method
         * @return Page
         */
        function setMethod($method) {
            $this->_method = $method;
            return $this;
        }
        
        function initialize() {
            $this->_submitInputEmulatorView= new HiddenView(null, $this, self::SUBMIT_INPUT_EMULATOR);
            parent::initialize();
        }
        
        function init(){
            $this->_submitInputEmulatorView->init();
            
            parent::init();
        }

        function show() {
            $this->showTemplate('Page/View/templates/page.phtml');
        }

        /**
         * выводит все видимое и не видимое содержимое формы
         * а поскольку все есть форма, то можно сказать что тут выводится все кроме заголовков <head>
         */
        function showForm() {
            $this->showTemplate($this->_formTemplate);
        }

        function showRequestData() {
            $rd = new \Page\RequestData();
            $rd->cutParam($this->getFullIo()."_");
            foreach ($rd->params as $eachParamName => $eachParamValue) {
                if (is_array($eachParamValue)) {
                    foreach ($eachParamValue as $eachParamValueEachKey => $eachParamValueEachValue) {
                        new HiddenView($eachParamValueEachValue, null, "{$eachParamName}[{$eachParamValueEachKey}]", true);
                    }
                } else {
                    new HiddenView($eachParamValue, null, "$eachParamName", true);
                }
            }
        }

        /**
         * показать страницу в модальном режиме.
         *
         * оберачивает страницу в трай-кэтч и ждет эксепшена череч @see closeModal
         *
         * @var string $io input-output
         * @return int
         */
        function showDialog() {
            try {
                $this->init();
                $this->action();
                $this->show();
                die;
            } catch (\Page\DialogResultException $e) {
                return $e->getCode();
            } catch (\Exception $ex) {
                //\Logger::getLogger(get_class($this))->error($ex->getMessage(), $ex);
                \Logger::getLogger(get_class($this))->error($ex);
                $this->getModel()->setError($ex);
                $this->show();
                die;
            }
        }
        
        /**
         * Отображает ошибку, если она присутствует
         */
        function safeNullShowError() {
            /* @var $model \Hd\AbstractPage */
            $model= $this->getModel();
            
            if ($model->getError()){
                new \Page\View\ExceptionView($model->getError(), $this, "error", true);
            }
        }

        function getScripts() {
            return $this->_scripts;
        }

        public function getCss() {
            return $this->_css;
        }

        public function setCss($_css) {
            $this->_css = $_css;
            return $this;
        }

    }

}