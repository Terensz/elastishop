<?php
namespace framework\kernel\EntityManager\entity;

use framework\kernel\component\Kernel;

class EntityPlaceholderCollector
{
	protected $collection;
	protected $multiple;

	public function __construct()
	{

	}

	public function addToCollection($element)
	{
		$this->collection[] = $element;
	}

	public function setCollection($collection)
	{
		$this->collection = $collection;
	}

	public function getCollection()
	{
		return $this->collection;
	}


	public function setMultiple($multiple)
    {
		$this->multiple = $multiple;
	}
	
	public function getMultiple()
    {
		return $this->multiple;
    }
}
