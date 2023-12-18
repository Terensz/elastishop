<?php
namespace framework\packages\WordClearingPackage\service;

use framework\component\parent\Service;
use framework\kernel\utility\BasicUtils;
use framework\packages\WordClearingPackage\repository\WordExplanationRepository;

class WordExplanationService extends Service
{
    private $keyTexts;
    private $usedKeyTexts = [];

    public function getWordExplanationText($keyText)
    {
        $this->getContainer()->wireService('WordClearingPackage/repository/WordExplanationRepository');
        $wordExplanationRepo = new WordExplanationRepository();
        $wordExplanation = $wordExplanationRepo->findOneBy(['conditions' => [
            ['key' => 'key_text', 'value' => $keyText]
        ]]);
        $result = '';
        if ($wordExplanation) {
            $result = html_entity_decode($wordExplanation->getExplanation());
        }
        return $result;
    }

    public function processWordExplanations($text)
    {
        $this->getContainer()->wireService('WordClearingPackage/repository/WordExplanationRepository');
        $wordExplanationRepo = new WordExplanationRepository();
        $wordExplanations = $wordExplanationRepo->findAll();
        foreach ($wordExplanations as $wordExplanation) {
            $rawKeyText = $wordExplanation->getKeyText();
            $keyText = BasicUtils::mbUcfirst($rawKeyText);
            if (mb_strpos($text, $keyText) !== false) {
                $this->keyTexts[] = $keyText;
                $text = $this->markWords($text, $keyText);
                $text = $this->processMarkedWords($text, $keyText);
            }
            $keyText = $rawKeyText;
            if (mb_strpos($text, $keyText) !== false) {
                $this->keyTexts[] = $keyText;
                $text = $this->markWords($text, $keyText);
                $text = $this->processMarkedWords($text, $keyText);
            }
        }
        return $text;
    }

    public function markWords($text, $keyText)
    {
        $text = str_replace($keyText, '<wordExplanation>'.$keyText.'</wordExplanation>', $text);
        return $text;
    }

    public function processMarkedWords($text, $keyText, $round = 1)
    {
        $wrappedKeywordLength = mb_strlen('<wordExplanation>'.$keyText.'</wordExplanation>');
        $pos = mb_strpos($text, '<wordExplanation>');
        if ($pos === false) {
            return $text;
        }
        $prevChar = mb_substr($text, ($pos - 1), 1);
        // dump($this->getProjectData('wordExplanationFirstOccurrencyOnly'));exit;
        if ($this->getProjectData('wordExplanationFirstOccurrencyOnly') && in_array(mb_strtolower($keyText), $this->usedKeyTexts)) {
            $text = BasicUtils::mbSubstrReplace($text, $keyText, $pos, $wrappedKeywordLength);
        } else {
            $this->usedKeyTexts[] = mb_strtolower($keyText);
            if (in_array(ord($prevChar), [32, 10])) {
                $text = BasicUtils::mbSubstrReplace($text, '<span class="wordExplanation">'.$keyText.'</span>', $pos, $wrappedKeywordLength);
            } else {
                $text = BasicUtils::mbSubstrReplace($text, $keyText, $pos, $wrappedKeywordLength);
            }
        }
        $posCheck = mb_strpos($text, '<wordExplanation>');
        if ($posCheck === false) {
            return $text;
        } else {
            return $this->processMarkedWords($text, $keyText, ($round + 1));
        }
    }
}
