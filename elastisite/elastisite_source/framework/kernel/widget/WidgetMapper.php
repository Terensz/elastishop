<?php
namespace framework\kernel\widget;

use App;
use framework\kernel\base\Cache;
use framework\kernel\component\Kernel;
use framework\kernel\utility\FileHandler;

class WidgetMapper extends Kernel
{
    public function __construct()
    {
        $this->create();
    }

    // public function getRealWidgetViewPath($viewFilePath)
    // {
    //     $widgetName = null;
    //     $viewFile = BasicUtils::explodeAndGetElement($viewFilePath, '/', 'last');
    //     $pathElements = explode('/', $viewFilePath);
    //     for ($i = 0; $i < count($pathElements); $i ++) {
    //         $widgetPos = strpos($pathElements[$i], 'Widget');
    //         if ($widgetPos !== false) {
    //             $widgetName = $pathElements[$i];
    //         }
    //     }
    //     $website = App::getWebsite();
    //     $projectViewFilePath = 'projects/'.$websiteName.'/view/widget/'.$widgetName.'/'.$viewFile;
    //     // dump($projectViewFilePath);
    //     return FileHandler::isFile($projectViewFilePath) ? $projectViewFilePath : $viewFilePath;
    // }

    public function create()
    {
        if (!Cache::cacheRefreshRequired()) {
            $widgetMapCache = App::$cache->read('widgetMap');
            if (!empty($widgetMapCache)) {
                App::getContainer()->setWidgetMap($widgetMapCache);
                return true;
            }
        }
        
        # 1.: Collecting widget-data from packages.
        $packageNames = FileHandler::getAllDirNames('framework/packages', 'source');
        foreach ($packageNames as $packageName) {
            $widgetNames = FileHandler::getAllDirNames('framework/packages/'.$packageName.'/view/widget', 'source');
            foreach ($widgetNames as $widgetName) {
                $path = FileHandler::completePath('framework/packages/'.$packageName.'/view/widget/'.$widgetName, 'source');
                $this->getContainer()->addWidgetToMap($widgetName, $path, 'packages', 'packages');
            }
        }

        # 2.: Collecting widget-data from projects.
        $widgetNames = FileHandler::getAllDirNames('projects/'.App::getWebProject().'/view/widget', 'projects');
        foreach ($widgetNames as $widgetName) {
            $widgetFiles = FileHandler::getAllFileNames('projects/'.App::getWebProject().'/view/widget/'.$widgetName, 'keep', 'projects');
            $path = FileHandler::completePath('projects/'.App::getWebProject().'/view/widget/'.$widgetName, 'projects');
            $scriptsLocation = null;
            foreach ($widgetFiles as $widgetFile) {
                if ($widgetFile == 'scripts.php') {
                    $scriptsLocation = 'projects';
                }
            }
            $this->getContainer()->addWidgetToMap($widgetName, $path, 'projects', $scriptsLocation);
        }
        // dump($this->getContainer()->getWidgetMap());
        // dump('alma');
        App::$cache->write('widgetMap', App::getContainer()->getWidgetMap());
    }
}
