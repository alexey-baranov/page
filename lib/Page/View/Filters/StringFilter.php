<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Page\View\Filters;

/**
 * Фильтр с методами обработки строк для StringView
 * @author Администратор
 */
class StringFilter {

    /**
     * 
     * @param string $input
     */
    static function filterPhoneNumber($this, $input) {
        $result = preg_replace('/((\+7|\b8)\s*(\(|-)?\d{3,4}(\)|-)?\s*[2-9][\d\-]{5,7}\d\b)|(\b[2-9][\d\-]{4,7}\d\b)/', "<span class='phoneNumber' title='Звонить на \\0' onclick=\"call('\\0'); return false;\">\\0</span>", $input);
        return $result;
    }

    static function filterLink($this, $input) {
        $result = preg_replace('/\b(\w|\d)[\w\d\.\-]*@[\w\d\.\-]+\.\w{2,3}\b/', '<a class="external" href="mailto:\\0">\\0</a>', $input);
        $result = preg_replace('/\b(\w+\:\/\/|www\.)[А-я\w\d\.\%\:\-\/\?\&\=\;]*[А-я\w\d\%\/\?\&\=\;]+\b/u', '<a class="external" target="_blank" href="\\0">\\0</a>', $result);
        // $result = preg_replace('/\w+\:\/\/[\w\d\.\%\:\-\/\?\&\=\;]*[\w\d\%\/\?\&\=\;]+/', '<span class="externalLink" onclick="window.open(\'\\0\'); ">\\0</span>', $result);
//        $result = preg_replace('/([^\w]+[^\:][^\/]+)(www\.[\w\d\.\%\:\-\/\?\&\=\;]*[\w\d\%\/\?\&\=\;]+)/', '\\1<a class="external" target="_blank" href="http://\\2">\\2</a>', $result);
        return $result;
    }

}