<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alexey_baranov
 * Date: 19.01.11
 * Time: 12:33
 * To change this template use File | Settings | File Templates.
 */

namespace Page\View{
    class ViewManager {
        static protected $_viewClasses= array();
        static protected $_editorViewClasses= array();
        
        static function setViewClass($modelClass, $viewClass){
            static::$_viewClasses[$modelClass]= $viewClass;
        }

        /**
         * @param array $viewClasses [modelClass]=>viewClass
         */
        static function setViewClasses($viewClasses){
            foreach($viewClasses as $eachModelClass=>$eachViewClass){
                static::$_viewClasses[$eachModelClass]= $eachViewClass;
            }
        }
        
        static function setEditorViewClass($modelClass, $viewClass){
            static::$_editorViewClasses[$modelClass]= $viewClass;
        }

        /**
         * @param array $viewClasses [modelClass]=>viewClass
         */
        static function setEditorViewClasses($viewClasses){
            foreach($viewClasses as $eachModelClass=>$eachViewClass){
                static::$_editorViewClasses[$eachModelClass]= $eachViewClass;
            }
        }
        
        /**
         * Класс вьюшки для заданной модели
         * 
         * @param string $modelClass
         * @param string $defaultClass
         * @return string
         * @throws \Page\Exception вьюшка для класса модели не зарегистрирована и не задана вьюшка по умолчанию
         */
        static protected function getViewClass($modelClass, $defaultClass=null){
            if (isset(static::$_viewClasses[$modelClass])){
                return static::$_viewClasses[$modelClass];
            }
            
            foreach(static::$_viewClasses as $eachModelClass => $eachViewClass){
                if (is_subclass_of($modelClass, $eachModelClass)){
                    return $eachViewClass;
                }
            }
            
            if ($defaultClass){
                return $defaultClass;
            }
            else{
                throw new \Page\Exception("Класс представления для класса \"{$modelClass}\" не задан. Возможно забыли задать класс по умолчанию.");
            }
        }
        
        /**
         * Класс вьюшки редактирования для заданной модели
         * 
         * @param string $modelClass
         * @param string $defaultClass
         * @return string
         * @throws \Page\Exception вьюшка для класса модели не зарегистрирована и не задана вьюшка по умолчанию
         */
        static protected function getEditorViewClass($modelClass, $defaultClass=null){
            if (isset(static::$_editorViewClasses[$modelClass])){
                return static::$_editorViewClasses[$modelClass];
            }
            
            foreach(static::$_editorViewClasses as $eachModelClass => $eachViewClass){
                if (is_subclass_of($modelClass, $eachModelClass)){
                    return $eachViewClass;
                }
            }
            
            if ($defaultClass){
                return $defaultClass;
            }
            else{
                throw new \Page\Exception("Класс представления редактирования для класса \"{$modelClass}\" не задан. Возможно забыли задать класс по умолчанию.");
            }
        }
        
        /**
         *
         * @param mixed $model
         * @param View $parent
         * @param string $io
         * @param int $action
         * @param string $defaultViewClass имя класса, который будет вьюшкой, если в менеджере не зарегистрирована вьюшка для класса модели
         * 
         * @return NodeView
         */
        static function getView($model, $parent=null, $io="p", $action= null, $defaultViewClass= null){
            $modelClass=null;
            if (is_object($model)){
                $modelClass= get_class($model);
            }
            else if (is_int($model)){
                $modelClass= "int";
            }
            else if (is_string($model)){
                $modelClass= "string";
            }
            else if (is_bool($model)){
                $modelClass= "bool";
            }
            else{
                throw new \Page\Exception("Не распознан класс модели");
            }
            
            $viewClass= static::getViewClass($modelClass, $defaultViewClass);
            $result= new $viewClass($model, $parent, $io, $action);
            return $result;
        }
        
        /**
         *
         * @param mixed $model
         * @param View $parent
         * @param string $io
         * @param int $action
         * @param string $defaultViewClass имя класса, который будет вьюшкой, если в менеджере не зарегистрирована вьюшка для класса модели
         * 
         * @return NodeView
         */
        static function getEditorView($model, $parent=null, $io="p", $action= null, $defaultViewClass= null){
            $modelClass=null;
            if (is_object($model)){
                $modelClass= get_class($model);
            }
            else if (is_int($model)){
                $modelClass= "int";
            }
            else if (is_string($model)){
                $modelClass= "string";
            }
            else if (is_bool($model)){
                $modelClass= "bool";
            }
            else{
                throw new \Page\Exception("Не распознан класс модели");
            }
            
            $viewClass= static::getEditorViewClass($modelClass, $defaultViewClass);
            $result= new $viewClass($model, $parent, $io, $action);
            return $result;
        }
    }
    
    ViewManager::setViewClasses(array(
        "int"=>"Page\View\IntView",
        "string"=>"Page\View\StringView",
        "bool"=>"Page\View\BoolView",
        "DateTime"=>"Page\View\DateView",
        "Exception"=>"Page\View\ExceptionView"
    ));
    
    ViewManager::setEditorViewClasses(array(
        "int"=>"Page\View\EditorIntView",
        "string"=>"Page\View\EditorStringView",
        "bool"=>"Page\View\EditorBoolView",
        "DateTime"=>"Page\View\EditorDateView"
    ));
}
