<?php
namespace framework\packages\SiteBuilderPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;

class ContentEditorRepository extends DbRepository
{
    public function find($id)
    {
        if (!$id) {
            return null;
        }

        $entity = $this->findOneBy([
            'conditions' => [
                ['key' => 'id', 'value' => $id],
                ['key' => 'website', 'value' => App::getWebsite()]
            ]
        ]);

        return $entity ? : null;
    }

    public function createCode()
    {
        $code = substr(time(), -6).'_'.BasicUtils::generateRandomString(12);

        return $code;
    }
}
