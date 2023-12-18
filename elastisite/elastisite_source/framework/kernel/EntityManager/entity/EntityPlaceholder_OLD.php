<?php
namespace framework\kernel\EntityManager\entity;

use framework\kernel\component\Kernel;

class EntityPlaceholder
{
	protected $collectionId;

	public function __construct()
	{

	}

	public function setCollectionId($collectionId)
	{
		$this->collectionId = $collectionId;
	}

	public function getCollectionId()
	{
		return $this->collectionId;
	}
}
