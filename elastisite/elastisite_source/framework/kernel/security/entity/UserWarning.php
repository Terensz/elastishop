<?php
namespace framework\kernel\security\entity;

use framework\component\parent\DbEntity;
use framework\component\parent\TechnicalEntity;
use framework\packages\UserPackage\entity\UserAccount;

class UserWarning extends TechnicalEntity
{
    // const ENTITY_ATTRIBUTES = [
    //     'repositoryPath' => 'framework/kernel/security/repository/SecurityEventRepository',
    //     'relations' => [
    //         'UserAccount' => [
    //             'targetClass' => UserAccount::class,
    //             'association' => 'manyToOne',
    //             'relationBinderTable' => false,
    //             'targetIdField' => 'id',
    //             'referencedIdField' => 'user_account_id'
    //         ]
    //     ],
    //     'active' => true
    // ];

    protected $id;
    protected $visitorCode;
    protected $userAccount;
    protected $acknowledged;
    protected $text;
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = $this->getCurrentTimestamp();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setVisitorCode($visitorCode)
    {
        $this->visitorCode = $visitorCode;
    }

    public function getVisitorCode()
    {
        return $this->visitorCode;
    }

    public function setUserAccount(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setAcknowledged($acknowledged)
    {
        $this->acknowledged = $acknowledged;
    }

    public function getAcknowledged()
    {
        return $this->acknowledged;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}
