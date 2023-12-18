<?php
namespace framework\packages\FrontendPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;

class Fonts extends Service
{
    public $defaultFont;

    public $errorMessage;

    public $registeredFonts = [];

    public function __construct()
    {
        $this->wireService('FrontendPackage/service/Font');
    }

    public function registerFonts()
    {
        $defaultFont = App::getContainer()->getSkinData('defaultFont');
        $fontDirs = FileHandler::getAllDirNames('public_folder/font', 'webRoot');

        foreach ($fontDirs as $fontDir) {
            $fontFiles = FileHandler::getAllFileNames('public_folder/font/'.$fontDir, 'webRoot');
            $this->registerFont($fontDir, $fontFiles, ($fontDir == $defaultFont));
            // dump($fontFiles);
            // $fonts->registerFont();
        }
    }

    private function registerFont($fontGroup, $fontFiles, $isDefault)
    {
        foreach ($fontFiles as $fontFile) {
            $fontFileMainParts = explode('.', $fontFile);
            $fontFileSubParts = explode('-', $fontFileMainParts[0]);

            if (isset($fontFileSubParts[1]) && in_array($fontFileSubParts[1], ['Regular', 'Bold'])) {
                $originalFontFamily = $fontFileMainParts[0];
                $isBold = false;
                if ($fontFileSubParts[1] == 'Bold') {
                    $isBold = true;
                }
                if ($isDefault) {
                    $fontFamily = $isBold ? 'DefaultFontBold' : 'DefaultFont';
                    $this->addFont($fontGroup, $fontFamily, $originalFontFamily, $fontFile, $isDefault, false);
                }

                $fontFamily = $originalFontFamily;
                $this->addFont($fontGroup, $fontFamily, $originalFontFamily, $fontFile, $isDefault, true);
            }
        }

        // $fontFamily, $source
        // $font = new Font();
        // $font->fontFamily = $fontFamily;
        // $font->source = $source;
    }

    private function addFont($fontGroup, $fontFamily, $originalFontFamily, $fontFile, $isDefault, $displayedOnLists)
    {
        $font = new Font();
        $font->fontGroup = $fontGroup;
        $font->fontFamily = $fontFamily;
        $font->originalFontFamily = $originalFontFamily;
        $font->source = App::getContainer()->getUrl()->getHttpDomain().'/public_folder/font/'.$fontGroup.'/'.$fontFile;
        $font->displayedOnLists = $displayedOnLists;
        $font->isDefault = $isDefault;

        $this->registeredFonts[] = $font;
    }
}