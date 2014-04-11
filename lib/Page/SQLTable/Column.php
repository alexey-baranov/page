<?php

namespace Page\SQLTable {

    class Column {

        /**
         * имя колонки, возвращенное SQL
         *
         * @var string
         *
         */
        public $originName;

        /**
         * имя колонки, приятное для пользователя
         *
         * @var string
         *
         */
        public $userName;
        /**
         * для редактируемых колонок, означает "ширину поля ввода"
         *
         * @var width= false
         *
         */
        public $width;

        /**
         * означает "отображаема, но не редактируема"
         *
         * @var bool= false
         *
         */
        //public $passive = false;

        /**
         * скрытая ячейка
         *
         * @var bool= false
         *
         */
        public $hidden = false;

        /**
         * агрегирующая функция, по которой расчитывается результат подвала
         *
         *
         * @var string
         *
         */
        public $foot;

        /**
         * алиас для имени колонки, возвращенного SQL
         *
         * @var string
         *
         */
        //public $alias;

        /**
         * тип значения в колонке согласно соглашениям ТОР
         *
         * @var int
         *
         */
        public $type;
        
        /**
         * ссылка, по которой должен уйти пользователь, нажав на данные.
         * только для не чойсерских колонок
         * в редактируемых таблицах только для не чойсерских и только пассивных
         *
         * @var string
         *
         */
        //public $onclick;

        /**
         * выравнивание
         * left | center | right
         * если не задано, берется сиходя из типа
         *
         * @var string
         */
        public $align;

        function __construct($onclick= null, $hidden= null, $passive=null, $typee= null, $foot= null, $alias= null, $width= null, $ondblclick= null, $align= null, $href= null) {
            if ($onclick !== null)
                $this->onclick = $onclick;
            if ($hidden !== null)
                $this->hidden = $hidden;
            if ($passive !== null)
                $this->passive = $passive;
            if ($typee !== null)
                $this->type = $typee;
            if ($foot !== null)
                $this->foot = $foot;
            if ($alias !== null)
                $this->alias = $alias;
            if ($width !== null)
                $this->alias = $width;
            if ($ondblclick !== null)
                $this->ondblclick = $ondblclick;
            if ($align !== null)
                $this->align = $align;
        }

        /**
         * возвращает алиас или название колонки, нужно для отображения
         *
         * @return string загоолвок колонки
         *
         */
        function getName() {
            return $this->userName ? : $this->originName;
        }

        function ondblclick($pageRow) {
            $ondblclick = $this->ondblclick;
            foreach ($pageRow->DATA as $COLUMN => $DATA) {
                $ondblclick = ereg_replace("\[$COLUMN\]", $DATA, $ondblclick);
                $ondblclick = ereg_replace("\-$COLUMN\-", $DATA, $ondblclick);
            }
            return $ondblclick;
        }

        /**
         * перекрывает своими свойствами свойства др колонки
         *
         * @param Column $column колонка, которую надо перекрыть
         * @return Column перекрытая колонка
         *
         */
        public function over(Column $column) {
            $result= new Column;
            //if ($this->onclick !== null)
                //$column->onclick = $this->onclick;
            if ($this->hidden !== null){
                $result->hidden=$this->hidden;
            }
            else{
                $result->hidden= $column->hidden;
            }
            //if ($this->passive !== null)
                //$column->passive = $this->passive;
            if ($this->type !== null){
                $result->type= $this->type;
            }
            else{
                $result->type= $column->type;
            }
            
            if ($this->foot !== null){
                $result->foot= $this->foot;
            }
            else{
                $result->foot= $column->foot;
            }
            
            if ($this->width !== null){
                $result->width= $this->width;
            }
            else{
                $result->width= $column->width;
            }
            
            if ($this->align !== null){
                $result->align= $this->align;
            }
            else{
                $result->align= $column->align;
            }
            
            if ($this->userName !== null){
                $result->userName= $this->userName;
            }
            else{
                $result->userName= $column->userName;
            }
            
            if ($this->originName !== null){
                $result->originName= $this->originName;
            }
            else{
                $result->originName= $column->originName;
            }
            
            return $result;
        }

    }

}