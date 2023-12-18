<?php
namespace framework\component\parent;

// use framework\component\parent\RouteRendering;

use App;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Response;
use framework\component\exception\ElastiException;

class PageController extends RouteRendering
{
    protected $controllerType = 'page';

    public function __construct()
    {
        // dump('Alma!!!!');exit;
        // dump(App::getContainer()->getProjectData('allowedPageProtocols'));
        // dump(App::getContainer()->getUrl()->getProtocol());exit;
        $currentProtocol = App::getContainer()->getUrl()->getProtocol();
        $allowedPageProtocols = App::getContainer()->getProjectData('allowedPageProtocols');
        if (is_array($allowedPageProtocols) && count($allowedPageProtocols) == 1 && !in_array($currentProtocol, $allowedPageProtocols)) {
            $oppositeProtocol = $allowedPageProtocols[0] == 'https://' ? 'http://' : 'https://';
            header('Location: '.str_replace($currentProtocol, $oppositeProtocol, App::getContainer()->getUrl()->getFullUrl()));
        }
    }
    
    // public function __construct()
    // {
    //     $actualRoute = $this->getContainer()->getRouting()->getActualRoute();
    //     $pageRoute = $this->getContainer()->getRouting()->getPageRoute();
    //     dump($actualRoute); 
    //     dump($pageRoute); 
    // }
}
