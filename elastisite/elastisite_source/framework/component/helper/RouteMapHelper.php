<?php
namespace framework\component\helper;

use App;
use framework\kernel\component\Kernel;

class RouteMapHelper extends Kernel
{
	public static function getAllMappedRouteNamesAndParamChains() : array
	{
		return array_merge(self::getAllMappedRouteNames(), self::getAllMappedParamChains());
	}

	public static function getAllMappedRouteNames() : array
	{
		return array_keys(App::getContainer()->fullRouteMap);
	}

	public static function getAllMappedParamChains() : array
	{
		$paramChains = [];
		foreach (App::getContainer()->fullRouteMap as $routeMapElement) {
			if (!isset($routeMapElement['paramChains'])) {
				// dump(App::getContainer()->fullRouteMap);
				$routeMapElement['paramChains'] = [];
			}
			$paramChains = array_merge($paramChains, $routeMapElement['paramChains']);
		}

		// dump($paramChains);
		return array_keys($paramChains);
    }
}
