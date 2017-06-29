<?php
namespace Squid\MySql\Impl\Connectors\Generic;


use lib\DataSet;
use lib\TDBAssert;

use PHPUnit\Framework\TestCase;


class InsertConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	private $table;
	
	
	private function subject(array $data = [])
	{
		$table = DataSet::table(['a', 'b'], $data);
		
		$connector = new InsertConnector();
		$connector
			->setConnector(DataSet::connector())
			->setTable($table);
		
		DataSet::connector()
			->direct("ALTER TABLE $table ADD PRIMARY KEY (a), CHANGE `a` `a` INT(11) NOT NULL AUTO_INCREMENT")
			->executeDml();
		
		$this->table = $table;
		
		return $connector;
	}
	
	
	public function test_insertRow_RowInserted()
	{
		$subject = $this->subject();
		$subject->insertRow(['a' => 1, 'b' => 2]);
		
		self::assertRowCount(1, $this->table, ['a' => 1, 'b' => 2]);
	}
	
	public function test_insertRow_CountReturned()
	{
		$subject = $this->subject();
		self::assertEquals(1, $subject->insertRow(['a' => 1, 'b' => 2]));
	}
	
	/**
	 * @expectedException \Squid\MySql\Exceptions\MySqlException
	 */
	public function test_insertRow_RawAlreadyExists()
	{
		$subject = $this->subject(['a' => 1, 'b' => 2]);
		$subject->insertRow(['a' => 1, 'b' => 2]);
	}
	
	public function test_insertRow_RawAlreadyExistsWithIgnoreFlag_NoError()
	{
		$subject = $this->subject(['a' => 1, 'b' => 2]);
		self::assertEquals(0, $subject->insertRow(['a' => 1, 'b' => 2], true));
	}
	
	
	public function test_insertAll_RowsInserted()
	{
		$subject = $this->subject();
		$subject->insertAll([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4]]);
		
		self::assertRowCount(1, $this->table, ['a' => 1, 'b' => 2]);
		self::assertRowCount(1, $this->table, ['a' => 3, 'b' => 4]);
	}
	
	public function test_insertAll_CountReturned()
	{
		$subject = $this->subject();
		self::assertEquals(2, $subject->insertAll([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4]]));
	}
	
	/**
	 * @expectedException \Squid\MySql\Exceptions\MySqlException
	 */
	public function test_insertAll_RawAlreadyExists()
	{
		$subject = $this->subject(['a' => 3, 'b' => 4]);
		$subject->insertAll([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4]]);
	}
	
	public function test_insertAll_RawAlreadyExistsWithIgnoreFlag_NoError()
	{
		$subject = $this->subject(['a' => 1, 'b' => 2]);
		self::assertEquals(1, $subject->insertAll([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4]], true));
	}
}