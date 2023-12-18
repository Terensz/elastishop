<?php
namespace framework\kernel\base;

use App;
use framework\kernel\utility\FileHandler;

class Cache
{
	const MAIN_ROUTES_REQUIRE_CACHE_REFRESH_WITHOUT_SUBROUTES = ['admin'];

	const MAIN_ROUTES_REQUIRE_CACHE_REFRESH_WITH_SUBROUTES = ['setup'];

	public $path;

	public $storage;

	public function setPath()
	{
		if (!$this->path) {
			$this->path = FileHandler::completePath('projects/'.App::getWebProject('Cache: setting cache path').'/cache', 'dynamic'); 
		}
	}

	public function getPathToFile($drawerName = null)
	{
		return $this->path.($drawerName ? '/'.$drawerName.'.txt' : '');
	}

	public function write($drawerName, $dataArray)
	{
		$this->setPath();
		// dump($this->getPathToFile($drawerName));
		@file_put_contents($this->getPathToFile($drawerName), json_encode($dataArray));
    }

	public function read($drawerName)
	{
		if (isset($this->storage[$drawerName])) {
			return $this->storage[$drawerName];
		}
		$this->setPath();
		$content = null;
		if (file_exists($this->getPathToFile($drawerName))) {
			$content = @file_get_contents($this->getPathToFile($drawerName));
		}
		if (!$content) {
			return array();
		} else {
			$decodedContent = json_decode($content, true);
			return $decodedContent && is_array($decodedContent) ? $decodedContent : array();
		}
    }

	public function clear($drawerName = null)
	{
		if ($drawerName) {
			@unlink($this->getPathToFile($drawerName));
		} else {
			$fileNames = FileHandler::getAllFileNames($this->getPathToFile(), 'remove', null);
			// dump($fileNames);exit;
			if (!empty($fileNames) && is_array($fileNames)) {
				foreach ($fileNames as $drawerName) {
					$pathToFile = $this->getPathToFile($drawerName);
					// dump($pathToFile);
					$unlinked = @unlink($pathToFile);
					// dump($unlinked);
					// dump(file_get_contents($pathToFile));
				}
			}
		}
		// exit;
	}

	// public function autoClearCache()
	// {
	// 	$url = App::getContainer()->getUrl();
		
	// 	if (in_array($url->getMainRouteRequest(), self::MAIN_ROUTES_REQUIRE_CACHE_REFRESH_WITH_SUBROUTES) || (in_array($url->getMainRouteRequest(), self::MAIN_ROUTES_REQUIRE_CACHE_REFRESH_WITHOUT_SUBROUTES) && !$url->getSubRouteRequest())) {
	// 		$this->clear();
	// 	}
	// }

	public static function cacheRefreshRequired()
	{

		// dump(App::getContainer()->getUrl());
		$url = App::getContainer()->getUrl();
		$isAjax = $url->getIsAjax();

		
		// if ($url->getMainRouteRequest() == 'admin' && !$url->getSubRouteRequest() && $isAjax) {
		// 	return true;
		// }
		// dump(in_array($url->getMainRouteRequest(), self::MAIN_ROUTES_REQUIRE_CACHE_REFRESH_WITH_SUBROUTES));exit;
		if ((in_array($url->getMainRouteRequest(), self::MAIN_ROUTES_REQUIRE_CACHE_REFRESH_WITH_SUBROUTES) || (in_array($url->getMainRouteRequest(), self::MAIN_ROUTES_REQUIRE_CACHE_REFRESH_WITHOUT_SUBROUTES) && !$url->getSubRouteRequest())) && (!$isAjax || ($isAjax && $url->getAjaxUrl() == $url->getFullUrl()))) {
			return true;
		}
		// if (!$isAjax || ($url->getMainRouteRequest() == 'admin' && ($isAjax && $url->getAjaxUrl() != $url->getFullUrl() ))) {
		// 	return true;
		// }
		// $isAjax = App::getContainer()->getUrl()->getIsAjax();

		// return $isAjax;
		
		return false;
	}
}