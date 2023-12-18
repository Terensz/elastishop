<?php
namespace framework\component\parent;

use framework\packages\UserPackage\entity\UserAccount;

abstract class ProjectUserBase extends DbEntity
{
    abstract public function setUserAccount(UserAccount $userAccount = null);

    abstract public function getUserAccount();
}
