<?php
namespace framework\component\interfaces;

interface EntityManagerInterface
{
    public function findBy($repo, $filter = null, $queryType = 'result');
}
