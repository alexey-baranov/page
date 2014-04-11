<?php
ini_set('display_errors','On');

require_once '../vendor/autoload.php';
//
//        const DSN = "mysql:host=217.114.191.210;dbname=hd";
//        const USERNAME = "root";
//        const PASSWORD = "mtuB98qp";
//        const SQL = "select * from (select id, _name from node limit 10) as a";
//        
        const DSN = "pgsql:host=10.0.14.14;dbname=request2";
        const USERNAME = "pgsql";
        const PASSWORD = "baranov";
        const SQL = "select * from (select id, id+1 from objectt0 limit 10) as a";

$connection = new \PDO(DSN, USERNAME, PASSWORD);
$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

Page\SQLTable\Table::setDefaultConnection($connection);

$table = new Page\SQLTable\Table(SQL);
$table->setTitle("bla bla- bla");

$tableView = new Page\SQLTable\View\TableView($table, null, "t");
//$tableView->setIsExportToExcelAvailable(false);
//$tableView->setIsMetadataHidden(TRUE);
$tableView->setIsTitleHidden(FALSE);

$tableView->init();
$tableView->action();
echo "<form>";
$tableView->show();
echo "</form>";