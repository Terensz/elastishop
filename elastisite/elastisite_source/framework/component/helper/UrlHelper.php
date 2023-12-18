<?php
namespace framework\component\helper;

class UrlHelper
{
    public static $cache;

    public static function getPseudoDomain()
    {
        if (isset(self::$cache['pseudoDomain'])) {
            return self::$cache['pseudoDomain'];
        }
        $pseudoDomain = $_SERVER['SCRIPT_NAME'];
        $pseudoDomain = str_replace('index.php?r=', '', $pseudoDomain);
        $pseudoDomain = str_replace('index.php', '', $pseudoDomain);
        $pseudoDomain = trim($pseudoDomain, '/');
        self::$cache['pseudoDomain'] = '/'.$pseudoDomain;

        return self::$cache['pseudoDomain'];
    }

    public static function getFullDomain()
	{
        if (isset(self::$cache['fullDomain'])) {
            return self::$cache['fullDomain'];
        }
		$pseudoDomain = self::getPseudoDomain();
        $fullDomain = $_SERVER['SERVER_NAME'] . (($_SERVER['SERVER_PORT'] != '80')
            ? (':' . $_SERVER['SERVER_PORT']).$pseudoDomain
            : ''.$pseudoDomain);

        $fullDomainColonSeparatedParts = explode(':', $fullDomain);
        $fullDomain = $fullDomainColonSeparatedParts[0];
        self::$cache['fullDomain'] = trim($fullDomain, '/');

        return self::$cache['fullDomain'];
	}
}