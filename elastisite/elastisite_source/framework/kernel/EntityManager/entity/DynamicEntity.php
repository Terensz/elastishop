<?php
namespace framework\kernel\EntityManager\entity;

use framework\kernel\component\Kernel;

class DynamicEntity
{
	protected $id;

	public function __construct()
	{

	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function __call($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling object method '$name' "
             . implode(', ', $arguments). "\n";
    }

    /**  As of PHP 5.3.0  */
    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling static method '$name' "
             . implode(', ', $arguments). "\n";
    }
}
