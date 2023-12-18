<?php
namespace framework\component\parent;

use framework\kernel\component\Kernel;

abstract class Entity extends Kernel
{
    abstract public function getEntityAttributes();

    abstract public function getIdFieldName();

    abstract public function getPropertyMap();

    abstract public function getClassName();

    abstract public function getRepository();

    abstract public function isActive() : bool;

    abstract public function getId();
}
