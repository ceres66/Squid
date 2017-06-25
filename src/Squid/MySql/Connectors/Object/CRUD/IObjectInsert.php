<?php
namespace Squid\MySql\Connectors\Object\CRUD;


interface IObjectInsert
{
	/**
	 * @param mixed|array $object
	 * @param bool $ignore
	 * @return false|int
	 */
	public function insert($object, bool $ignore = false);
}