<?php
namespace framework\packages\ExpandedWebshopPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\UserPackage\entity\UserAccount;

class ExpandedProductEntry extends DbEntity
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	const CREATE_TABLE_STATEMENT = "CREATE TABLE `expanded_product_entry` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `expanded_product_entry_head_id` int(11) DEFAULT NULL,
	`language_code` varchar(2) DEFAULT NULL,
    `textual_content` text DEFAULT NULL,
	`created_by` int(11) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=402000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	const ENTITY_ATTRIBUTES = [
		'passOverMissingFields' => ['created_by_id'],
		'passOverUnnecessaryFields' => ['created_by'],
	];

    protected $id;
    protected $expandedProductEntryHead;
	protected $languageCode;
    protected $textualContent;
	protected $createdBy;
    protected $createdAt;
    protected $status;

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

	public function setExpandedProductEntryHead(ExpandedProductEntryHead $expandedProductEntryHead)
	{
		$this->expandedProductEntryHead = $expandedProductEntryHead;
	}

	public function getExpandedProductEntryHead()
	{
		return $this->expandedProductEntryHead;
	}

	public function setLanguageCode($languageCode)
	{
		$this->languageCode = $languageCode;
	}

	public function getLanguageCode()
	{
		return $this->languageCode;
	}

	public function setTextualContent($textualContent)
	{
		$this->textualContent = $textualContent;
	}

	public function getTextualContent()
	{
		return $this->textualContent;
	}

    public function setCreatedBy(UserAccount $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
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