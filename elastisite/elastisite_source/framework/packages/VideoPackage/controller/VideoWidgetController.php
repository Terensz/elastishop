<?php
namespace framework\packages\VideoPackage\controller;

use framework\component\parent\WidgetController;
use framework\packages\ToolPackage\service\ImageService;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\packages\VideoPackage\service\VideoStream;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\VideoPackage\entity\Video;
use framework\packages\VideoPackage\repository\VideoRepository;
use framework\packages\VideoPackage\repository\SelectedVideoRepository;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\ToolPackage\service\VideoUploader;
// use framework\packages\VideoPackage\service\VideoService;

class VideoWidgetController extends WidgetController
{
    public function getVideoService()
    {
        $this->setService('VideoPackage/service/VideoService');
        return $this->getService('VideoService');
    }

    public function getFilePath()
    {
        return $this->getVideoService()->getFilePath();
    }

    public function cleanUpUnusedFiles($exceptCode, $exceptExtension)
    {
        return $this->getVideoService()->cleanUpUnusedFiles($exceptCode, $exceptExtension);
    }

    /**
    * Route: [name: videoBox_VideoBoxWidget, paramChain: /videoBox/VideoBoxWidget]
    */
    public function videoBoxWidgetAction()
    {
        // $viewPath = 'framework/packages/VideoPackage/view/widget/VideoBoxWidget/widget.php';
        // $response = [
        //     'view' => $this->renderWidget('VideoBoxWidget', $viewPath, [
        //         'container' => $this->getContainer(),
        //     ]),
        //     'data' => []
        // ];

        $viewPath = 'framework/packages/VideoPackage/view/widget/VideoBoxWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('VideoBoxWidget', $viewPath, [
                'container' => $this->getContainer(),
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: videoPlayer_VideoPlayerWidget, paramChain: /videoPlayer/VideoPlayerWidget]
    */
    public function videoPlayerWidgetAction()
    {
        $this->getContainer()->wireService('VideoPackage/repository/SelectedVideoRepository');
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');

        $selectedVideoRepo = new SelectedVideoRepository();
        $selectedVideo = $selectedVideoRepo->findFirst();
// dump($selectedVideo);exit;
        if ($selectedVideo && $selectedVideo->getVideo()) {
            $videoRepo = new VideoRepository();
            $video = $videoRepo->find($selectedVideo->getVideo()->getId());
        } else {
            $video = null;
        }

        // dump($video);exit;

        $viewPath = 'framework/packages/VideoPackage/view/widget/VideoPlayerWidget/widget.php';
        
        $response = [
            'view' => $this->renderWidget('VideoPlayerWidget', $viewPath, [
                'widgetName' => $video ? 'hasSelected' : 'doNotHasSelected',
                'video' => $video,
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        // $this->getContainer()->wireService('VideoPackage/service/VideoStream');
        // $videoStream = new VideoStream(FileHandler::completePath('video/demo/demo.mp4', 'dynamic'));
        // $response = [
        //     'view' => $videoStream->start(),
        //     'data' => []
        // ];

        // dump($response);exit;
        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_videos_widget, paramChain: /admin/videos/widget]
    */
    public function adminVideosWidgetAction()
    {
        // $this->setService('VideoPackage/repository/VideoRepository');
        // $repo = $this->getService('VideoRepository');
        // dump($repo->find(35007));

        // $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        // $repo = new VideoRepository();
        // $videoKey = $this->createVideoKey();

        // dump($this->createVideoKey());exit;
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $this->setService('VideoPackage/entity/Video');
        $this->setService('VideoPackage/repository/VideoRepository');
        $repo = $this->getService('VideoRepository');
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        
        $dataGridBuilder = new DataGridBuilder('AdminVideosDataGrid');
        $dataGridBuilder->setValueConversion(['status' => Video::STATUS_CODE_CONVERSIONS]);
        $dataGridBuilder->setPrimaryRepository($repo);
        $dataGrid = $dataGridBuilder->getDataGrid();
        $response = $dataGrid->render();

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_handleVideos_widget, paramChain: /admin/handleVideos/widget]
    */
    public function adminHandleVideosWidgetAction()
    {
        $this->getContainer()->wireService('VideoPackage/repository/SelectedVideoRepository');
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');

        $videoRepo = new VideoRepository();
        $videos = $videoRepo->findAll();

        $selectedVideoRepo = new SelectedVideoRepository();
        $selectedVideo = $selectedVideoRepo->findFirst();
        if (!$selectedVideo) {
            $selectedVideo = $selectedVideoRepo->createNewEntity();
            $selectedVideo->setVideo($videoRepo->createNewEntity());
        }
        // dump($selectedVideo);exit;

        $viewPath = 'framework/packages/VideoPackage/view/widget/AdminHandleVideosWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminHandleVideosWidget', $viewPath, [
                'container' => $this->getContainer(),
                'videos' => $videos,
                'selectedVideo' => $selectedVideo
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_handleVideos_selectVisitorVideo, paramChain: /admin/handleVideos/selectVisitorVideo]
    */
    public function adminHandleVideosSelectVisitorVideoAction()
    {
        $success = true;
        $this->getContainer()->wireService('VideoPackage/repository/SelectedVideoRepository');
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        $videoRepo = new VideoRepository();
        $selectedVideoRepo = new SelectedVideoRepository();
        $requestedVideo = $this->getContainer()->getRequest()->get('selectedVideo_video');
        if ($requestedVideo == '*null*') {
            $selectedVideoRepo->removeAll();
            // $selectedVideo = $selectedVideoRepo->createNewEntity();
            // $selectedVideo->setVideo(null);
            // $selectedVideoRepo->store($selectedVideo);
            $message = trans('video.successfully.removed');
        } else {
            $video = $videoRepo->find((int)$requestedVideo);
            if ($video) {
                $selectedVideoRepo->removeAll();
                $selectedVideo = $selectedVideoRepo->createNewEntity();
                $selectedVideo->setVideo($video);
                $selectedVideoRepo->store($selectedVideo);
                $message = (isset($selectedVideo) ? $selectedVideo->getVideo()->getTitle() : '').' '.trans('successfully.set');
            } else {
                $success = false;
                $message = trans('error.occurred.while.selecting.video');
            }
        }
        
        $response = [
            'view' => '',
            'data' => [
                'success' => $success,
                'message' => $message
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_video_new, paramChain: /admin/video/new]
    */
    public function adminVideoNewAction()
    {
        return $this->adminVideoEditAction();

        // $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        // $repo = new VideoRepository();
        // $id = (int)$this->getContainer()->getRequest()->get('videoId');
        // $viewPath = 'framework/packages/VideoPackage/view/widget/AdminVideosWidget/editVideo.php';
        // $response = [
        //     'view' => $this->renderWidget('newProductImage', $viewPath, [
        //         'container' => $this->getContainer(),
        //         'video' => $repo->createNewEntity(),
        //         // 'form' => $form,
        //         'videoId' => $id
        //         // 'productCategories' => $productCategoryRepo->findAll()
        //     ]),
        //     'data' => []
        // ];
        // return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_video_edit, paramChain: /admin/video/edit]
    */
    public function adminVideoEditAction()
    {
        // $this->wireService('FormPackage/service/FormBuilder');
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        $repo = new VideoRepository();
        $video = $repo->find($id);
        if (!$video) {
            $video = $repo->createNewEntity();
        }

        // dump($video);exit;
        // $formBuilder = new FormBuilder();
        // $formBuilder->setPackageName('VideoPackage');
        // $formBuilder->setSubject('editVideo');
        // $formBuilder->setPrimaryKeyValue($id);
        // $formBuilder->addExternalPost('id');
        // $formBuilder->addExternalPost('VideoPackage_editVideo_file');
        // $form = $formBuilder->createForm();
        // dump($form);exit;
        $viewPath = 'framework/packages/VideoPackage/view/widget/AdminVideosWidget/videoEdit.php';
        $response = [
            'view' => $this->renderWidget('videoEdit', $viewPath, [
                'id' => $id,
                'container' => $this->getContainer(),
                'video' => $video
            ]),
            'data' => [
                'label' => trans('edit.video')
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_video_editForm, paramChain: /admin/video/editForm]
    */
    public function adminVideoEditFormAction()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $id = (int)$this->getContainer()->getRequest()->get('id');
        // $id = 35007;
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('VideoPackage');
        $formBuilder->setSubject('editVideo');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        $formBuilder->addExternalPost('VideoPackage_editVideo_file');
        $formBuilder->setSaveRequested(false);
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($this->getContainer()->getRequest()->get('submitted') ? : false);
        $form = $formBuilder->createForm();

        if ($form->isSubmitted() || ($this->getContainer()->getRequest()->get('code') != '' && $this->getContainer()->getRequest()->get('extension') != '')) {
            $form->getEntity()->setCode($this->getContainer()->getRequest()->get('code'));
            $form->getEntity()->setExtension($this->getContainer()->getRequest()->get('extension'));
        }

        if ($form->isValid()) {
            $repo = $form->getEntity()->getRepository();
            $repo->store($form->getEntity());
        }
        // dump($form);exit;
        $viewPath = 'framework/packages/VideoPackage/view/widget/AdminVideosWidget/videoEditForm.php';
        $response = [
            'view' => $this->renderWidget('videoEditForm', $viewPath, [
                'container' => $this->getContainer(),
                'formIsValid' => $form->isValid(),
                'form' => $form,
                'formBuilder' => $formBuilder
            ]),
            'data' => [
                'submitted' => $formBuilder->getSubmitted(),
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_video_upload, paramChain: /admin/video/upload]
    */
    public function adminVideoUploadAction()
    {
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        $repo = new VideoRepository();

        $this->wireService('ToolPackage/service/VideoUploader');
        $upload = $this->getContainer()->getKernelObject('UploadRequest')->get(0);

        $fileNames = FileHandler::getAllFileNames($this->getFilePath(), 'keep');
        foreach ($fileNames as $faviconName) {
            // FileHandler::unlinkFile($this->getFilePath().'/'.$faviconName);
        }

        // $ext = BasicUtils::explodeAndGetElement($upload->getName(), '.', 'last');
        $uploader = new VideoUploader();
        $filePath = $this->getFilePath();
        $code = $repo->createCode();
        $uploader->setFilePath($filePath);
        $uploader->setFileName($code);
        // dump($uploader); exit;
        $uploadResult = $uploader->upload();
        $extension = null;
        if ($uploadResult['success']) {
            $extension = $uploadResult['data']['extension'];
            // $video = $repo->createNewEntity();
            // $video->setCode($code);
            // $video->setExtension($extension);
            $this->cleanUpUnusedFiles($code, $extension);
        }

        $response = [
            'view' => '',
            'data' => [
                'mime' => $upload->getMime(),
                // 'filePath' => $filePath,
                'uploadResult' => $uploadResult,
                'code' => $code,
                'extension' => $extension
            ]
        ];

        return new JsonResponse($response);
    }

    /**
    * Route: [name: admin_video_delete, paramChain: /admin/video/delete]
    */
    public function adminVideoDeleteAction()
    {
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        $repo = new VideoRepository();
        $repo->remove($this->getContainer()->getRequest()->get('id'));

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_video_unbindFile, paramChain: /admin/video/unbindFile]
    */
    public function adminVideoUnbindFileAction()
    {
        // $code = $this->getContainer()->getRequest()->get('code');
        // $codeParts = explode('_', $code);
        // $extension = $this->getContainer()->getRequest()->get('extension');
        // $pathToFile = $this->getFilePath().'/'.$code.'.'.$extension;
        // $errorMessage = null;
        // if (isset($codeParts[0]) && $codeParts[0] == $this->getSession()->get('visitorCode')) {
        $success = true;
        $errorMessage = '';
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        $repo = new VideoRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');
        // $video = $repo->findOneBy(['conditions' => [['key' => 'code', 'value' => $code], ['key' => 'extension', 'value' => $extension]]]);
        $video = $repo->find($id);
        if ($video) {
            $video->setCode(null);
            $video->setExtension(null);
            $repo->store($video);
            // if (is_file($pathToFile)) {
            //     unlink($pathToFile);
            // }
        } else {
            $errorMessage = trans('non.existing.video');
            $success = false;
        }

        // dump($video);exit;

        $response = [
            'view' => '',
            'data' => [
                'id' => $id,
                'success' => $success,
                'errorMessage' => $errorMessage
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_video_bindFile, paramChain: /admin/video/bindFile]
    */
    public function adminVideoBindFileAction()
    {
        $success = true;
        $errorMessage = '';
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        $repo = new VideoRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $video = $repo->find($id);
        if ($video) {
            $video->setCode($this->getContainer()->getRequest()->get('code'));
            $video->setExtension($this->getContainer()->getRequest()->get('extension'));
            $repo->store($video);
            // if (is_file($pathToFile)) {
            //     unlink($pathToFile);
            // }
        } else {
            $errorMessage = trans('non.existing.video');
            $success = false;
        }

        $response = [
            'view' => '',
            'data' => [
                'success' => $success,
                'errorMessage' => $errorMessage
            ]
        ];

        return $this->widgetResponse($response);
    }
}
