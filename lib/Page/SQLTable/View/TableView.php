<?php

namespace Page\SQLTable\View;

class TableView extends \Page\View\HtmlElementView {
    public $_isTitleHidden= true;
    /**
     *
     * @var bool возможность выгрузить данные
     */
    public $_isExportToExcelAvailable = true;
    /*
     * Скрывать все кроме данных
     * Названия колонок, количество строк и т.д.
     */
    protected $_isMetadataHidden= false;
    /**
     *
     * @var ImageView
     */
    protected $_exportToExcelView;
    
    /**
     * $customShowTdContent($this, $row, $COLUMN);
     * @var type переопределяет стандартную перерисовку содержимого ячейки
     */
    public $customShowTdContent;
    
    /**
     * $customTdContentHyperlink($this, $row, $COLUMN);
     * @var function переопределяет стандартный порядок гиперссылок
     */    
    public $customTdContentHyperlink;
    
    /**
     * функция, которая назначается и по которой определяется css строки
     * Если функция не задана, возвращается пустой класс
     * @var function
     */
    public $customGetRowClassName;

    function __construct($model, $parent = null, $io = "p", $action = false) {
        $this2 = $this;

        parent::__construct($model, $parent, $io, $action);

        $this->getModel()->changed->add(
                function(\Page\SQLTable\Table $sender) use($this2) {
                    $this2->initialize();
                }
        );
        
        $this->setClassName("sqlTable");
    }
    
    function initialize() {
        $this->_exportToExcelView= new \Page\View\ImageView(null, $this, "exportToExcel");
        $this->_exportToExcelView->click= array($this, "exportToExcelView_click");
        $this->_exportToExcelView->attributes["src"]="library/Page/SQLTable/images/exportToExcel.png";
        $this->_exportToExcelView->attributes['title'] = 'Экспортировать таблицу в Excel';
        
        parent::initialize();
    }
    public function getIsTitleHidden() {
        return $this->_isTitleHidden;
    }

    public function setIsTitleHidden($isTitleHidden) {
        $this->_isTitleHidden = $isTitleHidden;
    }

    public function getIsExportToExcelAvailable() {
        return $this->_isExportToExcelAvailable;
    }

    public function setIsExportToExcelAvailable($isExportToExcelAvailable) {
        $this->_isExportToExcelAvailable = $isExportToExcelAvailable;
    }

    public function getIsMetadataHidden() {
        return $this->_isMetadataHidden;
    }

    public function setIsMetadataHidden($isMetadataHidden) {
        $this->_isMetadataHidden = $isMetadataHidden;
    }

    function init() {
        /* @var $model \Page\SQLTable\Table */
        $model = $this->getModel();
        
        $pageNumberView= new \Page\View\EditorStringView(null, $this, "p", \Page\View\View::ACTION_INIT);
        if ($pageNumberView->getModel()!==null){
            $model->setPageNumber($pageNumberView->getModel());
        }
        
        $orderView= new \Page\View\EditorStringView(null, $this, "o", \Page\View\View::ACTION_INIT);
        if ($orderView->getModel()!==null){
            $model->setOrder($orderView->getModel());
        }
        
        foreach ($_REQUEST as $PROP => $prop) {
            $elems= array();
            if (preg_match("/^{$this->getFullIo()}_(\d+)$/", $PROP, $elems)){
                $model->SELECTED_ROWS[] = (int)$elems[1];
            }
        }
        
        parent::init();
    }

    function exportToExcelView_click() {
        /* @var $model \Page\SQLTable\Table */
        $model = $this->getModel();
        
        $excel= $model->toExcel();
        
        $excelWriter= \PHPExcel_IOFactory::createWriter($excel, "Excel2007");
        
        $filename= $model->_title ? "{$model->_title}.xlsx" : "без названия.xlsx";

        if (preg_match('/MSIE/', $_SERVER["HTTP_USER_AGENT"])){
            //$filename= 1;
        }
        
//        $filename= mb_str_replace("+", " ", urlencode($filename));
        $filename= str_replace("+", " ", urlencode($filename));

        
//        echo "<pre>";
//        var_dump($_SERVER);
//        die;

        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');

       
  	$excelWriter->save('php://output');
    }

    public function action() {
        $this->_exportToExcelView->action();
        
        parent::action();
    }

    function getRowClassName(\Page\SQLTable\Row $row) {
        /* @var $model \Page\SQLTable\Table */
        $model = $this->getModel();

        if ($this->customGetRowClassName) {
            $customGetRowClassName = $this->customGetRowClassName;
            return $customGetRowClassName($row);
        }
    }

