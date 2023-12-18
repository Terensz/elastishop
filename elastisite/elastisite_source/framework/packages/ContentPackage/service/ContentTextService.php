<?php
namespace framework\packages\ContentPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\kernel\utility\BasicUtils;
use framework\packages\ContentPackage\entity\ContentText;

class ContentTextService extends Service
{
    public $prefabPathBase;

    public $prefabPathBaseType = 'projects';

    public function __construct()
    {
        $this->wireService('ToolPackage/service/TextAssembler');
        $this->prefabPathBase = 'projects/'.App::getWebProject().'/view/contentTexts/';
    }

    public function getContentTextRepository()
    {
        $this->setService('ContentPackage/repository/ContentTextRepository');
        return $this->getService('ContentTextRepository');
    }

    public function getAllContentTextParams($documentType, $documentPart = 'content') : array
    {
        $defaultLocale = $this->getContainer()->getDefaultLocale();
        $fileParamsDef = $this->getPrefabContentFileParams($documentType, $defaultLocale, $documentPart);
        $pathDef = FileHandler::completePath($fileParamsDef['path'], $this->prefabPathBaseType);
        $fileParamsEn = $this->getPrefabContentFileParams($documentType, 'en', $documentPart);
        $pathEn = FileHandler::completePath($fileParamsEn['path'], $this->prefabPathBaseType);

        $result = [];
        foreach ($fileParamsDef['fileNames'] as $fileName) {
            $fileNameParts = explode('.', $fileName);
            $code = $fileNameParts[0];
            $codeParts = explode('_', $code);
            $packageName = $codeParts[0];
            if ($this->getContainer()->packageInstalled($packageName)) {
                $storedContentTextDef = $this->getStoredContentText($code, $documentType, $defaultLocale, $documentPart);
                if ($storedContentTextDef) {
                    $phraseDef = $storedContentTextDef->getPhrase();
                    $phraseLocationDef = 'database';
                } else {
                    $phraseDef = file_get_contents($pathDef.'/'.$fileName);
                    $phraseLocationDef = 'file';
                }
    
                $result[$defaultLocale][$code] = $this->assembleParams($documentType, $defaultLocale, $documentPart, $code, $phraseDef, $phraseLocationDef, $pathDef, $storedContentTextDef);
    
                $storedContentTextEn = $this->getStoredContentText($code, $documentType, 'en', $documentPart);
                if ($storedContentTextEn) {
                    $phraseEn = $storedContentTextEn->getPhrase();
                    $phraseLocationEn = 'database';
                } else {
                    $phraseEn = in_array($fileName, $fileParamsEn['fileNames']) ? file_get_contents($pathEn.'/'.$fileName) : null;
                    $phraseLocationEn = 'file';
                }

                $result['en'][$code] = $this->assembleParams($documentType, 'en', $documentPart, $code, $phraseEn, $phraseLocationEn, $pathEn, $storedContentTextEn);
            }
        }

        return $result;
    }

    public function assembleParams($documentType, $locale, $documentPart, $code, $phrase, $phraseLocation, $path, $storedContentText) : array
    {
        $uniqueId = $documentType.'-'.$documentPart.'-'.$locale.'-'.$code;
        $titleAndPackageName = $this->getTitleAndPackageName($code);

        $result = [
            'uniqueId' => $uniqueId,
            'code' => $code,
            'phrase' => $phrase ? $phrase : '',
            'phraseLocation' => $phraseLocation,
            'path' => $path,
            'packagePublicName' => $titleAndPackageName['packageName'],
            'title' => $titleAndPackageName['title'],
            'lead' => $this->createLead($phrase),
            'storedContentText' => $storedContentText
        ];

        return $result;
    }

    public function getTitleAndPackageName($code) : array
    {
        $codeParts = explode('_', $code);
        if (count($codeParts) == 2) {
            $packageName = $codeParts[0];
            $referenceKey = $codeParts[1];
        } elseif (count($codeParts) == 1) {
            $packageName = 'ContentPackage';
            $referenceKey = $codeParts[0];
        }

        return [
            'packageName' => trans(BasicUtils::constantToTranslationFormat($packageName)),
            'title' => trans(BasicUtils::constantToTranslationFormat($referenceKey))
        ];
    }

    // public function getStoredPhrase($code, $documentType, $locale, $documentPart)
    // {
    //     $contentText = $this->getStoredContentText($code, $documentType, $locale, $documentPart);
    //     if ($contentText) {
    //         return $contentText->getPhrase();
    //     }
    //     return null;
    // }

