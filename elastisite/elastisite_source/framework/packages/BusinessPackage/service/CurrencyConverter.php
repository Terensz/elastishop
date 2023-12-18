<?php
namespace framework\packages\BusinessPackage\service;

use App;
use framework\component\parent\Service;

/*
1.: Require token from /tokenExchange
*/
class CurrencyConverter extends Service
{
    public static function getRate($from, $to)
    {
        if ($from == $to) {
            return 1;
        }
    }
}
