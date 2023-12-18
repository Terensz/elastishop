<?php
namespace framework\packages\TranslatorPackage;

use App;
use framework\component\parent\Service;
use framework\component\exception\ElastiException;
use framework\kernel\base\Cache;
use framework\kernel\utility\FileHandler;
use framework\packages\TranslatorPackage\repository\TranslationCacheItemRepository;

class Translator extends Service
{
    const SUPPORTED_LOCALES = ['hu', 'en'];

    // const USE_DATABASE_CACHE = false;

    // private $translationCacheItemRepository;

    private $translations = array();

    private $includedFileKeys = array();

    public function __construct()
    {

    }

    public function getSupportedLocales()
    {
        return self::SUPPORTED_LOCALES;
    }

    // public function getTranslations($like = array())
    // {
    //     if ($like == array()) {
    //         return $this->translations;
    //     }
    //     else {
    //         $return = array();
    //         foreach ($this->translations as $key => $value) {
    //             foreach ($like as $keyWord) {
    //                 $pos = strpos($key, $keyWord);
    //                 if ($pos !== false) {
    //                     $return[] = $value;
    //                 }
    //             }
    //         }
    //         return $return;
    //     }
    // }

    public function cacheTranslations()
    {

    }

    // public function getTranslationCacheItemRepository() : TranslationCacheItemRepository
    // {
    //     if (empty($this->translationCacheItemRepository)) {
    //         App::getContainer()->wireService('TranslatorPackage/repository/TranslationCacheItemRepository');
    //         $this->translationCacheItemRepository = new TranslationCacheItemRepository();
    //     }

    //     return $this->translationCacheItemRepository;
    // }

    public function getTranslation($code, $locale)
    {
        if (empty($code)) {
            return '';
        }
        if (empty($locale)) {
            dump('Empty locale');exit;
        }
        if (is_array($code) || is_array($locale)) {
            var_dump($code);var_dump($locale);exit;
        }
        if (isset($this->translations[$locale]) && isset($this->translations[$locale][$code])) {
            return $this->translations[$locale][$code];
        }

        if (isset($this->translations[$locale]) && !isset($this->translations[$locale][$code])) {
            return $code;
        }
        
        if (!Cache::cacheRefreshRequired()) {
            $this->fillUpTranslations($locale);
        } else {
            $this->fillUpTranslationsFromFiles($locale);
        }

        // if (self::USE_DATABASE_CACHE) {
        //     $this->fillUpTranslations($locale);
        // } else {
        //     $this->fillUpTranslationsFromFiles($locale);
        // }

        if (isset($this->translations[$locale]) && isset($this->translations[$locale][$code])) {
            return $this->translations[$locale][$code];
        }

        return $code;
    }

    // public function getTranslation($code, $locale)
    // {
    //     if (isset($this->translations[$locale]) && isset($this->translations[$locale][$code])) {
    //         return $this->translations[$locale][$code];
    //     }

    //     if (isset($this->translations[$locale]) && !isset($this->translations[$locale][$code])) {
    //         return $code;
    //     }

    //     if (self::USE_DATABASE_CACHE) {
    //         $this->fillUpTranslations($locale);
    //     } else {
    //         $this->fillUpTranslationsFromFiles($locale);
    //     }

    //     if (isset($this->translations[$locale]) && isset($this->translations[$locale][$code])) {
    //         return $this->translations[$locale][$code];
    //     }

    //     return $code;
    // }

    public function fillUpTranslations($locale)
    {
        // if (isset($this->translations[$locale])) {
        //     return $this->getProcessedTranslation($code, $locale);
        // }

        /**
         * If no connection, we use the old procedure.
        */
        // $dbm = App::getContainer()->getKernelObject('DbManager');
        // if (!$dbm->getConnection()) {
        //     $this->fillUpTranslationsFromFiles($locale);
        // }

        /**
         * If we have connection, we fill $this->translations from the database.
        */
        try {
            // $this->fillUpTranslationsFromDatabase($locale);
            $this->fillUpTranslationsFromCache($locale);
            // dump($this->translations); exit;
            if (empty($this->translations)) {
                $this->fillUpTranslationsFromFiles($locale);
            }
        } catch (ElastiException $e) {
            $this->fillUpTranslationsFromFiles($locale);
            // return $this->getProcessedTranslation($code, $locale);
        } catch (\PDOException $e) {
            $this->fillUpTranslationsFromFiles($locale);
            // return $this->getProcessedTranslation($code, $locale);
        }

        // dump($e);
// exit;
    }

