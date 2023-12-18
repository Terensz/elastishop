<?php
namespace framework\packages\SiteBuilderPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;

class ContentEditorDisplayTool extends Service
{
    const PLACEHOLDER_START_STRING = '[';
    const PLACEHOLDER_END_STRING = ']';

    public static function displayDescription($description)
    {
        if (!$description) {
            return '';
        }
        $description = self::changePlaceholders($description);

        return nl2br(html_entity_decode($description));
    }

    public static function changePlaceholders($description, $loop = 0) : string
    {
        $placeholderStartPosition = mb_strpos($description, self::PLACEHOLDER_START_STRING);
        $placeholderEndPosition = mb_strpos($description, self::PLACEHOLDER_END_STRING);
        if ($placeholderStartPosition === false || $placeholderEndPosition === false) {
            return $description;
        }

        $placeholder = StringHelper::getStringBetween($description, $placeholderStartPosition, $placeholderEndPosition);
        $value = self::getPlaceholderValue($placeholder);
        $description = str_replace(self::PLACEHOLDER_START_STRING.$placeholder.self::PLACEHOLDER_END_STRING, $value, $description);

        return self::changePlaceholders($description, $loop++);
    }

    public static function getPlaceholderValue($placeholder) : string
    {
        if ($placeholder == 'pageTitle') {
            return trans(App::getContainer()->getRouting()->getPageRoute()->getTitle());
        }
        $linkPos = mb_strpos($placeholder, 'link|');
        if ($linkPos === 0) {
            $urlWithTitle = mb_substr($placeholder, 5);
            $urlAndTitle = explode('|', $urlWithTitle);
            $url = $urlAndTitle[0];
            $title = isset($urlAndTitle[1]) && $urlAndTitle[1] != '' ? $urlAndTitle[1] : $url;

            $httpPos = strpos($url, 'http://');
            $httpsPos = strpos($url, 'https://');
// dump(App::getContainer()->getSession()->get('site_adminViewState'));
            if ($httpPos === false && $httpsPos === false && App::getContainer()->getSession()->get('site_adminViewState') === false) {
                $classStr = 'class="ajaxCallerLink" ';
                $href = '/'.$url;
                $targetStr = '';
            } else {
                $href = $url;
                $classStr = '';
                $targetStr = ' target="_blank"';
            }

            $link = '<a '.$classStr.'href="'.(App::getContainer()->getSession()->get('site_adminViewState') ? '' : $href).'"'.(App::getContainer()->getSession()->get('site_adminViewState') ? ' onclick="Structure.preventDefault(event);"' : '').$targetStr.'>'.$title.'</a>';

            return $link;
        }

        return $placeholder;
    }
}
