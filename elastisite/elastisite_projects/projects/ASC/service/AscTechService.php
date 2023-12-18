<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;

class AscTechService extends Service
{
    const PRIMARY_SUBJECT = 'Primary';
    const SECONDARY_SUBJECT = 'Secondary';
    const TECHNICAL_SUBJECT = 'Technical';

    const SCALE = 'Scale';

    const SUBJECT_GOAL = 'Goal';
    const SUBJECT_PURPOSE = 'Purpose';
    const SUBJECT_POLICY = 'Policy';
    const SUBJECT_PLAN = 'Plan';
    const SUBJECT_IDEAL_SCENE = 'IdealScene';
    const SUBJECT_REAL_SCENE = 'RealScene';
    const SUBJECT_STAT = 'Stat';
    const SUBJECT_VALUABLE_FINAL_PRODUCT = 'ValuableFinalProduct';
    const SUBJECT_PROGRAM = 'Program';
    const SUBJECT_PROJECT = 'Project';
    const SUBJECT_TASK = 'Task';
    const SUBJECT_TARGET = 'Target';
    const SUBJECT_ORDER = 'Order';

    const SUBJECT_GOALS = 'Goals';
    const SUBJECT_PURPOSES = 'Purposes';
    const SUBJECT_POLICIES = 'Policies';
    const SUBJECT_PLANS = 'Plans';
    const SUBJECT_IDEAL_SCENES = 'IdealScenes';
    const SUBJECT_REAL_SCENES = 'RealScenes';
    const SUBJECT_STATISTICS = 'Statistics';
    const SUBJECT_VALUABLE_FINAL_PRODUCTS = 'ValuableFinalProducts';
    const SUBJECT_PROGRAMS = 'Programs';
    const SUBJECT_PROJECTS = 'Projects';
    const SUBJECT_TASKS = 'Tasks';
    const SUBJECT_TARGETS = 'Targets';
    const SUBJECT_ORDERS = 'Orders';

    // const SUBJECT_SINGULAR_GOALS = 'Goal';
    // const SUBJECT_SINGULAR_PURPOSES = 'Purpose';
    // const SUBJECT_SINGULAR_POLICIES = 'Policy';
    // const SUBJECT_SINGULAR_PLANS = 'Plan';
    // const SUBJECT_SINGULAR_IDEAL_SCENES = 'IdealScene';
    // const SUBJECT_SINGULAR_REAL_SCENES = 'RealScene';
    // const SUBJECT_SINGULAR_STATISTICS = 'Stats';
    // const SUBJECT_SINGULAR_VALUABLE_FINAL_PRODUCTS = 'ValuableFinalProduct';
    // const SUBJECT_SINGULAR_PROGRAMS = 'Program';
    // const SUBJECT_SINGULAR_PROJECTS = 'Project';

    const SUBJECT_SINGULAR_TRANS_REF_GOAL = 'goal';
    const SUBJECT_SINGULAR_TRANS_REF_PURPOSE = 'purpose';
    const SUBJECT_SINGULAR_TRANS_REF_POLICY = 'policy';
    const SUBJECT_SINGULAR_TRANS_REF_PLAN = 'plan';
    const SUBJECT_SINGULAR_TRANS_REF_IDEAL_SCENE = 'idealscene';
    const SUBJECT_SINGULAR_TRANS_REF_REAL_SCENE = 'realscene';
    const SUBJECT_SINGULAR_TRANS_REF_STAT = 'stat';
    const SUBJECT_SINGULAR_TRANS_REF_VALUABLE_FINAL_PRODUCT = 'valuable.final.product';
    const SUBJECT_SINGULAR_TRANS_REF_PROGRAM = 'program';
    const SUBJECT_SINGULAR_TRANS_REF_PROJECT = 'project';
    const SUBJECT_SINGULAR_TRANS_REF_TASK = 'task';
    const SUBJECT_SINGULAR_TRANS_REF_TARGET = 'target';
    const SUBJECT_SINGULAR_TRANS_REF_ORDER = 'order';

    const SUBJECT_PLURAL_TRANS_REF_GOAL = 'goals';
    const SUBJECT_PLURAL_TRANS_REF_PURPOSE = 'purposes';
    const SUBJECT_PLURAL_TRANS_REF_POLICY = 'policies';
    const SUBJECT_PLURAL_TRANS_REF_PLAN = 'plans';
    const SUBJECT_PLURAL_TRANS_REF_IDEAL_SCENE = 'idealscenes';
    const SUBJECT_PLURAL_TRANS_REF_REAL_SCENE = 'realscenes';
    const SUBJECT_PLURAL_TRANS_REF_STAT = 'statistics';
    const SUBJECT_PLURAL_TRANS_REF_VALUABLE_FINAL_PRODUCT = 'valuable.final.products';
    const SUBJECT_PLURAL_TRANS_REF_PROGRAM = 'programs';
    const SUBJECT_PLURAL_TRANS_REF_PROJECT = 'projects';
    const SUBJECT_PLURAL_TRANS_REF_TASK = 'tasks';
    const SUBJECT_PLURAL_TRANS_REF_TARGET = 'targets';
    const SUBJECT_PLURAL_TRANS_REF_ORDER = 'orders';

