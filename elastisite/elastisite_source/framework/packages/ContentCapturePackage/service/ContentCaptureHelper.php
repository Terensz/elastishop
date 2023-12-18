<?php 
namespace framework\packages\ContentCapturePackage\service;

use App;
use framework\component\parent\Service;

class ContentCaptureHelper extends Service
{
    public static function saveWebsiteImage($sourceAbsoluteUrl, $targetAbsolutePath)
    {
        // Target path
        // $targetPath = FileHandler::completePath('projects/ASC/upload/contentCapture/alma.jpg', 'dynamic');

        $ch = curl_init($sourceAbsoluteUrl);
        $fp = fopen($targetAbsolutePath, 'w');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        // Verifying is the upload succeeded or not
        if (file_exists($targetAbsolutePath)) {
            return [
                'success' => true,
                'errorMessage' => null
            ];
        } else {
            return [
                'success' => false,
                'errorMessage' => trans('error.occurred.while.dowdloading.image')
            ];
        }
    }
}

/*
Ahhoz, hogy hatákonyan tudjuk a termékeket összeszinkronizálni a mi rendszerünkkel elsősorban arra lenne szükségünk, hogy olyan hívást tudjunk az Önök szervere felé kezdeményezni, ami JSON, XML, vagy valamilyen könnyen tömbbé alakítható formában adja vissza a termékek adatait, mert ellenkező esetben a teljes html-kódból kellene kinyernünk az adatokat, ami egy nagyságrenddel nagyobb fejlesztési munka.

*/

