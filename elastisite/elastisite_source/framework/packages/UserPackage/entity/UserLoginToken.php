<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\component\parent\TechnicalEntity;
use framework\packages\UserPackage\entity\User;

class UserLoginToken extends TechnicalEntity
{
    // const CREATE_TABLE_STATEMENT = "CREATE TABLE `user_login_token` (
    //     `id` int(11) NOT NULL AUTO_INCREMENT,
    //     `token` varchar(200) COLLATE utf8_hungarian_ci DEFAULT NULL,
    //     `user_id` int(11) DEFAULT NULL,
    //     `created_at` datetime DEFAULT NULL,
    //     `redeemed_at` datetime DEFAULT NULL,
    //     PRIMARY KEY (`id`)
    //     ) ENGINE=InnoDB AUTO_INCREMENT=1700 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/packages/UserPackage/repository/UserLoginTokenRepository',
    //     'relations' => [
    //         'User' => [
    //             'targetClass' => User::class,
    //             'association' => 'oneToOne',
    //             'storageType' => 'technical',
    //             'relationBinderTable' => false,
    //             'referencedIdField' => null
    //         ]
    //     ],
    //     'active' => true
    // ];
    private $id;
    private $token;
    private $user;
    private $createdAt;
    private $redeemedAt;

    public function __construct()
    {

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

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
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
