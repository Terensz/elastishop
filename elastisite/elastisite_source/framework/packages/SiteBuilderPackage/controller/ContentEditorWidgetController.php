<?php
namespace framework\packages\SiteBuilderPackage\controller;

use App;
use framework\component\exception\ElastiException;
use framework\component\helper\StringHelper;
use framework\component\parent\WidgetController;
use framework\kernel\utility\BasicUtils;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\SiteBuilderPackage\entity\ContentEditor;
use framework\packages\SiteBuilderPackage\entity\ContentEditorBackgroundImage;
use framework\packages\SiteBuilderPackage\entity\ContentEditorUnit;
use framework\packages\SiteBuilderPackage\entity\ContentEditorUnitCase;
use framework\packages\SiteBuilderPackage\repository\ContentEditorUnitRepository;
use framework\packages\SiteBuilderPackage\repository\ContentEditorRepository;
use framework\packages\SiteBuilderPackage\repository\ContentEditorUnitCaseRepository;
use framework\packages\SiteBuilderPackage\service\ContentEditorImageService;
use framework\packages\SiteBuilderPackage\service\ContentEditorService;
use framework\packages\ToolPackage\service\ImageProcessor;

class ContentEditorWidgetController extends WidgetController
{
    private $contentEditor;

    private $contentEditorRepository;

