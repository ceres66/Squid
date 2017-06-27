<?php

namespace lib;


use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait TDBAssert
{
	public static function assertRowExists($table, $fields, $value = null)
	{
		if (is_string($fields))
			$fields = [$fields => $value];
		
		$result = DataSet::connector()->select()->from($table)->byFields($fields)->queryExists();
		
		self::assertTrue($result);
	}
	
	public static function assertRowCount($expected, $table, $fields = null, $value = null)
	{
		$select = DataSet::connector()->select()->from($table);
		
		if ($fields)
		{
			if (is_string($fields))
				$fields = [$fields => $value];
			
			$select->byFields($fields);
		}
		
		self::assertSame($expected, $select->queryCount());
	}
}