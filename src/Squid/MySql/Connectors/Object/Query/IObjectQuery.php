<?php
namespace Squid\MySql\Connectors\Object\Query;


interface IObjectQuery
{
	/**
	 * @return mixed
	 */
	public function queryAll();
	
	/**
	 * @return mixed
	 */
	public function queryFirst();
	
	/**
	 * @return mixed
	 */
	public function queryOne();
	
	/**
	 * @param callable $callback Called for each selected row. 
	 * If callback returns false, queryWithCallback will abort and return false.
	 * If callback returns 0, queryWithCallback will abort and return true.
	 * For any other value, callback will continue to the next row.
	 * @return bool
	 */
	public function queryWithCallback($callback);
	
	/**
	 * Return an iterator to iterate over all found objects.
	 */
	public function queryIterator(): iterable;
	
	/**
	 * Return an array where the result of one column is the index and loaded object is the value.
	 * @param string $key Name of the key column.
	 * @param bool $removeColumnFromRow Should remove the key column from values before converting them to objects.
	 * @return array|false
	 */
	public function queryMapRow(string $key, $removeColumnFromRow = false);
}