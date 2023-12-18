<?php
namespace framework\kernel\exception\entity;

class ExceptionTraceArg
{
    private $key;
    private $value;

    public function __construct($key = null, $value = null)
    {
        if ($key) {
            $this->key = $key;
        }
        if ($value) {
            $this->value = $value;
        }
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}
