<?php
namespace framework\packages\SiteBuilderPackage\repository;

use framework\component\parent\DbRepository;

class ContentEditorUnitRepository extends DbRepository
{
    public function store($entity)
    {
        if (!$entity->getSequenceNumber()) {
            $stm = "SELECT MAX(sequence_number) as max_seq FROM content_editor_unit ";
            $dbm = $this->getDbManager();
            $maxSeqArray = $dbm->findOne($stm);
            $maxSeq = $maxSeqArray ? (int)$maxSeqArray['max_seq'] : 0;
            $seq = $maxSeq + 1;

            $entity->setSequenceNumber($seq);
        }

        return parent::store($entity);
    }

    public function updateSequence(int $id, int $sequence)
    {
        $stm = "UPDATE content_editor_unit SET sequence_number = :sequence_number WHERE id = :id ";
        $dbm = $this->getDbManager();
        $dbm->execute($stm, [
            'sequence_number' => $sequence,
            'id' => $id
        ]);
    }
}
