<?php
namespace projects\ASC\service;

use App;
use framework\component\parent\Service;
use projects\ASC\entity\AscScale;
use projects\ASC\entity\AscUnit;
use projects\ASC\entity\ProjectTeam;
use projects\ASC\entity\ProjectTeamInvite;
use projects\ASC\entity\ProjectUser;
use projects\ASC\repository\AscScaleRepository;
use projects\ASC\repository\ProjectTeamInviteRepository;
use projects\ASC\repository\ProjectTeamRepository;
use projects\ASC\repository\ProjectTeamUserRepository;
use projects\ASC\repository\ProjectUserRepository;

class ProjectTeamworkService extends Service
{
    public static $projectTeam;

    public static function getRepository() : ProjectTeamRepository
    {
        App::getContainer()->wireService('projects/ASC/repository/ProjectTeamRepository');

        return new ProjectTeamRepository();
    }

    /**
     * @todo
    */
    public static function getTeamMembers()
    {
        return [];
    }

    public static function isTeamMember($userAccountId)
    {
        return false;
    }

    public static function validResponsible($requestedUserAccountId)
    {
        $valid = $requestedUserAccountId == (App::getContainer()->getUser()->getUserAccount()->getId() || self::isTeamMember($requestedUserAccountId)) ? true : false;

        return $valid;
    }

    public static function getProjectTeamworkData(AscScale $ascScale, int $projectUserId, int $ascUnitId = null)
    {
        App::getContainer()->wireService('projects/ASC/entity/AscScale');
        App::getContainer()->wireService('projects/ASC/repository/ProjectTeamRepository');
        $projectTeamRepo = new ProjectTeamRepository();
        $projectTeamData = $projectTeamRepo->getProjectTeamData($ascScale, $projectUserId);

        $projectTeamUserData = [];
        if (count($projectTeamData) > 0) {
            App::getContainer()->wireService('projects/ASC/repository/ProjectTeamUserRepository');
            $projectTeamUserRepo = new ProjectTeamUserRepository();
            $projectTeamUserData = $projectTeamUserRepo->getProjectTeamUserData($ascScale, $projectTeamData);
        }

        return [
            'projectTeamData' => $projectTeamData,
            'projectTeamUserData' => $projectTeamUserData,
            'scaleTeamUnconfirmedInviteData' => $projectTeamRepo->getScaleTeamUnconfirmedInviteData($ascScale)
        ];
    }

    // public static function getScaleProjectTeamUsers(AscScale $ascScale)
    // {
    //     App::getContainer()->wireService('projects/ASC/service/ProjectTeamworkService');
    //     $projectTeam = ProjectTeamworkService::getScaleProjectTeam($ascScale);
    //     App::getContainer()->wireService('projects/ASC/repository/ProjectTeamUserRepository');
    //     $projectTeamUserRepo = new ProjectTeamUserRepository();
    //     $projectTeamUsers = $projectTeamUserRepo->findOneBy([
    //         'conditions' => [
    //             ['key' => 'project_team_id', 'value' => $projectTeam->getId()],
    //         ]
    //     ]);
    //     return $projectTeamUsers;
    // }

    public static function findProjectUserByEmail($projectUserEmail) 
    {
        $projectUser = null;
        App::getContainer()->wireService('projects/ASC/repository/ProjectUserRepository');
        $projectUserRepo = new ProjectUserRepository();
        $allProjectUsers = $projectUserRepo->findAll();
        foreach ($allProjectUsers as $loopProjectUser) {
            $userAccount = $loopProjectUser->getUserAccount();
            if ($userAccount && $userAccount->getPerson()) {
                $email = $userAccount->getPerson()->getEmail();
                if ($email == $projectUserEmail) {
                    $projectUser = $loopProjectUser;
                }
            }
        }

        return $projectUser;
    }