    public function getStoredContentText($code, $documentType, $locale, $documentPart) : ? ContentText
    {
        $repo = $this->getContentTextRepository();
        // dump([
        //     ['key' => 'website', 'value' => App::getWebsite()],
        //     ['key' => 'code', 'value' => $code],
        //     ['key' => 'document_type', 'value' => $documentType],
        //     ['key' => 'locale', 'value' => $locale],
        //     ['key' => 'document_part', 'value' => $documentPart]
        // ]);
        $contentText = $repo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'code', 'value' => $code],
            ['key' => 'document_type', 'value' => $documentType],
            ['key' => 'locale', 'value' => $locale],
            ['key' => 'document_part', 'value' => $documentPart]
        ]]);

        return $contentText;
    }

    public function getAttributes($uniqueId) : array
    {
        $uniqueIdParts = explode('-', $uniqueId);

        return [
            'code' => $uniqueIdParts[3],
            'documentType' => $uniqueIdParts[0],
            'locale' => $uniqueIdParts[2],
            'documentPart' => $uniqueIdParts[1]
        ];
    }

    public function getContentTextParams(string $uniqueId) : array
    {
        // $repo = $this->getContentTextRepository();
        $attributes = $this->getAttributes($uniqueId);
        // dump($attributes);
        $fileName = $attributes['code'].'.php';
        
        $fileParams = $this->getPrefabContentFileParams($attributes['documentType'], $attributes['locale'], $attributes['documentPart']);
        $path = FileHandler::completePath($fileParams['path'], $this->prefabPathBaseType);

        $storedContentText = $this->getStoredContentText($attributes['code'], $attributes['documentType'], $attributes['locale'], $attributes['documentPart']);
        // dump($storedContentText);
        $phrase = null;
        if ($storedContentText) {
            $phrase = $storedContentText->getPhrase();
            $phraseLocation = 'database';
        } else {
            // dump(file_get_contents($path.'/'.$fileName));
            if (is_file($path.'/'.$fileName)) {
                $phrase = file_get_contents($path.'/'.$fileName);
            }
            $phraseLocation = 'file';
        }

        $return = $this->assembleParams($attributes['documentType'], $attributes['locale'], $attributes['documentPart'], $attributes['code'], $phrase, $phraseLocation, $path, $storedContentText);

        return $return;
    }

    public function storeContentText($uniqueId, $phrase)
    {
        $attributes = $this->getAttributes($uniqueId);
        return $this->storeContentTextFullArgs($attributes['code'], $attributes['documentType'], $attributes['locale'], $attributes['documentPart'], $phrase);
    }

    public function removeContentTextByUniqueId($uniqueId)
    {
        $attributes = $this->getAttributes($uniqueId);
        return $this->removeContentText($attributes['code'], $attributes['documentType'], $attributes['locale'], $attributes['documentPart']);
    }

    public function removeContentText($code, $documentType, $locale, $documentPart)
    {
        $repo = $this->getContentTextRepository();
        // dump([
        //     ['key' => 'website', 'value' => App::getWebsite()],
        //     ['key' => 'code', 'value' => $code],
        //     ['key' => 'document_type', 'value' => $documentType],
        //     ['key' => 'locale', 'value' => $locale],
        //     ['key' => 'document_part', 'value' => $documentPart]
        // ]);
        $contentText = $repo->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'code', 'value' => $code],
            ['key' => 'document_type', 'value' => $documentType],
            ['key' => 'locale', 'value' => $locale],
            ['key' => 'document_part', 'value' => $documentPart]
        ]]);
        if ($contentText) {
            $repo->remove($contentText->getId());
            return true;
        } else {
            return false;
        }
    }

    public function storeContentTextFullArgs($code, $documentType, $locale, $documentPart, $phrase)
    {
        $repo = $this->getContentTextRepository();
        $contentText = $this->getStoredContentText($code, $documentType, $locale, $documentPart);
        if (!$contentText) {
            $contentText = $repo->createNewEntity();
            $contentText->setCode($code);
            $contentText->setDocumentType($documentType);
            $contentText->setLocale($locale);
            $contentText->setDocumentPart($documentPart);
            $contentText->setPhrase($phrase);
            // $contentText = $repo->store($contentText);
        } else {
            $contentText->setPhrase($phrase);
        }
        $contentText = $repo->store($contentText);

        return $contentText;
    }

    public function getPrefabContentFileParams($documentType, $locale, $documentPart = 'content')
    {
        $path = $this->prefabPathBase.$documentType.'/'.$documentPart.'/'.$locale;
        $fileNames = FileHandler::getAllFileNames($path, 'keep', $this->prefabPathBaseType);

        return [
            'path' => $path,
            'fileNames' => $fileNames
        ];
    }

    public function createLead($phrase)
    {
        if (!$phrase || $phrase == '') {
            return '';
        }
        // dump($phrase);//exit;
        $phrase = html_entity_decode($phrase);
        $phrase = strip_tags($phrase);
        // $phrase = str_replace(['<p>', '</p>'], '', $phrase);
        $phrase = str_replace('<p>', '', $phrase);
        $phrase = str_replace('</p>', '', $phrase);
        // $phrase = htmlentities($phrase);
        // $phrase = str_replace('&#60;p&#62;', '', $phrase);
        // $phrase = str_replace('&#60;/p&#62;', '', $phrase);
        // dump($phrase);exit;
        $phraseParts = explode("\n", $phrase);

        $resultArray = [];
        $contentBegan = false;
        $removeFirstXRows = 0;
        $removedRows = 0;
        foreach ($phraseParts as $phraseRow) {
            $phraseRow = trim($phraseRow);
            if (($phraseRow && $phraseRow != '') || $contentBegan) {
                if ($removedRows < $removeFirstXRows) {
                    $removedRows++;
                } else {
                    $contentBegan = true;
                    $resultArray[] = $phraseRow;
                }
            }
        }

        $lead = implode("\n", $resultArray);
        $lead = '... '.StringHelper::cutLongString($lead, 240);
        return $lead;
    }
}