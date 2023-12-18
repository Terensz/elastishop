<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\DbRepository;
use projects\ASC\entity\AscScale;

class ProjectTeamRepository extends DbRepository
{
    public function findScaleTeam($ascScale)
    {
        $this->findOneBy([
            'conditions' => [
                ['key' => 'asc_scale_id', 'value' => $ascScale->getId()],
            ]
        ]);
    }

    public function getProjectTeamData(AscScale $ascScale, $projectUserId) : array
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        $currentUserAccount = App::getContainer()->getUser()->getUserAccount();
        $currentUserAccountId = $currentUserAccount ? $currentUserAccount->getId() : null;
        // dump($ascScale);//exit;
        $dbm = $this->getDbManager();
        $stm = "SELECT 
                    pt.id as project_team_id,
                    pt.name as project_team_name,
                    pu.id as project_user_id,
                    a.user_account_id as scale_owner_user_account_id
                FROM asc_scale a 
                INNER JOIN project_team pt ON pt.asc_scale_id = a.id 
                LEFT JOIN project_team_user ptu ON ptu.project_team_id = pt.id 
                LEFT JOIN project_user pu ON pu.id = ptu.project_user_id AND pu.id = :project_user_id
                WHERE a.id = :asc_scale_id 
        ";
        $searchResult = $dbm->findAll($stm, [
            'asc_scale_id' => $ascScale->getId(),
            'project_user_id' => $projectUserId,
        ]);

        $result = [];
        // App::getContainer()->wireService('projects/ASC/repository/ProjectUserRepository');
        // $projectUserRepo = new ProjectUserRepository();
        foreach ($searchResult as $searchResultRow) {
            // $projectTeam = $this->find($searchResultRow['project_team_id']);
            $row = [
                'projectTeamId' => $searchResultRow['project_team_id'],
                'projectTeamName' => $searchResultRow['project_team_name'],
                'projectUserId' => $searchResultRow['project_user_id'],
                'scaleOwnerUserAccountId' => $searchResultRow['scale_owner_user_account_id']
            ];
            $ownership = $row['scaleOwnerUserAccountId'] == $currentUserAccountId;
            $teamMembeship = $row['projectUserId'] == $projectUserId;
            if ($ownership || $teamMembeship) {
                $row['ownership'] = $ownership;
                $row['teamMembeship'] = $teamMembeship;
                $result[] = $row;
            }
        }

        // dump($result);exit;

        return $result;
    }

    public function getProjectUserData()
    {
        /**
         * @todo 
        */
        $ids = [];



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