<?php
namespace framework\component\helper;

class StringHelper
{
    public static function formatNumber($number, $decimals = 2, $decimalSeparator = '.', $thousandsSeparator = '')
    {
        return number_format((float)$number, $decimals, $decimalSeparator, $thousandsSeparator);
    }

    public static function isDecimal( $val )
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }

    public static function hexToRgb($hex, string $transparency = "1") {
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");

        return "rgba({$r},{$g},{$b},{$transparency})";
    }

    public static function getAllOccurrencies($haystack, $needle)
    {
        $lastPos = 0;
        $positions = array();
        
        while (($lastPos = mb_strpos(($haystack ? $haystack : ''), $needle, $lastPos))!== false) {
            $positions[] = $lastPos;
            $lastPos = $lastPos + mb_strlen($needle);
        }
        return $positions;
    }

    public static function getStringBetween(string $fullstring, int $startPosition, int $endPosition)
    {
        $startPosition++;
        $length = abs($startPosition - $endPosition);

        return mb_substr($fullstring, $startPosition, $length);
    }

    public static function mendValue($string)
    {
        if ($string == 'true') {
            return true;
        } elseif ($string == 'false') {
            return false;
        } elseif ($string == 'null') {
            return null;
        }

        return $string;
    }

    public static function booleanToInt($string)
    {
        if ($string == 'true' || $string === true) {
            return 1;
        } elseif ($string == 'false' || $string === false) {
            return 0;
        } else {
            return null;
        }

        return $string;
    }

    public static function intToBooleanString($int)
    {
        if ($int === 0) {
            return 'false';
        } elseif ($int === 1) {
            return 'true';
        } else {
            return null;
        }

        return $int;
    }

    public static function formatProgramCode($code)
    {
        return '<div style="background-color: #444444; padding: 10px; border-radius: 6px; box-shadow: 0 4px 6px #6a6a6a; font: 15px DefaultFont;">'.highlight_string($code, true).'</div>';
    }

    public static function cutLongString($string, $maxCharNum, $end = '...')
    {
        if (!$string) {
            return $string;
        }
        
        return (mb_strlen($string) > $maxCharNum) ? mb_substr($string, 0, $maxCharNum) . $end : $string;
    }

    public static function snakeToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
    }

    public static function camelToSnakeCase($string)
    {
        if (!$string) {
            return $string;
        }
        if (preg_match('/[A-Z]/', $string) === 0) {
            return $string;
        }
        $pattern = '/([a-z])([A-Z])/';
        $r = strtolower(preg_replace_callback($pattern, function ($a) {
            return $a[1] . "_" . strtolower ( $a[2] );
        }, $string));
        return $r;
    }

    public static function alterToTranslationFormat($string)
    {
        $string = self::camelToSnakeCase($string);
        $string = mb_strtolower($string);
        $string = str_replace('_', '.', $string);

        return $string;
    }    
}
