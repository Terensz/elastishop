<?php
namespace framework\packages\LegalPackage\service;

use App;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\packages\LegalPackage\entity\LegalDocument;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;

class LegalDocumentFactory extends Service
{
    public function getPlaceholders() {
        return array(
            array('from' => '[companyName]', 'to' => '<i>'.$this->getCompanyData('name').'</i>')
        );
    }

    public function getCompleteText()
    {
        $completeText = '';
        $allDocuments = $this->getAll();
        foreach ($allDocuments as $document) {
            $completeText .= '<div class="text-title">'.$document->getTitle().'</div>';
            $completeText .= '<div class="text-body">'.$document->getBody().'</div>';
        }
        return $completeText;
    }

    public function getAll()
    {
        FileHandler::includeFileOnce('framework/packages/LegalPackage/entity/LegalDocument.php', 'source');
        $legalDocuments = array();
        $userDefinedLegalDir = 'projects/'.App::getWebProject().'/legalText';
        $automaticLegalDir = 'framework/packages/LegalPackage/legalText';
        $isUserDefinedLegalDir = FileHandler::fileExists($userDefinedLegalDir, 'projects');
        $dir = $isUserDefinedLegalDir ? $userDefinedLegalDir : $automaticLegalDir;
        $pathBaseType = $dir == $automaticLegalDir ? 'source' : 'projects';
        $fileNames = FileHandler::getAllFileNames($dir, 'keep', $pathBaseType);
        if (count($fileNames) == 0 && $dir == $isUserDefinedLegalDir) {
            $dir = $automaticLegalDir;
            $fileNames = FileHandler::getAllFileNames($automaticLegalDir, 'keep', 'source');
        }
        if (count($fileNames) == 0 && $dir == $automaticLegalDir) {
            throw new ElastiException($this->wrapExceptionParams(), 1675);
        }
        foreach ($fileNames as $fileName) {
            $locale = BasicUtils::explodeAndGetElement(BasicUtils::explodeAndRemoveElement($fileName, '.', 'last'), '_', 'last');
            if ($locale == $this->getSession()->getLocale()) {
                $pathToFile = FileHandler::completePath($dir.'/'.$fileName, $pathBaseType);
                $rawContent = @file_get_contents($pathToFile);
                $legalDocument = $this->create($rawContent);
                if ($legalDocument->getActive()) {
                    $legalDocuments[] = $legalDocument;
                }
            }
        }
        // dump($legalDocuments);exit;
        return $legalDocuments;
    }

    public function fillPlaceholders($text)
    {
        foreach ($this->getPlaceholders() as $placeholder) {
            $text = str_replace($placeholder['from'], $placeholder['to'], $text);
        }
        return $text;
    }

    public function create($rawContent)
    {
        $rawContent = str_replace("\n\n", "</p><p>", $rawContent);
        $reflector = new Reflector();
        $legalDocument = new LegalDocument();
        $propertiesArray = $reflector->getProperties($legalDocument);
        // dump($propertiesArray);exit;
        $bodyStartsAtRow = null;
        $rawContentRows = explode("\n", $rawContent);
        for ($i = 0; $i < count($rawContentRows); $i++) {
            foreach ($propertiesArray as $propertySpecs) {
                $propertyDefinitionKey = 'legalDocument'.ucfirst($propertySpecs->getName()).':';
                $pos = strpos($rawContentRows[$i], $propertyDefinitionKey);
                if ($pos !== false) {
                    if ($propertySpecs->getName() != 'body') {
                        $value = trim(str_replace($propertyDefinitionKey, '', $rawContentRows[$i]));
                        $value = str_replace("\n", "", $value);
                        $value = $this->fillPlaceholders($value);
                        $setter = 'set'.ucfirst($propertySpecs->getName());
                        $legalDocument->$setter($value);
                    }
                    else {
                        if (str_replace($propertyDefinitionKey, '', $rawContentRows[$i]) == '') {
                            $bodyStartsAtRow = $i + 1;
                        }
                        else {
                            $bodyStartsAtRow = $i;
                        }
                    }
                }
            }
        }
        $body = '<p>';
        for ($i = $bodyStartsAtRow; $i < count($rawContentRows); $i++) {
            $paragraphTitlePos = strpos($rawContentRows[$i], 'paragraphTitle:');
            if ($paragraphTitlePos !== false) {
                $body .= '<div class="paragraphTitle">'.trim($rawContentRows[$i]).'</div>';
            }
            else {
                if (trim($rawContentRows[$i]) != '') {
                    $body .= $this->fillPlaceholders(trim($rawContentRows[$i]).' ');
                }
            }
        }
        $body .= '</p>';
        $legalDocument->setBody($body);
        return $legalDocument;
    }
}
