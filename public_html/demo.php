<?php

// echo $_SERVER['DOCUMENT_ROOT'];exit;

// function getBasePathConfig() {
//     return array(
//         'config' => $_SERVER['DOCUMENT_ROOT'].'elastisite/elastisite_config',
//         'dynamic' => $_SERVER['DOCUMENT_ROOT'].'elastisite/elastisite_dynamic',
//         'projects' => $_SERVER['DOCUMENT_ROOT'].'elastisite/elastisite_projects'
//     );
// }

// echo dirname(__FILE__); exit;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 3);
ini_set('proxy_buffering', 'off');
ini_set('fastcgi_buffering', 'off');
set_time_limit(3);
error_reporting(E_ALL);
// echo dirname(__FILE__).'/../elastisite_source/';exit;
set_include_path(dirname(__FILE__).'/../elastisite/elastisite_source/');
// $sourcePath = realpath(dirname(__FILE__) . '/../elastisite_source');
$sourcePath = dirname(__FILE__).'/../elastisite/elastisite_source/';
/**
 * CLI requires this filled, web needs it empty.
*/
$sourceRelPath = '';
include($sourceRelPath.'App.php');

$publicHtmlDirAbsolutePath = __DIR__;
$publicHtmlPathParts = explode(DIRECTORY_SEPARATOR, $publicHtmlDirAbsolutePath);
$publicHtmlPathParts[count($publicHtmlPathParts) - 1] = 'elastisite';
$frameworkDirAbsolutePath = implode(DIRECTORY_SEPARATOR, $publicHtmlPathParts);
// $pathToKernel = $frameworkDirAbsolutePath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, ['vendor', 'ElastiSite', 'kernel', 'Kernel.php']);
App::$frameworkDirAbsolutePath = $frameworkDirAbsolutePath;

// var_dump('alma!!'); exit;
include($sourceRelPath.'VendorAutoLoader.php');
VendorAutoLoader::init();

// echo $_SERVER['DOCUMENT_ROOT'].'/'.$_SERVER['SCRIPT_FILENAME'];exit;
include('../elastisite/elastisite_source/framework/kernel/base/ConfigReader.php');
App::$configReader = new framework\kernel\base\ConfigReader();
$pathBaseConfig = App::$configReader->read($sourcePath.'config/pathBaseConfig.txt');
$pathBaseConfig = array_merge($pathBaseConfig, ['webRoot' => dirname(__FILE__)]);
// var_dump($pathBaseConfig);exit;

// echo var_export($basePathConfig, true);exit;
// echo var_export($configReader, true);exit;
// \App::get()->includeOnce('basePathConfig.php');
// echo var_export(getBasePathConfig(), true);exit;
// session_save_path(getBasePathConfig()['dynamic'].'/temp/session');

include($sourcePath.'framework/kernel/engineLoader.php');

?>
