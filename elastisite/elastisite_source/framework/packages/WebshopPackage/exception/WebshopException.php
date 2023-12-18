<?php
namespace framework\packages\WebshopPackage\exception;

use framework\kernel\utility\BasicUtils;
use framework\kernel\exception\entity\ExceptionTrace;
use framework\kernel\exception\entity\ExceptionTraceArg;
use framework\kernel\base\Reflector;

class WebshopException extends \Exception
{
    private $traces;

    public function __construct($message) {
        \App::get()->includeOnce('framework/kernel/base/Reflector.php');
        \App::get()->includeOnce('framework/kernel/utility/BasicUtils.php');
        \App::get()->includeOnce('framework/kernel/exception/entity/ExceptionTrace.php');
        \App::get()->includeOnce('framework/kernel/exception/entity/ExceptionTraceArg.php');

        $message = '<span class="message-toggled">'.$message.'</span>';

        for ($i = 0; $i < count($this->getTrace()); $i++) {
            $this->traces[] = $this->objectifyTrace($this->getTrace()[$i]);
        }
    }

    public function getNotice()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getTraces()
    {
        return $this->traces;
    }

    public function __toString()
    {
        $string = $this->getNotice().($this->getCode() ? " (".$this->getCode().")" : "")."\n";
        foreach ($this->traces as $trace) {
            $string .= "File: ".$trace->getFile()."\n";
            $string .= "Line: ".$trace->getLine()."\n";
            $string .= "Function: ".$trace->getFunction()."\n";
            $string .= "Args: \n";
            foreach ($trace->getArgs() as $arg) {
                $string .= $arg->getKey().": ".$arg->getValue();
            }
        }
        return $string;
    }

    public function objectifyTrace($trace, $defaultMessage = null) {
        $returnTrace = new ExceptionTrace();
        if (isset($trace['file'])) {
            $returnTrace->setFile($this->stringify($trace['file']));
        }
        if (isset($trace['line'])) {
            $returnTrace->setLine($this->stringify($trace['line']));
        }
        if (isset($trace['function'])) {
            $returnTrace->setFunction($this->stringify($trace['function']));
        }
        if (isset($trace['args'])) {
            foreach ($trace['args'] as $argKey => $argValue) {
                $returnTrace->addArg(new ExceptionTraceArg($this->stringify($argKey), $this->stringify($argValue)));
            }
        }
        return $returnTrace;
    }

    public function stringify($data)
    {
        if (is_array($data)) {
            $data = BasicUtils::arrayToString($data);
        }
        elseif (is_object($data)) {
            $reflector = new Reflector();
            $data = $reflector->objectToString($data);
        }
        return $data;
    }
}
