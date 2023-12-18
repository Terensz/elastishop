<?php
namespace framework\component\entity;

use framework\kernel\component\Kernel;

class Widget
{
    private $divId;
    private $widgetPath;
    private $scriptsPath;
    private $name;
    private $content;

    public function getDivId()
    {
        return $this->divId;
    }

    public function setDivId($divId)
    {
        $this->divId = $divId;
    }

    public function getWidgetPath()
    {
        return $this->widgetPath;
    }
    
    public function setWidgetPath($widgetPath)
    {
        $this->widgetPath = $widgetPath;
    }

    public function getScriptsPath()
    {
        return $this->scriptsPath;
    }

    public function setScriptsPath($scriptsPath)
    {
        $this->scriptsPath = $scriptsPath;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

}
