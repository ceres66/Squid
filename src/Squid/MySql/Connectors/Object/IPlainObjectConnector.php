<?php
namespace Squid\MySql\Connectors\Object;


use Squid\MySql\Connectors\Object\CRUD\Generic;


interface IPlainObjectConnector extends 
	Generic\IObjectInsert,
	Generic\IObjectSelect,
	Generic\IObjectUpdate,
	Generic\IObjectUpsert
{
	
}