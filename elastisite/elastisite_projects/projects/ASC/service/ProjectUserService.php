<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\entity\ProjectUser;
use projects\ASC\repository\ProjectUserRepository;

class ProjectUserService extends Service
{
    public static $projectUser;

    public static function getProjectUser($alternativeUserAccount = null)
    {
        // dump(App::getContainer()->getUser());
        if (!App::getContainer()->getUser()) {
            // dump(App::getContainer()->getUser());
            return null;
        }
        $userAccount = $alternativeUserAccount ? : App::getContainer()->getUser()->getUserAccount();
        if (!$userAccount) {
            return null;
        }
        if (self::$projectUser) {
            return self::$projectUser;
        }

        App::getContainer()->wireService('projects/ASC/repository/ProjectUserRepository');
        $repo = new ProjectUserRepository();
        $projectUser = $repo->findOneBy([
            'conditions' => [
                ['key' => 'user_account_id', 'value' => $userAccount->getId()],
            ]
        ]);

        if (!$projectUser) {
            $projectUser = self::createBlankProjectUser();
            // dump($projectUser);
        }
        self::$projectUser = $projectUser;
        // dump($projectUser);exit;

        return $projectUser;
    }

    public static function getProjectUserSelectOptions($exceptCurrentUser = true)
    {
        $currentUserAccount = App::getContainer()->getUser()->getUserAccount();
        $currentUserAccountId = $currentUserAccount ? $currentUserAccount->getId() : null;
        // $currentPerson = $currentUserAccount ? $currentUserAccount->getPerson() : null;

        App::getContainer()->wireService('projects/ASC/repository/ProjectUserRepository');
        $repo = new ProjectUserRepository();
        $projectUsers = $repo->findAll();
        $return = [];
        foreach ($projectUsers as $projectUser) {
            $personUsername = '';
            $personFullName = '';
            $personEmail = '';
            if ($projectUser->getUserAccount() && $projectUser->getUserAccount()->getPerson()) {
                $personUsername = $projectUser->getUserAccount()->getPerson()->getUsername();
                $personFullName = $projectUser->getUserAccount()->getPerson()->getFullName();
                $personEmail = $projectUser->getUserAccount()->getPerson()->getEmail();
            }
            if (!$exceptCurrentUser || ($exceptCurrentUser && $projectUser->getUserAccount() && $projectUser->getUserAccount()->getId() != $currentUserAccountId)) {
                $return[] = [
                    'projectUserId' => $projectUser->getId(),
                    'personUsername' => $personUsername,
                    'personFullName' => $personFullName,
                    'personEmail' => $personEmail,
                    'personString' => $personFullName ? ($personFullName.($personEmail ? ' ('.$personUsername.' - '.$personEmail.')' : '')) : ''
                ];
            }
        }

        // return $repo->getProjectUserSelectOptions();
        return $return;
    }

    public static function assignProjectUser()
    {

    }

    public static function createBlankProjectUser($alternativeUserAccount = null)
    {
        $userAccount = $alternativeUserAccount ? : App::getContainer()->getUser()->getUserAccount();

        App::getContainer()->wireService('projects/ASC/entity/ProjectUser');
        $projectUser = new ProjectUser();
        $projectUser->setUserAccount($userAccount);
        $projectUser = $projectUser->getRepository()->store($projectUser);

        return $projectUser;
    }
}