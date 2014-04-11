<?php

namespace Page\View {
    /**
     * Вьюшка модели
     *
     * @author alexey_baranov
     */
    class View {
        const ACTION_INIT= 1;
        const ACTION_ACTION = 2;
        const ACTION_SHOW= 4;
        
        
        protected $_io;
        protected $_template;
        protected $_model;
        protected $_parent;
        /**
         * css class name
         */
        protected $_className;
        /**
         *
         * @var \Logger лог вьюшки
         */
        protected $_log;

        function __construct($model, $parent= null, $io="p", $action= false) {
            $this->_log= \Logger::getLogger(get_class($this));
            
            if ($parent){
                $this->setParent($parent);
            }
            if ($io!==null){ //потому что бывает $io="" и это должно быть установлена
                $this->setIo($io);
            }

            $this->setModel($model); // это в самом конце, потому что к моменту initialize() все остальное должно быть готово

            $this->doAction($action);
        }
        
        function __toString() {
            return $this->getHtml();
        }

        function doAction($action) {
            if ($action === true){
                $action = View::ACTION_SHOW;
            }
            if ($action & View::ACTION_INIT){
                $this->init();
            }
            if ($action & View::ACTION_ACTION){
                $this->action();
            }
            if ($action & View::ACTION_SHOW){
                $this->show();
            }
        }
        
        function initialize() {
            
        }

        /**
         * получить родительскую вьюшку
         *
         * @return View
         */
        function getParent() {
            return $this->_parent;
        }
        
        /**
         * 
         *
         * @param string $className
         * @ throws \Page\Exception нет родителей заданного типа
         * @return type 
         */
        function getParentOfTypeOrNull($className) {
            for ($eachParent = $this->getParent(); $eachParent; $eachParent = $eachParent->getParent()) {
                if (\is_subclass_of($eachParent, $className) || \get_class($eachParent) == $className) {
                    return $eachParent;
                }
            }
            //throw new \Page\Exception("У ноды нет родителей типа {$className}");
        }        

        /**
         * начначить родительскую вьюшку
         *
         * @param View $parent
         * @return View
         */
        function setParent($parent) {
            $this->_parent = $parent;
            return $this;
        }

        function getModel() {
            return $this->_model;
        }

        function setModel($m) {
            $this->_model = $m;

            $this->initialize();
            
            return $this;
        }

        function getIo() {
            return $this->_io;
        }

        function getFullIo() {
            if ($this->getParent())
                if ($this->getIo()===""){
                    return $this->getParent()->getFullIo();
                }
                else{
                    return $this->getParent()->getFullIo() . '_' . $this->getIo();
                }
            else
                return $this->getIo();
        }

        function setIo($io) {
            $this->_io = $io;
            return $this;
        }

        function getClassName() {
            return $this->_className;
        }

        function setClassName($className) {
            $this->_className = $className;
            return $this;
        }

        static function getDefaultTemplateForModel($className) {
            return '_' . strtolower($className) . '.phtml';
        }

        /**
         * рисует по шаблону в контексте вьюшки
         *
         * @param $template путь до файла начиная с пути от include_path и с расширением alexey_baranov/Page/View/templates/nodePage.phtml
         */
        function showTemplate($template) {
//            $selfReflection = new \ReflectionClass(get_class($this));
//            require dirname($selfReflection->getFileName())."/".$template;
            require $template;
        }

        /**
         * возвращает содержимое шаблона в виде строки
         *
         * @param $template @see showTemplate ()
         */
        function getTemplateHtml($template) {
            \ob_start();
            try{
                $this->showTemplate($template);
                $html = \ob_get_clean();
                return $html;
            }
            catch(\Exception $ex){
                \ob_clean();
                throw $ex;
            }
        }

        /**
         * JavaScript escaping
         * Пример:
         * $str= je(' "строка внутри ковычек" ');
         * echo 'alert("$str")'
         *
         * @param string $string строка, которую надо поместить внутрь js-строки
         *
         * @return string ескейпнутая строка
         *
         */
        function escapeJsString($string) {
            $string = \preg_replace("/\\\\/", '\\\\\\\\', $string); //      \  ->  \\ !пиздец!
            $string = \preg_replace('/"/', '\\"', $string);
            $string = \preg_replace("/'/", "\\'", $string);

            return $string;
        }
        
        function json($value) {
            if ($value instanceof \DateTime && $value){
                return "new Date({$value->getTimestamp()}000)";
            }
            else if ($value===null){
                return "null";
            }
            return \json_encode($value);
        }

        /**
         * HTML escaping
         *
         * @param string $string строка, которую надо поместить внутрь HTML-элемента
         * @return string ескейпнутая строка
         *
         */
        function escapeHTMLString($string) {
            return htmlentities($string, ENT_QUOTES, 'utf-8', false);
        }

        function toScriptTag($command) {
            return "
            <script type='text/javascript' charset='UTF-8'>
            $command;
            </script>";
        }

        /**
         *
         * @return View
         */
        function init() {
            return $this;
        }

        /**
         *
         * @return View
         */
        function action() {
            return $this;
        }

        /**
         * отрисовать модель
         *
         * @return void
         *
         */
        function show() {
            if ($this->_template){
                $this->showTemplate($this->_template);
            }
            else{
                print_r($this->getModel());
            }
        }

        /**
         * возвращает в виде строки
         *
         * выполняет @see show внутри буфера вывода
         *
         * @return string
         */
        public function render() {
            \ob_start();
            call_user_func_array(array($this, "show"), func_get_args());
            $html = \ob_get_clean();
            return $html;
        }

        /**
         * алиас для @see render
         *
         * @return string
         */
        function getHtml() {
            return call_user_func_array(array($this, "render"), func_get_args());
            //return $this->render();
        }

    }
}