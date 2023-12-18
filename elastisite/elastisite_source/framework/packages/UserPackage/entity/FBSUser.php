<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\Service;
use framework\component\parent\FileBasedStorageEntity;

class FBSUser extends FileBasedStorageEntity
{
    protected $id;
    protected $name;
    protected $username;
    protected $password;
    protected $email;
    protected $mobile;
    protected $active;
    protected $permissionGroups = array();
    // private $highestPermissionGroup;
    private $status;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setDisplayedPassword($displayedPassword)
    {
        if ($displayedPassword && $displayedPassword != '') {
            $this->password = md5($displayedPassword);
        }
    }

    public function getDisplayedPassword()
    {
        return '';
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function getPermissionGroups()
    {
        return $this->permissionGroups;
    }

    public function setPermissionGroups($permissionGroupArray)
    {
        $this->permissionGroups = $permissionGroupArray;
    }

    public function addPermissionGroup($permissionGroup)
    {
        if (!in_array($permissionGroup, $this->permissionGroups)) {
            $this->permissionGroups[] = $permissionGroup;
        }
    }

    public function setHighestPermissionGroup($highestPermissionGroup)
    {
        // $this->highestPermissionGroup = $highestPermissionGroup;
        if ($highestPermissionGroup == 'systemAdmin') {
            $this->permissionGroups = array('systemAdmin', 'projectSupervisor', 'projectAdmin', 'user', 'guest');
        }
        else {
            if ($highestPermissionGroup == 'projectSupervisor') {
                $this->permissionGroups = array('projectSupervisor', 'projectAdmin', 'user', 'guest');
            }
            else {
                if ($highestPermissionGroup == 'projectAdmin') {
                    $this->permissionGroups = array('projectAdmin', 'user', 'guest');
                } else { 
                    if ($highestPermissionGroup == 'user') {
                        $this->permissionGroups = array('user', 'guest');
                    }
                    else {
                        if ($highestPermissionGroup == 'guest') {
                            $this->permissionGroups = array('guest');
                        }
                    }
                }
            }
        }
    }

    public function getHighestPermissionGroup()
    {
        // return $this->highestPermissionGroup;
        if (in_array('systemAdmin', $this->permissionGroups)) {
            return 'systemAdmin';
        }
        else {
            if (in_array('projectSupervisor', $this->permissionGroups)) {
                return 'projectSupervisor';
            }
            else {
                if (in_array('projectAdmin', $this->permissionGroups)) {
                    return 'projectAdmin';
                } else { 
                    if (in_array('user', $this->permissionGroups)) {
                        return 'user';
                    }
                    else {
                        if (in_array('guest', $this->permissionGroups)) {
                            return 'guest';
                        }
                    }
                }
            }
        }
    }

    public function firstPermissionGroupIsHigherOrEquals($first, $second)
    {
        if (!$first && $second) {
            return false;
        }

        if ($first && !$second) {
            return true;
        }

        if (!$first && !$second) {
            return true;
        }
        // return $this->highestPermissionGroup;
        $values = [
            'systemAdmin' => 50,
            'projectSupervisor' => 40,
            'projectAdmin' => 30,
            'user' => 20,
            'guest' => 10
        ];
        $firstPoints = $values[$first];
        $secondPoints = $values[$second];
        return $firstPoints >= $secondPoints ? true : false;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
