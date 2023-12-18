<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Utility;

class Helper
{
    /**
     * ucfirst UTF-8 aware function
     *
     * @see http://ca.php.net/ucfirst
     */
    public static function spUcfirst(string $string, string $e = 'utf-8'): string
    {
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && strlen($string) > 0) {
            $string = mb_strtolower($string, $e);
            $upper = mb_strtoupper($string, $e);
            preg_match('#(.)#us', $upper, $matches);
            $string = $matches[1].mb_substr($string, 1, mb_strlen($string, $e), $e);
        } else {
            $string = ucfirst($string);
        }

        return $string;
    }
}
