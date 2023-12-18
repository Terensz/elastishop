<?php
namespace framework\kernel\base;

use framework\component\helper\StringHelper;
use framework\kernel\utility\BasicUtils;

class ConfigReader
{
    private $debug = false;

	public function read($absolutePathToFile)
	{
        // echo '<br>read KEZDODIK<br>';
        // echo '$absolutePath: '.$absolutePathToFile;exit;
        $content = file_get_contents($absolutePathToFile);

        $lines = explode("\n", $content);
        $result = array();
        foreach ($lines as $line) {
            // echo $line.'<br>';
            if (trim($line) != '' && substr($line, 0, 1) != '#') {
                $processedLine = $this->processLine($line);
                if (!isset($processedLine['value'])) {
                    // var_dump($line);exit;
                    // return false;
                    $result[$processedLine['key']] = false;
                }
                $result[$processedLine['key']] = $processedLine['value'];
            }
        }
        // if ($this->debug && function_exists('dump')) {
        //     dump($result);
        // }

        return $result;
    }
    
	public function processLine($line)
	{
        // echo 'processLine<br>';
        $line = trim($line);
        $parts = explode('=', $line);
        if (count($parts) == 0) {
            die('Uzemeltetesi hiba: 1401');
        }
        if (substr($parts[1], 0, 1) == '[') {
            // die('Uzemeltetesi hiba: 1401');
            $value = $this->processArray($this->getValueString($line));
        } else {
            $value = self::mendValue($parts[1]);
        }

        return array('key' => $parts[0], 'value' => $value);
    }

	public function getValueString($line)
	{
        $value = '';
        $parts = explode('=', $line);
        for ($i = 0; $i < count($parts); $i++) {
            if ($i != 0) {
                $value .= ($i > 1 ? '=' : '').$parts[$i];
            }
        }
        return $value;
    }

	public static function mendValue($string)
	{
        if (!$string) {
            return $string;
        }
        if (\App::isCLICall()) {
            // var_dump($_SERVER['PWD']);exit;
            $indexAbsolutePath = $_SERVER['PWD'];
        } else {
            $indexAbsolutePath = $_SERVER['SCRIPT_FILENAME'];
            $indexAbsolutePath = str_replace('/index.php', '', $indexAbsolutePath);
        }
        // $string = str_replace('{DOCUMENT_ROOT}', rtrim($_SERVER['DOCUMENT_ROOT'], '/'), $string);
        // if (!$string) {
        //     var_dump($string);
        // }
        $string = str_replace('{DOCUMENT_ROOT}', $indexAbsolutePath, $string);
        // echo '<b>'.$string.'</b> ';

        // $string = StringHelper::mendValue($string);

        if ($string === 'true') {
            return true;
        } elseif ($string === 'false') {
            return false;
        } elseif ($string === 'null') {
            return null;
        } elseif ($string === 'array') {
            return array();
        } else {
            return $string;
        }
        
        // $string = $string == 'true' ? true : $string;
        // $string = $string == 'false' ? false : $string;
        // $string = $string == 'null' ? null : $string;
        // $string = $string == 'array' ? array() : $string;
        // echo '<pre>';var_dump($string);echo '</pre>';echo '<br>';
        // return $string;
    }

	public function processArray($string)
	{
        // dump($string);exit;
        $string = ltrim($string, '[');
        $string = rtrim($string, ']');
        $array = array();
        $elements = explode(',', $string);
        foreach ($elements as $element) {
            $parts = explode('=>', $element);
            if (count($parts) == 1) {
                $array[] = $element;
            } else {
                $array[$parts[0]] = self::mendValue($parts[1]);
            }
        }
        return $array;
    }
}
