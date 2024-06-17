<?php
namespace framework\packages\UserPackage\entity;

use framework\component\parent\DbEntity;
use framework\component\interfaces\UserInterface;

class EmailReservation extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `email_reservation` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `visitor_code` varchar(100) DEFAULT NULL,
        `email` varchar(100) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'active' => true
    ];

    private $id;
    private $visitorCode;
    private $email;

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

    public function setEmail($email)
    {
        $this->email = $this->decrypt($email);
    }

    public function getEmail()
    {
        return $this->email;
    }
}