    public function __construct()
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/ContentEditorService');
        App::getContainer()->wireService('SiteBuilderPackage/service/ContentEditorDisplayTool');
    }

    /**
    * Route: [name: widget_ContentEditorWidget, paramChain: /widget/ContentEditorWidget]
    */
    public function contentEditorWidgetAction()
    {
        $editorToolbarView = $this->getEditorToolbarView();

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/ContentEditorWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('ContentEditorWidget'.ContentEditorService::getContentEditor()->getId(), $viewPath, [
                'grantedViewProjectAdminContent' => $this->getContainer()->isGranted('viewProjectAdminContent'),
                'editorToolbarView' => $editorToolbarView,
                'viewerView' => $this->getViewerView(false, ['viewerRounded' => false]),
                'contentEditorId' => ContentEditorService::getContentEditor()->getId(),
                'httpDomain' => $this->getUrl()->getHttpDomain(),
                'viewerRounded' => false
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: widget_ContentEditorWidget, paramChain: /widget/ContentEditorWidget]
    */
    public function wrappedContentEditorWidgetAction()
    {
        $editorToolbarView = $this->getEditorToolbarView();

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/ContentEditorWidget/wrappedWidget.php';
        $response = [
            'view' => $this->renderWidget('ContentEditorWidget'.ContentEditorService::getContentEditor()->getId(), $viewPath, [
                'grantedViewProjectAdminContent' => $this->getContainer()->isGranted('viewProjectAdminContent'),
                'editorToolbarView' => $editorToolbarView,
                'viewerView' => $this->getViewerView(false, ['viewerRounded' => true]),
                'contentEditorId' => ContentEditorService::getContentEditor()->getId(),
                'httpDomain' => $this->getUrl()->getHttpDomain(),
                'viewerRounded' => true
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ContentEditorWidget_editor_reload, paramChain: /ContentEditorWidget/editor/reload]
    */
    public function contentEditorWidgetEditorReloadAction()
    {
        $contentEditorId = App::getContainer()->getRequest()->get('contentEditorId');
        $viewerRounded = App::getContainer()->getRequest()->get('viewerRounded');
        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorRepository');
        $contentEditorRepo = new ContentEditorRepository();
        $contentEditor = $contentEditorRepo->find($contentEditorId);

        $submitForm = StringHelper::mendValue($this->getContainer()->getRequest()->get('submitForm'));
        if ($submitForm === true) {
            $contentEditor->setHeight($this->getContainer()->getRequest()->get('contentEditorBoard_toolbar_height'));
            $contentEditor->setBoxShadowStyle($this->getContainer()->getRequest()->get('contentEditorBoard_toolbar_shadow'));
            $contentEditor = $contentEditor->getRepository()->store($contentEditor);
        }

        $this->contentEditor = $contentEditor;

        $response = [
            'view' => '',
            'views' => [
                'editorToolbarView' => $this->getEditorToolbarView(),
                'viewerView' => $this->getViewerView(false, ['viewerRounded' => $viewerRounded]),
                'contentEditorId' => ContentEditorService::getContentEditor()->getId()
            ],
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ContentEditorWidget_editor_updateContentEditorUnitCasePosition, paramChain: /ContentEditorWidget/editor/updateContentEditorUnitCasePosition]
    */
    public function contentEditorWidgetEditorUpdateContentEditorUnitCasePositionAction()
    {

        $contentEditorUnitId = BasicUtils::explodeAndGetElement(App::getContainer()->getRequest()->get('contentEditorUnitHtmlId'), '_', 'last');
        if (!is_numeric($contentEditorUnitId)) {
            throw new \Exception('contentEditorUnitId is not numeric');
            // return false;
        }
        $verticalPosition = round(App::getContainer()->getRequest()->get('verticalPosition'));
        $horizontalPosition = round(App::getContainer()->getRequest()->get('horizontalPosition'));

        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorUnitCaseRepository');
        $repo = new ContentEditorUnitCaseRepository();
        $contentEditorUnitCase = $repo->find($contentEditorUnitId);
        if (!$contentEditorUnitCase) {
            throw new \Exception('ContentEditorUnitCase not existing');
            // return false;
        }
        $oldVertivalPosition = $contentEditorUnitCase->getVerticalPosition();
        $oldHorizontalPosition = $contentEditorUnitCase->getHorizontalPosition();
        if($verticalPosition == $oldVertivalPosition && $horizontalPosition == $oldHorizontalPosition) {
            $response = [
                'view' => '',
                'data' => [
                    'success' => true,
                    'saved' => false,
                    'verticalPosition' => $verticalPosition,
                    'horizontalPosition' => $horizontalPosition
                ]
            ];
        } else {
            $contentEditorUnitCase->setVerticalPosition($verticalPosition);
            $contentEditorUnitCase->setHorizontalPosition($horizontalPosition);
            $contentEditorUnitCase = $repo->store($contentEditorUnitCase);

            $response = [
                'view' => '',
                'data' => [
                    'success' => ($contentEditorUnitCase->getVerticalPosition() == $verticalPosition && $contentEditorUnitCase->getHorizontalPosition() == $horizontalPosition),
                    'saved' => true,
                    'verticalPosition' => $verticalPosition,
                    'horizontalPosition' => $horizontalPosition
                ]
            ];
        }

        return $this->widgetResponse($response);
    }

    // Unit

    /**
    * Route: [name: ContentEditorWidget_editor_addContentEditorUnit, paramChain: /ContentEditorWidget/editor/addContentEditorUnit]
    */
    public function contentEditorWidgetEditorAddContentEditorUnitAction()
    {
        $contentEditorId = App::getContainer()->getRequest()->get('contentEditorId');
        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorRepository');
        $contentEditorRepo = new ContentEditorRepository();
        $contentEditor = $contentEditorRepo->find($contentEditorId);
        if (!$contentEditor) {
            // return null; 
            throw new \Exception('Could not found content editor');
        }

        $contentEditorUnitCaseId = App::getContainer()->getRequest()->get('contentEditorUnitCaseId');
        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorUnitCaseRepository');
        $contentEditorUnitCaseRepo = new ContentEditorUnitCaseRepository();
        $contentEditorUnitCase = $contentEditorUnitCaseRepo->find($contentEditorUnitCaseId);
        if (!$contentEditorUnitCase) {
            // return null;
            throw new \Exception('Could not found container');
        }
        if ($contentEditorUnitCase->getContentEditor()->getId() != $contentEditor->getId()) {
            throw new \Exception('Content editor mismatch error');
        }

        $this->getContainer()->wireService('SiteBuilderPackage/entity/ContentEditorUnit');
        $contentEditorUnit = new ContentEditorUnit();
        $contentEditorUnit->setContentEditorUnitCase($contentEditorUnitCase);
        $contentEditorUnit->getRepository()->store($contentEditorUnit);

        $response = [
            'view' => '',
            'data' => [
                'contentEditorUnitId' => $contentEditorUnit->getId()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ContentEditorWidget_editor_editContentEditorUnit_form, paramChain: /ContentEditorWidget/editor/editContentEditorUnit/form]
    */
    public function contentEditorWidgetEditorEditContentEditorUnitFormAction()
    {
        return $this->contentEditorWidgetEditorEditContentEditorUnitAction(true);
    }

    /**
    * Route: [name: ContentEditorWidget_editor_editContentEditorUnit, paramChain: /ContentEditorWidget/editor/editContentEditorUnit]
    */
    public function contentEditorWidgetEditorEditContentEditorUnitAction($flexibleContent = false)
    {
        $formIsValid = false;
        $fonts = App::getContainer()->getKernelObject('Fonts');
        $form = $this->getEditContentEditorUnitForm();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $form->getEntity()->getRepository()->store($form->getEntity());
                $formIsValid = true;
            }
        }

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/ContentEditorWidget/'.($flexibleContent ? 'modalUnitFlex' : 'modalUnit').'.php';
        // dump($viewPath); exit;
        $response = [
            'view' => $this->renderWidget('editContentEditorUnitModalContent', $viewPath, [
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'contentEditorId' => ContentEditorService::getContentEditor()->getId(),
                'textShadowStyles' => ContentEditorUnit::TEXT_SHADOW_STYLES,
                'textAlignOptions' => ContentEditorUnit::TEXT_ALIGN_OPTIONS,
                // 'classes' => ContentEditorUnit::CLASSES,
                'fonts' => $fonts,
                'form' => $form
            ]),
            'data' => [
                'contentEditorUnitId' => $form->getEntity()->getId(),
                'label' => trans('edit.content.assembler.text'),
                'formIsValid' => $formIsValid
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getEditContentEditorUnitForm()
    {
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $this->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ElastiSite');
        $formBuilder->setSubject('editContentEditorUnit');
        $formBuilder->setSchemaPath('SiteBuilderPackage/form/EditContentEditorUnitSchema');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        return $form;
    }

    /**
    * Route: [name: ContentEditorWidget_editor_removeContentEditorUnit, paramChain: /ContentEditorWidget/editor/removeContentEditorUnit]
    */
    public function contentEditorWidgetEditorRemoveContentEditorUnitAction()
    {
        $contentEditorUnitId = (int)$this->getContainer()->getRequest()->get('contentEditorUnitId');
        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorUnitRepository');
        $repo = new ContentEditorUnitRepository();
        $result = $repo->remove($contentEditorUnitId);

        $response = [
            'view' => '',
            'data' => [
                'success' => $result ? true : false
            ]
        ];

        return $this->widgetResponse($response);
    }

    // UnitCase

    /**
    * Route: [name: ContentEditorWidget_editor_addContentEditorUnitCase, paramChain: /ContentEditorWidget/editor/addContentEditorUnitCase]
    */
    public function contentEditorWidgetEditorAddContentEditorUnitCaseAction()
    {
        $contentEditorId = App::getContainer()->getRequest()->get('contentEditorId');
        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorRepository');
        $contentEditorRepo = new ContentEditorRepository();
        $contentEditor = $contentEditorRepo->find($contentEditorId);
        if (!$contentEditor) {
            return null;
        }

        $this->getContainer()->wireService('SiteBuilderPackage/entity/ContentEditorUnitCase');
        $contentEditorUnitCase = new ContentEditorUnitCase();
        $contentEditorUnitCase->setContentEditor($contentEditor);
        $contentEditorUnitCase->setVerticalPositioningDirection(ContentEditorUnitCase::VERTICAL_POSITIONING_DIRECTION_TOP);
        $contentEditorUnitCase->setVerticalPosition(20);
        $contentEditorUnitCase->setHorizontalPositioningDirection(ContentEditorUnitCase::HORIZONTAL_POSITIONING_DIRECTION_LEFT);
        $contentEditorUnitCase->setHorizontalPosition(20);
        $contentEditorUnitCase = $contentEditorUnitCase->getRepository()->store($contentEditorUnitCase);

        $response = [
            'view' => '',
            'data' => [
                'contentEditorUnitCaseId' => $contentEditorUnitCase->getId()
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ContentEditorWidget_editor_editContentEditorUnitCase_form, paramChain: /ContentEditorWidget/editor/editContentEditorUnitCase/form]
    */
    public function contentEditorWidgetEditorEditContentEditorUnitCaseFormAction()
    {
        return $this->contentEditorWidgetEditorEditContentEditorUnitCaseAction(true);
    }

    /**
    * Route: [name: ContentEditorWidget_editor_editContentEditorUnitCase, paramChain: /ContentEditorWidget/editor/editContentEditorUnitCase]
    */
    public function contentEditorWidgetEditorEditContentEditorUnitCaseAction($flexibleContent = false)
    {
        $formIsValid = false;
        $fonts = App::getContainer()->getKernelObject('Fonts');
        $form = $this->getEditContentEditorUnitCaseForm();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $form->getEntity()->getRepository()->store($form->getEntity());
                $formIsValid = true;
            }
        }

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/ContentEditorWidget/'.($flexibleContent ? 'modalUnitCaseFlex' : 'modalUnitCase').'.php';
        // dump($viewPath); exit;
        $response = [
            'view' => $this->renderWidget('editContentEditorUnitModalContent', $viewPath, [
                'httpDomain' => $this->getContainer()->getUrl()->getHttpDomain(),
                'contentEditorId' => ContentEditorService::getContentEditor()->getId(),
                // 'textShadowStyles' => ContentEditorUnit::TEXT_SHADOW_STYLES,
                'classes' => ContentEditorUnitCase::CLASSES,
                // 'fonts' => $fonts,
                'form' => $form
            ]),
            'data' => [
                'contentEditorUnitId' => $form->getEntity()->getId(),
                'label' => trans('edit.content.assembler.text'),
                'formIsValid' => $formIsValid
            ]
        ];

        return $this->widgetResponse($response);
    }

    public function getEditContentEditorUnitCaseForm()
    {
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $this->wireService('FormPackage/service/FormBuilder');
        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('ElastiSite');
        $formBuilder->setSubject('editContentEditorUnitCase');
        $formBuilder->setSchemaPath('SiteBuilderPackage/form/EditContentEditorUnitCaseSchema');
        $formBuilder->setPrimaryKeyValue($id);
        $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        return $form;
    }

    /**
    * Route: [name: ContentEditorWidget_editor_removeContentEditorUnitCase, paramChain: /ContentEditorWidget/editor/removeContentEditorUnitCase]
    */
    public function contentEditorWidgetEditorRemoveContentEditorUnitCaseAction()
    {
        $contentEditorUnitCaseId = (int)$this->getContainer()->getRequest()->get('contentEditorUnitCaseId');
        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorUnitCaseRepository');
        $repo = new ContentEditorUnitCaseRepository();
        $result = $repo->remove($contentEditorUnitCaseId);

        $response = [
            'view' => '',
            'data' => [
                'success' => $result ? true : false
            ]
        ];

        return $this->widgetResponse($response);
    }

    // Toolbar

    public function getEditorToolbarView($render = false)
    {
        try {
            // dump('getEditorToolbarView!1');
            $contentEditor = ContentEditorService::getContentEditor();

            // dump('getEditorToolbarView!2');
            $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/ContentEditorWidget/editorToolbar.php';
            $response = [
                'view' => $this->renderWidget('getEditorToolbarView', $viewPath, [
                    // 'container' => $this->getContainer(),
                    'grantedViewProjectAdminContent' => $this->getContainer()->isGranted('viewProjectAdminContent'),
                    'contentEditor' => $contentEditor,
                    'contentEditorId' => ContentEditorService::getContentEditor()->getId()
                ]),
                'data' => []
            ];

        } catch(ElastiException $e) {
            if ($e->getCode() == 1660) {
                $response = [
                    'view' => '',
                    'data' => []
                ];
            }
        }

        return $render ? $this->widgetResponse($response) : $response['view'];
    }

    public function getViewerView($render = false, $options = [])
    {
        $this->getContainer()->wireService('SiteBuilderPackage/service/ContentEditorImageService');

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/ContentEditorWidget/viewer.php';
        $response = [
            'view' => $this->renderWidget('getViewerView', $viewPath, [
                // 'container' => $this->getContainer(),
                'grantedViewProjectAdminContent' => $this->getContainer()->isGranted('viewProjectAdminContent'),
                'backgroundImageLink' => ContentEditorImageService::createBackgroundImageLink(ContentEditorService::getContentEditor()),
                'contentEditor' => ContentEditorService::getContentEditor(),
                'contentEditorId' => ContentEditorService::getContentEditor()->getId(),
                'viewerRounded' => isset($options['viewerRounded']) ? $options['viewerRounded'] : false
            ]),
            'data' => []
        ];

        return $render ? $this->widgetResponse($response) : $response['view'];
    }

    /**
    * Route: [name: ContentEditorWidget_editor_sortContentEditorUnitCases, paramChain: /ContentEditorWidget/editor/sortContentEditorUnitCases]
    */
    public function contentEditorWidgetEditorSortContentEditorUnitCasesAction()
    {
        $contentEditorUnitCaseIds = $this->getContainer()->getRequest()->get('contentEditorUnitCaseIds');
        // dump($contentEditorUnitIds);exit;

        if (count($contentEditorUnitCaseIds) > 1) {
            $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorUnitCaseRepository');
            $repo = new ContentEditorUnitCaseRepository();

            $sequence = 1;
            foreach ($contentEditorUnitCaseIds as $contentEditorUnitCaseId) {
                $repo->updateSequence($contentEditorUnitCaseId, $sequence);
                $sequence++;
            }
        }

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/ContentEditorWidget/editorToolbarCaseList.php';
        $response = [
            'view' => $this->renderWidget('editorToolbarCaseList', $viewPath, [
                'contentEditor' => ContentEditorService::getContentEditor(),
                'contentEditorId' => ContentEditorService::getContentEditor()->getId()
            ]),
            'data' => [
                'success' => true
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ContentEditorWidget_editor_sortContentEditorUnits, paramChain: /ContentEditorWidget/editor/sortContentEditorUnits]
    */
    public function contentEditorWidgetEditorSortContentEditorUnitsAction()
    {
        $contentEditorUnitIds = $this->getContainer()->getRequest()->get('contentEditorUnitIds');
        // dump($contentEditorUnitIds);exit;

        if (count($contentEditorUnitIds) > 1) {
            $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorUnitRepository');
            $repo = new ContentEditorUnitRepository();

            $sequence = 1;
            foreach ($contentEditorUnitIds as $contentEditorUnitId) {
                $repo->updateSequence($contentEditorUnitId, $sequence);
                $sequence++;
            }
        }

        $viewPath = 'framework/packages/SiteBuilderPackage/view/widget/ContentEditorWidget/editorToolbarCaseList.php';
        $response = [
            'view' => $this->renderWidget('editorToolbarCaseList', $viewPath, [
                'contentEditor' => ContentEditorService::getContentEditor(),
                'contentEditorId' => ContentEditorService::getContentEditor()->getId()
            ]),
            'data' => [
                'success' => true
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: ContentEditorWidget_editor_uploadContentEditorBackgroundImage, paramChain: /ContentEditorWidget/editor/uploadContentEditorBackgroundImage]
    */
    public function contentEditorWidgetEditorUploadContentEditorBackgroundImageAction()
    {
        $this->getContainer()->wireService('SiteBuilderPackage/service/ContentEditorImageService');
        $contentEditorId = App::getContainer()->getRequest()->get('contentEditorId');
        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorRepository');
        $contentEditorRepo = new ContentEditorRepository();
        $contentEditor = $contentEditorRepo->find($contentEditorId);

        // dump('alma');exit;
        ContentEditorImageService::removeBackgroundImage(ContentEditorService::getContentEditor());//exit;

        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorRepository');
        $contentEditorRepo = new ContentEditorRepository();
        $this->getContainer()->wireService('ToolPackage/service/ImageProcessor');

        $contentEditorBackgroundImage = null;
        try {
            $imageProcessor = new ImageProcessor();
            $imageProcessor->setPathBaseType('dynamic');
            $imageProcessor->setGalleryName(ContentEditorBackgroundImage::GALLERY_NAME);
            $imageProcessor->setFilePath(ContentEditorImageService::getContentEditorRelativeImageDir());
            $imageProcessor->setCode($contentEditorRepo->createCode());
            $imageProcessor->setFileNamePattern('{code}_{camelCaseImageType}');
            $imageProcessor->setThumbnailTypes(['thumbnail_w120']);
            $imageHeader = $imageProcessor->handleUpload();

            $this->getContainer()->wireService('SiteBuilderPackage/entity/ContentEditorBackgroundImage');
            $contentEditorBackgroundImage = new ContentEditorBackgroundImage();
            $contentEditorBackgroundImage->setContentEditor($contentEditor);
            $contentEditorBackgroundImage->setImageHeader($imageHeader);
            $contentEditorBackgroundImage = $contentEditorBackgroundImage->getRepository()->store($contentEditorBackgroundImage);
        } catch(\Exception $e) {
            // dump($e);exit;
        }

        $response = [
            'view' => '',
            'data' => [
                'contentEditorBackgroundImageId' => $contentEditorBackgroundImage ? $contentEditorBackgroundImage->getId() : null,
                'success' => true
            ]
        ];

        return $this->widgetResponse($response);
    }
}
