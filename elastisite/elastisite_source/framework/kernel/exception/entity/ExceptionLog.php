<?php
namespace framework\kernel\exception\entity;

use framework\component\parent\DbEntity;

class ExceptionLog extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `exception_log` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `message` text COLLATE utf8_hungarian_ci DEFAULT NULL,
        `code` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `file` varchar(200) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `line` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `user_id` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `traces` longtext COLLATE utf8_hungarian_ci DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=40000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    // const ENTITY_ATTRIBUTES = [
    //     'technicalProperties' => array('traceArray')
    // ];

    protected $id;
    protected $message;
    protected $code;
    protected $file;
    protected $line;
    protected $userId;
    protected $createdAt;
    protected $traces;
    // protected $traceArray;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getTraces()
    {
        return $this->traces;
    }

    public function setTraces($traces)
    {
        $this->traces = $traces;
    }

    // public function getTraceArray()
    // {
    //     return $this->traceArray;
    // }

    // public function setTraceArray($traceArray)
    // {
    //     $this->traceArray = $traceArray;
    // }
}
