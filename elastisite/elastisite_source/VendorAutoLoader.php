<?php

use framework\kernel\utility\FileHandler;

/**
 * ElastiSite can handle vendors. But: for this, you must put the source to elastisite/vendor/{clasName}/{clasName} directory. 
 * If it doesn't work, check the namespace of a vendor file. The file path should be the same, begining with an additional directory, with the class name.
*/
class VendorAutoLoader 
{
    const VENDOR_PATH_BEGINNING_CONVERSION = [
        'Mpdf' => [
            'from' => 'Mpdf/',
            'to' => 'Mpdf/src/'
        ],
        'Google' => [
            'from' => 'Google/',
            'to' => 'Google/src/',
        ],
        'GuzzleHttp' => [
            'from' => 'GuzzleHttp/',
            'to' => 'GuzzleHttp/src/'
        ],
        'PsrHttp' => [
            'from' => 'PsrHttp/',
            'to' => 'PsrHttp/src/'
        ],
        'PsrCache' => [
            'from' => 'PsrCache/',
            'to' => 'PsrCache/src/'
        ],
    ];

    public static function init()
    {
        spl_autoload_register(function ($class) {
    
            $classParts = explode('\\', $class);
            $vendorRef = $classParts[0];
            $vendorDirs = FileHandler::getAllDirNames(App::$frameworkDirAbsolutePath . DIRECTORY_SEPARATOR . 'vendor');
    
            if (in_array($vendorRef, $vendorDirs)) {
    
                $relPathToFile = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
                
                if (isset(self::VENDOR_PATH_BEGINNING_CONVERSION[$vendorRef])) {
                    $from = self::VENDOR_PATH_BEGINNING_CONVERSION[$vendorRef]['from'];
                    $to = self::VENDOR_PATH_BEGINNING_CONVERSION[$vendorRef]['to'];
    
                    if (strpos($relPathToFile, $from) === 0) {
                        $relPathToFileEnd = substr($relPathToFile, strlen($from));
                        $relPathToFile = $to . $relPathToFileEnd;
                    }
                }
    
                $pathToFile = App::$frameworkDirAbsolutePath . DIRECTORY_SEPARATOR . 'vendor/' . $relPathToFile;
    
                include($pathToFile);
            } else {
                // dump($class);
                // $backtrace = debug_backtrace();
                // dump($backtrace);exit;
                // foreach ($backtrace as $trace) {
                //     if (isset($trace['file']) && isset($trace['line'])) {
                //         error_log("Autoloader invoked by: {$trace['file']} (line {$trace['line']})");
                //         break;
                //     }
                // }
                // dump($class);//exit;
            }
        });
    }
}