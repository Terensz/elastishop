<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\entity\AscTranslation;
use projects\ASC\entity\AscTranslationGroup;
use projects\ASC\repository\AscTranslationGroupRepository;
use projects\ASC\repository\AscTranslationRepository;

class AscTranslatorService extends Service
{
    const DEFAULT_LANGUAGE = 'en';

    const ACTIVE_LANGUAGES = [
        [
            'key' => 'en',
            'translationReference' => 'english'
        ],
        [
            'key' => 'hu',
            'translationReference' => 'hungarian'
        ]
    ];

    public static function getActiveLanguages()
    {
        return self::ACTIVE_LANGUAGES;
    }
}