    const SUBJECT_CONFIG = [
        self::SUBJECT_GOAL => [
            'singularRefName' => self::SUBJECT_GOAL,
            'pluralRefName' => self::SUBJECT_GOALS,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_GOAL,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_GOAL,
            'childOf' => self::SUBJECT_IDEAL_SCENE,
            'draggable' => true,
            'iconRefName' => 'flag-fill'
        ],
        self::SUBJECT_PURPOSE => [
            'singularRefName' => self::SUBJECT_PURPOSE,
            'pluralRefName' => self::SUBJECT_PURPOSES,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_PURPOSE,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_PURPOSE,
            'childOf' => self::SUBJECT_GOAL,
            'draggable' => true,
            'iconRefName' => 'flag'
        ],
        self::SUBJECT_POLICY => [
            'singularRefName' => self::SUBJECT_POLICY,
            'pluralRefName' => self::SUBJECT_POLICIES,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_POLICY,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_POLICY,
            'childOf' => null,
            'draggable' => true,
            'iconRefName' => 'book'
        ],
        self::SUBJECT_PLAN => [
            'singularRefName' => self::SUBJECT_PLAN,
            'pluralRefName' => self::SUBJECT_PLANS,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_PLAN,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_PLAN,
            'childOf' => self::SUBJECT_PURPOSE,
            'draggable' => false,
            'iconRefName' => 'diagram-3'
        ],
        self::SUBJECT_IDEAL_SCENE => [
            'singularRefName' => self::SUBJECT_IDEAL_SCENE,
            'pluralRefName' => self::SUBJECT_IDEAL_SCENES,
            'numberOfUnitPanels' => 2,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_IDEAL_SCENE,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_IDEAL_SCENE,
            'childOf' => self::SUBJECT_REAL_SCENE,
            'draggable' => true,
            'iconRefName' => 'emoji-sunglasses'
        ],
        self::SUBJECT_REAL_SCENE => [
            'singularRefName' => self::SUBJECT_REAL_SCENE,
            'pluralRefName' => self::SUBJECT_REAL_SCENES,
            'type' => self::PRIMARY_SUBJECT,
            // 'type' => self::TECHNICAL_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_REAL_SCENE,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_REAL_SCENE,
            'parentSubject' => null,
            'childOf' => null,
            'draggable' => true,
            'iconRefName' => 'emoji-neutral'
        ],
        self::SUBJECT_STAT => [
            'singularRefName' => self::SUBJECT_STAT,
            'plural' => self::SUBJECT_STATISTICS,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_STAT,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_STAT,
            'childOf' => null,
            'draggable' => false,
            'iconRefName' => 'bar-chart-line'
        ],
        self::SUBJECT_VALUABLE_FINAL_PRODUCT => [
            'singularRefName' => self::SUBJECT_VALUABLE_FINAL_PRODUCT,
            'plural' => self::SUBJECT_VALUABLE_FINAL_PRODUCTS,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_VALUABLE_FINAL_PRODUCT,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_VALUABLE_FINAL_PRODUCT,
            'childOf' => null,
            'draggable' => true,
            'iconRefName' => 'trophy'
        ],
        self::SUBJECT_PROGRAM => [
            'singularRefName' => self::SUBJECT_PROGRAM,
            'pluralRefName' => self::SUBJECT_PROGRAMS,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_PROGRAM,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_PROGRAM,
            'childOf' => self::SUBJECT_PLAN,
            'draggable' => true,
            'iconRefName' => 'list-check'
        ],
        self::SUBJECT_PROJECT => [
            'singularRefName' => self::SUBJECT_PROJECT,
            'pluralRefName' => self::SUBJECT_PROJECTS,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_PROJECT,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_PROJECT,
            'childOf' => self::SUBJECT_PROGRAM,
            'draggable' => true,
            'iconRefName' => 'check-all'
        ],
        self::SUBJECT_TASK => [
            'singularRefName' => self::SUBJECT_TASK,
            'pluralRefName' => self::SUBJECT_TASKS,
            'numberOfUnitPanels' => 1,
            'type' => self::PRIMARY_SUBJECT,
            'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_TASK,
            'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_TASK,
            'childOf' => self::SUBJECT_PROJECT,
            'draggable' => true,
            'iconRefName' => 'check2-square'
        ],
        // self::SUBJECT_TARGET => [
        //     'singularRefName' => self::SUBJECT_TARGET,
        //     'pluralRefName' => self::SUBJECT_TARGETS,
        //     'numberOfUnitPanels' => 1,
        //     'type' => self::TECHNICAL_SUBJECT,
        //     'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_TARGET,
        //     'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_TARGET,
        //     'childOf' => self::SUBJECT_PROJECT,
        //     'draggable' => true
        // ],
        // self::SUBJECT_ORDER => [
        //     'singularRefName' => self::SUBJECT_ORDER,
        //     'pluralRefName' => self::SUBJECT_ORDERS,
        //     'numberOfUnitPanels' => 1,
        //     'type' => self::TECHNICAL_SUBJECT,
        //     'translationReferenceSingular' => self::SUBJECT_SINGULAR_TRANS_REF_ORDER,
        //     'translationReferencePlural' => self::SUBJECT_PLURAL_TRANS_REF_ORDER,
        //     'childOf' => self::SUBJECT_PROJECT,
        //     'draggable' => true
        // ],
    ];

