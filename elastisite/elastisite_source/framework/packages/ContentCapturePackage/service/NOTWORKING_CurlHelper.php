<?php 
namespace framework\packages\ContentCapturePackage\service;

use framework\component\parent\Service;

class CurlHelper extends Service
{
    public static function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Adjunk hozzá egy felhasználói ügynök fejlécet a kéréshez
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
        
        // Esetleges további beállítások
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Követhető-e a redirect
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL ellenőrzés kikapcsolása
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            echo 'cURL hiba: ' . curl_error($ch);
        }
        
        curl_close($ch);

        return $response;
    }
}
