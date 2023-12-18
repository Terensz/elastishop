<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\repository\AscScaleRepository;
use projects\ASC\repository\AscUnitRepository;

class AscPermissionService extends Service
{
    // const PERMISSION_TYPE_INVITE = 'Invite';
    // const PERMISSION_TYPE_VIEW = 'View';
    // const PERMISSION_TYPE_EDIT = 'Edit';
    // const PERMISSION_TYPE_MOVE = 'Move';
    // const PERMISSION_TYPE_DELETE = 'Delete';
    // const PERMISSION_TYPE_COPY = 'Copy';

    const PERMISSION_VIEW_SCALE = 'ViewScale';
    const PERMISSION_CREATE_SCALE = 'CreateScale';
    const PERMISSION_EDIT_SCALE = 'EditScale';
    const PERMISSION_DELETE_SCALE = 'DeleteScale';
    const PERMISSION_DEMISE_SCALE = 'DemiseScale'; // Atruhaz
    const PERMISSION_CREATE_PROJECT_TEAM = 'CreateProjectTeam';
    const PERMISSION_EDIT_PROJECT_TEAM = 'EditProjectTeam';
    const PERMISSION_INVITE_PROJECT_USER = 'InviteProjectUser';
    const PERMISSION_REMOVE_PROJECT_USER = 'RemoveProjectUser';

    const PERMISSION_VIEW_UNIT = 'ViewUnit';
    const PERMISSION_CREATE_UNIT = 'CreateUnit';
    const PERMISSION_EDIT_UNIT = 'EditUnit';
    const PERMISSION_DELETE_UNIT = 'DeleteUnit';
    const PERMISSION_DEMISE_UNIT = 'DemiseUnit'; // Atruhaz
    const PERMISSION_MOVE_UNIT = 'MoveUnit';

    const ACTIVE_PERMISSIONS = [
        self::PERMISSION_VIEW_SCALE,
        self::PERMISSION_CREATE_SCALE,
        self::PERMISSION_EDIT_SCALE,
        self::PERMISSION_DELETE_SCALE,
        self::PERMISSION_DEMISE_SCALE,
        self::PERMISSION_CREATE_PROJECT_TEAM,
        self::PERMISSION_EDIT_PROJECT_TEAM,
        self::PERMISSION_INVITE_PROJECT_USER,
        self::PERMISSION_REMOVE_PROJECT_USER,
        self::PERMISSION_VIEW_UNIT,
        self::PERMISSION_CREATE_UNIT,
        self::PERMISSION_EDIT_UNIT,
        self::PERMISSION_DELETE_UNIT,
        self::PERMISSION_DEMISE_UNIT,
        self::PERMISSION_MOVE_UNIT
    ];
    
    public static function findAndCheckScale(int $ascScaleId = null)
    {
        $scale = null;
        $viewPermission = false;
        $editPermission = false;
        if ($ascScaleId) {
            App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
            $scaleRepo = new AscScaleRepository();
            $scale = $scaleRepo->find($ascScaleId);
            if ($scale) {
                if (self::checkScalePermission($scale, self::PERMISSION_VIEW_SCALE)) {
                    $viewPermission = true;
                }
                if (self::checkScalePermission($scale, self::PERMISSION_EDIT_SCALE)) {
                    $editPermission = true;
                }
            }
        }

        return [
            'ascScale' => $scale,
            'viewPermission' => $viewPermission,
            'editPermission' => $editPermission
        ];
    }

    public static function getScalePermissions(AscScale $ascScale)
    {
        $isOwned = self::isOwnedScale($ascScale);

        /**
         * @todo !!!
        */
        App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
        $scaleRepo = new AscScaleRepository();
        $onProject = false;
        if ($isOwned) {
            $onProject = true;
        } else {
            // $userAccount = App::getContainer()->getUser();
            App::getContainer()->wireService('projects/ASC/service/ProjectUserService');
            $projectUser = ProjectUserService::getProjectUser();
            if ($projectUser) {
                $isUserInScaleTeam = $scaleRepo->isUserInScaleTeam($ascScale, $projectUser->getId());
                if ($isUserInScaleTeam) {
                    $onProject = true;
                }
            }
        }

        /**
         * @todo !!!
        */
        $accessibilityProperties = self::getScaleAccessibilityProperties($ascScale);

        /**
         * @todo !!!
        */
        $publicityProperties = self::getScalePublicityProperties($ascScale);

        // return self::assemblePermissionsArray(false, $isOwned, $onProject, $accessibilityProperties, $publicityProperties);
        return self::assemblePermissionsArray([
            'forceAllFalse' => false,
            'isOwned' => $isOwned,
            'onProject' => $onProject,
            'accessibilityProperties' => $accessibilityProperties,
            'publicityProperties' => $publicityProperties
        ]);
    }

