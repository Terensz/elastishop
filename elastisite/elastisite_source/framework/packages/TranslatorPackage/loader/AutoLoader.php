<?php
namespace framework\packages\TranslatorPackage\loader;

use framework\component\parent\PackageLoader;
use framework\kernel\utility\FileHandler;

class AutoLoader extends PackageLoader
{
    public function __construct()
    {
        FileHandler::includeFile('framework/packages/TranslatorPackage/trans.php', 'source');
        $this->getContainer()->wireService('framework/packages/TranslatorPackage/Translator');
        $this->setService('TranslatorPackage/Translator');
    }
}
