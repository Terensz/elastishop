<?php
namespace framework\packages\UserPackage\repository;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\DbRepository;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\entity\FBSUser;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Person;
use framework\packages\UserPackage\repository\UserLoginTokenRepository as TokenRepo;
use framework\packages\UserPackage\service\Permission;

class UserRepository extends DbRepository
{
    public function createLoggedOutUser()
    {
        $user = new User();
        $user->setId(0);
        $user->setUserAccount(new UserAccount());
        $user->setPermissionGroups(Permission::BASIC_PERMISSION_GROUPS);
        $this->getContainer()->setUser($user);
        
        return $user;
    }

    // public function makeUserFromFBSUser(FBSUser $FBSUser, $forcedPermissionGroup = null)
    // {
    //     $user = new User();
    //     $user->setId($FBSUser->getId());
    //     $user->setUserAccount(new UserAccount());
    //     $user->setName($FBSUser->getName());
    //     $user->setUsername($FBSUser->getUsername());
    //     $user->setPassword($FBSUser->getPassword());
    //     $user->setEmail($FBSUser->getEmail());
    //     $user->setMobile(null);
    //     if (!$forcedPermissionGroup) {
    //         foreach ($FBSUser->getPermissionGroups() as $permissionGroup) {
    //             $user->addPermissionGroup($permissionGroup);
    //         }
    //     } else {
    //         $user->addPermissionGroup($forcedPermissionGroup);
    //     }
    //     $user->setStatus($FBSUser->getStatus());
    //     return $user;
    // }
}
