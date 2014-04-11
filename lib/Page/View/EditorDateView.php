<?php

namespace Page\View {

    /**
     * Description of EditorIntView
     *
     * @author alexey_baranov
     */
    class EditorDateView extends DateView{
        function initialize() {
            parent::initialize();
            $this->attributes['alt']= "гггг.мм.дд чч:мм";
            $this->attributes['size']= 5;
        }
        function init(){
            if (!isset($_REQUEST[$this->getFullIo()])){
                return;
            }
            elseif (trim($_REQUEST[$this->getFullIo()])===''){
                $this->setModel(null);
            }
            else{
                $this->setModel( \DateTime::createFromFormat ($this->_format, $_REQUEST[$this->getFullIo()]) );
                if (!$this->_model){
                    $this->setModel( \DateTime::createFromFormat ($this->_format, $_REQUEST[$this->getFullIo()]." 00:00"));
                }
                if (!$this->_model){
                    throw new \Page\Exception("Неверный формат даты {$_REQUEST[$this->getFullIo()]}");
                }
            }
        }
        
        function show(){
            /* @var $model \DateTime */
            $model= $this->_model;
            
            $MODEL= $model?$model->format($this->_format):null;
            $dateView= new EditorStringView($MODEL, $this, "");
            $dateView->attributes['title']= "Дата/время в формате \"ДД.ММ.ГГГГ\" или \"ДД.ММ.ГГГГ ЧЧ:ММ\"";
            $dateView->show();
            echo $this->toScriptTag("$(function(){
                        $('#{$this->getFullIo()}').datetimepicker(
                            {
                                showSecond: false,
                                showButtonPanel: true,
                                showOn: 'button',
                                beforeShow: function(input, inst)
                                {
                                    inst.dpDiv.css({marginLeft: input.offsetWidth + 50 + 'px'});
                                }
                            }
                        )
                    })");
            
        }
    }

}