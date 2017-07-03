<?php
namespace Squid\MySql\Impl\Connectors\Object\Join\OneToOne;


use Squid\MySql\Connectors\Object\Join\OneToOne\IOneToOneIdentityConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericObjectConnector;
use Squid\MySql\Connectors\Object\Generic\IGenericIdentityConnector;


abstract class AbstractOneToOneIdentityConnector extends AbstractOneToOneConnector implements IOneToOneIdentityConnector
{
	protected function getPrimary(): IGenericObjectConnector
	{
		return $this->getPrimaryIdentityConnector();
	}
	
	
	protected abstract function getPrimaryIdentityConnector(): IGenericIdentityConnector;
	
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function delete($object)
	{
		return $this->getPrimaryIdentityConnector()->delete($object);
	}

	/**
	 * @param mixed $object
	 * @return int|false
	 */
	public function update($object)
	{
		$count = $this->getPrimaryIdentityConnector()->update($object);
		
		if ($count === false)
			return false;
		
		$children = $this->config()->getChildren($object);
		
		if ($children)
		{
			$childCount = $this->childConnector()->update($children);
			
			if ($childCount === false)
				return false;
			
			$count += $childCount;
		}
		
		return $count;
	}
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function upsert($object)
	{
		$count = $this->getPrimaryIdentityConnector()->upsert($object);
		
		if ($count === false)
			return false;
		
		$children = $this->config()->getChildren($object);
		
		if ($children)
		{
			$childCount = $this->childConnector()->upsert($children);
			
			if ($childCount === false)
				return false;
			
			$count += $childCount;
		}
		
		return $count;
	}
	
	/**
	 * @param mixed|array $object
	 * @return int|false
	 */
	public function insert($object)
	{
		$count = $this->getPrimaryIdentityConnector()->insert($object);
		
		if ($count === false)
			return false;
		
		$children = $this->config()->getChildren($object);
		
		if ($children)
		{
			$childCount = $this->childConnector()->insert($children);
			
			if ($childCount === false)
				return false;
			
			$count += $childCount;
		}
		
		return $count;
	}
}