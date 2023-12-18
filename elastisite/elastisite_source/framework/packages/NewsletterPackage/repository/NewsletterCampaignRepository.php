<?php
namespace framework\packages\NewsletterPackage\repository;

use framework\component\parent\DbRepository;

class NewsletterCampaignRepository extends DbRepository
{
    public function isDeletable($id)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT count(a.id) as 'bound_count'
        FROM newsletter_campaign a
        INNER JOIN newsletter_dispatch_process b ON b.newsletter_campaign_id = a.id 
        WHERE a.id = :id
        ";
        $result = $dbm->findOne($stm, ['id' => $id])['bound_count'];
        return (int)$result == 0 ? true : false;
    }

    public function isEditable($id)
    {
        return $id ? $this->isDeletable($id) : true;
    }
}
