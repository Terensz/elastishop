<?php
namespace framework\packages\BusinessPackage\entity;

use framework\component\parent\DbEntity;
use framework\component\parent\TechnicalEntity;
use framework\packages\ToolPackage\entity\TechnicalFile;
use framework\packages\UserPackage\entity\Address;
use framework\packages\UserPackage\entity\UserAccount;

class OLD_Currency extends TechnicalEntity
{
    const CURRENCIES = [
        1 => [
            'fullNameEn' => 'Euro',
            'fullNameLocal' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€'
        ],
        2 => [
            'fullNameEn' => 'USA Dollar',
            'fullNameLocal' => 'USA Dollar',
            'code' => 'USD',
            'symbol' => '$'
        ],
        3 => [
            'fullNameEn' => 'Hungarian Forint',
            'fullNameLocal' => 'magyar Forint',
            'code' => 'HUF',
            'symbol' => '€'
        ],
    ];

    protected $id;
    protected $fullNameEn;
    protected $fullNameLocal;
    protected $code;
    protected $symbol;

    public function __construct($id = null)
    {
        if ($id && isset(self::CURRENCIES[$id])) {
            $currencyArray = self::CURRENCIES[$id];
            $this->id = $id;
            $this->fullNameEn = $currencyArray['fullNameEn'];
            $this->fullNameLocal = $currencyArray['fullNameLocal'];
            $this->code = $currencyArray['code'];
            $this->symbol = $currencyArray['symbol'];
            return $this;
        }
        return null;
    }

    // public function setId($id)
    // {
    //     $this->id = $id;
    // }

    public function getId()
    {
        return $this->id;
    }

    // public function setFullName($name)
    // {
    //     $this->name = $name;
    // }

    public function getFullNameEn()
    {
        return $this->fullNameEn;
    }

    public function getFullNameLocal()
    {
        return $this->fullNameLocal;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }
}
