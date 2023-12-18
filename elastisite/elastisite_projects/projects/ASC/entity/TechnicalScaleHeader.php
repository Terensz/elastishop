<?php

namespace projects\ASC\entity;

use framework\component\parent\TechnicalEntity;
use framework\packages\UserPackage\entity\UserAccount;

/**
 * This technical entity is used by AscSampleScaleFormSchema for gathering data
*/
class TechnicalScaleHeader extends TechnicalEntity
{
    protected $id;
	protected $situation;
	protected $initialLanguage;
    protected $title;
	protected $description;
	// protected $createdBy;
	// protected $createdAt;
	protected $status;

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

	public function setSituation($situation)
	{
		$this->situation = $situation;
	}

	public function getSituation()
	{
		return $this->situation;
	}

	public function setInitialLanguage($initialLanguage)
	{
		$this->initialLanguage = $initialLanguage;
	}

	public function getInitialLanguage()
	{
		return $this->initialLanguage;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getDescription()
	{
		return $this->description;
	}

    // public function setCreatedBy(UserAccount $createdBy)
    // {
    //     $this->createdBy = $createdBy;
    // }

    // public function getCreatedBy()
    // {
    //     return $this->createdBy;
    // }

    // public function setCreatedAt($createdAt)
    // {
    //     $this->createdAt = $createdAt;
    // }

    // public function getCreatedAt()
    // {
    //     return $this->createdAt;
    // }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}