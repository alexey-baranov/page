<?php

namespace Page\View {
    /**
     * Description of StringView
     *
     * @author alexey_baranov
     */
    class TextView extends StringView {
        function __construct($model, $parent=null, $io="p", $action=null){
            if (preg_match('#Mozilla/4\.0#', $_SERVER["HTTP_USER_AGENT"])){
                $this->attributes["style"]="white-space: pre;"; //это должно быть впереди, потому что вдруг вывод будет прям в конструкторе
            }
            else{
                $this->attributes["style"]="white-space: pre-line;"; //это должно быть впереди, потому что вдруг вывод будет прям в конструкторе
            }

//            $this->attributes["nowrap"]=;
            parent::__construct($model, $parent, $io, $action);
        }
    }
}