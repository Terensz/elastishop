<?php
namespace framework\packages\SiteBuilderPackage\repository;

use App;
use framework\component\parent\DbRepository;

class ArticleRepository extends DbRepository
{
    public function find($id)
    {
        if (!$id) {
            return false;
        }
        $return = $this->findOneBy(['conditions' => [
            ['key' => 'id', 'value' => $id],
            ['key' => 'website', 'value' => App::getWebsite()]
        ]]);

        return $return;
    }
}
