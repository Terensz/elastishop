<?php
namespace framework\packages\ToolPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;
// use framework\packages\VisitorPackage\entity\Visitor;
// use framework\packages\ToolPackage\entity\File;

class UserAccountFile extends DbEntity
{
    const ENTITY_ATTRIBUTES = [
        'repositoryPath' => 'framework/packages/ToolPackage/repository/UserAccountFileRepository',
        'relations' => [],
        'active' => false
    ];

    protected $id;
    protected $visitor;
    protected $userAccount;
    protected $file;
    protected $active;

    public function __construct()
    {
        $this->getContainer()->wireService('UserPackage/entity/UserAccount');
        // $this->getContainer()->wireService('VisitorPackage/entity/Visitor');
        // $this->getContainer()->wireService('ToolPackage/entity/TechnicalFile');
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setVisitor($visitor)
    {
        $this->visitor = $visitor;
    }

    public function getVisitor()
    {
        return $this->visitor;
    }

    public function setUserAccount(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public function getUserAccount()
    {
        return $this->userAccount;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getActive()
    {
        return $this->active;
    }
}
