<?php
namespace Squid\MySql\Impl\Traits\CmdTraits;


use Squid\MySql\Command\ICmdSelect;
use Squid\Exceptions\SquidException;


/**
 * Implements calculation behavior for the IWithWhere interface. Only method that is not implemented, 
 * is where. Where must be implemented by the using class.
 * @method mixed where(string $exp, $bind = false)
 * @see \Squid\MySql\Command\IWithSet
 */
trait TWithWhere {
	
	/**
	 * @inheritdoc
	 */
	public function byId($value) 
	{
		return $this->byField('Id', $value);
	}
	
	/**
	 * @inheritdoc
	 */
	public function byField($field, $value) 
	{
		if (is_null($value)) return $this->where("ISNULL($field)");
		
		return $this->where("$field=?", $value);
	}
	
	/**
	 * @inheritdoc
	 */
	public function byFields($fields, $values = null) 
	{
		if (isset($fields[0])) return $this->byFieldsNum($fields, $values);
		
		return $this->byFieldsAssoc($fields);
	}
	
	/**
	 * @inheritdoc
	 * @throws SquidException
	 */
	public function whereIn($field, $values, $negate = false) 
	{
		if (!$values)
			throw new SquidException('Empty values set passed to whereIn!');
		
		if ($values instanceof ICmdSelect) 
		{
			$in = $values->assemble();
			$values = $values->bind();
		}
		else 
		{
			$in = implode(',', array_pad(array(), count($values), '?'));
		}
		
		$statement = ($negate ? 'NOT IN' : 'IN');
		
		return $this->where("$field $statement ($in)", $values);
	}
	
	/**
	 * @inheritdoc
	 */
	public function whereNotIn($field, $values) 
	{
		return $this->whereIn($field, $values, true);
	}
	
	/**
	 * @inheritdoc
	 */
	public function whereExists(ICmdSelect $select, $negate = false) 
	{
		$in = $select->assemble();
		$statement = ($negate ? 'NOT EXISTS' : 'EXISTS');
		
		return $this->where("$statement ($in)", $select->bind());
	}
	
	/**
	 * @inheritdoc
	 */
	public function whereNotExists(ICmdSelect $select) 
	{
		return $this->whereExists($select, true);
	}
	
	
	/**
	 * @inheritdoc
	 */
	private function byFieldsNum($fields, $values) 
	{
		$fieldsCount = count($fields);
		
		for ($i = 0; $i < $fieldsCount; $i++) 
		{
			$self = $this->byField($fields[$i], $values[$i]);
		}
		
		/** @noinspection PhpUndefinedVariableInspection */
		return $self;
	}
	
	/**
	 * @inheritdoc
	 */
	private function byFieldsAssoc($fields) 
	{
		foreach ($fields as $field => $value) 
		{
			$self = $this->byField($field, $value);
		}
		
		/** @noinspection PhpUndefinedVariableInspection */
		return $self;
	}
}