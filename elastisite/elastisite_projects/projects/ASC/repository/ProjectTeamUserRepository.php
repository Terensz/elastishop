<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\UserPackage\repository\UserAccountRepository;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\ProjectTeamInvite;

class ProjectTeamUserRepository extends DbRepository
{
    public function getProjectTeamUserData(AscScale $ascScale, $projectTeamData) : array
    {
        App::getContainer()->wireService('UserPackage/repository/UserAccountRepository');
        $userAccountRepository = new UserAccountRepository();
        App::getContainer()->wireService('projects/ASC/entity/ProjectTeamInvite');
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        $currentUserAccount = App::getContainer()->getUser()->getUserAccount();
        $currentUserAccountId = $currentUserAccount ? $currentUserAccount->getId() : null;

        $dbm = $this->getDbManager();

        // dump($projectTeamData);
        $projectTeamUserData = [];
        foreach ($projectTeamData as $projectTeamDataRow) {
            $stm = "SELECT 
                        pti.id as project_team_invite_id,
                        -- pu.id as project_user_id 
                        pti.project_user_full_name as project_user_full_name,
                        pti.project_user_email as project_user_email,
                        pti.created_at as project_team_invite_created_at,
                        pti.invite_token_redeemed_at as project_team_invite_redeemed_at,
                        pti.status as project_team_invite_status
                    FROM project_team_invite pti 
                    LEFT JOIN project_team pt ON pt.id = pti.project_team_id
                    LEFT JOIN project_team_user ptu ON ptu.project_team_id = pt.id 
                    LEFT JOIN project_user pu ON pu.id = ptu.project_user_id 
                    WHERE pt.asc_scale_id = :asc_scale_id 
                    AND pt.id = :project_team_id 
                    -- AND pti.status <> :project_team_invite_status_used 
            ";
            $searchResult = $dbm->findAll($stm, [
                'asc_scale_id' => $ascScale->getId(),
                'project_team_id' => $projectTeamDataRow['projectTeamId'],
                // 'project_team_invite_status_used' => ProjectTeamInvite::STATUS_USED 
            ]);

            $projectTeamUserData[$projectTeamDataRow['projectTeamId']]['invites'] = [];

            foreach ($searchResult as $searchResultRow) {
                $inviteStatus = ProjectTeamInvite::STATUS_ACTIVE_TEXT;
                $tokenIsValid = true;
                if ($searchResultRow['project_team_invite_created_at'] == ProjectTeamInvite::STATUS_USED) {
                    $inviteStatus = ProjectTeamInvite::STATUS_USED_TEXT;
                    $tokenIsValid = false;
                } else {
                    $nowObject = new \DateTime();
                    $nowObject->format('Y-m-d H:i:s');
                    $expirationIntervalInMin = ProjectTeamInvite::EXPITATION_INTERVAL_IN_MIN;
                    $inviteCreatedAtObject = new \DateTime($searchResultRow['project_team_invite_created_at']);
                    $elapsedMinutes = $nowObject->getTimestamp() - $inviteCreatedAtObject->getTimestamp();
                    if ($elapsedMinutes > $expirationIntervalInMin) {
                        $inviteStatus = ProjectTeamInvite::STATUS_EXPIRED_TEXT;
                        $tokenIsValid = false;
                    }
                }
                // else {
                //     // A token még érvényes
                //     echo 'A token még érvényes.';
                // }
                
                $projectTeamUserData[$projectTeamDataRow['projectTeamId']]['invites'][] = [
                    'projectTeamInviteId' => $searchResultRow['project_team_invite_id'],
                    'userFullName' => $searchResultRow['project_user_full_name'],
                    'userEmail' => $searchResultRow['project_user_email'],
                    'tokenIsValid' => $tokenIsValid,
                    'inviteStatus' => $inviteStatus
                ];
            }

            $stm = "SELECT 
                        ptu.id as project_team_user_id,
                        pu.id as project_user_id,
                        ua.id as user_account_id
                    FROM project_team_user ptu 
                    LEFT JOIN project_team pt ON ptu.project_team_id = pt.id 
                    LEFT JOIN project_user pu ON pu.id = ptu.project_user_id 
                    INNER JOIN user_account ua ON ua.id = pu.user_account_id
                    WHERE pt.asc_scale_id = :asc_scale_id 
                    AND pt.id = :project_team_id
            ";
            $searchResult = $dbm->findAll($stm, [
                'asc_scale_id' => $ascScale->getId(),
                'project_team_id' => $projectTeamDataRow['projectTeamId']
            ]);

            $projectTeamUserData[$projectTeamDataRow['projectTeamId']]['teamMembers'] = [];

            foreach ($searchResult as $searchResultRow) {
                $userAccount = $userAccountRepository->find($searchResultRow['user_account_id']);
                $userFullName = null;
                $userEmail = null;
                if ($userAccount && $userAccount->getPerson()) {
                    $userFullName = $userAccount->getPerson()->getFullName();
                    $userEmail = $userAccount->getPerson()->getEmail();
                }
                $projectTeamUserData[$projectTeamDataRow['projectTeamId']]['teamMembers'][] = [
                    'projectTeamUserId' => $searchResultRow['project_team_user_id'],
                    'userFullName' => $userFullName,
                    'userEmail' => $userEmail
                ];
            }

            // dump($searchResult);
        }

        return $projectTeamUserData;
    }
}