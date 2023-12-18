<?php

namespace framework\kernel\utility;

use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;

class BasicUtils
{
    const RANDOM_STRING_TYPE_ALPHANUM_MIXED = 'alphanum_mixed';
    const RANDOM_STRING_TYPE_ALPHANUM_SMALL = 'alphanum_small';
    const RANDOM_STRING_TYPE_NUM_2 = 'num_2';

    const NUMBERS = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
    ];

    public static function beautifyJson($json, $ret= "\n", $ind="\t") {

        $beauty_json = '';
        $quote_state = FALSE;
        $level = 0; 
    
        $json_length = strlen($json);
    
        for ($i = 0; $i < $json_length; $i++) {                               
            $pre = '';
            $suf = '';
    
            switch ($json[$i]) {
                case '"':                               
                    $quote_state = !$quote_state;                                                           
                    break;
    
                case '[':                                                           
                    $level++;               
                    break;
    
                case ']':
                    $level--;                   
                    $pre = $ret;
                    $pre .= str_repeat($ind, $level);       
                    break;
    
                case '{':
    
                    if ($i - 1 >= 0 && $json[$i - 1] != ',')
                    {
                        $pre = $ret;
                        $pre .= str_repeat($ind, $level);                       
                    }   
    
                    $level++;   
                    $suf = $ret;                                                                                                                        
                    $suf .= str_repeat($ind, $level);                                                                                                   
                    break;
    
                case ':':
                    $suf = ' ';
                    break;
    
                case ',':
    
                    if (!$quote_state)
                    {  
                        $suf = $ret;                                                                                                
                        $suf .= str_repeat($ind, $level);
                    }
                    break;
    
                case '}':
                    $level--;   
    
                case ']':
                    $pre = $ret;
                    $pre .= str_repeat($ind, $level);
                    break;
            }
    
            $beauty_json .= $pre.$json[$i].$suf;
        }
    
        return $beauty_json;
    }

    public static function mbStrSplit($string, $split_length = 1, $encoding = null)
    {
        if (null !== $string && !\is_scalar($string) && !(\is_object($string) && \method_exists($string, '__toString'))) {
            trigger_error('mb_str_split(): expects parameter 1 to be string, '.\gettype($string).' given', E_USER_WARNING);
            return null;
        }
        if (null !== $split_length && !\is_bool($split_length) && !\is_numeric($split_length)) {
            trigger_error('mb_str_split(): expects parameter 2 to be int, '.\gettype($split_length).' given', E_USER_WARNING);
            return null;
        }
        $split_length = (int) $split_length;
        if (1 > $split_length) {
            trigger_error('mb_str_split(): The length of each segment must be greater than zero', E_USER_WARNING);
            return false;
        }
        if (null === $encoding) {
            $encoding = mb_internal_encoding();
        } else {
            $encoding = (string) $encoding;
        }
       
        if (! in_array($encoding, mb_list_encodings(), true)) {
            static $aliases;
            if ($aliases === null) {
                $aliases = [];
                foreach (mb_list_encodings() as $encoding) {
                    $encoding_aliases = mb_encoding_aliases($encoding);
                    if ($encoding_aliases) {
                        foreach ($encoding_aliases as $alias) {
                            $aliases[] = $alias;
                        }
                    }
                }
            }
            if (! in_array($encoding, $aliases, true)) {
                trigger_error('mb_str_split(): Unknown encoding "'.$encoding.'"', E_USER_WARNING);
                return null;
            }
        }
       
        $result = [];
        $length = mb_strlen($string, $encoding);
        for ($i = 0; $i < $length; $i += $split_length) {
            $result[] = mb_substr($string, $i, $split_length, $encoding);
        }
        return $result;
    }

    public static function isMixedCharacterChain($text, $tolerance = 1)
    {
        $textContainsNumber = self::containsNumber($text);
        if ($textContainsNumber === false) {
            return false;
        } else {
            return ($textContainsNumber > $tolerance) ? true : false;
        }
    }

    public static function containsNumber($text)
    {
        $counter = 0;
        foreach (self::NUMBERS as $number) {
            if (strpos($text, $number) !== false) {
                $counter++;
            }
        }
        return $counter == 0 ? false : $counter;
    }

    public static function removeNumbers($text)
    {
        foreach (self::NUMBERS as $number) {
            $text = str_replace($number, '', $text);
        }
        return $text;
    }

    public static function mbReplaceSubstr($text, $replace, $pos, $replaceLength = null)
    {
        $firstPart = mb_substr($text, 0, $pos);
        if (!$replaceLength) {
            $replaceLength = mb_strlen($replace);
        }
        $lastPart = mb_substr($text, ($pos + $replaceLength));
        return $firstPart.$replace.$lastPart;
    }

    public static function mbUcfirst($string, $encoding = "utf8")
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    // public static function getMonthName($monthNumber)
    // {
    //     $translationKeys = array(
    //         1 => 'january',
    //         2 => 'february',
    //         3 => 'march',
    //         4 => 'april',
    //         5 => 'may',
    //         6 => 'june',
    //         7 => 'july',
    //         8 => 'august',
    //         9 => 'september',
    //         10 => 'october',
    //         11 => 'november',
    //         12 => 'december'
    //     );
    //     return trans($translationKeys[(int)$monthNumber]);
    // }

    // public static function getPeriodDates(int $periodStartIndex = 0, $periodType = 'month', $periods = 1, $format = 'Y-m-d')
    // {
    //     if ($periodStartIndex < 0) {
    //         $periodStartIndex = (string)$periodStartIndex;
    //     } else {
    //         $periodStartIndex = (string)"+".$periodStartIndex;
    //     }

    //     $start = new \DateTime(date('Y-m-01').' '.$periodStartIndex.' '.$periodType);

    //     if ($start->format('Y-m') == date('Y-m')) {
    //         $end = new \DateTime();
    //     } else {
    //         $end = new \DateTime(date("Y-m-t", strtotime($start->format($format))));
    //     }

    //     $startDateTime = $start->format($format);
    //     $endDateTime = $end->format($format);

    //     if ($startDateTime > $endDateTime) {
    //         $earlierDateTime = $endDateTime;
    //         $laterDateTime = $startDateTime;
    //     } else {
    //         $earlierDateTime = $startDateTime;
    //         $laterDateTime = $endDateTime;
    //     }
    //     $laterDateTimeObj = new \DateTime($laterDateTime);

    //     return [
    //         'start' => $earlierDateTime,
    //         'end' => $laterDateTimeObj->format($format)
    //         // 'startObject' => $start
    //     ];
    // }

    public static function mbParseUrl($url)
    {
        $encodedUrl = preg_replace_callback(
            '%[^:/@?&=#]+%usD',
            function ($matches)
            {
                return urlencode($matches[0]);
            },
            $url
        );
        
        $parts = parse_url($encodedUrl);
        $result = array();
        
        if ($parts === false) {
            throw new \InvalidArgumentException('Malformed URL: ' . $url);
        }
        
        foreach($parts as $name => $value) {
            if ($name == 'query') {
                $pre = $parts['scheme'].'://'.$parts['host'].$parts['path'].'?';
                $value = $url;
                $result[$name] = str_replace($pre, '', urldecode($value));
            } else {
                $result[$name] = urldecode($value);
            }
        }
        
        return $result;
    }

    public static function constantToTranslationFormat($constant)
    {
        $constant = self::camelToSnakeCase($constant);
        return strtolower(str_replace('_', '.', $constant));
    }

    public static function concatArray($array)
    {
        return implode(', ', array_map(
            function ($v, $k) {
                if(is_array($v)){
                    return $k.'[]='.implode('&'.$k.'[]=', $v);
                }else{
                    return $k.'='.$v;
                }
            },
            $array,
            array_keys($array)
        ));
    }

    public static function endsWith($haystack, $needle)
    {
        $needleLength = strlen($needle);
        if(!$needleLength) {
            return true;
        }
        return substr($haystack, -$needleLength) === $needle;
    }

    public static function toCapitalUnderscore($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return strtoupper(implode('_', $ret));
    }

    public static function increaseSequence($string)
    {
        $lastChar = substr($string, -1);
        if (is_numeric($lastChar)) {
            $string = substr($string, 0, -1);
            return $string.($lastChar + 1);
        } else {
            return $string.'2';
        }
    }

    public static function dateDiff($date1, $date2)
    {

    }

    public static function kebabToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
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

    public static function urlExists($url)
    {
        $fileHeaders = get_headers($url);
        if ($fileHeaders and $fileHeaders[0] and $fileHeaders[0] != 'HTTP/1.1 404 Not Found') {
            return true;
        } else {
            return false;
        }
    }

    public static function addArrayLevels(array $keys, $value)
    {
          $return = [];
          $key = array_shift($keys);
          if ($keys) {
              $return[$key] = self::addArrayLevels($keys, $value);
          } else {
              $return[$key] = $value;
          }
          return $return;
    }

    public static function nl2br_indent($string, $indent = 0)
    {
       $string = str_replace("\r", '', $string);

       if (is_int($indent)) {
           $indent = str_repeat(' ', (int)$indent);
       }

       $string = str_replace("\n", "<br />\n".$indent, $string);
       $string = str_replace("&#13;&#10;", "<br />\n".$indent, $string);
       $string = $indent.$string;

       return $string;
    }

    public static function slugify($text)
    {
        if (!$text) {
            return $text;
        }
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ő'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ű'=>'U',
            'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ő'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ű'=>'u',
            'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
        );

        $text = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $text);
        $text = strtolower(strtr($text, $table));
        $text = preg_replace('~[^\pL\d]+~u', '_', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '_');
        $text = preg_replace('~-+~', '_', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function strReplaceFirst($from, $to, $content)
    {
        $from = '/'.preg_quote($from, '/').'/';
        return preg_replace($from, $to, $content, 1);
    }

    public static function explodeString($explodeBy, $string, $requiredElementCount = 1)
    {
        // Ugy mukodik, mint az explode(), csak meg lehet hatarozni a visszatero elemszamot, es ami amugy az
        // explode-dal nem jonne letre elem, az igy NULL lesz.
        $returnArray = array();
        $exploded = explode($explodeBy, $string);
        for ($i = 0; $i < count($exploded); $i++) {
            $returnArray[] = $exploded[$i];
        }
        if (count($exploded) < $requiredElementCount) {
            $diff = $requiredElementCount - count($exploded);
            for ($i2 = 0; $i2 < $diff; $i2++) {
                $returnArray[] = null;
            }
        }
        return $returnArray;
    }

    public static function newLinesToSpaces($string)
    {
        $remove_character = array("\n", "\r\n", "\r");
        return str_replace($remove_character , ' ', $string);
    }

    public static function generateRandomString($length = 10, $type = self::RANDOM_STRING_TYPE_ALPHANUM_MIXED)
    {
        if ($type == 'alphanum_mixed') {
            $characters = '0123456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        elseif (self::RANDOM_STRING_TYPE_ALPHANUM_SMALL) {
            $characters = '0123456789abcdefghijkmnopqrstuvwxyz';
        }
        elseif (self::RANDOM_STRING_TYPE_NUM_2) {
            $characters = '0123456789';
        }
        else {

        }
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function getArrayType($array)
    {
        if (!is_array($array)) return 'notArray';
        $counter = 0;
        foreach ($array as $key => $value) {
            if ($key === 0) {
                // if (ctype_digit($key))
                return 'sequential';
            } else {
                return 'associative';
            }
        }
    }

    public static function getValueFromMixedArrayByKey($array , $key)
    {
        $value = '';
        for ($i=0; $i<count($array); $i++){

        }
        return $value;
    }

    public static function explodeAndRemoveElement($string, $separator, $offsetToRemove)
    {
        // echo '$offset: '. $offset.'<br>';
        $offset = $offsetToRemove;

        $pos = strpos($string, $separator);
        if ($pos === false) {
            return $string;
        }
        $temp = explode($separator, $string);
        if ($offset == 'first') {
            $offsetToRemove = 0;
        }
        if ($offset == 'last') {
            $offsetToRemove = count($temp) - 1;
        }

        $new = array();
        for ($i = 0; $i < count($temp); $i++) {
            if ($offsetToRemove != $i) {
                $new[] = $temp[$i];
            }
        }
        return implode($separator, $new);
    }

    public static function explodeAndGetElement($string, $separator, $offset)
    {
        if (!$string || !$separator) {
            return null;
        }
        // dump($string);
        $temp = explode($separator, $string);
        if ($offset == 'first') {
            return $temp[0];
        }
        if ($offset == 'last') {
            return $temp[(count($temp)-1)];
        }
        if ($offset == 'last-1') {
            if (count($temp) > 1) {
                return $temp[(count($temp)-2)];
            } else {
                throw new ElastiException('Too low offset to get element before last.', ElastiException::ERROR_TYPE_SECRET_PROG);
            }
        }
        for ($i=0; $i<count($temp); $i++) {
            if ($offset == $i) {
                return $temp[$i];
            }
        }
    }

    public static function searchInOneDimensionalArray($element, $array)
    {
       $top = sizeof($array) -1;
       $bot = 0;

       while ($top >= $bot)
       {
          $p = floor(($top + $bot) / 2);
          if ($array[$p] < $element) $bot = $p + 1;
          elseif ($array[$p] > $element) $top = $p - 1;
          else return true;
       }
       return false;
    }

    public static function simplifyText($textInput)
    {
        setlocale(LC_ALL, 'en_GB');
        $simpleText = iconv('UTF-8', 'ASCII//TRANSLIT', $textInput);
        $specCharArray = array("'" , '"' , ',' , ';' , '-' , '_' , ' ');
        $simpleText = strtolower(str_replace($specCharArray , '' , $simpleText));
        return $simpleText;
    }

    public static function getContentBetween($fullstring, $startDelimiter, $endDelimiter)
    {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;

        while (false !== ($contentStart = strpos($fullstring, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($fullstring, $endDelimiter, $contentStart);

            if (false === $contentEnd) {
                break;
            }

            $contents[] = substr($fullstring, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }
        return $contents;
    }

    public static function searchInMultiDimensionalArray($array, $value, $key)
    {
        foreach ($array as $val) {
            if (is_array($val)) {
                if (self::searchInMultiDimensionalArray($val, $value, $key)) {
                    return true;
                }
            } else {
                if ($array[$key] == $value) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function arrayToString($array , $separator=' , ', $runFunctionOnElements = false)
    {
        \App::get()->includeOnce('framework/kernel/base/Reflector.php');

        if (!is_array($array)) {
            return $array;
        }

        $row = '';
        $counter = 0;

        foreach ($array as $key => $value) {
            $value = $runFunctionOnElements ? $runFunctionOnElements($value) : $value;

            if (is_object($value)) {
                $reflector = new Reflector();
                $value = $reflector->objectToString($value);
            }

            if (is_array($value)) {
                $value = self::arrayToString($value);
            }

            if ($counter > 0) {
                $preStr = $separator;
            } else {
                $preStr = '';
            }
            $row .= $preStr.$value;
            $counter++;
        }
        return $row;
    }

    public static function arrayToString_OLD($array , $separator=' , ', $runFunctionOnElements = false)
    {
        if (!is_array($array)) {
            return $array;
        }

        $row = '';
        $counter = 0;
        for ($i=0; $i < count($array); $i++) {
            $value = $array[$i];
            $value = $runFunctionOnElements ? $runFunctionOnElements($value) : $value;

            if ($counter > 0) {
                $preStr = $separator;
            } else {
                $preStr = '';
            }
            $row .= $preStr.$value;
            $counter++;
        }
        return $row;
    }

    public static function checkArrayElementsInArray($needlesArray , $haystackArray)
    {
        $hitCounter = 0;
        for ($i=0; $i < count($needlesArray); $i++) {
            if (in_array($needlesArray[$i], $haystackArray)) {
                $hitCounter++;
            }
        }
        if ($hitCounter > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getCurrentDate()
    {
        return date('Y-m-d H:i:s');
    }

    // public static function getDateParams($date)
    // {
    //     $temp1array = explode(' ', $date);
    //     $temp2array = explode('-', $temp1array[0]);
    //     $temp3array = explode(':', $temp1array[1]);
    //     $dateParams['year'] = $temp2array[0];
    //     $dateParams['month'] = $temp2array[1];
    //     $dateParams['day'] = $temp2array[2];
    //     $dateParams['hour'] = $temp3array[0];
    //     $dateParams['min'] = $temp3array[1];
    //     $dateParams['sec'] = $temp3array[2];
    //     return $dateParams;
    // }

    // public static function getUnixTimestamp($date)
    // {
    //     $dateParams = self::getDateParams($date);
    //     return mktime(
    //         $dateParams['hour'] , $dateParams['min'] ,
    //         $dateParams['sec'] , $dateParams['month'] ,
    //         $dateParams['day'] , $dateParams['year']
    //     );
    // }

    public static function cutLongString($string, $maxCharNum, $end = '...')
    {
        if (!$string) {
            return $string;
        }
        return (mb_strlen($string) > $maxCharNum) ? mb_substr($string, 0, $maxCharNum) . $end : $string;
    }

    public static function mbSubstrReplace($original, $replacement, $position, $length)
    {
        $startString = mb_substr($original, 0, $position, "UTF-8");
        $endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");
        $out = $startString . $replacement . $endString;
        return $out;
    }

    // public static function isCurrentDateInInterval($startDate, $endDate)
    // {
    //     $now = time();
    //     if (($startDate < $now) and  ($now < $endDate)) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public static function getParity(int $num)
    {
        if (is_int($num / 2) == true) {
            return 'even';
        } else {
            return 'odd';
        }
    }
}
