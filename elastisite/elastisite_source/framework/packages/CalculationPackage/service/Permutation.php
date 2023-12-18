<?php

namespace framework\packages\CalculationPackage\service;

use framework\kernel\utility\BasicUtils;

class Permutation
{
    private $result = array();

    public function __construct($str)
    {
        $this->result = $this->permute($str);
    }

    public function getResult()
    {
        return $this->result; 
    }

    private function permute($arg)
    {
        $array = is_string($arg) ? BasicUtils::mbStrSplit($arg) : $arg;
        if (1 === count($array)) {
            return $array;
        }
        $result = array();
        foreach ($array as $key => $item) {
            foreach ($this->permute(array_diff_key($array, array($key => $item))) as $p) {
                $result[] = $item . $p;
            }
        }
        return $result;
    }
}
