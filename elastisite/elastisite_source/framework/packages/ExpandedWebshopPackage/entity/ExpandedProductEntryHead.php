<?php
namespace framework\packages\ExpandedWebshopPackage\entity;

use App;
use framework\component\parent\DbEntity;
use framework\kernel\utility\BasicUtils;
use framework\packages\WebshopPackage\entity\ProductCategory;
use framework\packages\WebshopPackage\entity\ProductPriceActive;
use framework\packages\WebshopPackage\entity\ProductImage;

class ExpandedProductEntryHead extends DbEntity
{
	const DEFAULT_LANGUAGE = 'en';
	const AUTOMATIC_LOCALE = 'AUTOMATIC_LOCALE';

    const ENTRY_TYPE_TITLE = 'Title';
    const ENTRY_TYPE_SLOGAN = 'Slogan';
    const ENTRY_TYPE_USAGE_NOTICE = 'UsageNotice';

	const CREATE_TABLE_STATEMENT = "CREATE TABLE `expanded_product_entry_head` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
	`expanded_product_unit_head_id` int(11) DEFAULT NULL,
	`entry_type` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=403000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	// const ENTITY_ATTRIBUTES = [
	// 	'passOverMissingFields' => ['title_translation_group', 'description_translation_group'],
	// 	'passOverUnnecessaryFields' => ['title_translation_group_id', 'description_translation_group_id'],
	// ];

    protected $id;
	protected $expandedProductUnitHead;
	protected $expandedProductEntry = array();
	protected $entryType;

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

	/**
	 * This is a technical method, not part of the ORM.
	*/
	public function findEntry($entryType, $locale = self::AUTOMATIC_LOCALE, $languageStrictly = true)
	{
		$desiredLocale = $locale == self::AUTOMATIC_LOCALE ? App::getContainer()->getLocale() : $locale;
		$selectedEntry = null;
		if ($this->expandedProductEntry && is_array($this->expandedProductEntry)) {
			foreach ($this->expandedProductEntry as $entryLoop) {
				if ($entryLoop->getLanguageCode() == $desiredLocale) {
					return $entryLoop;
				} elseif ($entryLoop->getLanguageCode() == self::DEFAULT_LANGUAGE) {
					$selectedEntry = $entryLoop;
				} elseif (!$selectedEntry && !$languageStrictly) {
					$selectedEntry = $entryLoop;
				}
			}
			return $selectedEntry;
		} else {
			return null;
		}
	}

	public function setExpandedProductUnitHead(ExpandedProductUnitHead $expandedProductUnitHead)
	{
		$this->expandedProductUnitHead = $expandedProductUnitHead;
	}

	public function getExpandedProductUnitHead()
	{
		return $this->expandedProductUnitHead;
	}

	public function addExpandedProductEntry(ExpandedProductEntry $expandedProductEntry)
	{
		$this->expandedProductEntry[] = $expandedProductEntry;
	}

	public function getExpandedProductEntry()
	{
		return $this->expandedProductEntry;
	}

	public function setEntryType($entryType)
	{
		$this->entryType = $entryType;
	}

	public function getEntryType()
	{
		return $this->entryType;
	}
}
