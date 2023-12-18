<?php
namespace framework\packages\ToolPackage\entity;

use framework\component\parent\TechnicalEntity;
use framework\packages\UserPackage\entity\Person;

class TechnicalEmail extends TechnicalEntity
{
    private $senderName;
    private $senderEmail;
    private $subject;
    private $body;
    private $status;

    public function __construct()
    {
        $this->status = 1;
        // dump($this);
    }

    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    public function getSenderName()
    {
        return $this->senderName;
    }

    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }

    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
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