    public static function getSubjectOptionArray() : array
    {
        $return = [];
        foreach (self::SUBJECT_CONFIG as $subjectKey => $subjectConfigRow) {
            $return[$subjectKey] = trans($subjectConfigRow['translationReferenceSingular']);
        }

        return $return;
    }

    public static function issetMutatableToSubject($string)
    {
        return in_array($string, [self::SUBJECT_PROGRAM, self::SUBJECT_PROJECT, self::SUBJECT_TARGET]);
    }

    public static function issetSubject($searchedSubject)
    {
        $result = false;
        if (isset(self::SUBJECT_CONFIG[$searchedSubject])) {
            $result = true;
        }

        return $result;
    }

    public static function issetPrimarySubject($searchedSubject)
    {
        $result = false;
        if (isset(self::SUBJECT_CONFIG[$searchedSubject]) && self::SUBJECT_CONFIG[$searchedSubject]['type'] == self::PRIMARY_SUBJECT) {
            $result = true;
        }

        return $result;
    }

    public static function findSubjectConfigValue($subject, $key)
    {
        return isset(self::SUBJECT_CONFIG[$subject][$key]) ? self::SUBJECT_CONFIG[$subject][$key] : null;
        // return self::SUBJECT_CONFIG[$subject][$key];
    }

    public static function getPrimarySubjectConfig() : array
    {
        // App::getContainer()->wireService('projects/ASC/service/AscTechService');
        // $data = AscTechService::SUBJECT_CONFIG;
        // $result = [];
        // foreach ($data as $key => $dataRow) {
        //     if ($dataRow['type'] == AscTechService::PRIMARY_SUBJECT) {
        //         $result[$key] = $dataRow;
        //     }
        // }
        // dump($result);
        return self::getSubjectConfig(null, AscTechService::PRIMARY_SUBJECT);
    }

    public static function getSubjectData(string $subject) : ? array
    {
        return isset(self::SUBJECT_CONFIG[$subject]) ? self::SUBJECT_CONFIG[$subject] : null;
    }

    public static function getSubjectConfig(string $subjectName = null, $type = null) : array
    {
        // App::getContainer()->wireService('projects/ASC/service/AscTechService');
        $data = AscTechService::SUBJECT_CONFIG;
        $result = [];
        foreach ($data as $key => $dataRow) {
            if (($subjectName && $subjectName == $key) || (!$subjectName && (!$type || ($type && $dataRow['type'] == $type)))) {
                $result[$key] = $dataRow;
            }
        }
        // dump($result);

        return $result;
    }

    public static function getPrimarySubjectNames() : array
    {
        App::getContainer()->wireService('projects/ASC/service/AscTechService');

        return array_keys(AscTechService::SUBJECT_CONFIG);
    }

    public static function getParentSubjectProperties($searchedSubject)
    {
        $data = AscTechService::SUBJECT_CONFIG;
        foreach ($data as $subject => $dataRow) {
            // $parent = $dataRow['childOf'];
            if ($subject == $searchedSubject) {
                return $dataRow['childOf'] ? $data[$dataRow['childOf']] : null;
            }
        }
        // dump($result);

        return null;
    }

    public static function isPossibleParent($searchedSubject, $checkedParentSubject)
    {
        $parentProperties = self::getParentSubjectProperties($searchedSubject);
        
        return $parentProperties['singularRefName'] == $checkedParentSubject ? true : false;
    }

    public static function getChildSubjectData($parentSubject)
    {
        if (!$parentSubject) {
            return null;
        }
        
        $data = AscTechService::SUBJECT_CONFIG;
        foreach ($data as $subject => $dataRow) {
            // $parent = $dataRow['childOf'];
            if (isset($dataRow['childOf']) && $dataRow['childOf'] == $parentSubject) {
                return $dataRow;
            }
        }
        // dump($result);

        return null;
    }

    public static function getParentSubjectData($childSubject)
    {
        if (isset(AscTechService::SUBJECT_CONFIG[$childSubject]) && isset(AscTechService::SUBJECT_CONFIG[$childSubject]['childOf'])) {
            $parentSubject = AscTechService::SUBJECT_CONFIG[$childSubject]['childOf'];
            if ($parentSubject) {
                return AscTechService::SUBJECT_CONFIG[$parentSubject];
            }
        }

        return null;
    }
}
