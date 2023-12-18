<?php
namespace framework\packages\UserPackage\repository;

use framework\component\parent\FileBasedStorageRepository;
use framework\kernel\utility\FileHandler;
use framework\packages\UserPackage\entity\FBSUser;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\entity\UserAccount;

class FBSUserRepository extends FileBasedStorageRepository
{
    public function __construct()
    {
        $this->setEncryptFile(true);
        $this->filePath = FileHandler::completePath('projects/_all/file_based_storage/users/FBSUsers.txt', 'dynamic');
    }

    public function createAdmins()
    {
        $this->removeAllObjects();
        $user = new FBSUser();
        $user->setName('Terence');
        $user->setUsername('terenszman');
        $user->setPassword(md5('alma'));
        $user->setPermissionGroups(['systemAdmin']);
        $this->store($user);
    }

    public function selectFBSUser($id)
    {
        return $this->find($id);
    }

    public function findUser($params)
    {
        if (isset($params['id']) && $params['id'] === 0) {
            return false;
        }

        $user = $this->findAndSetFBSUser($params);
        if ($user && $user instanceof User) {
            return $user;
        }

        // $user = $this->findAndSetDatabaseUser($params);
        // if ($user && $user instanceof User) {
        //     return $user;
        // }

        $this->addSystemMessage('login.error', 'error', 'login');

        return false;
    }

    public function findAndSetFBSUser($params)
    {
        $this->setService('UserPackage/service/UserService');
        $userService = $this->getService('UserService');

        $filter['conditions'] = array();
        foreach ($params as $paramKey => $paramValue) {
            $filter['conditions'][] = ['key' => $paramKey, 'value' => $paramValue];
        }
        $FBSUser = $this->getService('FBSUserRepository')->findOneBy($filter);

        // dump($FBSUser);

        if (isset($FBSUser) && $FBSUser instanceof FBSUser && in_array($FBSUser->getHighestPermissionGroup(), array('systemAdmin', 'projectSupervisor', 'projectAdmin')) && $FBSUser->getStatus() == 1) {
            $this->getSession()->set('userId', $FBSUser->getId());
            $this->getSession()->set('userStorageType', 'FBS');
            if ($this->getRequest()->get('LoginWidget_username') && $this->getRequest()->get('LoginWidget_username') != '') {
                $this->addSystemMessage('login.success', 'success', 'login');
            }

            if ($this->getProjectData('adminLoginTokenMethod') && isset($params['username'])) {
                if ($this->getProjectData('adminLoginTokenMethod') == 'email') {
                    $user = $this->makeUserFromFBSUser($FBSUser, 'tokenRequired');
                    $mailSuccess = $userService->sendLoginTokenByEmail($user);
                    // if (!$mailSuccess) {
                    //     $user = $this->makeUserFromFBSUser(new FBSUser());
                    // }
                    return $user;
                }
            } else {
                return $this->makeUserFromFBSUser($FBSUser);
            }
        }
        else {
            return null;
        }
    }

    public function makeUserFromFBSUser(FBSUser $FBSUser, $forcedPermissionGroup = null)
    {
        $user = new User();
        $user->setId($FBSUser->getId());
        $user->setUserAccount(new UserAccount());
        $user->setName($FBSUser->getName());
        $user->setUsername($FBSUser->getUsername());
        $user->setPassword($FBSUser->getPassword());
        $user->setEmail($FBSUser->getEmail());
        $user->setMobile(null);
        $user->setType(User::TYPE_ADMINISTRATOR);
        if (!$forcedPermissionGroup) {
            foreach ($FBSUser->getPermissionGroups() as $permissionGroup) {
                $user->addPermissionGroup($permissionGroup);
            }
        } else {
            $user->addPermissionGroup($forcedPermissionGroup);
        }
        $user->setStatus($FBSUser->getStatus());
        return $user;
    }
}
