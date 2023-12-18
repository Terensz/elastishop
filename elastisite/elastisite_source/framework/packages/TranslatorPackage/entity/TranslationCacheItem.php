<?php
namespace framework\packages\TranslatorPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\VideoPackage\entity\Video;

class TranslationCacheItem extends DbEntity
{
    const CREATE_TABLE_STATEMENT = "CREATE TABLE `translation_cache_item` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `locale_code` varchar(3) DEFAULT NULL,
        `code` text DEFAULT NULL,
        `translation` text DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=79000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'active' => false
    ];
    
    protected $id;
    protected $localeCode;
    protected $code;
    protected $translation;

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

    public function setLocaleCode($localeCode)
    {
        $this->localeCode = $localeCode;
    }

    public function getLocaleCode()
    {
        return $this->localeCode;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setTranslation($translation)
    {
        $this->translation = $translation;
    }

	public function getTranslation()
	{
		return $this->translation;
	}
}
