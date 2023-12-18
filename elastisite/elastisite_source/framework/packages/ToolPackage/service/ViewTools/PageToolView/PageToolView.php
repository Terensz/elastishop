<?php
namespace framework\packages\ToolPackage\service\ViewTools;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\packages\ToolPackage\service\PageTool;

class PageToolView extends Service
{
    private $viewFilePath = 'framework/packages/ToolPackage/view/ViewTools/pageTool';

    private $pageToolIdPre = '';

    public function __construct()
    {
        $this->wireService('ToolPackage/service/PageTool');
    }

    public function getPageToolId()
    {
        return 'PageToolView_'.$this->pageToolIdPre;
    }

    public function setPageToolIdPre($pageToolIdPre)
    {
        $this->pageToolIdPre = $pageToolIdPre;
    }

    public function getPageToolIdPre()
    {
        return $this->pageToolIdPre;
    }

    // public function getPageTool() : PageTool
    // {
    //     App::getContainer()->setService('ToolPackage/service/PageTool');

    //     return App::getContainer()->getService('PageTool');
    // }

    public function showCustomizablePageRoutesSelector()
    {
        $view = '';
        $customPageRoutes = PageTool::getCustomPageRoutes();

        $view .= $this->renderView(FileHandler::completePath($this->viewFilePath.'/pageRoutes/search.php'), [
            // 'form' => $this->form, 
            // 'formViewElement' => $formViewElement
        ]);
        $view = str_replace('[pageToolId]', $this->getPageToolId(), $view);
        // dump($this->getPageTool()->getBuiltInPageRoutes());
        foreach (PageTool::getBuiltInPageRoutes() as $route) {
            // dump($route);
            if (!in_array($route['name'], $customPageRoutes)) {
                $routeView = $this->renderView(FileHandler::completePath($this->viewFilePath.'/pageRoutes/route.php'), [
                    // 'form' => $this->form, 
                    // 'formViewElement' => $formViewElement
                ]);
    
                $routeParamChains = '';
                $paramChainCounter = 0;
                foreach ($route['paramChains'] as $routeParamChain => $locale) {
                    // dump($locale);
                    // dump($routeParamChain);
                    $routeParamChains .= ($paramChainCounter == 0 ? '' : '<br>').($routeParamChain == '' ? '('.trans('empty').')' : $routeParamChain).' (<b>'.$locale.'</b>)';
                    $paramChainCounter++;
                }
    
                $routeView = str_replace('[routeName]', $route['name'], $routeView);
                $routeView = str_replace('[routeParamChains]', $routeParamChains, $routeView);
                $routeView = str_replace('[routeTitle]', trans($route['title']), $routeView);
                $routeView = str_replace('[pageToolId]', $this->getPageToolId(), $routeView);
    
                // dump(htmlentities($routeView));exit;
                $view .= $routeView;
            }
        }

        echo $view;
    }

    public function getParamChains($routeName)
    {
        foreach (PageTool::getBuiltInPageRoutes() as $route) {
            if ($route['name'] == $routeName) {
                return $route['paramChains'];
            }
        }
        return null;
    }

    public function getParamChainString($routeName)
    {
        $paramChainString = '';
        $paramChains = $this->getParamChains($routeName);
        if (!$paramChains) {
            return null;
        }
        $paramChainCounter = 0;
        foreach ($paramChains as $routeParamChain => $locale) {
            // dump($locale);
            // dump($routeParamChain);
            $paramChainString .= ($paramChainCounter == 0 ? '' : '<br>').($routeParamChain == '' ? '('.trans('empty').')' : $routeParamChain).' (<b>'.$locale.'</b>)';
            $paramChainCounter++;
        }
        return $paramChainString;
    }

    // public function getTitleReference($routeName)
    // {
    //     // $titleRef = '';
    //     foreach (PageTool::getBuiltInPageRoutes() as $route) {
    //         if ($route['name'] == $routeName) {
    //             return $route['title'];
    //         }
    //     }
    //     return '';
    // }

    public function getTitleString($routeName)
    {
        
        // $titleRef = '';
        foreach (PageTool::getBuiltInPageRoutes() as $route) {
            if ($route['name'] == $routeName) {
                return '<b>'.trans($route['title']).'</b>';
            }
        }
        return '';
    }

    // public function resolveInputPlaceholders($view, $route)
    // {
    //     // foreach (self::INPUT_PLACEHOLDER_PARAMS as $placeholderParam => $placeholderConfig) {
    //     //     $object = $placeholderConfig['propertyLocation'] == 'this' ? $this : $formViewElement;
    //     //     $getter = 'get'.ucfirst($placeholderParam);
    //     //     $value = $object->$getter();
    //     //     if (is_object($value) || is_array($value)) {
    //     //         dump($value);//exit;
    //     //     }
    //     //     $view = str_replace('{{ '.$placeholderParam.' }}', ($value ? $value : ''), $view);
    //     // }
    //     return $view;
    // }
}