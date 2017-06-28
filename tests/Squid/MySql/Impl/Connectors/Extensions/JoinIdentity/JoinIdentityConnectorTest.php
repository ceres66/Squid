<?php
namespace Squid\MySql\Impl\Connectors\Extensions\JoinIdentity;


use lib\DataSet;
use lib\TDBAssert;

use Objection\LiteObject;
use Objection\LiteSetup;
use PHPUnit\Framework\TestCase;
use Squid\MySql\Connectors\Extensions\JoinIdentity\IJoinIdentityConfig;
use Squid\MySql\Connectors\Object\IIdentityConnector;
use Squid\MySql\Impl\Connectors\Extensions\JoinIdentity\Config\JoinConfigByFields;
use Squid\MySql\Impl\Connectors\Object\SimpleConnector;


class JoinIdentityConnectorTest extends TestCase
{
	use TDBAssert;
	
	
	// Table A has elements with id < 100
	private $tableA;
	
	// Table B has elements with id > 100 and < 10000
	private $tableB;
	
	
	private function insertAObjects($data)
	{
		if ($data && !is_array($data[0]))
			$data = [$data];
		
		return DataSet::table(['ID', 'Name'], $data);
	}
	
	private function insertBObjects($data)
	{
		if ($data && !is_array($data[0]))
			$data = [$data];
		
		return DataSet::table(['ID', 'RefID', 'Data'], $data);
	}
	
	private function subject(array $dataA = [], array $dataB = [])
	{
		$this->tableA = $this->insertAObjects($dataA);
		$this->tableB = $this->insertBObjects($dataB);
		$config = new JoinConfigByFields('ID', 'RefID', 'Prop');
		
		DataSet::connector()->direct("ALTER TABLE {$this->tableA} ADD PRIMARY KEY (ID), CHANGE `ID` `ID` INT(11) NOT NULL AUTO_INCREMENT")->executeDml();
		DataSet::connector()->direct("ALTER TABLE {$this->tableB} ADD PRIMARY KEY (ID), CHANGE `ID` `ID` INT(11) NOT NULL AUTO_INCREMENT")->executeDml();
		
		$join = new JoinIdentityConnector();
		$join
			->setConfig($config)
			->setObjectConnector(
				(new SimpleConnector())
					->setConnector(DataSet::connector())
					->setTable($this->tableA)
					->setAutoincrementID('ID')
					->setObjectMap(JoinHelper_A::class, ['Prop'])
			)
			->setDataConnector(
				(new SimpleConnector())
					->setConnector(DataSet::connector())
					->setTable($this->tableB)
					->setAutoincrementID('ID')
					->setObjectMap(JoinHelper_B::class)
			);
		
		return $join;
	}
	
	
	public function test_ReturnSelf()
	{
		$join = new JoinIdentityConnector();
		
		/** @var IIdentityConnector $connector */
		$connector = $this->getMockBuilder(IIdentityConnector::class)->getMock();
		
		/** @var IJoinIdentityConfig $config */
		$config = $this->getMockBuilder(IJoinIdentityConfig::class)->getMock();
		
		self::assertSame($join, $join->setConfig($config));
		self::assertSame($join, $join->setObjectConnector($connector));
		self::assertSame($join, $join->setDataConnector($connector));
	}
	
	public function test_load_ObjectNotFound_ReturnNull()
	{
		self::assertNull($this->subject()->load(0));
	}
	
	public function test_load_ArrayOfObjectsNotFound_EmptyArrayReturned()
	{
		self::assertSame([], $this->subject()->load([0, 1]));
	}
	
	public function test_load_ObjectFound_ObjectLoaded()
	{
		$subject = $this->subject([['ID' => 1, 'Name' => 'hello']]);
		
		$res = $subject->load(1);
		
		self::assertInstanceOf(JoinHelper_A::class, $res);
		self::assertEquals(1, $res->ID);
		self::assertEquals('hello', $res->ID);
	}
}


class JoinHelper_A extends LiteObject
{
	public function __construct($data = [])
	{
		parent::__construct();
		if ($data) $this->fromArray($data);
	}

	protected function _setup()
	{
		return [
			'ID'	=> LiteSetup::createInt(null),
			'Name'	=> LiteSetup::createString(0),
			'Prop'	=> LiteSetup::createInstanceOf(JoinHelper_B::class)
		];
	}
}

class JoinHelper_B extends LiteObject
{
	public function __construct($data = [])
	{
		parent::__construct();
		if ($data) $this->fromArray($data);
	}
	
	protected function _setup()
	{
		return [
			'ID'	=> LiteSetup::createInt(null),
			'RefID'	=> LiteSetup::createInt(null),
			'Data'	=> LiteSetup::createString(0)
		];
	}
}