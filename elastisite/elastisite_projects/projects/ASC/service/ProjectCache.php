<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\entity\ProjectUser;
use projects\ASC\repository\ProjectUserRepository;

class ProjectCache extends Service
{
    public static $cache;

    // public static function storeCachedData($key, $data)
    // {
    //     self::$cache[$key] = $data;
    // }

    // public static function getCachedData($key)
    // {
    //     if (isset(self::$cache[$key])) {
    //         return self::$cache[$key];
    //     }

    //     return null;
    // }
}