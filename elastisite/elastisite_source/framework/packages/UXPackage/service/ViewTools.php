<?php
namespace framework\packages\UXPackage\service;

use App;
use framework\component\parent\Service;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;

class ViewTools extends Service
{
    const TOOLS = array(
        'form' => array('namespace' => 'framework\packages\ToolPackage\service\ViewTools\FormView'),
        'pageTool' => array('namespace' => 'framework\packages\ToolPackage\service\ViewTools\PageToolView'),
    );

    public static function displayComponent($componentPath, array $viewData = [])
    {
        echo self::renderComponent($componentPath, $viewData);
    }

    public static function renderComponent($componentPath, array $viewData = []) : string
    {
        $viewFilePath = FileHandler::completePath('framework/packages/UXPackage/view/'.$componentPath.'.php', 'source');
        
        return App::renderView($viewFilePath, $viewData);
    }

    public function create($toolkit)
    {
        if (!in_array($toolkit, array_keys(self::TOOLS))) {
            throw new ElastiException('Unknown toolkit: '.$toolkit, ElastiException::ERROR_TYPE_SECRET_PROG);
        }
        $toolkitClass = ucfirst($toolkit).'View';
        $this->wireService('ToolPackage/service/ViewTools/'.$toolkitClass.'/'.$toolkitClass);
        $toolkitNamespace = self::TOOLS[$toolkit]['namespace'];
        return new $toolkitNamespace();
    }
}
