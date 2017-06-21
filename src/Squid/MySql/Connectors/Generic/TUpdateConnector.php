<?php

namespace Squid\MySql\Connectors\Generic;


/**
 * @mixin IUpdateConnector
 */
trait TUpdateConnector 
{
	/**
	 * @param string[] $fields
	 * @param array $row
	 * @return int|null
	 */
	public function updateByFields(array $fields, array $row): ?int
	{
		$where = [];
		
		foreach ($fields as $field)
		{
			$where[$field] = $row[$field];
			unset($row[$field]);
		}
		
		return $this->update($fields, $row);
	}
}