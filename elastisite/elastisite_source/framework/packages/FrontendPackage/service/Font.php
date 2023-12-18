<?php
namespace framework\packages\FrontendPackage\service;

use framework\component\parent\Service;

class Font extends Service
{
    public $fontGroup;

    public $fontFamily;

    public $originalFontFamily;

    public $source;

    public $displayedOnLists;

    public $isDefault;

    public function __construct()
    {
        
    }
}