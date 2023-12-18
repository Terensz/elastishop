<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\TechnicalRepository;
use projects\ASC\entity\AscEntry;

class TechnicalScaleHeaderRepository extends TechnicalRepository
{
    public function find($id)
    {
        // dump($id);exit;
        App::getContainer()->wireService('projects/ASC/repository/AscScaleRepository');
        $ascScaleRepository = new AscScaleRepository();
        $ascScale = $ascScaleRepository->find($id);
        $technicalScaleHeader = $this->createNewEntity();
        $scaleEntry = null;
        if ($ascScale) {
            $scaleEntryHead = $ascScale->getAscEntryHead();
            $scaleEntry = $scaleEntryHead->findEntry();
            if (!$scaleEntry) {
                App::getContainer()->wireService('projects/ASC/entity/AscEntry');
                $scaleEntry = new AscEntry();
            }
            $technicalScaleHeader->setId($ascScale->getId());
            $technicalScaleHeader->setSituation($ascScale->getSituation());
            $technicalScaleHeader->setInitialLanguage($scaleEntry->getLanguageCode());
            $technicalScaleHeader->setTitle($scaleEntry->getTitle());
            $technicalScaleHeader->setDescription($scaleEntry->getDescription());
            $technicalScaleHeader->setStatus($ascScale->getStatus());
        }

        // dump($technicalScaleHeader);
        // dump($scaleEntry);exit;
        // $scaleHeaderRepo = 

        return $technicalScaleHeader;
    }
}