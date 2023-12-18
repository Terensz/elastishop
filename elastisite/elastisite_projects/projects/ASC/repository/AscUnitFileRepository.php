<?php

namespace projects\ASC\repository;

use App;
use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class AscUnitFileRepository extends DbRepository
{
    public function createCode()
    {
        $code = BasicUtils::generateRandomString(20, 'alphanum_small');
        $isCode = $this->isCode($code);
        return $isCode ? $this->createCode() : $code;
    }

    public function isCode($code)
    {
        $stm = "SELECT id FROM asc_unit_file WHERE code = :code ";
        $params = array(':code' => $code);
        $dbm = $this->getDbManager();
        $ret = $dbm->findOne($stm, $params);
        return $ret;
    }

    public function remove($id)
    {
        // $dbm = $this->getDbManager();
        // $stm = "DELETE FROM built_page_param_chain WHERE built_page_id = :built_page_id ";
        // $dbm->execute($stm, ['built_page_id' => $id]);
        // $stm = "DELETE FROM built_page_widget WHERE built_page_id = :built_page_id ";
        // $dbm->execute($stm, ['built_page_id' => $id]);

        $ascUnitFile = $this->find($id);
        if ($ascUnitFile) {
            $pathToLargeImage = FileHandler::completePath('projects/'.App::getWebProject().'/upload/userImages/large/'.$ascUnitFile->getFileName().'.'.$ascUnitFile->getExtension(), 'dynamic');
            $pathToThumbnail = FileHandler::completePath('projects/'.App::getWebProject().'/upload/userImages/thumbnail/'.$ascUnitFile->getFileName().'.'.$ascUnitFile->getExtension(), 'dynamic');
            if (is_file($pathToLargeImage)) {
                unlink($pathToLargeImage);
            }
            if (is_file($pathToThumbnail)) {
                unlink($pathToThumbnail);
            }
        }

        return parent::remove($id);
    }

}