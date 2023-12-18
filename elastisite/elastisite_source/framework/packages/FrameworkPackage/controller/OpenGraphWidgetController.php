<?php
namespace framework\packages\FrameworkPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\FileHandler;
use framework\packages\ToolPackage\service\ImageProcessor;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\FrameworkPackage\repository\OpenGraphRepository;
use framework\packages\DataGridPackage\service\DataGridBuilder;
use framework\packages\ToolPackage\repository\ImageHeaderRepository;
use framework\packages\FrameworkPackage\repository\OpenGraphImageHeaderRepository;
use framework\packages\FrameworkPackage\entity\OpenGraphImageHeader;

class OpenGraphWidgetController extends WidgetController
{
    public function getOpenGraphAbsoluteImageDir()
    {
        return $this->getOpenGraphService()->getOpenGraphAbsoluteImageDir();
    }

    public function getOpenGraphRelativeImageDir()
    {
        return $this->getOpenGraphService()->getOpenGraphRelativeImageDir();
    }

    private function getRepository()
    {
        $this->setService('FrameworkPackage/repository/OpenGraphRepository');
        return $this->getService('OpenGraphRepository');
    }

    private function getOpenGraphService()
    {
        $this->setService('FrameworkPackage/service/OpenGraphService');
        return $this->getService('OpenGraphService');
    }

    public function cleanUpUnusedFiles($exceptCode, $exceptExtension)
    {
        return $this->getOpenGraphService()->cleanUpUnusedFiles($exceptCode, $exceptExtension);
    }

