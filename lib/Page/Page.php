<?php

namespace Page {
    use Zend\Session\Container;

    /**
     * Голая страница
     *
     * @author ABaranov
     */
    class Page{
        const DIALOG_RESULT_OK= 91;
        const DIALOG_RESULT_CANCEL= 33;
        const DIALOG_RESULT_CLOSE= 332;

        /**
         *
         * @var Page
         */
        protected $_parent;
        protected $_autoReloadDelay;
        protected $_title;
        protected $_noAccessMessage = 'Доступ запрещен. Обратитесь к админиcтратору.';
        protected $_error;
        
        /**
         *
         * @var Container
         */
        protected $_sessionContainer;

        function __construct() {
//            $this->_sessionContainer = new Container(get_class($this));
        }
        
        public function getParent() {
            return $this->_parent;
        }

        public function setParent($parent) {
            $this->_parent = $parent;
        }

        /**
         * время автоматической перезагрузги в секундах
         * в зависимости от того GET или POST будет location.reload или form.submit()
         *
         * добавляет js в конец страницы
         *
         * @return int задержка в секундах
         */
        function getAutoReloadDelay() {
            return $this->_autoReloadDelay;
        }

        /**
         * время автоматической перезагрузги в секундах
         * в зависимости от того GET или POST будет location.reload или form.submit()
         *
         * @param string $seconds
         * @return Page 
         */
        function setAutoReloadDelay($seconds) {
            $this->_autoReloadDelay = $seconds;
            return $this;
        }

        /*  это имело бы смысл только если бы контентом страницы всегда был один объект, но это не так
         * контентом страницы могут быть пара массивов и три таблицы или любая другая комбинация объектов и простых данных
          function getContent() {
          return $this->content;
          }

          function setContent($content) {
          $this->content = $content;
          return $this;
          }
         *
         */

        /**
         * текст в заголовке браузера
         * @return string
         */
        function getTitle() {
            return $this->_title;
        }

        /**
         * текст в заголовке браузера
         *
         * @param string $title
         * @return Page
         */
        function setTitle($title) {
            $this->_title = $title;
            return $this;
        }

        function getNoAccessMessage() {
            return $this->_noAccessMessage;
        }

        /**
         *
         * @param string $noAccessMessage
         * @return Page
         */
        function setNoAccessMessage($noAccessMessage) {
            $this->_noAccessMessage = $noAccessMessage;
            return $this;
        }

        /**
         * доступ открыт ?
         *
         * @return bool доступ открыт?
         */
        function getAccess() {
            return true;
        }

        /**
         * закрывает модальное окно
         *
         * @param $code код возврата
         * @throws CloseModalException закрывает showModal()
         */
        function setDialogResult($code) {
            throw new DialogResultException('internal Page close dialog exception', $code);
        }
        
        
        function getError() {
            return $this->_error;
        }

        function setError($_error) {
            $this->_error = $_error;
        }
    }
}