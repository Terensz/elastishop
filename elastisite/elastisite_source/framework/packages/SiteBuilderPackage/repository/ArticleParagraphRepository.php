<?php
namespace framework\packages\SiteBuilderPackage\repository;

use App;
use framework\component\parent\DbRepository;

class ArticleParagraphRepository extends DbRepository
{
    public function getNewSequenceNumber(int $articleId) : int
    {
        $stm = "SELECT MAX(sequence_number) as max_sequence_number FROM ".$this->getTableName()." WHERE article_id = :article_id ";
        $dbm = $this->getDbManager();
        $maxSeqenceResult = $dbm->findOne($stm, [
            // 'website' => App::getWebsite(),
            'article_id' => $articleId
        ]);

        $maxSequence = $maxSeqenceResult['max_sequence_number'];
        $maxSequence = $maxSequence ? : 0;
        $maxSequence++;
        // dump($maxSequence);exit;

        return $maxSequence;
    }
}