    public function fillUpTranslationsFromCache($locale, $lastRound = false)
    {
        $translationCache = App::$cache->read('translation');
        if (!empty($translationCache)) {
            $this->translations = $translationCache;
            return true;
        }
    }

    public function writeToCache()
    {
        App::$cache->write('translation', $this->translations);
    }

    // public function fillUpTranslationsFromDatabase($locale, $lastRound = false)
    // {
    //     $repo = $this->getTranslationCacheItemRepository();
    //     $translationCacheItems = $repo->findByLocale($locale);

    //     if (!$lastRound && empty($translationCacheItems)) {
    //         $this->refreshDatabaseCache();
    //         /**
    //          * Preventing infinite recursion
    //         */
    //         $this->fillUpTranslationsFromDatabase($locale, true);
    //     }

    //     $translations = [];
    //     foreach ($translationCacheItems as $translationCacheItem) {
    //         $translations[$translationCacheItem['code']] = $translationCacheItem['translation'];
    //     }

    //     $this->translations[$locale] = $translations;
    // }

    // public function refreshDatabaseCache()
    // {
    //     foreach (self::SUPPORTED_LOCALES as $supportedLocale) {
    //         $this->fillUpTranslationsFromFiles($supportedLocale);
    //     }

    //     $repo = $this->getTranslationCacheItemRepository();
    //     $repo->removeAllRecords();
    //     foreach ($this->translations as $locale => $translationArray) {
    //         foreach ($translationArray as $code => $translation) {
    //             $repo->storeRecord($locale, $code, $translation);
    //         }
    //         // dump($translationArray);
    //     }
    //     // exit;
    // }

    /**
     * It's only used if database connection is off.
    */
    public function getProcessedTranslation($code, $locale)
    {
        if (!isset($this->translations[$locale])) {
            $this->translations[$locale] = [];
            $this->fillUpTranslationsFromFiles($locale);
        }

        return isset($this->translations[$locale][$code]) ? $this->translations[$locale][$code] : $code;
    }

    public function includeFile($pathToFile, $pathBaseType)
    {
        $key = $pathToFile.','.$pathBaseType;
        if (!in_array($key, $this->includedFileKeys)) {
            $this->includedFileKeys[] = $key;
            FileHandler::includeFile($pathToFile, $pathBaseType);
        }
    }

    /**
     * Basic operation:
     * Basically sets translation to $this->translations if this array does not exist.
     * If you want another locale (e.g. for admin, they can use different to default), than this class automatically sets the translations on that language.
     * ------
     * Changes on 16. feb. 2023: 
     * Now it caches. I use only $this->otherLocaleTranslations for caching.
    */
    public function fillUpTranslationsFromFiles($locale = null)
    {
        $packages = FileHandler::getAllDirNames('framework/packages', 'source');
        // dump($packages);
        foreach ($packages as $package) {
            $hasTranslation = FileHandler::fileExists('framework/packages/'.$package.'/translation', 'source');
            // dump($hasTranslation);
            if ($hasTranslation) {
                $fileToUse = $this->getFileToUse($package, null, $locale);
                // dump($fileToUse);
                $this->includeFile($fileToUse['primaryPathToFile'], $fileToUse['pathBaseType']);
                $translationClass = $fileToUse['primaryClass'];
                $translation = new $translationClass();
                if (!isset($this->translations[$locale])) {
                    $this->translations[$locale] = [];
                }
                $this->translations[$locale] = array_merge($this->translations[$locale], $translation->getTranslation($locale));

                if ($fileToUse['secondaryPathToFile']) {
                    $this->includeFile($fileToUse['secondaryPathToFile'], $fileToUse['pathBaseType']);
                    // FileHandler::includeFile($fileToUse['secondaryPathToFile'], $fileToUse['pathBaseType']);
                    $translationClass2 = $fileToUse['secondaryClass'];
                    $translation2 = new $translationClass2();

                    $this->translations[$locale] = array_merge($this->translations[$locale], $translation2->getTranslation($locale));
                }
            }
        }

        // dump('Innentol a projectek');
        $fileToUse = $this->getFileToUse(null, App::getWebProject(), $locale);
        $this->includeFile($fileToUse['primaryPathToFile'], $fileToUse['pathBaseType']);
        $translationClass = $fileToUse['primaryClass'];
        $translation = new $translationClass();
        $this->translations[$locale] = array_merge($this->translations[$locale], $translation->getTranslation($locale));

        // $this->translations = array_merge($this->translations, $translation->getTranslation($otherLocale));

        if ($fileToUse['secondaryPathToFile']) {
            $this->includeFile($fileToUse['secondaryPathToFile'], $fileToUse['pathBaseType']);
            $translationClass2 = $fileToUse['secondaryClass'];
            $translation2 = new $translationClass2();
            $this->translations[$locale] = array_merge($this->translations[$locale], $translation2->getTranslation($locale));
        }

        // dump($this);exit;
        $this->writeToCache();
    }