    public function show() {
        /* @var $model \Page\SQLTable\Table */
        $model = $this->getModel();

        echo "
            <div class='Page_SQL_Table'>";
        if (!$this->_isTitleHidden){
            $titleView= new \Page\View\StringView($model->getTitle(), $this, "title");
            echo "
                <div class='title'>{$titleView->getHtml()}</div>";
        }
        
        //жкспорт в эксель
        if ($this->_isExportToExcelAvailable && $model->getTotalRowCount()) {
            echo "
            <div class='download'>{$this->_exportToExcelView->getHtml()}</div>";
        }

        if (!$this->_isMetadataHidden) {
            //пажинация
            new \Page\View\HiddenView($model->getSafePageNumber(), $this, 'p', true);

            //order
            new \Page\View\HiddenView($model->getSafeOrder(), $this, 'o', true);

            echo "<div class='sqlTablePageNumbers'>";

            if ($model->getTotalRowCount() > 3) {
                echo sizeof($model->getPageRows()) . " из {$model->getTotalRowCount()} ";
            }

            //алфавитная пажинация
            if ($model->abcPaging()) {
                $arr = array();
                foreach ($model->abc as $value) {
                    $arr[] = sprintf("<span class='%s'><a href= '%s'>$value</a></span>", $model->getSafePageNumber() == $value ? 'page selected' : 'page', URL::wwParam(array($this->getFullIo() . '_p' => $value, $this->getFullIo() . '_cp' => ''), 'searchh'));
                }
                echo sprintf("<span class='%s'><a href= '%s'>все</a></span>", $model->paging() ? 'page' : 'page selected', URL::wwParam(array($this->getFullIo() . '_p' => 'all', $this->getFullIo() . '_cp' => ''), 'searchh'));
                echo ' | ' . implode(' | ', $arr);
            }

            //порядковая пажинация и не 'all' и есть что показать
            elseif ($model->getSafePageNumber() != \Page\SQLTable\Table::PAGE_NUMBER_ALL && $model->getTotalRowCount()) {
                $start = $end = null; //индексация начинается с 1
                $start = max((int) $model->getSafePageNumber() - round($model->MAX_LINKS / 2 - 1), 1);
                $end = min($start + $model->MAX_LINKS - 1, $model->getPageCount());
                $start = max(1, $end - $model->MAX_LINKS + 1);
                if ($end != $start) {
                    $requestData = new \Page\RequestData();
                    $arr = array();
                    $allPageNumberClassName = "all" == $model->getSafePageNumber() ? "currentPageNumber" : "pageNumber";
                    $arr[] = "<span class='{$allPageNumberClassName}'><a href='{$requestData->wParam(array($this->getFullIo() . '_p' => "all"))}'>все</a></span>";
                    if ($start>1){
                        $arr[] = "<span class='pageNumber'><a href='{$requestData->wParam(array($this->getFullIo() . '_p' => 1))}'>1</a></span>";
                    }
                    for ($i = $start; $i <= $end; $i++) {
                        $eachPageNumberClassName = (string) $i == $model->getSafePageNumber() ? "currentPageNumber" : "pageNumber";
                        $arr[] = "<span class='{$eachPageNumberClassName}'><a href='{$requestData->wParam(array($this->getFullIo() . '_p' => $i))}'>{$i}</a></span>";
                    }
                    if ($end< $model->getPageCount()){
                        $arr[] = "<span class='pageNumber'><a href='{$requestData->wParam(array($this->getFullIo() . '_p' => $model->getPageCount()))}'>{$model->getPageCount()}</a></span>";
                    }

                    echo implode(' | ', $arr);
                }
            }
            echo "</div>";
        }
        
        if ($model->getTotalRowCount()) {
            echo "
            <table {$this->getAttributesHtml()}>";
            if (!$this->_isMetadataHidden) {
                echo "
            <thead class='ui-state-default2 ui-widget-content2''>
            <tr>";
                //выбрать все
                if ($model->rowSelectMode == \Page\SQLTable\Table::ROW_SELECT_MODE_MULTIPLE) {
                    echo "
                <td class='select'><input type='checkbox' id='{$this->getFullIo()}_a' name='{$this->getFullIo()}_a'></td>";
                } else if ($model->rowSelectMode == \Page\SQLTable\Table::ROW_SELECT_MODE_SINGLE) {
                    echo "
                    <td class='select'></td>";
                }
                //заголовки колонок
                foreach ($model->getColumns() as $EACH_COLUMN => $eachColumn) {
                    if ($eachColumn->hidden) {
                        continue;
                    }
                    $escapedEachColumnName = htmlspecialchars($eachColumn->getName());
                    if ((string) ($EACH_COLUMN + 1) == $model->getSafeOrder()) {
                        $escapedEachColumnName.='⇧';
                        $EACH_ORDER = ($EACH_COLUMN + 1) . " DESC";
                    } else if ((string) ($EACH_COLUMN + 1) . ' DESC' == $model->getSafeOrder()) {
                        $escapedEachColumnName.= '⇩';
                        $EACH_ORDER = ($EACH_COLUMN + 1);
                    } else {
                        $escapedEachColumnName.="<span style='visibility:hidden'>⇩</span>";
                        $EACH_ORDER = $EACH_COLUMN + 1;
                    }
                    $requestData = new \Page\RequestData();
                    $ref = $requestData->wParam(array($this->getFullIo() . '_o' => $EACH_ORDER));
                    echo "<td class='type{$eachColumn->type}' style='text-align:{$eachColumn->align}'><a href='$ref'>{$escapedEachColumnName}</a></td>";
                }
                echo "
                </tr>
		</thead>";
            }
            echo "
		<tbody class='ui-widget-content2'>";
            
            //строки страницы
            foreach ($model->getPageRows() as $eachRow) {
                $this->showTr($eachRow);
            }
            echo "
		</tbody>";
            if ($model->foot() && count($model->pageRows()) > 1) {
                echo '
		<tfoot>
		<tr>';
                if ($model->rowSelectMode)
                    echo '<td></td>';
                //подвал таблицы (по всем видимым и невидимым страницам)
                $foot = array();
                $WIDTH = $FIRST = 0;
                foreach ($model->footRow()->DATA as $INDEX => $DATA)
                    if ($model->getColumns($INDEX)->hidden)
                        continue;
                    elseif (!$DATA)
                        $WIDTH++;
                    else {
                        $FIRST = $INDEX;
                        break;
                    }
                echo "<td colspan='$WIDTH'>ИТОГО:</td>";
                for ($i = $FIRST; $i < sizeof($model->getColumns()); $i++)
                    echo "<td style='text-align:{$model->getColumns($INDEX)->align}'>{$model->footRow()->DATA[$i]}</td>";
                echo '
		</tr>
		</tfoot>';
            }
            echo '
		</table>';
        }
        //нет строк на странице
        else {
            echo "
            <span class='noRowMessage'>{$model->noRowMessage}</span>";
        }
        echo "
		</div>";
        //отметить все строки на странице или отмечать только одну строку
        ?>
            <script language='javascript'>
//            $(" #<?=$this->getFullIo() ?>").find("tbody tr").each(function(index, eachDomRow){
//                $(eachDomRow).hover(function(){
//                    $(this).addClass("ui-state-hover");
//                },function(){
//                            $(this).removeClass("ui-state-hover")
//                })
//            })
        <?php
        if ($model->rowSelectMode == 2)
            echo "
				
				$('#{$this->getFullIo()}_a').bind('click', function(e){
					$('#{$this->getFullIo()} tbody td.select input').prop('checked', $('#{$this->getFullIo()}_a').prop('checked'));
				})";
        elseif ($model->rowSelectMode == 1)
            echo "
                $('#{$this->getFullIo()} tbody td.select input').bind('click', function(e){
                    var target= $(this);
                    $('#{$this->getFullIo()} tbody td.select input').prop('checked', false);
                    target.prop('checked', true);
                })";
        echo "
		</script>";
    }

