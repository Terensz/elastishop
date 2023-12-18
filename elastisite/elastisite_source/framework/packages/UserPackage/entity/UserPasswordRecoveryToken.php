<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class UserPasswordRecoveryToken extends DbEntity
{
    const EXPIRES_IN_MINUTES = 20;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `user_password_recovery_token` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `token` varchar(200) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `user_account_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `redeemed_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1700 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    private $id;
    private $token;
    private $userAccount;
    private $createdAt;
    private $redeemedAt;

    public function __construct()
    {
        $this->getContainer()->wireService('UserPackage/entity/UserAccount');
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setUserAccount(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setRedeemedAt($redeemedAt)
    {
        $this->redeemedAt = $redeemedAt;
    }

    public function getRedeemedAt()
    {
        return $this->redeemedAt;
    }
}
