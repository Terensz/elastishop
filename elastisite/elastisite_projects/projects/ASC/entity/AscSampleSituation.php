<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;

class AscSampleSituation extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `asc_sample_situation` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `asc_entry_id` int(11) DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=221000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $ascEntry;
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

	public function setAscEntry(AscEntry $ascEntry)
	{
		$this->ascEntry = $ascEntry;
	}

	public function getAscEntry()
	{
		return $this->ascEntry;
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