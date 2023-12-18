<?php 
namespace framework\packages\ContentCapturePackage\service;

use App;
use framework\component\parent\Service;

class WebserviceClient extends Service
{
    /**
     * Sends a GET request to target URL, and returns with the response.
     *
     * @param string $url The request's URL
     * @return string|false The response message 
     */
    public static function sendGetRequest($url)
    {
        // Creating the cURL resource
        $ch = curl_init();

        // Setting up cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Sending the GET request
        $response = curl_exec($ch);

        // Verifying errors
        if ($response === false) {
            return false; // Error occurred during the request
        }

        // Closing cURL resource
        curl_close($ch);

        return $response; // returning the response
    }
}