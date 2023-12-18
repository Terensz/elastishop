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

    const PERMISSION_OVERRIDE_KEY_FOR_ENTIRE_SCALE = '-Scale-';

    const PERMISSION_SCALE_OWNERSHIP = 'ScaleOwner';
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

    const ACTIVE_PERMISSION_KEYS = [
        self::PERMISSION_SCALE_OWNERSHIP,
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

    /**
     * Everything is true for owners, until I don't change it here.
    */
    const DEFAULT_PERMISSIONS_OWNER = [
        self::PERMISSION_SCALE_OWNERSHIP => true,
        self::PERMISSION_VIEW_SCALE => true,
        self::PERMISSION_CREATE_SCALE => true,
        self::PERMISSION_EDIT_SCALE => true,
        self::PERMISSION_DELETE_SCALE => true,
        self::PERMISSION_DEMISE_SCALE => true,
        self::PERMISSION_CREATE_PROJECT_TEAM => true,
        self::PERMISSION_EDIT_PROJECT_TEAM => true,
        self::PERMISSION_INVITE_PROJECT_USER => true,
        self::PERMISSION_REMOVE_PROJECT_USER => true,
        self::PERMISSION_VIEW_UNIT => true,
        self::PERMISSION_CREATE_UNIT => true,
        self::PERMISSION_EDIT_UNIT => true,
        self::PERMISSION_DELETE_UNIT => true,
        self::PERMISSION_DEMISE_UNIT => true,
        self::PERMISSION_MOVE_UNIT => true,
    ];

    /**
     * Applies on "accessible" scales and units.
    */
    const DEFAULT_PERMISSIONS_MEMBER = [
        self::PERMISSION_SCALE_OWNERSHIP => false,
        self::PERMISSION_VIEW_SCALE => true,
        self::PERMISSION_CREATE_SCALE => false,
        self::PERMISSION_EDIT_SCALE => true,
        self::PERMISSION_DELETE_SCALE => false,
        self::PERMISSION_DEMISE_SCALE => false,
        self::PERMISSION_CREATE_PROJECT_TEAM => false,
        self::PERMISSION_EDIT_PROJECT_TEAM => false,
        self::PERMISSION_INVITE_PROJECT_USER => false,
        self::PERMISSION_REMOVE_PROJECT_USER => false,
        self::PERMISSION_VIEW_UNIT => false,
        self::PERMISSION_CREATE_UNIT => false,
        self::PERMISSION_EDIT_UNIT => false,
        self::PERMISSION_DELETE_UNIT => false,
        self::PERMISSION_DEMISE_UNIT => false,
        self::PERMISSION_MOVE_UNIT => false,
    ];

    public static $cache;

    public static function checkScalePermission($permissionName, $ascScale = null, int $projectUserId = null, $ascUnitId = null)
    {
        $accessibility = !$ascScale && !$ascUnitId ? self::getCurrentScaleAccessibility() : self::getScaleAccessibility($ascScale, $projectUserId, $ascUnitId);

        return isset($accessibility['permissions'][$permissionName]) && $accessibility['permissions'][$permissionName] === true;
    }

    public static function getCurrentScaleAccessibility($processedRequestData = null)
    {
        /**
         * The reson you can give $processedRequestData to this method is: the AscRequestService::getProcessedRequestData() calls this method at the end :-)
        */
        if (!$processedRequestData) {
            App::getContainer()->wireService('projects/ASC/service/AscRequestService');
            $processedRequestData = AscRequestService::getProcessedRequestData(true);
            // // dump($processedRequestData);
            // dump($processedRequestData);
            if (!$processedRequestData['ascScale']) {
                throw new \Exception(trans('request.does.not.contain.a.valid.scale'));
                // return false;
            }
        }

        if (!$processedRequestData['ascScale']) {
            throw new \Exception(trans('request.does.not.contain.a.valid.scale'));
            // return false;
        }

        $accessibilityData = self::getScaleAccessibility($processedRequestData['ascScale'], null, ($processedRequestData['ascUnit'] ? $processedRequestData['ascUnit']->getId() : null));

        return $accessibilityData;
    }

    /**
     * This will be the main method to get all the permissions.
     * We need all that data.
    */
    public static function getScaleAccessibility(AscScale $ascScale = null, int $projectUserId = null, int $ascUnitId = null)
    {
        // $errorMessages = [];
        App::getContainer()->wireService('projects/ASC/entity/AscScale');

        if (!$ascScale && !$ascUnitId) {
            throw new \Exception(trans('no.scale.and.unit'));
            // return false;
        }

        /**
         * Getting scale from unitID
         * + Minor security checks
        */
        if (!$ascScale && $ascUnitId) {
            App::getContainer()->wireService('projects/ASC/repository/AscUnitRepository');
            $unitRepo = new AscUnitRepository();
            $ascUnit = $unitRepo->find($ascUnitId);
            if (!$ascUnit) {
                // dump('No ascUnit');
                // return false;
                throw new \Exception(trans('not.existing.unit'));
                // $errorMessages[] = 'not.existing.unit';
            }

            $ascScale = $ascUnit->getAscScale();
            if (!$ascScale) {
                throw new \Exception(trans('unit.has.no.valid.scale'));
                // $errorMessages[] = 'unit.has.no.valid.scale';
                // return false;
            }
        }

        App::getContainer()->wireService('projects/ASC/service/ProjectCache');
        if (isset(ProjectCache::$cache['scaleAccessibility'][$ascScale->getId().'-'.$ascUnitId])) {
            return ProjectCache::$cache['scaleAccessibility'][$ascScale->getId().'-'.$ascUnitId];
        }

        $currentUserAccount = App::getContainer()->getUser() ? App::getContainer()->getUser()->getUserAccount() : null;
        $currentUserAccountId = $currentUserAccount ? $currentUserAccount->getId() : null;

        if (!$projectUserId) {
            App::getContainer()->wireService('projects/ASC/service/ProjectUserService');
            $projectUser = ProjectUserService::getProjectUser();
            $projectUserId = $projectUser->getId();

            /**
             * Almost no chance to get into this, but I made it for security reasons.
            */
            if (!$projectUserId) {
                throw new \Exception(trans('invalid.project.user'));
                // dump('No valid project user.');exit;
                // return false;
            }
        }

        App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
        $scaleRepo = new AscScaleRepository();

        /**
         * If the current user is the scale owner
        */
        // $scaleOwnership = false;
        if ($ascScale->getUserAccount() && $currentUserAccountId == $ascScale->getUserAccount()->getId()) {
            // $scaleOwnership = true;
            $accessibility = [
                'scaleOwnership' => true,
                'scaleIsFullyAccessible' => true,
                'scaleIsPartiallyAccessible' => true,
                'unitIsAccessible' => true,
                'accessibleUnitIds' => null,
                'permissions' => self::DEFAULT_PERMISSIONS_OWNER,
                'permissionsOfAccessibleUnits' => null
            ];
        /**
         * If the current user is an invited member of an actual project team.
        */
        } else {
            // if (isset(ProjectCache::$cache['rawScaleAccessibility'])) {
            //     $rawAccessibility = ProjectCache::$cache['rawScaleAccessibility'];
            // } else {
            //     $rawAccessibility = $scaleRepo->getScaleAccessibility($ascScale->getId(), $projectUserId);
            //     ProjectCache::$cache['rawScaleAccessibility'][$ascScale->getId()] = $rawAccessibility;
            // }

            $rawAccessibility = $scaleRepo->getScaleAccessibility($ascScale->getId(), $projectUserId);

            $unitIsAccessible = false;
            if (in_array($ascUnitId, $rawAccessibility['accessibleUnitIds'])) {
                $unitIsAccessible = true;
            }
    
            /**
             * We want to work separately with the entire scale's permission overrides, so we unset them from the original array.
            */
            $entireScalePermissionOverrides = [];
            $currentUnitPermissionOverrides = [];
            if ($ascUnitId) {
                if (isset($rawAccessibility['permissionOverrides'][self::PERMISSION_OVERRIDE_KEY_FOR_ENTIRE_SCALE])) {
                    $entireScalePermissionOverrides = $rawAccessibility['permissionOverrides'][self::PERMISSION_OVERRIDE_KEY_FOR_ENTIRE_SCALE];
                    unset($rawAccessibility['permissionOverrides'][self::PERMISSION_OVERRIDE_KEY_FOR_ENTIRE_SCALE]);
                }

                if (isset($rawAccessibility['permissionOverrides'][$ascUnitId])) {
                    $currentUnitPermissionOverrides = $rawAccessibility['permissionOverrides'][$ascUnitId];
                }
            }

            // $permissionOverrides = $ascUnitId ? $unitSpecificPermissionOverrides : $rawAccessibility['permissionOverrides'];
    
            // $scalePermissions = [];
            // $unitPermissions = [];
            // dump($ascUnitId);

            /**
             * Ez volt az eredeti elkepzeles, de ez megsem jo.
             * 
            */
            // $permissions = !$ascUnitId ? self::assembleFullyDeniedPermissions() : self::assembleSingleUnitPermissions(
            //     $entireScalePermissionOverrides, 
            //     $currentUnitPermissionOverrides
            // );

            $permissions = self::assembleSingleUnitPermissions(
                $entireScalePermissionOverrides, 
                $currentUnitPermissionOverrides
            );

            /**
             * We use these on the 
            */
            $permissionsOfAccessibleUnits = null;

            if (!isset($rawAccessibility['permissionOverrides'])) {
                $rawAccessibility['permissionOverrides'] = [];
                // dump($rawAccessibility);
            }

            if (!empty($rawAccessibility['accessibleUnitIds'])) {
                $permissionsOfAccessibleUnits = self::assembleMultipleUnitPermissions(
                    $rawAccessibility['accessibleUnitIds'],
                    $entireScalePermissionOverrides, 
                    $rawAccessibility['permissionOverrides']
                );
            }

            $accessibility = [
                'scaleOwnership' => false,
                'scaleIsFullyAccessible' => $rawAccessibility['scaleIsFullyAccessible'],
                'scaleIsPartiallyAccessible' => $rawAccessibility['scaleIsPartiallyAccessible'],
                'unitIsAccessible' => $unitIsAccessible,
                'accessibleUnitIds' => $rawAccessibility['accessibleUnitIds'],
                'permissions' => $permissions,
                'permissionsOfAccessibleUnits' => $permissionsOfAccessibleUnits
                // 'scalePermissions' => $scalePermissions,
                // 'unitPermissions' => $unitPermissions
            ];

            ProjectCache::$cache['scaleAccessibility'][$ascScale->getId().'-'.$ascUnitId] = $accessibility;
        }

        // dump($accessibility);exit;

        return $accessibility;
        // dump($ascScale->getUserAccount());
        // dump(App::getContainer()->getUser());
    }

    public static function assembleFullyDeniedPermissions()
    {
        return [
            self::PERMISSION_SCALE_OWNERSHIP => false,
            self::PERMISSION_VIEW_SCALE => false,
            self::PERMISSION_CREATE_SCALE => false,
            self::PERMISSION_EDIT_SCALE => false,
            self::PERMISSION_DELETE_SCALE => false,
            self::PERMISSION_DEMISE_SCALE => false,
            self::PERMISSION_CREATE_PROJECT_TEAM => false,
            self::PERMISSION_EDIT_PROJECT_TEAM => false,
            self::PERMISSION_INVITE_PROJECT_USER => false,
            self::PERMISSION_REMOVE_PROJECT_USER => false,
            self::PERMISSION_VIEW_UNIT => false,
            self::PERMISSION_CREATE_UNIT => false,
            self::PERMISSION_EDIT_UNIT => false,
            self::PERMISSION_DELETE_UNIT => false,
            self::PERMISSION_DEMISE_UNIT => false,
            self::PERMISSION_MOVE_UNIT => false,
        ];
    }

    public static function assembleSingleUnitPermissions($entireScalePermissionOverrides = [], $unitPermissionOverrides = [])
    {
        $permissions = self::DEFAULT_PERMISSIONS_MEMBER;

        // dump($permissions);

        if (!empty($entireScalePermissionOverrides)) {
            $permissions = self::overridePermissions($permissions, $entireScalePermissionOverrides);
        }
        if (!empty($unitPermissionOverrides)) {
            $permissions = self::overridePermissions($permissions, $unitPermissionOverrides);
        }

        // dump($permissions);

        return $permissions;
    }

    /**
     * This method helps us to assemble permissions for listed units on the scale dashboard page.
    */
    public static function assembleMultipleUnitPermissions($unitIds, $entireScalePermissionOverrides = [], $unitsPermissionOverridesArray = [])
    {
        $permissionsOfUnits = [];
        foreach ($unitIds as $unitId) {
            $permissionsOfUnits[$unitId] = self::DEFAULT_PERMISSIONS_MEMBER;
            if (isset($unitsPermissionOverridesArray[$unitId])) {
                $permissionsOfUnits[$unitId] = self::assembleSingleUnitPermissions($entireScalePermissionOverrides, $unitsPermissionOverridesArray[$unitId]);
            }
        }

        return $permissionsOfUnits;
    }

    private static function overridePermissions($permissions, $permissionOverrides = [])
    {
        foreach ($permissions as $permissionKey => $permissionValue) {
            if (isset($permissionOverrides[$permissionKey])) {
                $permissions[$permissionKey] = $permissionOverrides[$permissionKey];
            }
        }

        return $permissions;
    }
}