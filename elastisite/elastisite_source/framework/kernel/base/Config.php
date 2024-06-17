<?php
namespace framework\kernel\base;

use App;
use framework\component\helper\UrlHelper;
// use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
// use framework\kernel\base\ConfigReader;

class Config
{
	private $globalVariables = array();
	private $frameworkDataCache;
	private $companyDataCache;
	private $projectDataCache;
	private $skinDataCache;

	public function __construct()
	{
		// var_dump('__construct');
		// dump('Ide meg kell csinalni a config-feldolgozast');exit;
		$config = $this->processConfigFile();
		// dump('Ide meg kell csinalni a config-feldolgozast');exit;
		foreach ($config as $key => $value) {
			$this->setGlobal($key, $value);
		}
		// dump($this->globalVariables);
	}

	// public function getConfigReader()
	// {
	// 	// return $this->getContainer()->getKernelObject('ConfigReader');
	// 	return App::$configReader;
	// }

	// public function getWebsite()
	// {
	// 	return App::getWebsite();
	// }

	public function getConfiguredData($configCategory, $dataType = null, $key = null)
	{
		// dump('getConfiguredData');
		$cacheName = ($configCategory == 'projects' ? lcfirst($dataType) : $configCategory).'DataCache';
		// if (!$this->$cacheName) {
		// 	$pathToFile = App::getContainer()->getPathBase('config').($configCategory == 'projects' ? '/projects/'.App::getWebsite().'/'.lcfirst($dataType) : '/'.$configCategory.'/'.$dataType).'_config.txt';
		// 	$this->$cacheName = App::$configReader->read($pathToFile);
		// }
		if (!isset($this->$cacheName[App::getWebsite()])) {
			$pathToFile = App::getContainer()->getPathBase('config').($configCategory == 'projects' ? '/projects/'.App::getWebsite().'/'.lcfirst($dataType) : '/'.$configCategory.'/'.$dataType).'_config.txt';
			$this->$cacheName[App::getWebsite()] = App::$configReader->read($pathToFile);
		}

		if (!$key) {
			return $this->$cacheName[App::getWebsite()];
		}
		
		return isset($this->$cacheName[App::getWebsite()][$key]) ? $this->$cacheName[App::getWebsite()][$key] : null;
	}

	public function getSupportedLocales()
	{
		return $this->getConfiguredData('framework', 'framework', 'supportedLocales');
	}

	public function getFrameworkData($key = null)
	{
		return $this->getConfiguredData('framework', 'framework', $key);
	}

	public function getCompanyData($key = null)
	{
		return $this->getConfiguredData('projects', 'company', $key);
	}

	public function getProjectData($key = null)
	{
		return $this->getConfiguredData('projects', 'project', $key);
	}

	public function getSkinData($key = null)
	{
		return $this->getConfiguredData('projects', 'skin', $key);
	}

	public function processConfigFile()
	{
		$fullDomain = UrlHelper::getFullDomain();

		// var_dump($fullDomain);exit;
		// dump($this->getContainer()->getPathBase());

		// var_dump(App::getWebsite());exit;
		// var_dump(App::getWebProject());exit;
		
		$fileName = '.asc_env_test';

		// if (in_array($fullDomain, ['meheszellato'])) {
		// 	$fileName = '.meheszellato_env';
		// }

		// if (in_array($fullDomain, ['elastishop'])) {
		// 	$fileName = '.elastishop_env';
		// }

		// if (in_array($fullDomain, ['elastishop'])) {
		// 	$fileName = '.asc_env_local';
		// }

		// if (in_array($fullDomain, ['asc'])) {
		// 	$fileName = '.asc_env_local';
		// }

		// if (in_array($fullDomain, ['axxxxsxxxxcxxxxxx.hu', 'axxxxsxxxxcxxxxxx.com', 'adminscalecreator.hu', 'adminscalecreator.com', 'ascsite.hu', 'ascsite.com'])) {
		// 	$fileName = '.asc_env_test';
		// }

		// if (in_array($fullDomain, ['klicc', 'kli.cc'])) {
		// 	$fileName = '.klicc_env';
		// }

		// if (in_array($fullDomain, ['supplementvalue', 'supplementvalue.com'])) {
		// 	$fileName = '.supplementvalue_env';
		// }

		// var_dump($fullDomain);
		// var_dump($fileName);exit;

		$pathToFile = App::getContainer()->getPathBase('config').'/sysadmin/' . $fileName;
		$config = App::$configReader->read($pathToFile);
		return $config;
	}

	public function getCount($array)
	{
		if (!is_array($array)) {
			return 0;
		} else {
			return count($array);
		}
	}

	public function setGlobal($paramName, $paramValue)
	{
		$this->globalVariables[$paramName] = $paramValue;
	}

	public function getGlobal($paramName)
	{
		// var_dump('getGlobal');
		// var_dump($paramName);
		if (isset($this->globalVariables[$paramName])) {
			$getGlobal = $this->globalVariables[$paramName];
		} else {
			$getGlobal = null;
		}
		// if ($paramName == 'siteName') {
		// 	var_dump($getGlobal);
		// }

		return $getGlobal;
	}

	public function getAllGlobals()
	{
		return $this->globalVariables;
	}

	public function isSetGlobal($paramName)
	{
		if (isset($this->globalVariables[$paramName]) AND !empty($this->globalVariables[$paramName])) {
			return true;
		} else {
			return false;
		}
	}

	public function addToGlobalArray($paramName, $paramValue)
	{
		if (!isset($this->globalVariables[$paramName])) {
			$this->globalVariables[$paramName] = array();
		}
		if (BasicUtils::getArrayType($paramValue) == 'associative') {
			$this->globalVariables[$paramName][count($this->globalVariables[$paramName])] = $paramValue;
		} elseif (BasicUtils::getArrayType($paramValue) == 'sequential') {
			for ($i = 0; $i < count($paramValue); $i++) {
				$elementCount = $this->getCount($this->globalVariables[$paramName]);
				$this->globalVariables[$paramName][$elementCount] = $paramValue[$i];
			}
		}
	}

	public function unsetGlobalArray($paramName)
	{
		$this->globalVariables[$paramName] = array();
	}

	public function getGlobalArray($paramName)
	{
		if (isset($this->globalVariables[$paramName]) AND is_array($this->globalVariables[$paramName])) {
			return $this->globalVariables[$paramName];
		} else {
			return null;
		}
	}

	public function setGlobalArray($paramName, $paramValue)
	{
		$this->globalVariables[$paramName] = $paramValue;
	}
}
