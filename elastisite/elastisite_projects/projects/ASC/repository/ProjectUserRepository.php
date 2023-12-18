<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\DbRepository;

class ProjectUserRepository extends DbRepository
{
    // public function getProjectUserSelectOptions()
    // {
    //     $dbm = $this->getDbManager();
    //     $stm = "SELECT 
    //                 pt.id as project_team_id,
    //                 pt.name as project_team_name,
    //                 pu.id as project_user_id,
    //                 a.user_account_id as scale_owner_user_account_id
    //             FROM project_user pu 
    //             INNER JOIN user_account ua ON ua.id = pu.user_account_id 
    //             INNER JOIN  ptu ON ptu.project_team_id = pt.id 
    //             LEFT JOIN project_user pu ON pu.id = ptu.project_user_id AND pu.id = :project_user_id
    //             WHERE a.id = :asc_scale_id 
    //     ";
    //     $searchResult = $dbm->findAll($stm, [
    //         'asc_scale_id' => $ascScale->getId(),
    //         'project_user_id' => $projectUserId,
    //     ]);

    //     $result = [];
    //     // App::getContainer()->wireService('projects/ASC/repository/ProjectUserRepository');
    //     // $projectUserRepo = new ProjectUserRepository();
    //     foreach ($searchResult as $searchResultRow) {
    //         // $projectTeam = $this->find($searchResultRow['project_team_id']);
    //         $row = [
    //             'projectTeamId' => $searchResultRow['project_team_id'],
    //             'projectTeamName' => $searchResultRow['project_team_name'],
    //             'projectUserId' => $searchResultRow['project_user_id'],
    //             'scaleOwnerUserAccountId' => $searchResultRow['scale_owner_user_account_id']
    //         ];
    //         $ownership = $row['scaleOwnerUserAccountId'] == $currentUserAccountId;
    //         $teamMembeship = $row['projectUserId'] == $projectUserId;
    //         if ($ownership || $teamMembeship) {
    //             $row['ownership'] = $ownership;
    //             $row['teamMembeship'] = $teamMembeship;
    //             $result[] = $row;
    //         }
    //     }
    // }
}