<?php 

use framework\kernel\base\Reflector;

if (!function_exists('dump'))
{
	function dump($data = null, $options = array())
	{
        $dumpArray = AdvancedDump::dump($data, $options);

		$trace = debug_backtrace();
		$current = $trace[0];
		$file = $current['file'];
		$serverPath = getcwd();
		$file = str_replace($serverPath, '', $file);
		$line = $current['line'];
		$caller = isset($trace[1]) ? $trace[1] : null;
        $callerFunction = $caller && isset($caller['function']) ? $caller['function'] : '';

        // var_dump($caller);exit;

        return AdvancedDump::displayDump($dumpArray, $file, $line, $callerFunction, $options);
        // foreach ($dumpArray as $dumpRow) {
        //     AdvancedDump::displayRow($dumpRow);
        // }
	}
}

class AdvancedDump
{
    const INDENT_DEPTH_WIDTH = 20;
    const COLORS = [
        'object' => '#c62bad',
        'method' => '#d46cca',
        'property' => '#d46cca',
        'array' => '#4288bb',
        'arrayKey' => '#7cb5d4',
        'value' => '#55db4d',
        'string' => '#55db4d',
        'null' => '#b99a43',
        'true' => '#b99a43',
        'false' => '#b99a43',
        'numeric' => '#3f61bb',
        'other' => '#afafaf'
    ];

    public static function displayDump($dumpArray, $file, $line, $callerFunction, $options)
    {
        echo '
<div style="background-color: #000; color: #fff; font: 18px Arial; padding: 10px; word-break: break-all;">
';

        if (!isset($options['header']) || $options['header'] === true):
echo '
    <div style="color: #bfb12d;">
            File: <span style="color: '.self::COLORS['other'].';">'.$file.'</span>
    </div>
    <div style="color: #bfb12d;">
            Line: <span style="color: '.self::COLORS['numeric'].';">'.$line.'</span>
    </div>
    <div style="color: #bfb12d;">
            Caller: <span style="color: '.self::COLORS['other'].';">'.$callerFunction.'</span>
    </div>
';
        endif;

        foreach ($dumpArray as $dumpRow) {
            AdvancedDump::displayRow($dumpRow);
        }

        echo '
</div>';
    }

    public static function displayRow($dumpRow)
    {
        // echo str_repeat("\t", $dumpRow['depth']) . $dumpRow['text']."\n";
        $leftPadding = $dumpRow['depth'] * self::INDENT_DEPTH_WIDTH;
        // if (!isset(self::COLORS[$dumpRow['type']])) {
        //     var_dump($dumpRow['type']);
        //     var_dump(self::COLORS);
        // }
        $keyTypeStyleStr = $dumpRow['keyType'] ? ' style="color: '.self::COLORS[$dumpRow['keyType']].'"' : '';
        echo '
    <div style="margin-left: '.$leftPadding.'px;">
        <span'.$keyTypeStyleStr.'>'.$dumpRow['keyString'].'</span>'.$dumpRow['text'].'
    </div>
';
    }

