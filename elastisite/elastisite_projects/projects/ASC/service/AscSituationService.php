<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\repository\AscTranslationRepository;

class AscSituationService extends Service
{
    const BUILT_IN_SITUATIONS = [
        [
            'key' => 'Studying',
            'translationReference' => 'studying'
        ],
        [
            'key' => 'Education',
            'translationReference' => 'education'
        ],
        [
            'key' => 'Creating2D',
            'translationReference' => 'love.affairs.marriage',
            'translationReferenceScn' => 'creating.second.dynamic',
            'subcategories' => [
                [
                    'key' => 'Handling2DNonExistence',
                    'translationReference' => 'dating',
                    'translationReferenceScn' => 'handling.2d.non.existence',
                ],
                [
                    'key' => 'CreatingMarriage',
                    'translationReference' => 'operating.marriage',
                    'translationReferenceScn' => 'creating.marriage'
                ],
                [
                    'key' => 'SavingMarriage',
                    'translationReference' => 'saving.marriage'
                ],
                [
                    'key' => 'ExitingMarriage',
                    'translationReference' => 'exiting.marriage'
                ],
            ]
        ],
        [
            'key' => 'AlteringBody',
            'translationReference' => 'altering.body',
            'subcategories' => [
                [
                    'key' => 'LoosingWeight',
                    'translationReference' => 'loosing.weight'
                ],
                [
                    'key' => 'BuildingMuscles',
                    'translationReference' => 'building.muscles'
                ],
                [
                    'key' => 'FreedomFromIllness',
                    'translationReference' => 'freedom.from.illness'
                ]
            ]
        ],
        [
            'key' => 'CreatingBusiness',
            'translationReference' => 'creating.business',
            'subcategories' => [
                [
                    'key' => 'CreatingStartupCompany',
                    'translationReference' => 'creating.startup.company'
                ],
                [
                    'key' => 'AnnualPlanning',
                    'translationReference' => 'annual.planning'
                ]
            ]
        ],
        [
            'key' => 'EnvironmentalProtection',
            'translationReference' => 'environmental.protection'
        ],
        [
            'key' => 'GoalOfLife',
            'translationReference' => 'goal.of.life'
        ]
    ];

    public static function gatherBuiltInSituations($situationDataArray = null, $parent = null, $level = 0)
    {
        if (!$situationDataArray) {
            $situationDataArray = self::BUILT_IN_SITUATIONS;
        }
        $result = [];
        foreach ($situationDataArray as $situationLoopData) {
            $result[] = [
                'key' => $situationLoopData['key'],
                'translationReference' => $situationLoopData['translationReference'],
                'parent' => $parent,
                'level' => $level
            ];

            if (isset($situationLoopData['subcategories'])) {
                $subData = self::gatherBuiltInSituations($situationLoopData['subcategories'], $situationLoopData['key'], ($level + 1));
                $result = array_merge($result, $subData);
            }
        }

        return $result;
    }

    public static function getAllSituationData($requiredParent = null, $requiredLevel = null) : ? array
    {
        $result = [];
        foreach (self::gatherBuiltInSituations() as $situationData) {
            if ((!$requiredLevel || $requiredLevel == $situationData['level']) && (!$requiredParent || $requiredParent == $situationData['parent'])) {
                $result[] = $situationData;
            }
        }

        return $result;
    }

    public static function findSituationData($key) : ? string
    {
        $result = null;
        foreach (self::gatherBuiltInSituations() as $situationData) {
            if ($situationData['key'] == $key) {
                return $situationData;
            } 
        }

        return $result;
    }

    // public static function getAllSituations2()
    // {
    //     return self::BUILT_IN_SITUATIONS;
    // }
}