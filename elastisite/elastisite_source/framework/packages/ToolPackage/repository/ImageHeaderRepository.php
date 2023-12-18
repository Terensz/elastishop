<?php
namespace framework\packages\ToolPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\FrameworkPackage\entity\OpenGraphImageHeader;

class ImageHeaderRepository extends DbRepository
{
    public function __construct()
    {
        
    }

    public function isDeletable($id)
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT count(ogih.id) as 'ogih_id_count'
        FROM image_header ih 
        INNER JOIN open_graph_image_header ogih ON ogih.image_header_id = ih.id
        WHERE ih.id = :imageHeaderId
        ";
        $result = $dbm->findOne($stm, ['imageHeaderId' => $id])['ogih_id_count'];

        return (int)$result == 0 ? true : false;
    }

    public function getOpenGraphGalleryImageHeaders()
    {
        $this->getContainer()->wireService('FrameworkPackage/entity/OpenGraphImageHeader');

        $dbm = $this->getDbManager();
        $stm = "SELECT ih.id as ih_id
        FROM image_header ih 
        INNER JOIN image_file ifi ON ifi.image_header_id = ih.id 
        INNER JOIN file f ON f.id = ifi.file_id
        WHERE f.website = '".App::getWebsite()."'
        AND f.gallery_name = :galleryName 
        AND ifi.image_type = :imageType
        ";
        $imageHeaderIds = $dbm->findAll($stm, [
            'galleryName' => OpenGraphImageHeader::GALLERY_NAME,
            'imageType' => OpenGraphImageHeader::THUMBNAIL_IMAGE_TYPE
        ]);
// dump($imageHeaderIds);exit;

        $result = [];
        foreach ($imageHeaderIds as $imageHeaderId) {
            $result[] = $this->find($imageHeaderId['ih_id']);
        }
        
        return $result;
    }

    public function remove($id, $path = null)
    {
        // $pdo = $this->getContainer()->getKernelObject('DbManager')->getConnection();
        if (!$this->isDeletable($id)) {
            return false;
        }
        // $imageHeader = $this->find($id);
        // $temporaryAccount = $shipment->getTemporaryAccount();
		// $pdo->beginTransaction();
        $this->getContainer()->setService('ToolPackage/repository/ImageFileRepository');
        $imageFileRepo = $this->getContainer()->getService('ImageFileRepository');

        $this->getContainer()->setService('ToolPackage/repository/FileRepository');
        $fileRepo = $this->getContainer()->getService('FileRepository');

        $dbm = $this->getDbManager();
        $stm = "SELECT imf.id as 'image_file_id', f.id as 'file_id', f.file_name as 'file_name', f.extension as 'extension'
        FROM image_header ih 
        LEFT JOIN image_file imf ON imf.image_header_id = ih.id
        LEFT JOIN file f ON f.id = imf.file_id 
        WHERE ih.id = :ih_id
        ";
        $result = $dbm->findAll($stm, ['ih_id' => $id]);

        foreach ($result as $row) {
            $fileRepo->remove($row['file_id']);
            $imageFileRepo->remove($row['image_file_id']);

            if ($path) {
                @unlink($path.'/'.$row['file_name'].'.'.$row['extension']);
            }
        }

        parent::remove($id);
        // $pdo->commit();
    }

    // public function remove_OLD($id, $path = null)
    // {
    //     $pdo = $this->getContainer()->getKernelObject('DbManager')->getConnection();
    //     if (!$this->isDeletable($id)) {
    //         return false;
    //     }
    //     // $imageHeader = $this->find($id);
    //     // $temporaryAccount = $shipment->getTemporaryAccount();
	// 	$pdo->beginTransaction();
	// 	try {
    //         $this->getContainer()->setService('ToolPackage/repository/ImageFileRepository');
    //         $imageFileRepo = $this->getContainer()->getService('ImageFileRepository');

    //         $this->getContainer()->setService('ToolPackage/repository/FileRepository');
    //         $fileRepo = $this->getContainer()->getService('FileRepository');

    //         $dbm = $this->getDbManager();
    //         $stm = "SELECT imf.id as 'image_file_id', f.id as 'file_id', f.file_name as 'file_name', f.extension as 'extension'
    //         FROM image_header ih 
    //         LEFT JOIN image_file imf ON imf.image_header_id = ih.id
    //         LEFT JOIN file f ON f.id = imf.file_id 
    //         WHERE ih.id = :ih_id
    //         ";
    //         $result = $dbm->findAll($stm, ['ih_id' => $id]);

    //         foreach ($result as $row) {
    //             $fileRepo->remove($row['file_id']);
    //             $imageFileRepo->remove($row['image_file_id']);

    //             if ($path) {
    //                 @unlink($path.'/'.$row['file_name'].'.'.$row['extension']);
    //             }
    //         }

    //         parent::remove($id);

	// 		$pdo->commit();
	// 	} catch(\Exception $e) {
    //         dump($e);
    //         dump('kecs');exit;
	// 		$pdo->rollback();
	// 	}
    // }
}
