<?php
namespace framework\packages\FrameworkPackage\repository;

use App;
use framework\component\parent\DbRepository;

class OpenGraphImageHeaderRepository extends DbRepository
{

    public function findAllOnWebsite()
    {
        $all = parent::findAll();
        $allOnWebsite = [];
        foreach ($all as $entity) {
            if ($entity->getOpenGraph()->getWebsite() == App::getWebsite()) {
                $allOnWebsite[] = $entity;
            }
        }
        // dump($allOnWebsite);exit;

        return $allOnWebsite;
    }

    public function isDeletable($id)
    {
        // $dbm = $this->getDbManager();
        // $stm = "SELECT count(ogih.id) as 'ogih_id_count'
        // FROM open_graph_image_header ogih
        // INNER JOIN image_header ih ON ogih.image_header_id = ih.id
        // INNER JOIN open_graph og ON og.id = ogih.open_graph_id
        // WHERE ogih.id = :open_graph_image_header_id
        // AND og.website = '".App::getWebsite()."'
        // ";

        // $result = $dbm->findOne($stm, ['open_graph_image_header_id' => $id])['ogih_id_count'];
        // return (int)$result == 0 ? true : false;
        return true;
    }
}
