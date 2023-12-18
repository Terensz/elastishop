<?php
namespace framework\packages\WebshopPackage\entity;

use framework\component\parent\DbEntity;

class Currency extends DbEntity
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `currency` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `code` varchar(10) DEFAULT NULL,
        `name` varchar(20) DEFAULT NULL,
        `symbol` varchar(10) DEFAULT NULL,
        `exchange_rate_to_eur` decimal(13,2) DEFAULT NULL,
        `exchange_rate_last_updated` datetime DEFAULT NULL,
        `status` int(2) DEFAULT ".self::STATUS_ACTIVE.",
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ONCREATE_QUERIES = [
        "INSERT INTO currency (id, code, name, symbol, exchange_rate_to_eur, exchange_rate_last_updated, status) 
        VALUES (348, 'HUF', 'Forint', 'HUF', null, null, ".self::STATUS_ACTIVE.")",
        "INSERT INTO currency (id, code, name, symbol, exchange_rate_to_eur, exchange_rate_last_updated, status) 
        VALUES (978, 'EUR', 'Euro', '€', 1, null, ".self::STATUS_INACTIVE.")"
    ];

    protected $id;
    protected $code;
    protected $name;
    protected $symbol;
    protected $exchangeRateToEur;
    protected $exchangeRateLastUpdated;
    protected $status;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function setExchangeRateToEur($exchangeRateToEur)
    {
        $this->exchangeRateToEur = $exchangeRateToEur;
    }

    public function getExchangeRateToEur()
    {
        return $this->exchangeRateToEur;
    }

    public function setExchangeRateLastUpdated($exchangeRateLastUpdated)
    {
        $this->exchangeRateLastUpdated = $exchangeRateLastUpdated;
    }

    public function getExchangeRateLastUpdated()
    {
        return $this->exchangeRateLastUpdated;
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
