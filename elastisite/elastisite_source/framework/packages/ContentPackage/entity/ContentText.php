<?php 
namespace framework\packages\ContentPackage\entity;

use App;
use framework\component\parent\DbEntity;

class ContentText extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `content_text` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `website` varchar(250) DEFAULT NULL,
        `code` varchar(100) DEFAULT NULL,
        `document_type` varchar(10) DEFAULT NULL,
        `locale` varchar(5) DEFAULT NULL,
        `document_part` varchar(10) DEFAULT NULL,
        `phrase` text DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `status` smallint(2) DEFAULT 0,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=67000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $website;
    protected $code;
    protected $documentType;
    protected $locale;
    protected $documentPart;
    protected $phrase;
    protected $createdAt;
    protected $status;

    public function __construct()
    {
        $this->website = App::getWebsite();
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = 1;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
    }

    public function getDocumentType()
    {
        return $this->documentType;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setDocumentPart($documentPart)
    {
        $this->documentPart = $documentPart;
    }

    public function getDocumentPart()
    {
        return $this->documentPart;
    }

    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;
    }

    public function getPhrase()
    {
        return $this->phrase;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
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