    /**
     * отрисовать строку страницы
     *
     * @param SQLTableRow $row строка страницы
     */
    function showTr($row) {
        /* @var $model \Page\SQLTable\Table */
        $model = $this->getModel();
        echo "
            <tr class='{$this->getRowClassName($row)}'>";
        if ($model->rowSelectMode!= \Page\SQLTable\Table::ROW_SELECT_MODE_NONE) {
            $checked = $model->isRowSelected($row) ? 'checked' : null;
            echo "
            <td class='select'><input type='checkbox' id='{$this->getFullIo()}_{$model->getRowId($row)}' name='{$this->getFullIo()}_{$model->getRowId($row)}' $checked></td>";
        }
        foreach ($model->getColumns() as $EACH_COLUMN => $eachColumn) {
            if ($eachColumn->hidden){
                continue;
            }
            $this->showTd($row, $EACH_COLUMN);
        }
        echo "
            </tr>";
    }

    /**
     * отрисовать ячейку таблицы
     *
     * @param SQLTableRow $row строка страницы
     * @param int $COLUMN 0-based индекс ячейки строки
     * @param int $colspan объединить $colspan ячеек
     */
    function showTd(\Page\SQLTable\Row $row, $COLUMN, $colspan=null) {
        /* @var $model \Page\SQLTable\Table */
        $model = $this->getModel();

        echo "<td ".($colspan?"colspan='{$colspan}'":"")." class='type{$model->getColumns($COLUMN)->type}' style='text-align:{$model->getColumns($COLUMN)->align}'>";
        if ($this->customShowTdContent){
            $customShowTdContent= $this->customShowTdContent;
            $customShowTdContent($this, $row, $COLUMN);
        }
        else{
            $this->showTdContent($row, $COLUMN);
        }
        echo "</td>";
    }
    
