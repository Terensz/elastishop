<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;
use projects\ASC\entity\ProjectUser;

class ProjectTeamInviteRepository extends DbRepository
{
    // ProjectUser $projectUser
    public function createInviteToken() {
        App::getContainer()->wireService('projects/ASC/entity/ProjectUser');
        $inviteToken = BasicUtils::generateRandomString(32);
        $isInviteTokenOccupied = $this->isInviteTokenOccupied($inviteToken);

        return $isInviteTokenOccupied ? $this->createInviteToken() : $inviteToken;
    }

    public function isInviteTokenOccupied($inviteToken)
    {
        $stm = "SELECT id FROM project_team_invite WHERE invite_token = :invite_token ";
        $params = array(':invite_token' => $inviteToken);
        $dbm = $this->getDbManager();
        $ret = $dbm->findOne($stm, $params);
        return empty($ret) ? false : true;
    }
}