    public static function dump($data, $options, $depth = 0, &$processed = array(), $key = null, $keyType = null)
    {
        $result = [];
        $detailedObjects = true;
        if (isset($options['detailedObjects']) && $options['detailedObjects'] === false) {
            $detailedObjects = false;
        }

        $keyString = '';
        if ($key !== null && $key !== true && $key !== false) {
            $keyString = '['.$key.'] ';
            // $result[] = [
            //     'type' => $keyType,
            //     'depth' => $depth,
            //     'text' => $key
            // ];
            // $depth++;
        }

        if (is_object($data)) {
            // Ellenőrizd, hogy az objektum már feldolgozott-e
            if (in_array($data, $processed, true) || !$detailedObjects) {
                return [
                    [
                        'type' => 'object',
                        'depth' => $depth,
                        'text' => self::wrapValue(get_class($data) . " {...}", 'object'),
                        'value' => get_class($data) . " {...}",
                        'keyString' => $keyString,
                        'keyType' => $keyType
                    ]
                ];
            }

            // Add hozzá az objektumot a feldolgozott elemekhez
            $processed[] = $data;

            $result[] = [
                'type' => 'object',
                'depth' => $depth,
                'text' => self::wrapValue(get_class($data), 'object'),
                'value' => get_class($data),
                'keyString' => $keyString,
                'keyType' => $keyType
            ];

            // Feldolgozza az objektum property-jeit
            $reflection = new ReflectionObject($data);
            $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE);
            // var_dump($properties);//exit;
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $propertyName = $property->getName();
                $propertyValue = $property->getValue($data);
                $result = array_merge($result, self::dump($propertyValue, $options, $depth + 1, $processed, $propertyName, 'property'));
            }

            // Feldolgozza az objektum metódusait
            // $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED | ReflectionMethod::IS_PRIVATE);
            // foreach ($methods as $method) {
            //     $result[] = [
            //         'type' => 'method',
            //         'depth' => $depth + 1,
            //         'text' => $method->getName() . '()'
            //     ];
            // }
        } elseif (is_array($data)) {
            // Ellenőrizd, hogy a tömb már feldolgozott-e
            // if (in_array($data, $processed, true)) {
            //     return [
            //         [
            //             'type' => 'array',
            //             'depth' => $depth,
            //             'text' => self::wrapValue('Array {...}', 'array'),
            //             'value' => 'Array {...}',
            //             'keyString' => $keyString,
            //             'keyType' => $keyType
            //         ]
            //     ];
            // }

            // Add hozzá a tömböt a feldolgozott elemekhez
            $processed[] = $data;
            $textValue = count($data) == 0 ? 'Empty array' : 'Array';
            $result[] = [
                'type' => 'array',
                'depth' => $depth,
                'text' => self::wrapValue($textValue, 'array'),
                'value' => $textValue,
                'keyString' => $keyString,
                'keyType' => $keyType
            ];

            foreach ($data as $key => $value) {
                $result = array_merge($result, self::dump($value, $options, $depth + 1, $processed, $key, 'arrayKey'));
            }
        } else {
            $result[] = [
                'type' => 'value',
                'depth' => $depth,
                'text' => self::formatValue($data),
                'value' => $data,
                'keyString' => $keyString,
                'keyType' => $keyType
            ];
        }

        return $result;
    }

    private static function formatValue($value)
    {
        if ($value === null) {
            return self::wrapValue('null', 'null');
        } elseif (is_string($value)) {
            return self::wrapValue($value, 'string');
        } elseif (is_bool($value)) {
            return $value ? self::wrapValue('true', 'true') : self::wrapValue('false', 'false');
        } elseif (is_numeric($value)) {
            return self::wrapValue($value, 'numeric');
        } else {
            return self::wrapValue($value, 'string');
            // return $value;
        }
    }

    public static function wrapValue($value, $type)
    {
        return '<span style="color: '.self::COLORS[$type].';">'.$value.'</span>';
    }

    // {
    //     $reflector = new Reflector();
    //     $propertiesArray = $reflector->getProperties($object, false);

    //     foreach ($propertiesArray as $reflectionProperty) {
    //         $propertyName = is_string($reflectionProperty) || is_numeric($reflectionProperty) ? $reflectionProperty : $reflectionProperty->getName();
    //         $value = $reflector->getValue($object, $propertyName);	
    //         if (is_object($value)) {
    //             $this->dumpObject($value, $propertyName, $blockId, 'object', 0, ($level + 1));
    //         } elseif (is_array($value)) {
    //             $this->dumpArray($value, $propertyName, $blockId, 'object', 0);
    //         } else {
    //             $this->dumpString($value, $propertyName, $blockId, 'object');
    //         }
    //     }
    // }
}