    /**
     * Возвращает ссылку, по которой пользователь будет отправлен по клику
     * @param \Page\SQLTable\Row $row
     * @param int $COLUMN
     * @return 
     */
    function getTdContentHyperlink(\Page\SQLTable\Row $row, $COLUMN) {
        
    }

    /**
     *
     * @param SQLTableRow $row
     * @param int $COLUMN 0-based
     */
    function showTdContent($row, $COLUMN) {
        /* @var $model \Page\SQLTable\Table */
        $model = $this->getModel();
        

        
        /* @var $column \Page\SQLTableColumn */
        $column= $model->getColumns($COLUMN);
        $DATA = $row->DATA[$COLUMN];
        switch ($column->type) {
            case \Page\SQLTable\Table::TYPE_INT:
                $view = new \Page\View\IntView($DATA, $this, "{$model->getRowId($row)}_{$COLUMN}");
                $DATA = $view->getHtml();
                break;
            case \Page\SQLTable\Table::TYPE_STRING:
                $view = new \Page\View\StringView($DATA, $this, "{$model->getRowId($row)}_{$COLUMN}");
                $DATA = $view->getHtml();
                break;
            case \Page\SQLTable\Table::TYPE_TEXT:
                $view = new \Page\View\TextView($DATA, $this, "{$model->getRowId($row)}_{$COLUMN}");
                $view->noRef = false;
                $DATA = $view->getHtml();
                break;
            case \Page\SQLTable\Table::TYPE_DATETIME:
                $view = new \Page\View\DateView($DATA?new \DateTime($DATA):null, $this, "{$model->getRowId($row)}_{$COLUMN}");
                $DATA = $view->getHtml();
                break;
            case \Page\SQLTable\Table::TYPE_BOOL:
                $view = new \Page\View\BoolView($DATA, $this, "{$model->getRowId($row)}_{$COLUMN}");
                $DATA = $view->getHtml();
                break;
            case 106:
                require_once 'view/StorageView.php';
                $storage = new Storage();
                $storage->fromString($DATA);
                $view = new \Page\View\StorageAnywhereView($storage, "{$model->getColumns($COLUMN)->originName}");
                $view->attr['MOTHER'] = $row->id;
                $DATA = $view->getHtml();
                break;
            case \Page\SQLTable\Table::TYPE_FLOAT:
                $view = new \Page\View\FloatView($DATA, $this, "{$model->getRowId($row)}_{$COLUMN}");
                $DATA = $view->getHtml();
                break;
            case \Page\SQLTable\Table::TYPE_MONEY:
                $view = new \Page\View\MoneyView(Persistent::Pg2PhpMoney($DATA), $this, "{$model->getRowId($row)}_{$COLUMN}");
                $DATA = $view->getHtml();
                break;
            default:
                break;
        }
        
        //$onclick = $column->onclick($row);
        //$onclick = $onclick ? " onclick= \"$onclick\" " : null;
        //$ondblclick = $column->ondblclick($row);
        //$ondblclick = $ondblclick ? " ondblclick=\"$ondblclick\"; " : null;
        
        /* @var $hyperlink \Page\Hyperlink */
        $hyperlink= null;
        if ($this->customTdContentHyperlink){
            $customTdContentHyperlink= $this->customTdContentHyperlink;
            $hyperlink= $customTdContentHyperlink($this, $row, $COLUMN);
        }
        else{
            $hyperlink= $this->getTdContentHyperlink($row, $COLUMN);
        }
        
        if ($hyperlink){
            echo "<a target='{$hyperlink->getTarget()}' href='{$this->escapeHTMLString($hyperlink->getHref())}'>{$DATA}</a>";
        }
        else{
            echo $DATA;
        }
        /*
        if ($column->href){
            echo "<a target='{$column->target}' href='{$column->getHrefForRow($row)}' >{$DATA}</a>";
        }
        else{
            echo $DATA;
        }
         */
    }
}