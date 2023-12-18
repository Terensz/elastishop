<?php
namespace framework\packages\FrameworkPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\FrameworkPackage\entity\Language;

class LanguageService extends Service
{
    const DATA = [
        'en' => [
            // 'code' => 'en',
            'translationReference' => 'english'
        ],
        'hu' => [
            // 'code' => 'hu',
            'translationReference' => 'hungarian'
        ]
    ];

    public static function wireLanguageEntity()
    {
        App::getContainer()->wireService('FrameworkPackage/entity/Language');
    }

    public static function getLanguage(string $languageCode = null) :? Language
    {
        if (!$languageCode) {
            $languageCode = App::getContainer()->getSession()->getLocale();
        }
        self::wireLanguageEntity();
        $language = new Language();
        if (isset(self::DATA[$languageCode])) {
            $data = self::DATA[$languageCode];
            $language->code = $languageCode;
            $language->translationReference = $data['translationReference'];
        } else {
            $language->code = $languageCode;
            $language->translationReference = 'unknown';
        }

        return $language;
    }
}