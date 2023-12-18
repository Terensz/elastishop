<?php

namespace projects\ASC\entity;

use framework\component\parent\DbEntity;

class AscSubscriptionOffer extends DbEntity
{
	const CREATE_TABLE_STATEMENT = "CREATE TABLE `asc_subscription_offer` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `eur_price` decimal(13,2) DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=224000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $eurPrice;
    protected $status;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

	public function setEurPrice($eurPrice)
	{
		$this->eurPrice = $eurPrice;
	}

	public function getEurPrice()
	{
		return $this->eurPrice;
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