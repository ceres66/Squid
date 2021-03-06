<?php
namespace Squid\MySql\Connectors\Object\Join;


interface IJoinConnector
{
	/**
	 * @param mixed|array $parents
	 * @return mixed|false
	 */
	public function loaded($parents);

	/**
	 * @param mixed|array $parents
	 * @param bool $ignore
	 * @return false|int
	 */
	public function inserted($parents, $ignore = false);

	/**
	 * @param mixed $parent
	 * @return int|false
	 */
	public function updated($parent);

	/**
	 * @param mixed|array $parents
	 * @return int|false
	 */
	public function upserted($parents);

	/**
	 * @param mixed|array $parents
	 * @return int|false
	 */
	public function saved($parents);
}