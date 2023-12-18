<?php
namespace framework\packages\BasicPackage\entity;

use framework\component\parent\DbEntity;

class Country extends DbEntity
{
    /*
    https://en.wikipedia.org/wiki/List_of_ISO_3166_country_codes
    */

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `country` (
        `id` int(11) NOT NULL,
        `alpha_two` varchar(2)  DEFAULT NULL,
        `alpha_three` varchar(3)  DEFAULT NULL,
        `currency` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `local_currency_abbrev` varchar(10) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `intl_currency_abbrev` varchar(10) COLLATE utf8_hungarian_ci DEFAULT NULL,
        `translation_reference` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ONCREATE_QUERIES = [
        "INSERT INTO country (id, alpha_two, alpha_three, currency, local_currency_abbrev, intl_currency_abbrev, translation_reference) 
        VALUES (348, 'HU', 'HUN', 'Forint', 'Ft', 'HUF', 'hungary')",
        "INSERT INTO country (id, alpha_two, alpha_three, currency, local_currency_abbrev, intl_currency_abbrev, translation_reference) 
        VALUES (826, 'GB', 'GBR', 'Pound sterling', '£', 'GBP', 'great.britain')"
    ];

    /**
    * primary key
    */
    protected $id;
    
    /**
    * @var $alphaTwo
    */
    private $alphaTwo;

    /**
    * @var $alphaThree
    */
    private $alphaThree;

    /**
    * @var $currency
    */
    private $currency;

    /**
    * @var $localCurrencyAbbrev
    */
    private $localCurrencyAbbrev;

    /**
    * @var $intlCurrencyAbbrev
    */
    private $intlCurrencyAbbrev;
    
    /**
    * @var $translationReference
    */
    private $translationReference;

    public function __construct($id = 0)
    {
        if ($id) {
            $repo = $this->getRepository();
            $found = $repo->find($id);
            if ($found) {
                foreach (get_object_vars($found) as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAlphaTwo($alphaTwo)
    {
        $this->alphaTwo = $alphaTwo;
    }

    public function getAlphaTwo()
    {
        return $this->alphaTwo;
    }

    public function setAlphaThree($alphaThree)
    {
        $this->alphaThree = $alphaThree;
    }

    public function getAlphaThree()
    {
        return $this->alphaThree;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setLocalCurrencyAbbrev($localCurrencyAbbrev)
    {
        $this->localCurrencyAbbrev = $localCurrencyAbbrev;
    }

    public function getLocalCurrencyAbbrev()
    {
        return $this->localCurrencyAbbrev;
    }

    public function setIntlCurrencyAbbrev($intlCurrencyAbbrev)
    {
        $this->intlCurrencyAbbrev = $intlCurrencyAbbrev;
    }

    public function getIntlCurrencyAbbrev()
    {
        return $this->intlCurrencyAbbrev;
    }

    public function setTranslationReference($translationReference)
    {
        $this->translationReference = $translationReference;
    }

    public function getTranslationReference()
    {
        return $this->translationReference;
    }
}