    public function getFileToUse($package = null, $webProject = null, $otherLocale = null)
    {
        $allowInformalLanguage = $this->getContainer()->getProjectData('website.allowInformalLanguage');
        $locale = $otherLocale ? $otherLocale : $this->getContainer()->getSession()->getLocale();

        // if ($otherLocale) {
        //     dump($locale);
        // }

        if ($package) {
            $pathBody = 'framework/packages/'.$package.'/translation/';
            $pathBaseType = 'source';
            $classPre = 'framework\\packages\\'.$package.'\translation\\';
            $reference = $package;
        } elseif ($webProject) {
            $pathBody = 'projects/'.$webProject.'/translation/';
            $pathBaseType = 'projects';
            $classPre = 'projects\\'.$webProject.'\translation\\';
            $reference = $webProject;
        } else {
            throw new ElastiException('Either of package or website must be different from null', ElastiException::ERROR_TYPE_SECRET_PROG);
        }

        $formalClass = 'Translation_'.$locale.'For';
        $hasFormalFile = FileHandler::fileExists($pathBody.$formalClass.'.php', $pathBaseType);
        $informalClass = 'Translation_'.$locale.'Inf';
        $hasInformalFile = FileHandler::fileExists($pathBody.$informalClass.'.php', $pathBaseType);
        $generalClass = 'Translation_'.$locale;
        $hasGeneralFile = FileHandler::fileExists($pathBody.$generalClass.'.php', $pathBaseType);

        // if (!$hasGeneralFile) {
        //     $path = $pathBody.$generalClass.'.php';
        //     $pathToFile = FileHandler::completePath($path, $pathBaseType);
        //     dump($pathToFile);
        // }

        // dump($pathPre.$generalClass.'.php');
        // dump($hasGeneralFile);
        // $file = null;

        if ($allowInformalLanguage && $hasInformalFile) {
            $class = $informalClass;
        } elseif ($allowInformalLanguage && $hasGeneralFile) {
            $class = $generalClass;
        } elseif ($allowInformalLanguage && $hasFormalFile) {
            $class = $formalClass;
        } elseif (!$allowInformalLanguage && $hasFormalFile) {
            $class = $formalClass;
        } elseif (!$allowInformalLanguage && $hasGeneralFile) {
            $class = $generalClass;
        } else {
            // dump($pathBody);
            // dump($package);
            // dump($website);
            throw new ElastiException('No translation file for '.$reference, ElastiException::ERROR_TYPE_SECRET_PROG);
        }

        // dump($reference);
        // dump('Pack: '.$package.', website: '.$website);
        // dump($pathBody.$class.'.php');

        $return = array(
            'pathBaseType' => $pathBaseType,
            'primaryPathToFile' => $pathBody.$class.'.php',
            'primaryClass' => $classPre.$class,
            'secondaryPathToFile' => ($class != $generalClass && $hasGeneralFile) ? $pathBody.$generalClass.'.php' : null,
            'secondaryClass' => ($class != $generalClass && $hasGeneralFile) ? $classPre.$generalClass : null
        );

        // if ($otherLocale) {
        //     dump($return);
        // }

        // if ($website) {
        //     dump($pathBody.$class.'.php');
        //     dump($return);
        // }
        // dump($return);
        return $return;
    }
}
