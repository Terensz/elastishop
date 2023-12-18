<?php
namespace framework\packages\NewsletterPackage\repository;

use framework\component\parent\DbRepository;

class NewsletterDispatchRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return true;
    }

    public function isEditable($id)
    {
        return $id ? $this->isDeletable($id) : true;
    }
}
