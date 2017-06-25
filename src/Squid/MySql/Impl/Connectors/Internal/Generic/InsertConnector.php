<?php
namespace Squid\MySql\Impl\Connectors\Internal\Generic;


use Squid\MySql\Connectors\Generic\TInsertConnector;
use Squid\MySql\Connectors\Generic\IInsertConnector;
use Squid\MySql\Impl\Connectors\Internal\Table\AbstractSingleTableConnector;


class InsertConnector extends AbstractSingleTableConnector implements IInsertConnector 
{
	use TInsertConnector;
	
	
	private function doInsert(array $data, bool $ignore, ?array $fields = null)
	{
		return $this->getConnector()
			->insert()
			->into($this->getTableName(), $fields)
			->ignore($ignore)
			->valuesBulk($data)
			->executeDml(true);
	}
	
	
	/**
	 * @param array $rows
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function all(array $rows, bool $ignore = false)
	{
		return $this->doInsert($rows, $ignore, null);
	}
	
	/**
	 * @param array $fields
	 * @param array $rows
	 * @param bool $ignore
	 * @return int|false Number of affected rows
	 */
	public function allIntoFields(array $fields, array $rows, bool $ignore = false)
	{
		return $this->doInsert($rows, $ignore, $fields);
	}
}