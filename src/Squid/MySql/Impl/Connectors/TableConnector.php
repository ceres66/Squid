<?php
namespace Squid\MySql\Impl\Connectors;


use Squid\MySql\Command;
use Squid\MySql\IMySqlConnector;


class TableConnector
{
	private $table;
	private $columns = [];
	
	/** @var IMySqlConnector */
	private $connector;
	
	
	public function __construct(IMySqlConnector $connector)
	{
		$this->connector = $connector;
	}

	
	/**
	 * @param string $name
	 * @return static
	 */
	public function setTableName($name)
	{
		$this->table = $name;
		return $this;
	}

	/**
	 * @param string|array ...$columns
	 */
	public function setColumns(...$columns)
	{
		if (count($columns) == 1 && is_array($columns[0]))
		{
			$this->columns = $columns[0];
		}
		else
		{
			$this->columns = $columns;
		}
	}
	
	
	/**
	 * @return Command\ICmdDelete
	 */
	public function delete()
	{
		return $this->connector->delete()->from($this->table);
	}

	/**
	 * @param array|bool $columns Amend to use the fields passed to setColumns(...) method.
	 * @return Command\ICmdInsert
	 */
	public function insert($columns = false)
	{
		$columns = $columns ?: $this->columns;
		return $this->connector->insert()->into($this->table, $columns);
	}

	/**
	 * @param string|bool $alias
	 * @return Command\ICmdSelect
	 */
	public function select($alias = false)
	{
		return $this->connector->select()
			->from($this->table, $alias);
	}
	
	/**
	 * @return Command\ICmdUpdate
	 */
	public function update()
	{
		return $this->connector->update()->table($this->table);
	}
	
	/**
	 * @param array|bool $columns Amend to use the fields passed to setColumns(...) method.
	 * @return Command\ICmdUpsert
	 */
	public function upsert($columns = false)
	{
		$columns = $columns ?: $this->columns;
		return $this->connector->upsert()->into($this->table, $columns);
	}
	
	
	/**
	 * @return string
	 */
	public function name()
	{
		return $this->table;
	}
}