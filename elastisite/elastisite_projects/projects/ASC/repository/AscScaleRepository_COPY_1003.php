<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\DbRepository;
use projects\ASC\entity\AscScale;

class AscScaleRepository extends DbRepository 
{
    public function isDeletable($id)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT count(a.id) as 'bound_count'
        FROM asc_scale a
        INNER JOIN asc_unit b ON b.asc_scale_id = a.id 
        WHERE a.id = :id
        ";
        $result = $dbm->findOne($stm, ['id' => $id])['bound_count'];
        return (int)$result == 0 ? true : false;
    }

    // public function getTeamScales(int $projectUserId = null) : array
    // {
    //     if (!$projectUserId) {
    //         return [];
    //     }

    //     $dbm = $this->getDbManager();
    //     $stm = "SELECT 
    //         a.id as asc_scale_id
    //     FROM asc_scale a
    //     INNER JOIN project_team pt ON pt.asc_scale_id = a.id 
    //     INNER JOIN project_team_user ptu ON ptu.project_team_id = pt.id 
    //     INNER JOIN project_user pu ON pu.id = ptu.project_user_id 
    //     WHERE pu.id = :project_user_id
    //     ";
    //     $ids = $dbm->findAll($stm, [
    //         'project_user_id' => $projectUserId
    //     ]);

    //     $result = [];
    //     foreach ($ids as $idRow) {
    //         $result[] = $this->find($idRow['asc_scale_id']);
    //     }

    //     return $result;
    // }

    // public function getScaleAndUnitAccessibility(int $ascScaleId, int $projectUserId, int $ascUnitId = null) : array
    // {
    //     if (!$projectUserId) {
    //         return [];
    //     }

    //     $dbm = $this->getDbManager();

    //     $params = [];
    //     $stm = "SELECT 
    //         pt.id as project_team_id,
    //         pt.name as project_team_name
    //     FROM asc_scale a 
    //     ".($ascUnitId ? "INNER JOIN asc_unit au ON au.asc_scale_id = a.id" : "")."
    //     INNER JOIN project_team pt ON pt.asc_scale_id = a.id
    //     INNER JOIN project_team_user ptu ON ptu.project_team_id = pt.id 
    //     INNER JOIN project_user pu ON pu.id = ptu.project_user_id 
    //     WHERE a.id = :asc_scale_id 
    //     ".($ascUnitId ? "AND au.id = :asc_unit_id " : "")."
    //     AND pu.id = :project_user_id ";

    //     $params['asc_scale_id'] = $ascScaleId;

    //     if ($ascUnitId) {
    //         $params['asc_unit_id'] = $ascUnitId;
    //     }

    //     $params['project_user_id'] = $projectUserId;

    //     $ids = $dbm->findAll($stm, $params);

    //     $result = [];
    //     foreach ($ids as $idRow) {
    //         $result[] = $this->find($idRow['asc_scale_id']);
    //     }

    //     return $result;
    // }

    public function getAccessibility(int $ascScaleId, int $projectUserId, int $ascUnitId = null) : array
    {
        $scaleAccessibility = $this->getScaleAndUnitAccessibility($ascScaleId, $projectUserId, $ascUnitId);

        $unitIsAccessible = false;
        if (in_array($ascUnitId, $scaleAccessibility['accessibleUnits'])) {
            $unitIsAccessible = true;
        }

        return [
            'scaleIsFullyAccessible' => $scaleAccessibility['scaleIsFullyAccessible'],
            'scaleIsPartiallyAccessible' => $scaleAccessibility['scaleIsPartiallyAccessible'],
            'unitIsAccessible' => $unitIsAccessible,
            'permissionOverrides' => $permissionOverrides
        ];
    }

    /**
     * Fully or partially.
     * This is required, because 
    */
    public function getScaleAndUnitAccessibility(int $ascScaleId, int $projectUserId = null, $ascUnitId = null) : array
    {
        if (!$projectUserId) {
            return [];
        }

        $dbm = $this->getDbManager();

        $fullyAccessible = false;
        $partiallyAccessible = false;

        $params = [];
        $stm = "SELECT 
            a.id as asc_scale_id,
            au.id as asc_unit_id, 
            pt.children_included as children_included
        FROM asc_scale a 
        LEFT JOIN asc_unit au ON au.asc_scale_id = a.id
        INNER JOIN project_team pt ON pt.asc_scale_id = a.id
        INNER JOIN project_team_user ptu ON ptu.project_team_id = pt.id 
        INNER JOIN project_user pu ON pu.id = ptu.project_user_id 
        WHERE a.id = :asc_scale_id 
        AND pu.id = :project_user_id ";

        $params['asc_scale_id'] = $ascScaleId;
        $params['project_user_id'] = $projectUserId;

        $foundRecords = $dbm->findAll($stm, $params);

        // $result = [];
        $sharedUnits = [];
        foreach ($foundRecords as $foundRecordRow) {
            /**
             * If we have at least one row, than the scale is partially accessible for sure.
            */
            $partiallyAccessible = true;
            if ($foundRecordRow['asc_unit_id']) {
                $sharedUnits[] = $foundRecordRow['asc_unit_id'];
            }
            if (count($foundRecords) == 1 && !$foundRecordRow['asc_unit_id']) {
                /**
                 * If we have one team for this scale, and no unitId, than we have access for the entire scale.
                */
                $fullyAccessible = true;
            }
        }

        /**
         * If we have only partially access, than we have to find all the units we have access for.
        */
        $accessibleUnits = [];
        $permissionOverrides = [];
        if ($partiallyAccessible && !$fullyAccessible) {
            $accessibleUnits = self::getAccessibleUnits($ascScaleId, $projectUserId, $sharedUnits);
            $permissionOverrides = self::getPermissionOverrides($accessibleUnits, $projectUserId);
        }

        return [
            'scaleIsFullyAccessible' => $fullyAccessible,
            'scaleIsPartiallyAccessible' => $partiallyAccessible,
            'accessibleUnits' => $accessibleUnits,
            'permissionOverrides' => $permissionOverrides
        ];
    }

    /**
     * Shared units: units which are directly shared to the user.
     * Accessible units: the siblings of the shared units, if 
    */
    public function getAccessibleUnits(int $ascScaleId, int $projectUserId = null, array $sharedUnits = []) : array
    {
        if (!$projectUserId || empty($sharedUnits)) {
            return [];
        }
    
        $dbm = $this->getDbManager();
    
        $params = [];
        $accessibleUnits = [];
    
        // Rekurzív függvény a gyermek egységek gyűjtéséhez
        function collectChildUnits($dbm, $ascScaleId, &$accessibleUnits, &$params, $sharedUnits) {
            $placeholders = implode(',', array_map(function($index) use (&$params, $sharedUnits) {
                $paramName = ":shared_unit{$index}";
                $params[$paramName] = $sharedUnits[$index];
                return $paramName;
            }, array_keys($sharedUnits)));

            $stm = "SELECT DISTINCT au.id as asc_unit_id
                    FROM asc_unit au
                    WHERE au.asc_scale_id = :asc_scale_id
                    AND au.parent IN (".$placeholders.")";
    
            $params['asc_scale_id'] = $ascScaleId;
            $params['shared_units'] = $sharedUnits;
    
            $childUnits = $dbm->findAll($stm, $params);
    
            foreach ($childUnits as $childUnit) {
                $accessibleUnits[] = $childUnit['asc_unit_id'];

                // Rekurzív hívás a gyermek egységek gyermekeinek gyűjtéséhez
                collectChildUnits($dbm, $ascScaleId, $accessibleUnits, $params, [$childUnit['asc_unit_id']]);
            }
        }
    
        collectChildUnits($dbm, $ascScaleId, $accessibleUnits, $params, $sharedUnits);
    
        return $accessibleUnits;
    }    

    public function getPermissionOverrides(array $ascUnitIds, int $projectUserId = null) : array
    {
        if (!$projectUserId) {
            return [];
        }

        $params = [];
        $ascUnitIdPlaceholders = implode(',', array_map(function($index) use (&$params, $ascUnitIds) {
            $paramName = ":asc_unit_id{$index}";
            $params[$paramName] = $ascUnitIds[$index];
            return $paramName;
        }, array_keys($ascUnitIds)));

        $dbm = $this->getDbManager();
        $stm = "SELECT 
            au.asc_scale_id as asc_scale_id, 
            au.id as asc_unit_id, 
            ptpo.permission_name as permission_name,
            ptpo.new_value as new_value
        FROM asc_unit au 
        -- INNER JOIN asc_scale a ON a.id = au.asc_scale_id
        INNER JOIN project_team pt ON pt.asc_scale_id = au.asc_scale_id
        INNER JOIN project_team_user ptu ON ptu.project_team_id = pt.id 
        INNER JOIN project_user pu ON pu.id = ptu.project_user_id 
        LEFT JOIN project_team_user_unit_permission_override ptuupo ON ptuupo.project_team_user_id = ptu.id AND ptuupo.asc_unit_id IN (".$ascUnitIdPlaceholders.")
        WHERE pu.id = :project_user_id 
        ";

        $params['project_user_id'] = $projectUserId;

        $result = $dbm->findAll($stm, $params);

        return $result;
    }

    // public function isUserInScaleTeam(AscScale $ascScale, int $projectUserId = null) : bool
    // {
    //     $teamScales = $this->getTeamScales($projectUserId);
    //     foreach ($teamScales as $teamScale) {
    //         if ($teamScale->getId() == $ascScale->getId()) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    public function getProjectTeamworkData(AscScale $ascScale) : array
    {
        dump($ascScale);//exit;
        $dbm = $this->getDbManager();
        $stm = "SELECT 
            pu.id as project_user_id
        FROM asc_scale a
        INNER JOIN project_team pt ON pt.asc_scale_id = a.id 
        INNER JOIN project_team_user ptu ON ptu.project_team_id = pt.id 
        INNER JOIN project_user pu ON pu.id = ptu.project_user_id 
        WHERE a.id = :asc_scale_id 
        ";
        $ids = $dbm->findAll($stm, [
            'asc_scale_id' => $ascScale->getId()
        ]);

        dump($stm);exit;

        $result = [];
        App::getContainer()->wireService('projects/ASC/repository/ProjectUserRepository');
        $projectUserRepo = new ProjectUserRepository();
        foreach ($ids as $idRow) {
            $projectUser = $projectUserRepo->find($idRow['project_user_id']);
            if ($projectUser->getUserAccount() && $projectUser->getUserAccount()->getPerson()) {
                $result[] = [
                    // 'userAccount' => $projectUser->getUserAccount(),
                    'projectUser' => $projectUser,
                    'name' => $projectUser->getUserAccount()->getPerson()->getFullName(),
                    'email' => $projectUser->getUserAccount()->getPerson()->getEmail(),
                ];
            }
            // $result[] = $projectUserRepo->find($idRow['project_user_id']);
        }

        return $result;
    }

    public function getScaleTeamUnconfirmedInviteData(AscScale $ascScale) : array
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT 
            pti.id as project_team_invite_id
        FROM asc_scale a
        INNER JOIN project_team pt ON pt.asc_scale_id = a.id 
        INNER JOIN project_team_invite pti ON pti.project_team_id = pt.id 
        LEFT JOIN project_team_user ptu ON ptu.project_team_id = pt.id 
        LEFT JOIN project_user pu ON pu.id = ptu.project_user_id 
        WHERE a.id = :asc_scale_id 
        AND pu.id IS NULL
        ";
        $ids = $dbm->findAll($stm, [
            'asc_scale_id' => $ascScale->getId()
        ]);

        $result = [];
        App::getContainer()->wireService('projects/ASC/repository/ProjectTeamInviteRepository');
        $projectTeamInviteRepo = new ProjectTeamInviteRepository();
        foreach ($ids as $idRow) {
            $projectTeamInvite = $projectTeamInviteRepo->find($idRow['project_team_invite_id']);
            $result[] = [
                'name' => $projectTeamInvite->getProjectUserFullName(),
                'email' => $projectTeamInvite->getProjectUserEmail(),
                'createdAt' => $projectTeamInvite->getCreatedAt()
            ];
        }

        return $result;
    }
}