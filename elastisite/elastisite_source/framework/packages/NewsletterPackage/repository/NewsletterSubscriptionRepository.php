<?php
namespace framework\packages\NewsletterPackage\repository;

use framework\component\parent\DbRepository;

class NewsletterSubscriptionRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return true;
    }

    public function store($object)
    {
        if ($object->getSubscribed() == true) {
            parent::store($object);
        }
    }
}