    public static function getUnitPermissions(AscUnit $ascUnit)
    {
        $scale = $ascUnit->getAscScale();
        if (!$scale) {
            return self::assemblePermissionsArray(true);
        }
        $scalePermissions = self::getScalePermissions($scale);

        return $scalePermissions;
    }

    // public static function assemblePermissionsArray($forceAllFalse = false, $isOwned = null, $onProject = null, $accessibilityProperties = null, $publicityProperties = null)
    public static function assemblePermissionsArray($options = [])
    {
        $forceAllFalse =            isset($options['forceAllFalse']) ? $options['forceAllFalse'] : false;
        $isOwned =                  isset($options['isOwned']) ? $options['isOwned'] : false;
        $onProject =                isset($options['onProject']) ? $options['onProject'] : false;
        $accessibilityProperties =  isset($options['accessibilityProperties']) ? $options['accessibilityProperties'] : null;
        $publicityProperties =      isset($options['publicityProperties']) ? $options['publicityProperties'] : null;
        
        return [
            self::PERMISSION_TYPE_INVITE => $forceAllFalse ? false : ($isOwned || ($onProject && $accessibilityProperties['inviteAllowed'])),
            self::PERMISSION_TYPE_VIEW => $forceAllFalse ? false : ($isOwned || $onProject || (!$onProject && $publicityProperties && isset($publicityProperties['viewable']) && $publicityProperties['viewable'])),
            // self::PERMISSION_TYPE_EDIT => $forceAllFalse ? false : ($isOwned || ($onProject && $accessibilityProperties['editable'])),
            // self::PERMISSION_TYPE_MOVE => $forceAllFalse ? false : ($isOwned || ($onProject && $accessibilityProperties['movable'])),
            self::PERMISSION_TYPE_EDIT => $forceAllFalse ? false : ($isOwned || $onProject),
            self::PERMISSION_TYPE_MOVE => $forceAllFalse ? false : ($isOwned || $onProject),
            // self::PERMISSION_TYPE_DELETE => $forceAllFalse ? false : ($isOwned || ($onProject && $accessibilityProperties['deletable'])),
            self::PERMISSION_TYPE_DELETE => $forceAllFalse ? false : ($isOwned),
            self::PERMISSION_TYPE_COPY => $forceAllFalse ? false : (!$onProject && $publicityProperties && isset($publicityProperties['copyable']) && $publicityProperties['copyable'])
        ];
    }

    public static function checkScalePermission(AscScale $ascScale, $permissionType = self::PERMISSION_TYPE_VIEW)
    {
        $permissions = self::getScalePermissions($ascScale);
        // dump($permissions);

        return $permissions[$permissionType];
    }

    /**
     * We check if the current logged in user is the owner
    */
    public static function isOwnedScale(AscScale $ascScale, $ownerOnly = false)
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');

        return ($ascScale->getUserAccount() && $ascScale->getUserAccount()->getId() == App::getContainer()->getUser()->getUserAccount()->getId()) ? true : false;
    }

    public static function getScaleAccessibilityProperties(AscScale $ascScale)
    {
        // return [
        //     'viewable' => true,
        //     'editable' => true,
        //     'deletable' => true,
        //     'movable' => true,
        // ];
        return [
            'inviteAllowed' => false,
            'viewable' => false,
            'editable' => false,
            'deletable' => false,
            'movable' => false,
        ];
    }

    /**
     * @todo !!!!
     * We check if the scale is public or not
    */
    public static function getScalePublicityProperties(AscScale $ascScale)
    {
        // return [
        //     'viewable' => true,
        //     'copyable' => true,
        // ];
        return [
            'viewable' => false,
            'copyable' => false,
        ];
    }

    /**
     * @todo !!!!
     * We check if the public scale is available or not
    */
    // public static function isAvailableScale(AscScale $ascScale)
    // {
    //     return false;
    // }

    /**
     * @todo !!! Ezt meg kell csinálni rendesen. A csapat-tagsag meg egyeb tenyezok alapjan kalkulal, hogy van-e jogod.
     * @var $permissionType -t is be kell kotni.
    */
    public static function checkUnitPermission(AscUnit $ascUnit, $permissionType = self::PERMISSION_TYPE_VIEW)
    {
        $permissions = self::getUnitPermissions($ascUnit);

        return $permissions[$permissionType];
    }

    /**
     * We check if the current logged in user is the owner
    */
    public static function isOwnedUnit(AscUnit $ascUnit)
    {
        App::getContainer()->wireService('projects/ASC/entity/AscUnit');
        $ascScale = $ascUnit->getAscScale();
        if (!$ascScale) {
            return false;
        }

        return self::isOwnedScale($ascScale);
    }
}