<?php

namespace Page\SQLTable;

/**
 * SQL- таблица
 *
 */
class Table {

    const ROW_SELECT_MODE_NONE = 0;
    const ROW_SELECT_MODE_SINGLE = 1;
    const ROW_SELECT_MODE_MULTIPLE = 2;
    const PAGE_NUMBER_FIRST = "first";
    const PAGE_NUMBER_LAST = "last";
    const PAGE_NUMBER_ALL = "all";
    const TYPE_INT = "int";
    const TYPE_BOOL = "bool";
    const TYPE_FLOAT = "float";
    const TYPE_STRING = "string";
    const TYPE_TEXT = "text";
    const TYPE_DATETIME = "datetime";
    const TYPE_INTERVAL = "interval";
    const TYPE_MONEY = "money";

    /**
     *
     * @var \PDO
     */
    protected static $_defaultConnection;

    /**
     *
     * @var string заголовок пойдет в название выгруженного файла
     */
    public $_title;

    /**
     * колонки, которые получены из базы данных с использованием запроса таблицы.
     * загружаются динамически по первому требованию.
     * перекрываются программными колонками в фн: colunms()
     *
     * @var Column
     *
     */
    protected $_originColumns;

    /**
     * свойства колонки, задаваемые программно.
     * в fn columns() перекрывают собой свойства $_originColumns, получаемые автоматически из таблицы.
     *
     * @var array
     */
    public $userColumns = array();

    /**
     * строка подвала
     *
     * @var string 
     * 
     */
    private $_footRow;

    /**
     * общее количество строк в таблице, когда ее развернут
     *
     * @var int 
     *
     */
    private $_TOTAL_ROWS;

    /**
     * SQL ВСЕЙ таблицы, а не страницы
     *
     * @sql string
     *
     */
    public $sql = 'неопределенный SQL';

    /**
     * максимальное количество страниц в шапке таблицы
     *
     * @var int 
     *
     */
    public $MAX_LINKS = 10;

    /**
     * массив из ID выделенных строк
     *
     * @var array 
     *
     */
    public $SELECTED_ROWS = array();

    /**
     * можно ли в таблице выбрать строки?
     * 0- совсем нельзя
     * 1- можно по одной строчке
     * 2- можно выбрать больше одной строчки
     *
     * @var int
     *
     */
    public $rowSelectMode = Table::ROW_SELECT_MODE_MULTIPLE;

