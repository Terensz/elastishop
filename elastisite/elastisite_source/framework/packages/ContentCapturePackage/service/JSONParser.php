<?php 
namespace framework\packages\ContentCapturePackage\service;

use App;
use framework\component\parent\Service;

class JSONParser extends Service
{
    public static function parseJSON($json) 
    {
        $data = json_decode($json, true);
        if ($data === null) {
            // JSON dekódolása sikertelen
            return null;
        }

        return $data;
    }
}