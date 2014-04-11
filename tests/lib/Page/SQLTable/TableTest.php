<?php
namespace Page\SQLTable;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-11-19 at 10:17:09.
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
//    const PDO_DSN= "pgsql:host=10.0.14.14 port=5432 dbname=request2";
//    const PDO_USER= "pgsql";
//    const PDO_PASSWORD= "baranov";
//    const SQL= "select id, typee from objectt0 limit 10";
    const PDO_DSN= "pgsql:host=217.114.191.86 port=5432 dbname=request3";
    const PDO_USER= "postgres";
    const PDO_PASSWORD= "qwerty123!;";
    const SQL= "select * from (select id, _name from node limit 10) as a";

    /**
     *
     * @var \PDO
     */
    protected $_pdo;
    
    /**
     * @var Table
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_pdo= new \PDO(self::PDO_DSN,self::PDO_USER,self::PDO_PASSWORD);
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        
        $this->object = new Table(self::SQL);
        $this->object->setConnection($this->_pdo);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Page\SQLTable\Table::setDefaultConnection
     * @todo   Implement testSetDefaultConnection().
     */
    public function testSetDefaultConnection()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getDefaultConnection
     * @todo   Implement testGetDefaultConnection().
     */
    public function testGetDefaultConnection()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getConnection
     * @todo   Implement testGetConnection().
     */
    public function testGetConnection()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::setConnection
     * @todo   Implement testSetConnection().
     */
    public function testSetConnection()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getConnectionOrDefault
     * @todo   Implement testGetConnectionOrDefault().
     */
    public function testGetConnectionOrDefault()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::setSql
     * @todo   Implement testSetSql().
     */
    public function testSetSql()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getSql
     * @todo   Implement testGetSql().
     */
    public function testGetSql()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getTitle
     * @todo   Implement testGetTitle().
     */
    public function testGetTitle()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::setTitle
     * @todo   Implement testSetTitle().
     */
    public function testSetTitle()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getRowId
     * @todo   Implement testGetRowId().
     */
    public function testGetRowId()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getColumns
     * @todo   Implement testGetColumns().
     */
    public function testGetColumns()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getPageRows
     * @todo   Implement testGetPageRows().
     */
    public function testGetPageRows()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::addPageRow
     * @todo   Implement testAddPageRow().
     */
    public function testAddPageRow()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::abcPaging
     * @todo   Implement testAbcPaging().
     */
    public function testAbcPaging()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getSafePageNumber
     * @todo   Implement testGetSafePageNumber().
     */
    public function testGetSafePageNumber()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getSearch
     * @todo   Implement testGetSearch().
     */
    public function testGetSearch()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::setSearch
     * @todo   Implement testSetSearch().
     */
    public function testSetSearch()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getOrder
     * @todo   Implement testGetOrder().
     */
    public function testGetOrder()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::setOrder
     * @todo   Implement testSetOrder().
     */
    public function testSetOrder()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getPageNumber
     * @todo   Implement testGetPageNumber().
     */
    public function testGetPageNumber()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::setPageNumber
     * @todo   Implement testSetPageNumber().
     */
    public function testSetPageNumber()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getSafeOrder
     * @todo   Implement testGetSafeOrder().
     */
    public function testGetSafeOrder()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::foot
     * @todo   Implement testFoot().
     */
    public function testFoot()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getTotalRowCount
     * @todo   Implement testGetTotalRowCount().
     */
    public function testGetTotalRowCount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getPageCount
     * @todo   Implement testGetPageCount().
     */
    public function testGetPageCount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::isRowSelected
     * @todo   Implement testIsRowSelected().
     */
    public function testIsRowSelected()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::getPageSql
     * @todo   Implement testGetPageSql().
     */
    public function testGetPageSql()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::footSQL
     * @todo   Implement testFootSQL().
     */
    public function testFootSQL()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::footRow
     * @todo   Implement testFootRow().
     */
    public function testFootRow()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::reset
     * @todo   Implement testReset().
     */
    public function testReset()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Page\SQLTable\Table::toExcel
     * @todo   Implement testToExcel().
     */
    public function testToExcel()
    {
        $this->assertInstanceOf('PHPExcel', $this->object->toExcel());
    }
}