    /**
     * индекс колонки, по которой ведется алфавитный пажинг. нумерация колонок начинается с 0.
     *
     * @var int
     *
     */
    public $ABC;
    //1 или "а" или "first" или "last" или "all"
    //null - 1 или "а" в зависимости от алфавитности
    public $abc = array('а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'ий', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'фхц', 'ч', 'шщ', 'эюя');

    /**
     *
     * @var string
     *
     */
    protected $_search;

    /**
     * текущая страница таблицы. порядковая нумерация начинается с 1!
     *
     * @var string 
     *
     */
    public $pageNumber = 1;
    public $PAGE_SIZE = 20;

    /**
     * порядок сортировки "1 DESC, 2, 3 DESC". если допущена ошибка, order() ее поправит
     *
     * @ORDER string
     *
     */
    protected $_order = '1';

    /**
     * строки страницы таблицы. загружаются динамически по первому требованию в fn pageRows()
     *
     * @var array
     */
    public $pageRows;

    /**
     *
     * @var string сообщение, которое должен увидеть пользователь, если в таблице нет ни одной строки
     */
    public $noRowMessage = 'не заведено';

    /**
     * функция, которая назначается и по которой строке возвращает связанный объект
     * Если функция не задана, объект не назначается
     * @var function
     */
    public $customGetRowAssociatedObject;

    /**
     * функция, которая назначается и по которой определяется идентификатор строки
     * Если функция не задана, за идентификатор принимается первая колонка
     * @var function
     */
    public $customGetRowId;

    /**
     * setSql()
     * и др. изменениях в таблице
     * 
     * @var MulticastDelegate
     */
    public $changed;

    /**
     *
     * @var \PDO
     */
    protected $_connection;

    static function setDefaultConnection($value) {
        self::$_defaultConnection = $value;
    }

    /**
     * 
     * @return \PDO
     */
    static function getDefaultConnection() {
        return self::$_defaultConnection;
    }

    function __construct($sql = null) {
        $this->changed = new \Page\MulticastDelegate();
        if ($sql) {
            $this->setSql($sql);
        }
    }

    public function getConnection() {
        return $this->_connection;
    }

    public function setConnection(\PDO $value) {
        $this->_connection = $value;
    }

    /**
     * 
     * @return PDO
     */
    public function getConnectionOrDefault() {
        return $this->_connection? : self::$_defaultConnection;
    }

    function setSql($value) {
        $this->sql = $value;
        $this->reset();

        $this->changed->call($this);
    }

    function getSql() {
        return $this->sql;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function setTitle($title) {
        $this->_title = $title;
    }

    /**
     * вычисляет идентификатор строки таблицы (чаще всего это идентификатор объекта строки из первой колонки)
     */
    function getRowId(Row $row) {
        if ($this->customGetRowId) {
            $customGetRowId = $this->customGetRowId;
            return $customGetRowId($row);
        } else {
            return $row->DATA[0]; //(int) - зачем тут приведение к int ?
        }
    }

    /**
     * строит массив колонок _SQLColumns по $sql таблицы
     * 
     * @return void
     *
     */
    private function _buildOriginColumns() {
        if (!$this->sql) {
            throw new Exception("Не задана SQL команда таблицы {$this->_title}");
        }
        $originColumnsSql = "select * from ($this->sql) as a limit 1";
        $this->_originColumns = array();
        $st = $this->getConnectionOrDefault()->query($originColumnsSql);

        for ($i = 0; $i < $st->columnCount(); $i++) {
            $column = new Column();
            $meta = $st->getColumnMeta($i);
            $column->originName = $meta['name'];
            switch (strtolower($meta['native_type'])) {
                case 'bool':
                    $column->type = self::TYPE_BOOL;
                    break;
                case 'integer':
                case 'int4':
                case 'int8':
                case 'int8':
                case 'long':
                case 'longlong':
                    $column->type = self::TYPE_INT;
                    break;
                case 'float':
                case 'float8':
                    $column->type = self::TYPE_FLOAT;
                    break;
                case 'money':
                    $column->type = Table::TYPE_MONEY;
                    break;
                case 'string':
                case 'varchar':
                case 'name':
                case 'var_string':
                    $column->type = self::TYPE_STRING;
                    break;
                case 'blob':
                case 'text':
                    $column->type = self::TYPE_TEXT;
                    break;
                case 'timestampt':
                case 'timestamptz':
                case 'datetime':
                case 'timestamp':
                    $column->type = self::TYPE_DATETIME;
                    break;
                case 'interval':
                    $column->type = Table::TYPE_INTERVAL;
                    break;
                default:
                    $column->type = $meta['native_type'];
                    throw new Exception("Неопределенный тип {$column->type} таблица caption: {$this->_title}, колонка: {$column->originName}");
                    break;
            }
            $this->_originColumns[] = $column;
        }
    }

    /**
     * строит страницу таблицы 
     *
     * @return void
     *
     */
    private function _buildPageRows() {
        $this->pageRows = array();
        $pageSql = $this->getPageSql();
//            echo "<pre>$pageSql</pre>";
        try {
            $st = $this->getConnectionOrDefault()->query($pageSql);
        } catch (\PDOException $e) {
            new PreView($pageSql, null, null, true);
            throw $e;
        }
        while ($row = $st->fetch(\PDO::FETCH_NUM)) {
            $eachPageRow = new Row();
            $eachPageRow->DATA = $row;
            if ($this->customGetRowAssociatedObject) {
                $getRowAssociatedObject = $this->customGetRowAssociatedObject;
                $eachPageRow->associatedObject = $getRowAssociatedObject($this, $eachPageRow);
            }
            $this->pageRows[$this->getRowId($eachPageRow)] = $eachPageRow;
        }
    }

    /**
     * строит строку подвала $_footRow
     *
     * @return viod
     *
     */
    private function _buildFootRow() {
        $footSQL = $this->footSQL();
        try {
            $st = $this->getConnectionOrDefault()->query($footSQL);
        } catch (\PDOException $e) {
            HTMLer::pre($footSQL);
            throw $e;
        }
        $footRow = new SQLTableFootRow();
        $footRow->DATA = $st->fetch(PDO::FETCH_NUM);
        $this->_footRow = $footRow;
    }

    public function getColumns($COLUMN = null) {
        if ($this->_originColumns === null) {
            $this->_buildOriginColumns();
        }
        if ($COLUMN !== null && $COLUMN >= sizeof($this->_originColumns)) {
            throw new Exception("Колонка №{$COLUMN} выходит за пределы таблицы {$this->_title}");
        }
        $result = array();
        foreach ($this->_originColumns as $EACH_ORIGIN_COLUMN => $eachOriginColumn) {
            if (isset($this->userColumns[$EACH_ORIGIN_COLUMN])) {
                $result[] = $this->userColumns[$EACH_ORIGIN_COLUMN]->over($eachOriginColumn);
            } else {
                $result[] = $eachOriginColumn;
            }
        }
        return $COLUMN === null ? $result : $result[$COLUMN];
    }

    /**
     * возвращает строки (строку) страницы таблицы
     *
     * @param int $ROW индекс строки страницы
     * @return Row строка страницы
     *
     */
    function getPageRows($ROW = null) {
        if ($this->pageRows === null) {
            $this->_buildPageRows();
        }
        return $ROW === null ? ($this->pageRows) : $this->pageRows[$ROW];
    }

    function addPageRow(Row $row) {
        if ($this->pageRows === null) {
            $this->pageRows = array();
        }
        $this->pageRows[$this->getRowId($row)] = $row;
    }

    /**
     * алфавитный паджинг или нет. если алфавитный- вернет колонку
     *
     * @return mixed This is the return value description
     *
     */
    public function abcPaging() {
        if ($this->ABC) {
            return $this->getColumns($this->ABC);
        }
    }

    /**
     * текущая страница СТРОКОЙ или "all". first и last преобразуются в номер или символ
     *
     * @return string текущая страница
     *
     */
    public function getSafePageNumber() {
        if (strtolower($this->pageNumber) == Table::PAGE_NUMBER_ALL) {
            return Table::PAGE_NUMBER_ALL;
        } else if ($this->abcPaging()) {
            if (strtolower($this->pageNumber) == 'first')
                return $this->abc[0];
            elseif (strtolower($this->pageNumber) == 'last')
                return $this->abc[sizeof($this->abc) - 1];
            elseif (in_array(strtolower($this->pageNumber), $this->abc))
                return strtolower($this->pageNumber);
            else
                return $this->abc[0];
        }
        else {
            if (strtolower($this->pageNumber) == 'first') {
                return '1';
            } elseif (strtolower($this->pageNumber) == 'last') {
                return (string) $this->getPageCount();
            } elseif ($this->pageNumber < 1) {
                return '1';
            } elseif ($this->pageNumber > $this->getPageCount()) {
                return (string) max(array(1, $this->getPageCount()));
            } elseif (preg_match('/^\d+$/', (string) $this->pageNumber)) {
                return (string) $this->pageNumber;
            } else {
                return '1';
            }
        }
    }

    public function getSearch() {
        return $this->_search;
    }

    public function setSearch($search) {
        $this->_search = $search;
    }

    public function getOrder() {
        return $this->_order;
    }

    public function setOrder($order) {
        if ($this->_order != $order) {
            $this->_order = $order;
            $this->pageRows = null;

            $this->changed->call($this);
        }
    }

    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function setPageNumber($pageNumber) {
        if ($this->pageNumber == $pageNumber) {
            return;
        }
        $this->pageRows = null;
        $this->pageNumber = $pageNumber;

        $this->changed->call($this);
    }

    /**
     * возвращает порядок сортировки, исправленный от ошибок выхода за границы таблицы
     *
     * @return string порядок сортировки
     *
     */
    public function getSafeOrder() {
        $result = (int) $this->_order;
        if ($result < 1) {
            return '1';
        } else if ($result > sizeof($this->getColumns())) {
            return '1';
        }
        return $this->_order;
    }

    /**
     * имеет ли таблица подвал
     *
     * @return bool имеет ли таблица подвал
     *
     */
    public function foot() {
        foreach ($this->userColumns as $column)
            if ($column->foot)
                return true;
    }

    private function russianDate($value) {
        if (preg_match('/\d+.\d+/', $value)) {
            $time = strstr($value, ' ');
            $date = substr($value, 0, strlen($value) - strlen($time));
            $arr = explode('.', $date);
            for ($i = 0; $i < sizeof($arr); $i++) {
                if (strlen($arr[$i]) == 1) {
                    $arr[$i] = '0' . $arr[$i];
                }
            }
            $arr = array_reverse($arr);
            $value = implode('-', $arr) . $time;
        }
        return $value;
    }

    /**
     * общее количество строк, когда таблицу развернут
     *
     * @return int количество строк в таблице
     *
     */
    public function getTotalRowCount() {
        if ($this->_TOTAL_ROWS !== null) {
            return $this->_TOTAL_ROWS;
        }
//            $totalRowCountSQL = "select count(*) from ({$this->getSearchSQL()}) as a";
//            new \Page\View\PreView($totalRowCountSQL, null, null, TRUE);
        $searchSQL = $this->getSearchSQL();

        //убираю все вложенные селекты по принципу "когда количество from-ов сравняется с количеством select-ов, то то что между ними вырезать и заменить на count(*)"
        $positions = array();
        $totalRowCountSQL = null;
        preg_match_all('/select|from/i', $searchSQL, $positions, PREG_OFFSET_CAPTURE);
        $counter = 0;
        //        echo "<pre>";
        //        var_dump($searchSQL);
        //        var_dump($positions);
        //        echo "</pre>";

        foreach ($positions[0] as $eachPosition) {
            if (strtolower($eachPosition[0]) == "select") {
                $counter++;
            } else {
                $counter--;
            }
            if (!$counter) {
                //                $x= $positions[0][0][1] + 6;
                //                $y= $eachPosition[1] - ($positions[0][0][1] + 6);
                //                echo "substr_replace({$searchSQL}, ' count(*) ', {$x}, {$y});";
                $totalRowCountSQL = substr_replace($searchSQL, ' count(*) ', $positions[0][0][1] + 6, $eachPosition[1] - ($positions[0][0][1] + 6));
                break;
            }
        }

//            new \Page\View\PreView($totalRowCountSQL, null, null, TRUE);

        try {
            $connection = $this->getConnectionOrDefault();
            $st = $connection->query($totalRowCountSQL);
        } catch (\PDOException $e) {
            //new \Page\View\PreView($totalRowSql, null, null, true);
            throw $e;
        }
        $this->_TOTAL_ROWS = (int) $st->fetchColumn();
        return $this->_TOTAL_ROWS;
    }

    /**
     * возвращает количество страниц по $PAGESIZE и exception для алфавитной паджинации
     *
     * @return int количество страниц
     *
     */
    public function getPageCount() {
        if ($this->abcPaging()) {
            throw new Exception('Количество страниц не имеет смысла для алфавитной страничности');
        } else {
            return (int) ceil($this->getTotalRowCount() / $this->PAGE_SIZE);
        }
    }

    /**
     * выбрана ли строка таблицы
     *
     * @param Row $row строка таблицы
     * @return bool выбрана ли строка таблицы
     *
     */
    public function isRowSelected($row) {
        return in_array($this->getRowId($row), $this->SELECTED_ROWS);
    }

    /**
     * SQL->searchSQL->orderSQL->pageSQL: упорядочивает строки исходного SQL
     *
     * @return string SQL
     *
     */
    private function getOrderSql() {
        return "{$this->getSearchSQL()} order by " . $this->getSafeOrder();
    }

    /**
     * SQL->searchSQL->orderSQL->pageSQL: оставляет из всех строк исходного SQL только поподающие под поиск строки
     *
     * @return string SQL
     *
     */
    protected function getSearchSQL() {
//            return $this->sql;
        // You can only use column aliases in GROUP BY, ORDER BY, or HAVING clauses
        if (!$this->_search) {
            return $this->sql;
        }
        $wheres = array();
        foreach ($this->getColumns() as $column) {
            if ($column->hidden) {
                continue;
            }
            switch ($column->type) {
                /* тип boolean отображается галочкой поэтому поиск для него не осуществим
                  case self::TYPE_BOOL:
                  $where[] = "
                  case COALESCE(\"$column->name\", false)
                  when false then 'нет'
                  else 'да'
                  end like '%{$this->_search}%' ";
                  break;
                 */
                case self::TYPE_TEXT:
                case self::TYPE_STRING:
                    $wheres[] = "\"{$column->originName}\" ilike {$this->getConnectionOrDefault()->quote("%{$this->_search}%")}";
                    break;
                case self::TYPE_DATETIME:
                    $wheres[] = "substring(CAST(\"{$column->originName}\" as character varying)  from 1 for 16) ilike {$this->getConnectionOrDefault()->quote("%{$this->_search}%")}";
                    break;
                case self::TYPE_INT:
                case self::TYPE_FLOAT:
                case self::TYPE_MONEY:
                    $wheres[] = "CAST(\"{$column->originName}\" as character varying) ilike {$this->getConnectionOrDefault()->quote("%{$this->_search}%")}";
                    break;
            }
        }
        $sql = "select * from ({$this->sql}) as a where (" . implode(' or ', $wheres) . " )";
//                echo "<pre>$sql</pre>";
        return $sql;
    }

    /**
     * SQL->searchSQL->orderSQL->pageSQL: оставляет из всех строк исходного SQL только строки текущей страницы
     *
     * @return string SQL страницы
     *
     */
    public function getPageSql() {
        if ($this->getSafePageNumber() == Table::PAGE_NUMBER_ALL || $this->abcPaging() && $this->_search) {
            $pageSQL = $this->getOrderSql();
        } else if ($this->abcPaging()) {
            $pageSQL = "select * from ({$this->getOrderSql()}) as a where '{$this->getSafePageNumber()}' ilike '%'||substr(\"{$this->abcPaging()->name}\",1,1)||'%'";
        } else {
            $pageSQL = "{$this->getOrderSql()} limit {$this->PAGE_SIZE} offset " . ($this->getSafePageNumber() - 1) * $this->PAGE_SIZE;
        }
        return $pageSQL;
    }

    /**
     * SQL->searchSQL->footSQL: SQL подвала вместе с лидирующими и замыкающими '', ''
     *
     * @return string SQL подвала
     *
     */
    public function footSQL() {
        if (!$this->foot())
            throw new Exception("У таблищы нет подвала", null, 'свойство $foot колонок не задано', 'Установи колонки подвала или не вызывай footSQL()');
        $select = $group = array();
        $columns = $this->getColumns();
        foreach ($columns as $INDEX => $column)
            if ($column->foot)
                $select[] = "{$column->foot}(\"{$column->name}\")";
            else
                $select[] = "''";
        $footSQL = "select " . implode(',', $select) . " from ({$this->getSearchSQL()}) as a";
        return $footSQL;
    }

    public function footRow() {
        if ($this->_footRow === null)
            $this->_buildFootRow();
        return $this->_footRow;
    }

    public function reset() {
        $this->_originColumns = $this->pageRows = $this->_footRow = $this->_TOTAL_ROWS = null;
        //$this->userColumns = array();
    }

    /**
     * возвращает себя в Excel
     */
    function toExcel() {
        //получить из orderSQL nonHiddenSQL, в котором нет скрытих колонок
        $columns = array();
        foreach ($this->getColumns() as $eachColumn) {
            if (!$eachColumn->hidden) {
                $columns[] = "\"{$eachColumn->originName}\"";
            }
        }
        $excelSQL = "
            select " . implode(',', $columns) . " 
            from
            (
            {$this->getOrderSql()}
            ) as a";

//            echo "<pre>$excelSQL</pre>";

        $st = $this->getConnectionOrDefault()->query($excelSQL);
        $excelReader = new \PHPExcelEx\Reader\PDO;
        $excel = $excelReader->load($st);
        return $excel;
    }
}