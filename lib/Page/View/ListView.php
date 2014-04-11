<?php

namespace Page\View {

    /**
     * Моделью вьюшки является строка - код выбранного элемента
     *
     * @author alexey_baranov
     */
    class ListView extends ComboboxView {

        function __construct($model, $parent=null, $io="p", $action=null){
            parent::__construct($model, $parent, $io, $action);
            $this->attributes["size"]=5;
            //$this->attributes["multiple"]="multiple";
        }
    }
}