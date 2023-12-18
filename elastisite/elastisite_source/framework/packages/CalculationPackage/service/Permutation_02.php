<?php

namespace framework\packages\ToolPackage\service;

class Permutation
{
    private $B = array();

    public function __construct($A)
    {
        $n = count($A);
        $this->heap($n,$A);
    }

    /*
    generate permutations of items in the list
    */
    // public function generate($A)
    // {
    //     $instance = new SWWWPermutations($A);
    //     return $instance -> get();
    // }

    /*
    swap function used by Heap routine
    */
    private function swap(&$x, &$y)
    {
        list($x,$y) = array($y,$x);
    }

    /*
    (slightly extended) Heap algorithm for find permutations of items in list A
    */
    private function heap($n,&$A)
    {
        if ($n <= 0) {
            $this -> B[] = array();
            return $A;
        }

        if ($n === 1) {
            $this -> B[] = $A;
            return $A;
        }

        for ($i = 0; $i < $n - 1; $i++) {
            $this->heap($n - 1, $A);
            ($n % 2) === 0 ? $this->swap($A[$i],$A[$n-1]) : $this->swap($A[0],$A[$n-1]);
        }
        $this->heap($n - 1,$A);
    }

    public function get()
    {
        return $this->B;
    }
}
