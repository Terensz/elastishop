<?php
namespace projects\ASC\controller;

use App;
use framework\component\parent\WidgetController;
use framework\packages\ToolPackage\service\UploadOrganizer;
use projects\ASC\entity\AscUnitFile;
use projects\ASC\repository\AscUnitFileRepository;
use projects\ASC\repository\AscUnitRepository;
use projects\ASC\service\AscPermissionService;
use projects\ASC\service\AscRequestService;

class AscUploadController extends WidgetController 
{
    /**
    * Route: [name: asc_upload, paramChain: /asc/upload]
    */
    public function ascUploadAction($new = false)
    {
        App::getContainer()->wireService('ToolPackage/service/UploadOrganizer');
        App::getContainer()->wireService('ToolPackage/service/ImageUploader');
        App::getContainer()->wireService('projects/ASC/service/AscRequestService');
        App::getContainer()->wireService('projects/ASC/service/AscPermissionService');
        App::getContainer()->wireService('projects/ASC/entity/AscUnitFile');

        $ascUnitId = (int)App::getContainer()->getRequest()->get('unitId');
        $ascUnitProperties = AscRequestService::findUnitAndGetProperties($ascUnitId);
        $ascUnit = $ascUnitProperties['ascUnitObject'];

        $ascUnitFile = null;
        
        if ($ascUnit && !$ascUnitProperties['errorMessage']) {
            $uploadOrganizer = new UploadOrganizer();
            $ascUnitFile = new AscUnitFile();
            $ascUnitFileRepository = $ascUnitFile->getRepository();
            $code = $ascUnitFileRepository->createCode();
            $ascUnitFile->setCode($code);
            $uploadOrganizer->newFileName = $code;
            $uploadOrganizer->upload();
            if (isset($uploadOrganizer->uploadResult['success']) && $uploadOrganizer->uploadResult['success'] == true) {
                $ascUnitFile->setAscUnit($ascUnit);
                $ascUnitFile->setFileName($code);
                $ascUnitFile->setExtension($uploadOrganizer->extension);
                $ascUnitFile->setSubType(UploadOrganizer::FILE_SUBTYPE_IMAGE);
                $ascUnitFile = $ascUnitFile->getRepository()->store($ascUnitFile);
            }
        } else {
            throw new \Exception('Invalid ascUnit');
        }

        // var_dump(App::getContainer()->getUploadRequest()->get());exit;
        // var_dump('-=ascUploadAction=-');

        // var_dump($uploadArranger);exit;
        $response = [
            'view' => '',
            'data' => [
                'ascUnitId' => $ascUnitId,
                'storedAscUnitFileId' => $ascUnitFile ? $ascUnitFile->getId() : null,
                'upload' => $uploadOrganizer
            ]
        ];

        // dump('hello!!!!');exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_upload_previewBar, paramChain: /asc/upload/previewBar]
    */
    public function ascUploadPreviewBarAction()
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitFileRepository');
        $ascUnitFileRepository = new AscUnitFileRepository();
        $ascUnitId = (int)App::getContainer()->getRequest()->get('unitId');
        $ascUnitFiles = $ascUnitFileRepository->findBy(['conditions' => [['key' => 'asc_unit_id', 'value' => $ascUnitId]]]);
           
        $viewPath = 'projects/ASC/view/AscScaleBuilder/Uploader/previewBar.php';
        $response = [
            'view' => $this->renderWidget('AscScaleBuilder_Uploader_previewBar', $viewPath, [
                'ascUnitFiles' => $ascUnitFiles
            ]),
            'data' => [
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: asc_upload_delete, paramChain: /asc/upload/delete]
    */
    public function ascUploadDeleteAction()
    {
        App::getContainer()->wireService('projects/ASC/repository/AscUnitFileRepository');
        $ascUnitFileRepository = new AscUnitFileRepository();
        $ascUnitFileId = (int)App::getContainer()->getRequest()->get('fileId');
        $ascUnitFile = $ascUnitFileRepository->find($ascUnitFileId);
        if ($ascUnitFile) {
            $ascUnitFileRepository->remove($ascUnitFile->getId());
        }
           
        $viewPath = 'projects/ASC/view/AscScaleBuilder/Uploader/html/previewBar.php';
        $response = [
            'view' => '',
            'data' => [
            ]
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }
}