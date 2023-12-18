<?php
namespace framework\kernel\DbSchemaManager;

use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class FieldSchema extends Service
{
    private $name;
    private $nullable;
    private $default;
    private $autoIncrement;

    public function set($paramName, $paramValue)
    {
        $this->$paramName = $paramValue;
    }
}
