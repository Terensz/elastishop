<?php
namespace framework\kernel\exception\entity;

class ExceptionTrace
{
    private $file;
    private $line;
    private $function;
    private $class;
    private $type;
    private $args = array();

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function setFunction($function)
    {
        $this->function = $function;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }


    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function addArg($arg)
    {
        $this->args[] = $arg;
    }

    public function setArgs($args)
    {
        $this->args = $args;
    }
}
