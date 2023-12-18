<?php
namespace framework\component\entity;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\packages\BackgroundPackage\entity\FBSPageBackground;
use framework\packages\BackgroundPackage\repository\FBSPageBackgroundRepository;
use framework\packages\BackgroundPackage\entity\FBSBackground;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;

class Route extends Kernel
{
    private $name;
    // private $keywords;
    // private $description;
    private $paramChains;
    private $paramChain;
    private $controller;
    private $action;
    private $permission;
    private $inMenu;
    private $title;
    private $structure;
    private $skinName;
    private $backgroundColor;
    private $widgetChanges = array();
    private $pageSwitchBehavior = array();
    private $backgroundEngine;
    private $backgroundTheme;
    private $error;

    public function set($routeArray)
    {
        // dump($routeArray);
        $this->name = $routeArray['name'];
        $this->paramChains = $routeArray['paramChains'];
        if (isset($routeArray['paramChain'])) {
            $this->paramChain = $routeArray['paramChain'];
        }
        $this->controller = $routeArray['controller'];
        $this->action = $routeArray['action'];
        $this->permission = $routeArray['permission'];
        $this->inMenu = isset($routeArray['inMenu']) ? $routeArray['inMenu'] : null;
        $this->title = isset($routeArray['title']) ? $routeArray['title'] : null;
        $this->structure = isset($routeArray['structure']) ? $routeArray['structure'] : 'general';
        $this->skinName = isset($routeArray['skinName']) ? $routeArray['skinName'] : null;
        // $this->backgroundColor = isset($routeArray['backgroundColor']) ? $routeArray['backgroundColor'] : null;
        if (isset($routeArray['widgetChanges'])) {
            $this->setWidgetChanges($routeArray['widgetChanges']);
        }
        if (isset($routeArray['pageSwitchBehavior'])) {
            $this->setPageSwitchBehavior($routeArray['pageSwitchBehavior']);
        } else {
            $this->setPageSwitchBehavior(array());
        }
        $this->setBackground($routeArray);
        $this->error = (isset($routeArray['error'])) ? $routeArray['error'] : null;
        // dump($this->widgetChanges);exit;
        // dump($this->getWidgetChanges());exit;
    }

    // public function setKeywords($keywords)
    // {
    //     $this->keywords = $keywords;
    // }

    // public function getKeywords()
    // {
    //     return $this->keywords;
    // }

    // public function setDescription($description)
    // {
    //     $this->description = $description;
    // }

    // public function getDescription()
    // {
    //     return $this->description;
    // }

    public function setBackground($routeArray)
    {
        $this->backgroundEngine = 'Simple';
        $this->backgroundTheme = 'empty';

        $this->getContainer()->setService('FrameworkPackage/service/CustomPageService');
        $customPageService = $this->getContainer()->getService('CustomPageService');
        $backgroundParams = $customPageService->getBackgroundParams($routeArray['name']);

        if ($backgroundParams) {
            $this->backgroundEngine = $backgroundParams['engine'];
            $this->backgroundTheme = $backgroundParams['theme'];
            $this->backgroundColor = $backgroundParams['color'];

            // dump($this);exit;
        }
    }

    public function getWidgetPosition($checkedWidgetName)
    {
        // dump($this->widgetChanges);
        foreach ($this->widgetChanges as $position => $widgetPath) {
            $widgetName = BasicUtils::explodeAndGetElement($widgetPath, '/', 'last');
            if ($checkedWidgetName == $widgetName) {
                return $position;
            }
        }
        return $checkedWidgetName;
    }

    public function setWidgetChanges($widgetChanges)
    {
        foreach ($widgetChanges as $key => $value) {
            $widget = $this->getContainer()->getServiceLinkParams($value)['pathToFile'];
            $this->widgetChanges[$key] = BasicUtils::explodeAndGetElement(str_replace('.php', '', $widget), '/', 'last');
        }
    }

    public function setPageSwitchBehavior($pageSwitchBehavior)
    {
        foreach ($pageSwitchBehavior as $key => $value) {
            $this->pageSwitchBehavior[$key] = $value;
        }

        $defaultPageSwitchBehavior = $this->getProjectData('defaultPageSwitchBehavior');
        $defaultPageSwitchBehavior = $defaultPageSwitchBehavior ? $defaultPageSwitchBehavior : [];
        foreach ($defaultPageSwitchBehavior as $key => $value) {
            if (!isset($this->pageSwitchBehavior[$key])) {
                $this->pageSwitchBehavior[$key] = $value;
            }
        }
    }

    public function getPageSwitchBehavior()
    {
        return $this->pageSwitchBehavior;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParamChains()
    {
        return $this->paramChains;
    }

    public function getParamChain()
    {
        return $this->paramChain;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function getInMenu()
    {
        return $this->inMenu;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getStructure()
    {
        return $this->structure;
    }

    public function getSkinName()
    {
        return $this->skinName;
    }

    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    public function getChangeWidget($placeholder)
    {
        return $this->widgetChanges[$placeholder];
    }

    public function getWidgetChanges()
    {
        return $this->widgetChanges;
    }

    public function getBackgroundEngine()
    {
        return $this->backgroundEngine;
    }

    public function getBackgroundTheme()
    {
        return $this->backgroundTheme;
    }

    public function getError()
    {
        return $this->error;
    }
}
