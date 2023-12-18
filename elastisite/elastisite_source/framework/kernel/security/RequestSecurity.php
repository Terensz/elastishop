<?php

namespace framework\kernel\security;

use framework\kernel\component\Kernel;

class RequestSecurity extends Kernel
{
    public $sqlInjectionKeywords = array(
        'select' => array('from', 'into'),
        'insert' => 'into',
        'update' => 'set',
        'delete' => 'from',
        'drop' => 'table',
        'create' => 'table',
        'alter' => 'table'
    );

    public function secureRequest($key, $value)
    {
        return $value;
        // return $this->checkSqlInjection($key, $value) ? '*Removed insecure text*' : $value;
    }

    public function checkSqlInjection($key, $text)
    {
        $result1 = $this->checkSqlInjection_type1($key, $text);
        $result2 = $this->checkSqlInjection_type2($key, $text);
        return ($result1 === true || $result2 === true) ? true : false;
    }

    public function checkSqlInjection_type1($key, $originalText)
    {
        $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        $lowerText = strtolower($originalText);
        $result = false;
        foreach ($this->sqlInjectionKeywords as $keyword1 => $keyword2array) {
            $pos1 = strpos($lowerText, $keyword1);
            if ($pos1 !== false) {
                if (!is_array($keyword2array)) {
                    $keyword2array = array($keyword2array);
                }
                foreach ($keyword2array as $keyword2) {
                    $pos2 = strpos(substr($lowerText, $pos1), $keyword2);
                    if ($pos2 !== false) {
                        $result = true;
                        $securityEventHandler->addEvent('SQL_INJECTION_ATTEMPT', $key, $originalText);
                    }
                }
            }
        }
        return $result;
    }

    public function checkSqlInjection_type2($key, $text)
    {
        $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
        $textParts = preg_split('/\s+/', $text);
        $result = false;
        foreach ($textParts as $index => $textPart) {
            $textPart = strtolower(trim($textPart));
            if ($textPart == 'or') {
                $equalPos = strpos($text, '=');
                if ($equalPos !== false) {
                    $result = true;
                    $securityEventHandler->addEvent('SQL_INJECTION_ATTEMPT', $key, $text);
                }
            }
        }

        return $result;
    }
}
