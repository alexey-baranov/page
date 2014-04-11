<?php

namespace Page {

    /**
     * Description of Url
     *
     * @author alexey_baranov
     */
    class RequestData {
        public $scheme;
        
        public $host;
        
        public $port;
        
        /**
         * все до ?
         *
         * @var string
         */
        public $path;
        /**
         * парамы как в $_REQUEST:
         * параметры с .x и .y замененны на _x и _y
         * массивы в виде array[4], array[1], array[5], т.е. индексы из строки запроса сохраняются
         * и в декодированном виде
         *
         * @var array
         */
        public $params= array();

        function __construct($url=null) {
            if (!$url){
                //SERVER_HOST содержит порт
                //PHP_SELF начинается со "/"
                $url= (isset($_SERVER['HTTPS'])?"https":"http")."://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
                //echo "<br>$url<br>";
                //echo "<br>{$_SERVER['HTTP_HOST']}<br>";
                //echo "<br>{$_SERVER['PHP_SELF']}<br>";
            }
            $this->fromUrl($url);
            
            $this->appandParamsFromPost(); //папаметры могут лежать и в $_POST
            
            /*
                $this->path = $_SERVER['PHP_SELF'];
                foreach ($_REQUEST as $eachName => $eachValue) {
                    if (\array_key_exists($eachName, $_COOKIE)){
                        continue;
                    }
                    else{
                        $this->params[$eachName]=$eachValue;
                    }
                }
            }
            */
        }

        function fromUrl($url) {
            $components= parse_url($url);
            
            $this->scheme=isset($components["scheme"])?$components["scheme"]:null;
            $this->host= isset($components["host"])?$components["host"]:null; //тут хост уже с портом - НИХУЯ!!
            $this->port= (isset($components["port"]) && $components["port"])?$components["port"]:null;
            $this->path= $components["path"];
            if (isset($components["query"])){
                parse_str($components["query"], $this->params);
            }
            else{
                $this->params= array();
            }
            
        }
        
        /**
         * Дополнить параметры запроса информацией из $_POST
         */
        function appandParamsFromPost() {
            foreach($_POST as $EACH_PARAM=>$eachParam){
                $this->params[$EACH_PARAM]= $eachParam;
            }
        }

        /**
         *
         * @return string www.tor.ru
         */
        function host() {
            return $this->host;
        }

        
        protected function getPathWoPage(){
            $result= preg_replace('/[^\/]*$/','', $this->getPath());
            return $result;
        }
        
        /**
         * /welcome.php
         * 
         * @return string
         */
        function getPath() {
            return $this->path;
        }
        /**
         *
         * @param string $page заменяет в пути страницу
         */
        function replacePageInPath($page){
            $this->path= $this->getPathWoPage().$page;
        }
        
        

        /**
         * добавляет новые параметры или заменяет существующие
         *
         * @param array|string $add массив параметров $param=>$value | $param
         * @return UL
         */
        function wParam($add) {
            //'ac'  -> array('ac'=>'')
            if (!is_array($add))
                $add = array($add => '');
            //вычистил параметры
            foreach ($add as $p => $v) {
                $this->params[$p] = $v;
            }
            return $this;
        }

        /**
         * исключает параметры
         *
         * @param array|string $except массив параметров array($param) | $param
         * @return UL
         */
        function woParam($except) {
            //'ac'  -> array('ac')
            if (!is_array($except)) {
                $except = array($except);
            }
            //вычистил параметры
            foreach ($except as $p) {
                unset($this->params[$p]);
            }
            return $this;
        }

        /**
         * вырезает параметры, ВКЛЮЧАЯ базу
         *
         * @param array|string $base база удаления
         * @return UL
         */
        function cutParam($base) {
            if (!is_array($base)) {
                $base = array($base);
            }
            foreach ($base as $c) {
                foreach ($this->params as $p => $v) {
                    if (preg_match("/^$c/i", $p))
                        unset($this->params[$p]);
                }
            }
            return $this;
        }

        /**
         *
         * @return string в закодированном виде, готовая для отправки в header()
         */
        function __toString() {
            $params = array();
            foreach ($this->params as $p => $v) {
                if (is_array($v)) {
                    $param = array();
                    foreach ($v as $PARAMINDEX => $PARAMVALUE)
                        $param[] = $p . '[' . $PARAMINDEX . ']=' . urlencode($PARAMVALUE);
                    $params[$p] = implode('&', $param);
                }
                else
                    $params[$p] = preg_replace('/(\w)_(x|y)$/', '\\1.\\2', $p) . '=' . urlencode($v); //параметр _x не преобразуется в .x
            }
            $dotPort= $this->port?":{$this->port}":null;
            //возвращию. причем $this->host по правилам parse_url уже содержит порт
            $result="";
            //может быть без схемы и порта например просто "index.php?abc=123"
            if ($this->scheme){
                $result.= "{$this->scheme}://{$this->host}{$dotPort}";
            }
            $result.=$this->path . '?' . implode('&', $params);
            //echo $result;
            return $result;
        }
        
        function getHost() {
            //return $_SERVER["HTTP_HOST"];
            return $this->host;
        }
    }
}