    public function renderInfoBlock()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminOpenGraphsWidget/infoBlock.php';
        return $this->renderWidget('openGraphEditInfoBlock', $viewPath, [
            'container' => $this->getContainer()
        ]);
    }

    public function renderImagesBlock()
    {
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminOpenGraphsWidget/imagesBlock.php';
        return $this->renderWidget('openGraphEditImagesBlock', $viewPath, [
            'container' => $this->getContainer()
        ]);
    }

    /**
    * name: admin_openGraphs_widget, paramChain: /admin/openGraphs/widget
    * This version handles 1 open graph.
    */
    public function adminOpenGraphsWidgetAction()
    {
        $response = $this->adminOpenGraphsListAction(false);
        $infoBlock = $this->renderInfoBlock();
        $imagesBlock = $this->renderImagesBlock();
        $response['view'] = $infoBlock.$imagesBlock.$response['view'];
        $response = $this->widgetResponse($response);

        return $response;
    }

    /**
    * name: admin_openGraphs_list, paramChain: /admin/openGraphs/list
    * This version handles 1 open graph.
    */
    public function adminOpenGraphsListAction($widgetResponse = true)
    {
        $this->wireService('DataGridPackage/service/DataGridBuilder');
        $repo = $this->getRepository();
        $dataGridBuilder = new DataGridBuilder('AdminOpenGraphsGrid');
        $dataGridBuilder->setCreateNewText(trans('create.new.open.graph'));
        $dataGridBuilder->setPrimaryRepository($repo);
        $dataGrid = $dataGridBuilder->getDataGrid();
        $dataGrid->setListActionUrl($this->getUrl()->getHttpDomain().'/admin/openGraphs/list');
        $dataGrid->setJavaScriptOnDeleteConfirmed('OpenGraphImageHandler.loadGallery();');
        $response = $dataGrid->render();

        return $widgetResponse ? $this->widgetResponse($response) : $response;
    }

    /**
    * Route: [name: admin_openGraph_editForm, paramChain: /admin/openGraph/editForm]
    */
    public function adminOpenGraphEditFormAction()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $this->wireService('ToolPackage/repository/ImageHeaderRepository');
        $this->wireService('FrameworkPackage/repository/OpenGraphImageHeaderRepository');

        $id = (int)$this->getContainer()->getRequest()->get('id');
        $isSubmitted = $this->getContainer()->getRequest()->get('submitted');

        $imageHeaderId = (int)$this->getContainer()->getRequest()->get('imageHeaderId');
        $openGraphImageHeaderId = (int)$this->getContainer()->getRequest()->get('openGraphImageHeaderId');
        $imageHeaderRepo = new ImageHeaderRepository();
        $imageHeader = $imageHeaderRepo->find($imageHeaderId);

        // $openGraphImageError = false;
        $openGraphImageErrorMessage = '';

        if (!$imageHeader) {
            $imageHeader = null;
            $imageHeaderId = null;
        }

        // $id = 35007;
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('FrameworkPackage');
        $formBuilder->setSubject('openGraphEdit');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->addExternalPost('id');
        $formBuilder->addExternalPost('openGraphImage');
        $formBuilder->setSaveRequested(false);
        $formBuilder->setAutoSubmit(false);
        $formBuilder->setSubmitted($isSubmitted ? : false);
        $form = $formBuilder->createForm();
        $openGraph = $form->getEntity();

        // if ($form->isSubmitted() || $imageHeaderId) {

        // }

        if ($form->isValid() && $imageHeaderId) {
            $openGraphRepo = $openGraph->getRepository();

            if (!$openGraph->getId()) {
                $openGraph = $openGraphRepo->store($openGraph);
            }

            // $form->getEntity()->setCode();
            $this->getOpenGraphService()->removeOpenGraphImageHeaders($openGraph->getId());
            $openGraphImageHeaderRepo = new OpenGraphImageHeaderRepository();
            if ($openGraphImageHeaderId) {
                $openGraphImageHeader = $openGraphImageHeaderRepo->find($openGraphImageHeaderId);
            }
            $openGraphImageHeader = $openGraphImageHeader ? : $openGraphImageHeaderRepo->createNewEntity();
            $openGraphImageHeader->setOpenGraph($openGraph);
            // dump($imageHeaderId);
            // dump($imageHeader);exit;
            $openGraphImageHeader->setImageHeader($imageHeader);
            $openGraphImageHeader = $openGraphImageHeaderRepo->store($openGraphImageHeader);
            $openGraph->addOpenGraphImageHeader($openGraphImageHeader);
            $openGraphRepo->store($openGraph);
            // $form->getEntity()->setExtension($this->getContainer()->getRequest()->get('extension'));
        }

        if ($form->isValid() && !$imageHeaderId) {
            $form->setValid(false);
            // $openGraphImageError = true;
            $openGraphImageErrorMessage = trans('please.choose.an.image');
        }

        // dump($form);exit;
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminOpenGraphsWidget/openGraphEditForm.php';
        $response = [
            'view' => $this->renderWidget('openGraphEditForm', $viewPath, [
                'container' => $this->getContainer(),
                // 'openGraphImageError' => $openGraphImageError,
                'openGraphImageErrorMessage' => $openGraphImageErrorMessage,
                // 'mainImageHeaderId' => $imageHeaderId,
                'formIsValid' => $form->isValid(),
                'form' => $form,
                'formBuilder' => $formBuilder
            ]),
            'data' => [
                'label' => $form->getEntity()->getId() ? trans('edit.open.graph') : trans('new.open.graph'),
                'submitted' => $formBuilder->getSubmitted(),
                'formIsValid' => $form->isValid(),
                'id' => $openGraph->getId(),
                'imageHeaderId' => $imageHeaderId,
                'openGraphImageHeaderId' => $openGraphImageHeaderId,
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getOpenGraphImageAbsolutePath()
    {
        $fileNames = FileHandler::getAllFileNames($this->getOpenGraphAbsoluteImageDir());
        if ($fileNames == array()) {
            return null;
        }
        foreach ($fileNames as $fileName) {
            return $this->getOpenGraphAbsoluteImageDir().'/'.$fileName;
        }

        return null;
    }

    /**
    * name: admin_openGraph_new, paramChain: /admin/openGraph/new
    */
    public function adminOpenGraphNewAction()
    {
        return $this->adminOpenGraphEditAction();
    }

    /**
    * name: admin_openGraph_edit, paramChain: /admin/openGraph/edit
    */
    public function adminOpenGraphEditAction()
    {
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $repo = $this->getRepository();
        $openGraph = $repo->find($id);
        if (!$openGraph) {
            $openGraph = $repo->createNewEntity();
        }

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminOpenGraphsWidget/openGraphEdit.php';
        $response = [
            'view' => $this->renderWidget('openGraphEdit', $viewPath, [
                'id' => $id,
                'container' => $this->getContainer(),
                'openGraph' => $openGraph
            ]),
            'data' => [
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_openGraph_uploadImage, paramChain: /admin/openGraph/uploadImage]
    */
    public function adminOpenGraphUploadImageAction()
    {
        // dump('adminOpenGraphUploadImageAction');
        $this->getContainer()->wireService('FrameworkPackage/entity/OpenGraphImageHeader');
        $this->getContainer()->wireService('FrameworkPackage/repository/OpenGraphRepository');
        $repo = new OpenGraphRepository();

        $this->getContainer()->wireService('ToolPackage/service/ImageProcessor');
        $imageProcessor = new ImageProcessor();
        $imageProcessor->setGalleryName(OpenGraphImageHeader::GALLERY_NAME);
        $imageProcessor->setPathBaseType('dynamic');
        $imageProcessor->setFilePath($this->getOpenGraphRelativeImageDir());
        $imageProcessor->setCode($repo->createCode());
        $imageProcessor->setFileNamePattern('{code}_{camelCaseImageType}');
        $imageProcessor->setThumbnailTypes(['thumbnail_w120']);
        $imageHeader = $imageProcessor->handleUpload();

        // dump($imageHeader);
        if (!$imageHeader) {
            $response = [
                'view' => '',
                'data' => [
                    'success' => false,
                    'imageHeaderId' => null,
                    'code' => null
                ]
            ];

            return new JsonResponse($response);
        }

        $response = [
            'view' => '',
            'data' => [
                'success' => true,
                'imageHeaderId' => $imageHeader->getId(),
                'code' => $imageHeader->getCode()
            ]
        ];

        return new JsonResponse($response);
    }

    /**
    * name: admin_openGraph_imageGallery, paramChain: /admin/openGraph/imageGallery
    */
    public function adminOpenGraphImageGalleryAction()
    {
        $this->wireService('ToolPackage/repository/ImageHeaderRepository');
        $imageHeaderRepo = new ImageHeaderRepository();
        $imageHeaders = $imageHeaderRepo->getOpenGraphGalleryImageHeaders();
        
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminOpenGraphsWidget/imagesBlock_gallery.php';
        $response = [
            'view' => $this->renderWidget('openGraphEdit', $viewPath, [
                'container' => $this->getContainer(),
                'imageHeaders' => $imageHeaders ? : []
            ]),
            'data' => [
            ]
        ];

        return new JsonResponse($response);
    }

    /**
    * name: admin_openGraph_selectorGallery, paramChain: /admin/openGraph/selectorGallery
    */
    public function adminOpenGraphSelectorGalleryAction()
    {
        $this->wireService('ToolPackage/repository/ImageHeaderRepository');
        $imageHeaderRepo = new ImageHeaderRepository();
        $imageHeaders = $imageHeaderRepo->getOpenGraphGalleryImageHeaders();
        
        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminOpenGraphsWidget/imageContainer_selectorGallery.php';
        $response = [
            'view' => $this->renderWidget('openGraphEdit', $viewPath, [
                'container' => $this->getContainer(),
                'imageHeaders' => $imageHeaders ? : []
            ]),
            'data' => [
            ]
        ];

        return new JsonResponse($response);
    }

    /**
    * name: admin_openGraph_selectingImage, paramChain: /admin/openGraph/selectingImage
    */
    public function adminOpenGraphSelectingImageAction()
    {
        $this->getContainer()->wireService('ToolPackage/repository/ImageHeaderRepository');
        $repo = new ImageHeaderRepository();
        $imageHeaderId = (int)$this->getContainer()->getRequest()->get('imageHeaderId');
        $ih = $repo->find($imageHeaderId);

        if ($ih) {
            $response = [
                'result' => true,
                'imageHeaderId' => $imageHeaderId
            ];
        } else {
            $response = [
                'result' => false,
                'imageHeaderId' => null
            ];
        }

        return new JsonResponse($response);
    }

    /**
    * name: admin_openGraph_getImageContainer, paramChain: /admin/openGraph/getImageContainer
    */
    public function adminOpenGraphGetImageContainerAction()
    {
        $idExisting = true;
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $repo = $this->getRepository();
        $openGraph = $repo->find($id);
        if (!$openGraph) {
            $idExisting = false;
            $openGraph = $repo->createNewEntity();
        }

        $this->wireService('FrameworkPackage/repository/OpenGraphImageHeaderRepository');
        $openGraphImageHeaderRepo = new OpenGraphImageHeaderRepository();

        $this->wireService('ToolPackage/repository/ImageHeaderRepository');
        $imageHeaderRepo = new ImageHeaderRepository();

        $imageHeaderId = (int)$this->getContainer()->getRequest()->get('imageHeaderId');
        $imageHeader = $imageHeaderRepo->find($imageHeaderId);
        if (!$imageHeader) {
            $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminOpenGraphsWidget/imageContainer.php';
            $response = [
                'view' => $this->renderWidget('openGraphEdit', $viewPath, [
                    // 'openGraphImageError' => $openGraphImageError,
                    'success' => false,
                    'errorMessage' => trans('image.not.found'),
                    'container' => $this->getContainer(),
                    'openGraph' => $openGraph
                ]),
                'data' => [
                ]
            ];
    
            return new JsonResponse($response);
        }

        $openGraphImageHeader = null;
        if ($id && $idExisting) {
            $openGraphImageHeader = $openGraphImageHeaderRepo->findOneBy(['conditions' => [['key' => 'open_graph_id', 'value' => $id]]]);
        }

        if (!$openGraphImageHeader) {
            $openGraphImageHeader = $openGraphImageHeaderRepo->createNewEntity(); 
            $openGraphImageHeader->setOpenGraph($openGraph);
        }

        $openGraphImageHeader->setImageHeader($imageHeader);
        $openGraphImageHeader = $openGraphImageHeaderRepo->store($openGraphImageHeader);
        $openGraph->resetOpenGraphImageHeader();
        $openGraph->addOpenGraphImageHeader($openGraphImageHeader);

        $viewPath = 'framework/packages/FrameworkPackage/view/widget/AdminOpenGraphsWidget/imageContainer.php';
        $response = [
            'view' => $this->renderWidget('openGraphEdit', $viewPath, [
                'success' => true,
                'errorMessage' => null,
                'container' => $this->getContainer(),
                'openGraph' => $openGraph
            ]),
            'data' => [
                'openGraphImageHeaderId' => $openGraphImageHeader->getId()
            ]
        ];

        return new JsonResponse($response);
    }

    /**
    * Route: [name: admin_openGraph_delete, paramChain: /admin/openGraph/delete]
    */
    public function adminOpenGraphDeleteAction()
    {
        $id = (int)$this->getContainer()->getRequest()->get('id');
        
        $this->getOpenGraphService()->removeOpenGraphImageHeaders($id);

        $this->getContainer()->wireService('FrameworkPackage/repository/OpenGraphRepository');
        $repo = new OpenGraphRepository();
        $repo->remove($id);

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_openGraph_unbindImage, paramChain: /admin/openGraph/unbindImage]
    */
    public function adminOpenGraphUnbindImageAction()
    {
        $this->wireService('FormPackage/service/FormBuilder');
        $this->wireService('ToolPackage/repository/ImageHeaderRepository');
        $this->wireService('FrameworkPackage/repository/OpenGraphRepository');
        $this->wireService('FrameworkPackage/repository/OpenGraphImageHeaderRepository');

        $id = (int)$this->getContainer()->getRequest()->get('id');

        $openGraphimageHeaderId = (int)$this->getContainer()->getRequest()->get('openGraphImageHeaderId');
        $openGraphImageHeaderRepo = new OpenGraphImageHeaderRepository();
        $openGraphImageHeader = $openGraphImageHeaderRepo->find($openGraphimageHeaderId);

        if (!$openGraphImageHeader) {
            $response = [
                'view' => '',
                'data' => [
                    'success' => false,
                    'errorMessage' => trans('image.or.open.graph.or.their.bounding.not.found')
                ]
            ];
    
            return $this->widgetResponse($response);
        }

        $result = $openGraphImageHeaderRepo->remove($openGraphimageHeaderId);

        $response = [
            'view' => '',
            'data' => [
                'success' => true,
                'errorMessage' => null,
                'openGraphimageHeaderId' => $openGraphimageHeaderId 
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_openGraph_deleteGalleryImage, paramChain: /admin/openGraph/deleteGalleryImage
    */
    public function adminOpenGraphDeleteGalleryImageAction()
    {
        $this->wireService('ToolPackage/repository/ImageHeaderRepository');
        $imageHeaderRepo = new ImageHeaderRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');

        $success = true;
        $errorMessage = null;

        // dump($imageHeaderRepo->find($id));//exit;
        if ($imageHeaderRepo->isDeletable($id)) {
            // dump($imageHeaderRepo->isDeletable($id));exit;
            $imageHeaderRepo->remove($id, $this->getOpenGraphService()->getOpenGraphAbsoluteImageDir());
        } else {
            $success = false;
            $errorMessage = trans('image.is.bound.to.an.open.graph');
        }

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
    * name: admin_openGraph_deleteImage, paramChain: /admin/openGraph/deleteImage
    */
    // public function adminOpenGraphDeleteImageAction()
    // {
    //     $this->wireService('FrameworkPackage/repository/OpenGraphImageHeaderRepository');
    //     $openGraphImageHeaderRepo = new OpenGraphImageHeaderRepository();
    //     $id = (int)$this->getContainer()->getRequest()->get('id');

    //     $success = true;
    //     $errorMessage = null;
    //     if ($openGraphImageHeaderRepo->isDeletable($id)) {
    //         $openGraphImageHeaderRepo->remove($id, $this->getOpenGraphService()->getOpenGraphAbsoluteImageDir());
    //     } else {
    //         $success = false;
    //         $errorMessage = trans('image.is.bound.to.an.open.graph');
    //     }

    //     $response = [
    //         'view' => '',
    //         'data' => [
    //             'id' => $id,
    //             'success' => $success,
    //             'errorMessage' => $errorMessage
    //         ]
    //     ];

    //     return $this->widgetResponse($response);
    // }
}
