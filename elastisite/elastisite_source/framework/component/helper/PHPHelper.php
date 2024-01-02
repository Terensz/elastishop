<?php
namespace framework\component\helper;

use App;

class PHPHelper
{
    public static function redirect($requestedLocation, $requestedBy = null)
    {
        $currentLocation = '/'.App::getContainer()->getUrl()->getParamChain();

        if (strpos($requestedLocation, '://') !== false) {
            $requestedLocationParts0 = explode('://', $requestedLocation);
            $requestedLocation = $requestedLocationParts0[1];
            $requestedLocation = str_replace(App::getContainer()->getUrl()->getFullDomain(), '', $requestedLocation);
            $requestedLocation = '/'.trim($requestedLocation, '/');
        }

        if ($currentLocation != $requestedLocation) {
            header('Location: '.$requestedLocation);
        } else {
            dump('Requested location is same as we are now.');
            dump($requestedLocation);
            dump($requestedBy);exit;
        }
    }
}