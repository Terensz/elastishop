<?php
namespace framework\packages\ToolPackage\service;

use framework\component\parent\Service;
use framework\packages\CalculationPackage\service\Permutation;
use framework\kernel\utility\BasicUtils;

class TextAnalist extends Service
{
    private $accuracy = 3;
    private $maxWordLength = 9;
    private $similarities = array();
    public $invalidAnalysis = false;

    public function __construct($word = null) 
    {
        if ($word) {
            // dump($word);
            $this->createSimilarities($word);
        }
    }

    public function createSimilarities($word)
    {
        $this->getSimilarities($word, true);
    }

    public function findSimilarities($searchIn, $debug = false)
    {
        if ($this->invalidAnalysis) {
            return false;
        }
        // dump($searchIn);
        // dump($this->similarities);exit;
        foreach ($this->similarities as $similarity) {
            $pos = strpos($searchIn, $similarity);
            if ($pos !== false) {
                return true;
            }
        }
        return false;
    }

    public function getSimilarities($word, $createOnly = false)
    {
        $this->invalidAnalysis = false;
        // dump($word);//exit;
        if (BasicUtils::isMixedCharacterChain($word)) {
            $this->similarities = [$word];
            $this->invalidAnalysis = true;
            return $this->similarities;
        }

        $word = BasicUtils::removeNumbers($word);
        if (strlen($word) > $this->maxWordLength) {
            $word = substr($word, 0, $this->maxWordLength);
        }
        
        $this->similarities = array();
        $this->getContainer()->wireService('CalculationPackage/service/Permutation');

        for ($i = 0; $i < mb_strlen($word); $i++) {
            $newWordPart = mb_substr($word, $i, $this->accuracy);
            foreach ($this->getModifications($newWordPart) as $newWordPartModification) {
                $newWord = BasicUtils::mbReplaceSubstr($word, $newWordPartModification, $i);
                $this->addSimilarity($newWord);
            }
        }
        $this->addAllSingleCharactersToSimilarities();
        $this->addPossibleMissingCharactersToSimilarities();
        $this->addFrequentlyDoubleCharactersToSimilarities();

        return $this->similarities;
    }

    public function addSimilarity($newWord)
    {
        if (!in_array($newWord, $this->similarities)) {
            $this->similarities[] = $newWord;
        }
    }

    public function getModifications($wordPart)
    {
        $permutation = new Permutation($wordPart);
        return $permutation->getResult();
    }

    public function addAllSingleCharactersToSimilarities()
    {
        foreach ($this->similarities as $word) {
            for ($i = 0; $i < mb_strlen($word); $i++) {
                if ($i > 1 && mb_substr($word, $i, 1) == mb_substr($word, ($i - 1), 1)) {
                    $newWord = BasicUtils::mbReplaceSubstr($word, '', $i, 1);
                    $this->addSimilarity($newWord);
                }
            }
        }
    }

    public function addPossibleMissingCharactersToSimilarities()
    {
        $mostFrequentLetters = array('e');
        // $mostFrequentLetters = array('e','t','a','o','i','n','s','y');
        foreach ($this->similarities as $word) {
            for ($i = 0; $i < mb_strlen($word); $i++) {
                foreach ($mostFrequentLetters as $letter) {
                    $newWord = BasicUtils::mbReplaceSubstr($word, $letter, $i, 1);
                    $this->addSimilarity($newWord);
                }
            }
        }
    }

    public function addFrequentlyDoubleCharactersToSimilarities()
    {
        $letters = array('p','t','n','s');
        foreach ($this->similarities as $word) {
            foreach ($letters as $letter) {
                $word = str_replace($letter.$letter, $letter, $word);
                $word = str_replace($letter, $letter.$letter, $word);
                $this->addSimilarity($word);
            }
        }
    }
}
