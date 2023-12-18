<?php

namespace projects\ASC\entity;

use App;
use framework\component\parent\DbEntity;
// use framework\packages\UserPackage\entity\UserAccount;

class AscEntryHead extends DbEntity
{
	const DEFAULT_LANGUAGE = 'en';

	const AUTOMATIC_LOCALE = 'AUTOMATIC_LOCALE';

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	const CREATE_TABLE_STATEMENT = "CREATE TABLE `asc_entry_head` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
	`asc_unit_id` int(11) DEFAULT NULL,
	`subject_category` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=222000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

	// const ENTITY_ATTRIBUTES = [
	// 	'passOverMissingFields' => ['title_translation_group', 'description_translation_group'],
	// 	'passOverUnnecessaryFields' => ['title_translation_group_id', 'description_translation_group_id'],
	// ];

    protected $id;
	protected $ascUnit;
	protected $ascEntry = array();
	protected $subjectCategory;

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
	public function findEntry($locale = self::AUTOMATIC_LOCALE, $languageStrictly = true)
	{
		$desiredLocale = $locale == self::AUTOMATIC_LOCALE ? App::getContainer()->getLocale() : $locale;
		$selectedEntry = null;
		if ($this->ascEntry) {
			foreach ($this->ascEntry as $entryLoop) {
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

	/**
	 * This is a technical method, not part of the ORM.
	*/
	public function findTitle()
	{
		$entry = $this->findEntry();
		if ($entry) {
			return $entry->getTitle();
		}

		return null;
	}

	/**
	 * This is a technical method, not part of the ORM.
	*/
	public function findDescription()
	{
		$entry = $this->findEntry();
		if ($entry) {
			return $entry->getDescription();
		}
		
		return null;
	}

	public function setAscUnit(AscUnit $ascUnit)
	{
		$this->ascUnit = $ascUnit;
	}

	public function getAscUnit()
	{
		return $this->ascUnit;
	}

	public function addAscEntry(AscEntry $ascEntry)
	{
		$this->ascEntry[] = $ascEntry;
	}

	public function getAscEntry()
	{
		return $this->ascEntry;
	}

	public function setSubjectCategory($subjectCategory)
	{
		$this->subjectCategory = $subjectCategory;
	}

	public function getSubjectCategory()
	{
		return $this->subjectCategory;
	}
}