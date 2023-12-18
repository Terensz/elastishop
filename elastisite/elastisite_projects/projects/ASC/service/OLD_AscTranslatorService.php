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

    public static function wireRepositories()
    {
        App::getContainer()->wireService('projects/ASC/repository/AscTranslationGroupRepository');
        App::getContainer()->wireService('projects/ASC/repository/AscTranslationRepository');
    }

    // public static function findTranslationByReference(string $reference, string $languageCode = null) : string
    // {
    //     self::wireRepositories();
    //     $translations = AscTranslationRepository::findAllTranslationsForReference($reference);
    //     $foundTranslation = null;
    //     $counter = 0;
    //     foreach ($translations as $translation) {
    //         if ($counter == 0) {
    //             $foundTranslation = $translation;
    //         }

    //         if ($translation['language_code'] == self::DEFAULT_LANGUAGE) {
    //             $foundTranslation = $translation;
    //         }

    //         if ($translation['language_code'] == $languageCode) {
    //             $foundTranslation = $translation;
    //             continue;
    //         }

    //         $counter++;
    //     }

    //     return $foundTranslation;
    // }

    public static function getTranslation(int $groupId, string $languageCode = null) : string
    {
        $foundTranslation = self::findTranslationParams($groupId, $languageCode);

        return $foundTranslation ? $foundTranslation['translation_value'] : self::createTranslationIdentifier($groupId);
    }

    public static function createTranslationIdentifier(int $groupId) : string
    {
        return 'asc_translation-'.$groupId;
    }

    public static function getTranslationGroup(int $groupId = null) : ? AscTranslationGroup
    {
        App::getContainer()->wireService('projects/ASC/repository/AscTranslationGroupRepository');
        $groupRepo = new AscTranslationGroupRepository();
        $ascTranslationGroup = null;
        if ($groupId) {
            $ascTranslationGroup = $groupRepo->find($groupId);
        }

        return $ascTranslationGroup ? : self::createTranslationGroup();
    }

    public static function createTranslationGroup() : ? AscTranslationGroup
    {
        App::getContainer()->wireService('projects/ASC/entity/AscTranslationGroup');
        $ascTranslationGroup = new AscTranslationGroup();
        $ascTranslationGroup->setReference(null);
        $ascTranslationGroup->setStatus(AscTranslationGroup::STATUS_ACTIVE);
        $ascTranslationGroup = $ascTranslationGroup->getRepository()->store($ascTranslationGroup);

        return $ascTranslationGroup;
    }

    public static function createTranslation(string $languageCode, string $value, int $groupId = null) : ? AscTranslation
    {
        $ascTranslationGroup = self::getTranslationGroup($groupId);
        $translationParams = self::findTranslationParams($ascTranslationGroup->getId(), $languageCode);

        if ($translationParams) {
            App::getContainer()->wireService('projects/ASC/repository/AscTranslationRepository');
            $ascTranslationRepo = new AscTranslationRepository();
            $ascTranslation = $ascTranslationRepo->find($translationParams['asc_translation_id']);
            // if (!$ascTranslation) {
            //     $translationParams = null;
            // }
        }

        if (!$ascTranslation) {
            // App::getContainer()->wireService('projects/ASC/entity/AscTranslationGroup');
            App::getContainer()->wireService('projects/ASC/entity/AscTranslation');

            $ascTranslation = new AscTranslation();
            $ascTranslation->setAscTranslationGroup($ascTranslationGroup);
            $ascTranslation->setLanguageCode($languageCode);
            $ascTranslation->setValue($value);
            $ascTranslation->setStatus(AscTranslation::STATUS_ACTIVE);
            $ascTranslation = $ascTranslation->getRepository()->store($ascTranslation);
        }

        return $ascTranslation;
    }

    public static function findTranslationParams(int $groupId, string $languageCode = null) : array
    {
        self::wireRepositories();
        $allTranslationParams = AscTranslationRepository::getAllTranslationParams($groupId);
        $foundTranslationParams = null;
        $counter = 0;
        foreach ($allTranslationParams as $translationParams) {
            if ($counter == 0) {
                $foundTranslationParams = $translationParams;
            }

            if ($translationParams['language_code'] == self::DEFAULT_LANGUAGE) {
                $foundTranslationParams = $translationParams;
            }

            if ($translationParams['language_code'] == $languageCode) {
                $foundTranslationParams = $translationParams;
                continue;
            }

            $counter++;
        }

        return $foundTranslationParams;
    }

    // public static function createAscTranslationGroup($language, $title, $description)
    // {
    //     App::getContainer()->wireService('projects/ASC/entity/AscTranslationGroup');
    //     App::getContainer()->wireService('projects/ASC/entity/AscTranslation');

    //     $ascTranslationGroup = new AscTranslationGroup();
    //     $ascTranslationGroup->setReference(null);
    //     $ascTranslationGroup->setStatus(AscTranslationGroup::STATUS_ACTIVE);
    //     $ascTranslationGroup = $ascTranslationGroup->getRepository()->store($ascTranslationGroup);

    //     $ascTranslation = new AscTranslation();
    // }
}