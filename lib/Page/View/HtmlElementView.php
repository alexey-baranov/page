<?php

namespace Page\View {

    /**
     * Description of HTMLElementView
     *
     * @author alexey_baranov
     */
    class HtmlElementView extends View {

        public $attributes = array();

        public function setIo($io) {
            $result= parent::setIo($io);
            $this->attributes['name'] = $this->getFullIo();
            $this->attributes['id'] = $this->getFullIo();
            return $result;
        }

        function setClassName($className) {
            $this->attributes["class"] = $className;
            return parent::setClassName($className);
        }

        function showAttributes() {
            //name это бес, потому что после установки io у ПАРЕНТА могла смениться io
            if (isset($this->attributes['name'])) {
                //echo " name= '{$this->attributes['name']}' ";
            }
            //id это бес, потому что после установки io у ПАРЕНТА могла смениться io
            if (isset($this->attributes['id']) && $this->attributes['id'] !== null && $this->attributes['id'] !== ''){
                //echo " id= '{$this->attributes['id']}' ";
            }
            
            echo " name= '{$this->getFullIo()}'";
            echo " id= '{$this->getFullIo()}'";
            
            
            foreach ($this->attributes as $EACH_ATTRIBUTE => $eachAttribute) {
                if ($EACH_ATTRIBUTE=="id" || $EACH_ATTRIBUTE=="name"){
                    continue;
                }
                echo "{$EACH_ATTRIBUTE}=\"{$this->escapeHTMLString($eachAttribute)}\" ";
            }
/*            
            //class
            if (isset($this->attributes['class']))
                echo " class= '{$this->attributes['class']}' ";
            //accesskey
            if (isset($this->attributes['accesskey']))
                echo " accesskey='{$this->attributes['accesskey']}' ";
            //title
            if (isset($this->attributes['title'])) {
                echo " title = '{$this->escapeHTMLString($this->attributes['title'])}' ";
                $alt = strlen($this->attributes['title']) > 20 ? (trim(mb_substr($this->attributes['title'], 0, 15)) . '...') : trim($this->attributes['title']);
                echo " alt = '{$this->escapeHTMLString($alt)}' ";
            }
            //tabindex
            if (isset($this->attributes['tabindex']))
                echo " tabindex= '{$this->attributes['tabindex']}' ";
            //disabled
            if (isset($this->attributes['disabled']) && $this->attributes["disabled"])
                echo " disabled ";
            //readonly
            if (isset($this->attributes['readonly']) && $this->attributes["readonly"])
                echo " readonly ";
            //size
            if (isset($this->attributes['size']))
                echo " size='{$this->attributes['size']}' ";
            //maxlength
            if (isset($this->attributes['maxlength']))
                echo " maxlength='{$this->attributes['maxlength']}' ";
            //rows
            if (isset($this->attributes['rows']))
                echo " rows='{$this->attributes['rows']}' ";
            //cols
            if (isset($this->attributes['cols']))
                echo " cols='{$this->attributes['cols']}' ";
            //value
            if (isset($this->attributes['value']))
                echo " value='{$this->escapeHTMLString($this->attributes['value'])}' ";
            //size
            if (isset($this->attributes['size']))
                echo " size='{$this->attributes['size']}' ";
            //style
            if (isset($this->attributes['style']))
                echo " style='{$this->attributes['style']}' ";
            //checked
            if (isset($this->attributes['checked']) && $this->attributes['checked'])
                echo " checked='checked' ";
            //checked
            if (isset($this->attributes['src']) && $this->attributes['src']){
                echo "src='{$this->attributes['src']}' ";
            }
            //multiple
            if (isset($this->attributes['multiple']) && $this->attributes['multiple']){
                echo "multiple='{$this->attributes['multiple']}' ";
            }
            //min
            if (isset($this->attributes['min'])){
                echo "min='{$this->attributes['min']}' ";
            }            
            //max
            if (isset($this->attributes['max'])){
                echo "max='{$this->attributes['max']}' ";
            }          
            //events
            foreach ($this->attributes as $eachAttribute => $eachValue) {
                if (\preg_match('/^on/', $eachAttribute)) {
                    echo "\n{$eachAttribute}= '{$this->escapeHTMLString($eachValue)}' ";
                }
            }
 */
        }

        public function getAttributesHtml() {
            \ob_start();
            $this->showAttributes();
            $html = \ob_get_clean();
            return $html;
        }

    }

}