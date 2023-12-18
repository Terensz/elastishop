<?php

use framework\kernel\base\ConfigReader;
use framework\kernel\base\Container;

class App 
{
    const ENV_DEV = 'dev';
    const ENV_PROD = 'prod';

    private static $selfIstance;

    private static $env;

    private static $startTime;

    public static $frameworkDirAbsolutePath;

    public static $sourceRelativePath = '';

    private static $webProject;

    private static $website;

    public static $configReader;

    public static $cache;

    public static $projectPathBase;

    public static $javaScriptLibraryPaths = [];

    public static function isAdminPage()
    {
        return self::getContainer()->getUrl()->getMainRouteRequest() == 'admin';
    }

    public static function sendDevelopersMessage($message)
    {
        /**
         * @todo
        */
    }

    public static function get() : App
    {
        if (!self::$selfIstance) {
            self::$selfIstance = new self();
        }

        return self::$selfIstance;
    }

    public static function setEnv()
	{
        // $user = Container::getSelfObject()->getUser();
        // var_dump($user);exit;
        // var_dump($_SERVER['REMOTE_ADDR']);
        self::$env = App::ENV_DEV; return;
        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {
            self::$env = App::ENV_DEV;
        } else {
            self::$env = App::ENV_PROD;
        }
    }

    // public static function setEnv($env)
    // {
    //     self::$env = $env;
    // }

    public static function getEnv()
    {
        return self::$env;
    }

    public static function setStartTime()
    {
        self::$startTime = microtime(true);
    }

    public static function getElapsedLoadingTime()
    {
        $now = microtime(true);
        $elapsedLoadingTime = $now - self::$startTime;

        return $elapsedLoadingTime;
    }

    public static function isCLICall()
    {
        return (php_sapi_name() == "cli") ? true : false;
    }

    public static function addJavaScriptLibraryPath($javaScriptLibraryPath)
    {
        self::$javaScriptLibraryPaths[] = $javaScriptLibraryPath;
    }

    public static function getJavaScriptLibraryPath()
    {
        return self::$javaScriptLibraryPaths;
    }

    /**
     * Used by CLI
    */
    public static function setSourceRelativePath($sourceRelativePath)
    {
        self::$sourceRelativePath = $sourceRelativePath;
    }

    public static function getContainer() : Container
    {
        return Container::getSelfObject();
    }

    public static function getConfigReader() : ConfigReader
    {
        return self::$configReader;
    }

    public static function setWebProject($webProject)
    {
        self::$webProject = $webProject;
    }

    public static function getWebProject($debug = null) : string
    {
        // var_dump('App::getWebProject()!!!');
        // var_dump($debug);
        if (!self::$webProject) {
            dump(App::get());
            throw new \Exception('WebProject is null');
        }
        return self::$webProject;
    }

    public static function setWebsite($website)
    {
        self::$website = $website;
    }

    public static function isWebsiteSet() : bool
    {
        return self::$website ? true : false;
    }

    public static function getWebsite() : string
    {
        // dump('App::getWebsite()!!!');
        if (!self::$website) {
            dump(App::get());
            throw new \Exception('Website is null');
        }
        return self::$website;
    }

    public static function renderView($viewFilePath, array $viewData = []) : string
    {
        ob_start();
        extract($viewData);
        ob_implicit_flush(false);
        include($viewFilePath);
        
        return ob_get_clean();
    }

    public static function includeOnce($pathPartToFile)
    {
        include_once(self::$sourceRelativePath . $pathPartToFile);
    }

    public static function redirect($path)
    {
        header('Location: ' . $path, false);
        exit;
    }
}