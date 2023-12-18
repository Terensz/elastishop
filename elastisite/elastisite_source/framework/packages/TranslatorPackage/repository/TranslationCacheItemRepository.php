<?php
namespace framework\packages\TranslatorPackage\repository;

use framework\component\parent\DbRepository;

class TranslationCacheItemRepository extends DbRepository
{
    public function __construct()
    {

    }

    public function removeAllRecords()
    {
        $dbm = $this->getDbManager();
        $stm = "DELETE FROM translation_cache_item ";
        $dbm->execute($stm, []);

        return true;
    }

    public function storeRecord($locale, $code, $translation)
    {
        $dbm = $this->getDbManager();
        $stm = "INSERT INTO translation_cache_item (locale_code, code, translation) VALUES (:locale_code, :code, :translation)";
        $dbm->execute($stm, [
            'locale_code' => $locale,
            'code' => $code,
            'translation' => $translation
        ]);

        return true;
    }

    public function findByLocale($locale)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT code, translation FROM translation_cache_item WHERE locale_code = :locale_code ";
        $result = $dbm->findAll($stm, [
            'locale_code' => $locale
        ]);

        return $result;
    }
}
