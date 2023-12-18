<?php
namespace framework\component\interfaces;

interface UserInterface
{
    public function setId($id);

    public function getId();

    public function setName($name);

    public function getName();

    public function setUsername($username);

    public function getUsername();

    public function setPassword($password);

    public function getPassword();

    public function getPermissionGroups();

    public function addPermissionGroup($permissionGroup);
}
