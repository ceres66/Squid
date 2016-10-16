<?php
namespace Squid\MySql\Impl\Connection;


use Squid\MySql\Config\MySqlConnectionConfig;
use Squid\MySql\Connection\IMySqlExecutor;
use Squid\MySql\Connection\IMySqlConnection;


class MySqlConnectionDecorator implements IMySqlConnection
{
	/** @var IMySqlConnection */
	private $connection;
	
	/** @var IMySqlExecutor */
	private $executor;


	/**
	 * @param IMySqlConnection $connection
	 * @param IMySqlExecutor $executor
	 */
	public function __construct(IMySqlConnection $connection, IMySqlExecutor $executor)
	{
		$this->connection = $connection;
		$this->executor = $executor;
	}
	
	
	/**
	 * @param MySqlConnectionConfig|string $db
	 * @param string $user
	 * @param string $pass
	 * @param string $host
	 */
	public function setConfig($db, $user = null, $pass = null, $host = null)
	{
		$this->connection->setConfig($db, $user, $pass, $host);
	}
	
	/**
	 * Close this connection. Do nothing if the connection is already closed.
	 */
	public function close()
	{
		$this->connection->close();
	}
	
	/**
	 * @return bool
	 */
	public function isOpen()
	{
		$this->connection->isOpen();
	}
	
	/**
	 * @param string $cmd
	 * @param array $bind
	 * @return \PDOStatement
	 */
	public function execute($cmd, array $bind = [])
	{
		return $this->executor->execute($cmd, $bind);
	}
}