    public static function findProjectTeamInviteByEmail($projectUserEmail) 
    {
        App::getContainer()->wireService('projects/ASC/repository/ProjectTeamInviteRepository');
        $projectTeamInviteRepo = new ProjectTeamInviteRepository();
        $allProjectTeamInvites = $projectTeamInviteRepo->findAll();
        foreach ($allProjectTeamInvites as $loopProjectTeamInvite) {
            $loopEmail = $loopProjectTeamInvite->getProjectUserEmail();
            if ($projectUserEmail && $loopEmail) {
                return true;
            }
        }

        return false;
    }

    public static function getScaleOwnerData(AscScale $ascScale)
    {
        $scaleOwnerProperties = [];
        $userAccount = $ascScale->getUserAccount();
        if (!$userAccount) {
            return [
                'name' => null,
                'email' => null
            ];
        }
        $scaleOwnerProperties = [
            'name' => $userAccount->getPerson() ? $userAccount->getPerson()->getFullName() : null,
            'email' => $userAccount->getPerson() ? $userAccount->getPerson()->getEmail() : null
        ];

        return $scaleOwnerProperties;
    }

    // public static function getScaleTeamUserProperties()
    // {

    // }

    // public static function getScaleProjectTeam(AscScale $ascScale, AscUnit $ascUnit, $forceCreate = true) :? ProjectTeam
    // {
    //     if (self::$projectTeam) {
    //         return self::$projectTeam;
    //     }
    //     // App::getContainer()->wireService('projects/ASC/repository/ProjectTeamRepository');
    //     $repo = self::getRepository();
    //     $projectTeam = $repo->findOneBy([
    //         'conditions' => [
    //             ['key' => 'asc_scale_id', 'value' => $ascScale->getId()],
    //         ]
    //     ]);

    //     if (!$projectTeam && $forceCreate) {
    //         $projectTeam = self::createBlankProjectTeam($ascScale);
    //     }
    //     self::$projectTeam = $projectTeam;

    //     return $projectTeam;
    // }

    // public static function assignProjectUser()
    // {

    // }

    // public static function createBlankProjectTeam(AscScale $ascScale)
    // {
    //     // `id` int(11) NOT NULL AUTO_INCREMENT,
    //     // `asc_scale_id` int(11) DEFAULT NULL,
    //     // `asc_unit_id` int(11) DEFAULT NULL,
    //     // `children_included` int(1) DEFAULT NULL,
    //     // `name` varchar(250) DEFAULT NULL,
    //     // -- `lead_by` int(11) DEFAULT NULL,
    //     // `created_at` datetime DEFAULT NULL,
    //     // `status` int(2) DEFAULT 1,

    //     App::getContainer()->wireService('projects/ASC/entity/ProjectTeam');
    //     $projectTeam = new ProjectTeam();
    //     $projectTeam->setAscScale($ascScale);
    //     // $userAccount = App::getContainer()->getUser()->getUserAccount();
    //     App::getContainer()->wireService('projects/ASC/service/ProjectUserService');
    //     $projectUser = ProjectUserService::getProjectUser();
    //     $projectTeam->setLeadBy($projectUser);
    //     $projectTeam = self::getRepository()->store($projectTeam);

    //     return $projectTeam;
    // }

    // public function createProjectTeamworkView($processedRequestData)
    // {
    //     // $ascScale = AscRequestService::getScaleFromUrl();
    //     $ascScale = $processedRequestData['ascScale'];

    //     if (!$ascScale) {
    //         return null;
    //     }

    //     App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
    //     $ascScaleRepo = new AscScaleRepository();

    //     $viewPath = 'projects/ASC/view/AscScaleBuilder/ScaleDashboard/ScaleDashboard.php';
    //     $view = $this->renderWidget('AscScaleBuilderWidget_ProjectTeamwork', $viewPath, [
    //         'projectTeamworkData' => $ascScaleRepo->getProjectTeamworkData($ascScale),
    //     ]);

    //     return $view;
    // }

