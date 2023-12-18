<?php
namespace framework\kernel\routing;

// use framework\kernel\component\Kernel;

use App;
use framework\kernel\utility\FileHandler;

class SetWebsite
{
    public function __construct()
    {
        App::$projectPathBase = App::getContainer()->getPathBase('projects').'/projects/'.App::getWebProject();
        $pathToMiddlewareSetWebProject = App::$projectPathBase.'/middleware/SetWebsite.php';
        if (FileHandler::fileExists($pathToMiddlewareSetWebProject)) {
            App::getContainer()->setService('projects/'.App::getWebProject().'/middleware/SetWebsite', 'MiddlewareWebsiteSetter');
            $service = App::getContainer()->getService('MiddlewareWebsiteSetter');
            // dump($service);exit;
        } else {
            App::setWebsite(App::getWebProject('SetWebsite: __construct()'));
        }
        // dump();
        // dump($fullDomain);
        
    }
}