    public static function processInviteConfirmation()
    {
        // App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        // $processedRequestData = AscRequestService::getProcessedRequestData();
        App::getContainer()->wireService('projects/ASC/service/ProjectTeamworkService');
        App::getContainer()->wireService('projects/ASC/entity/ProjectTeamInvite');
        App::getContainer()->wireService('projects/ASC/repository/ProjectTeamInviteRepository');
        $projectTeamInviteRepo = new ProjectTeamInviteRepository();

        $success = false;
        $message = null;

        $urlParamChain = App::getContainer()->getUrl()->getParamChain();
        $urlParams = explode('/', $urlParamChain);
        $urlToken = $urlParams[count($urlParams) - 1];
        $urlTokenParts = explode('-', $urlToken);
        if (count($urlTokenParts) != 2) {
            throw new \Exception('Manipulated url!');
        }
        $requestedAscScaleId = $urlTokenParts[0];
        $requestedInviteToken = $urlTokenParts[1];

        $projectTeamInvite = $projectTeamInviteRepo->findOneBy([
            'conditions' => [
                ['key' => 'invite_token', 'value' => $requestedInviteToken]
            ],
        ]);

        $ascScale = null;

        $pageRoute = App::getContainer()->getRouting()->getPageRoute();

        if (!$projectTeamInvite) {
            $message = trans('not.existing.invite');
        } else {
            $currentTimestamp = time(); // Jelenlegi idő UNIX időbélyegként
            $createdAtTimestamp = strtotime($projectTeamInvite->getCreatedAt()); // A token létrehozásának időbélyegként
            $expirationTimestamp = $createdAtTimestamp + (ProjectTeamInvite::EXPITATION_INTERVAL_IN_MIN * 60); // A lejárati idő időbélyegként
            $isExpired = $currentTimestamp > $expirationTimestamp;
            
            if ($isExpired) {
                $message = trans('token.is.expired');
            } else {
                $projectTeam = $projectTeamInvite->getProjectTeam();
                if (!$projectTeam) {
                    $message = trans('corrupted.data');
                } else {
                    $foundAscScale = $projectTeam->getAscScale();
                    if (!$foundAscScale || $foundAscScale->getId() != $requestedAscScaleId) {
                        $message = trans('admin.scale.is.invalid');
                    } else {
                        // dump("juhi");
                        $projectUserEmail = $projectTeamInvite->getProjectUserEmail();
                
                        $projectUser = ProjectTeamworkService::findProjectUserByEmail($projectUserEmail);

                        /**
                         * When the user opens this link for the first time, of course they have no projectUser in the database.
                         * If they come from the asc_inviteUser_registration route, it's okay, if they come from asc_inviteUser_join, they'll be refused.
                        */

                        /**
                         * We can get here following two differenct routes: 
                         * - asc_inviteUser_registration
                         * - asc_inviteUser_join
                         * regarding that the invited person already has a registration or not.
                        */
                        if ($pageRoute && $pageRoute->getName() == 'asc_inviteUser_registration') {
                            if ($projectUser) {
                                $ascScale = $foundAscScale;
                                /**
                                 * In case of reg, we mustn't have registered e-mail
                                */
                                $message = trans('meanwhile.registered');
                            } else {
                                $success = true;
                                $message = 'correct';
                                $ascScale = $foundAscScale;
                            }
                
                        } elseif ($pageRoute && $pageRoute->getName() == 'asc_inviteUser_join') {
                            if (!$projectUser) {
                                $message = trans('manipulated.url');
                            } else {
                                $success = true;
                                $message = 'correct';
                                $ascScale = $foundAscScale;
                            }
                        }
                    }
                }
            }
        }

        return [
            'success' => $success,
            'message' => $message,
            'pageRouteName' => $pageRoute->getName(),
            'ascScale' => $ascScale,
            'projectTeamInvite' => $projectTeamInvite
        ];
    }
}