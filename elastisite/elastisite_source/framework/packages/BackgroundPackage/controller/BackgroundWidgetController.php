<?php
namespace framework\packages\BackgroundPackage\controller;

use App;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\WidgetController;
use framework\packages\ToolPackage\service\ImageUploader;
use framework\packages\BackgroundPackage\service\BackgroundMaker;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;
use framework\packages\BackgroundPackage\entity\FBSPageBackground;
use framework\packages\BackgroundPackage\repository\FBSPageBackgroundRepository;
use framework\packages\BackgroundPackage\repository\FBSBackgroundImageRepository;
use framework\kernel\utility\FileHandler;
use framework\packages\ToolPackage\service\GridAjaxInterface;
use framework\packages\FormPackage\service\FormBuilder;

class BackgroundWidgetController extends WidgetController
{
    /**
    * name: admin_background_bindings_widget, paramChain: /admin/background/bindings/widget
    */
    public function adminBgBindingsWidgetAction()
    {
        $viewPath = 'framework/packages/ToolPackage/view/grid/defaultGridWidget.php';
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSPageBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        $repo = new FBSPageBackgroundRepository();
        $this->getContainer()->setService('ToolPackage/entity/Grid');
        $grid = $this->getContainer()->getService('Grid');
        $grid->setGridName('editFBSPageBackground');
        $grid->setData($this->arrangePageBgData($repo->findBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()]
        ]])));
        
        $grid->setProperties([
            ['name' => 'id', 'title' => 'id'],
            ['name' => 'routeName', 'title' => trans('route.name'), 'colWidth' => '5'],
            ['name' => 'backgroundColor', 'backgroundColor' => 'backgroundColor', 'title' => trans('background.color'), 'colWidth' => '2'],
            // ['name' => 'routeTitle', 'title' => trans('route.title'), 'colWidth' => '4'],
            ['name' => 'fbsBackgroundTitle', 'title' => trans('background.name'), 'colWidth' => '3']
        ]);
        $grid->addDeleteLink();

        //dump($grid);exit;

        // $this->getContainer()->setService('FrameworkPackage/service/GridAjaxInterface');
        // $gridAjaxInterface = $this->getContainer()->getService('GridAjaxInterface');
        // $gridAjaxInterface->setPackageName('Background');
        // $gridAjaxInterface->setSubject('editFBSPageBackground');
        // $gridAjaxInterface->setEntity($grid->getGridName());
        // $gridAjaxInterface->setEditActionParamChain('admin/background/binding/edit');
        // $gridAjaxInterface->setDeleteActionParamChain('admin/background/binding/delete');
        // $gridAjaxInterface->setDeleteResponseScript("AdminBgBindingsWidget.call();");

        $this->getContainer()->wireService('ToolPackage/service/GridAjaxInterface');
        $gridAjaxInterface = new GridAjaxInterface();
        $gridAjaxInterface->setGridName('editFBSPageBackground');
        $gridAjaxInterface->setEditActionParamChain('admin/background/binding/edit');
        $gridAjaxInterface->setDeleteActionParamChain('admin/background/binding/delete');
        $gridAjaxInterface->setDeleteResponseScript("AdminBgBindingsWidget.call();");
        
        $response = [
            'view' => $this->renderWidget('AdminBgBindingsWidget', $viewPath, [
                'container' => $this->getContainer(),
                'renderedGrid' => $grid->render(),
                'gridAjaxInterface' => $gridAjaxInterface->render()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    public function arrangePageBgData($fbsPageBackgrounds)
    {
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();

        $routeMap = $this->getContainer()->getPermittedFullRouteMap(array('viewGuestContent', 'viewUserContent'));
        $return = array();
        if (!$fbsPageBackgrounds) {
            return array();
        }
        foreach ($fbsPageBackgrounds as $fbsPageBackground) {
            foreach ($routeMap as $routeMapElement) {
                if ($routeMapElement['name'] == $fbsPageBackground->getRouteName()) {
                    $fbsBackground = $bgRepo->findOneBy(['conditions' => [['key' => 'theme', 'value' => $fbsPageBackground->getFbsBackgroundTheme()]]]);
                    $return[] = array(
                        'id' => $fbsPageBackground->getId(),
                        'routeName' => $this->getContainer()->getRoutingHelper()
                            ->getObviousParamChain($routeMapElement['paramChains']).' ('.trans($routeMapElement['title']).')',
                        'backgroundColor' => $fbsPageBackground->getBackgroundColor(),
                        'fbsBackgroundTitle' => $fbsBackground ? $fbsBackground->getTitle() : null
                    );
                }
            }
        }
        return $return;
    }

    public function getPageBgRouteMap($pageBgs, $pageBackground)
    {
        // dump($pageBackground->getRouteName());exit;
        $pageBgList = array();
        if ($pageBgs) {
            foreach ($pageBgs as $pageBg) {
                $pageBgList[] = $pageBg->getRouteName();
            }
        }
        $routeMap = $this->getContainer()->getPermittedFullRouteMap(array('viewGuestContent', 'viewUserContent'));
        $return = array();
        foreach ($routeMap as $routeMapElement) {
            if (!in_array($routeMapElement['name'], $pageBgList) || $pageBackground->getRouteName() == $routeMapElement['name']) {
                $return[] = $routeMapElement;
            }
        }
        return $return;
    }

    /**
    * Route: [name: admin_background_binding_edit, paramChain: /admin/background/binding/edit]
    */
    public function adminBackgroundBindingEditAction()
    {
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSPageBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        $pageBgRepo = new FBSPageBackgroundRepository();
        $this->setService('FormPackage/service/FormBuilder');
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();

        $pageBackgroundId = $this->getContainer()->getRequest()->get('id');

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('BackgroundPackage');
        $formBuilder->setSubject('editFBSPageBackground');
        $formBuilder->setPrimaryKeyValue($pageBackgroundId);
        $formBuilder->addExternalPost('id');
        // $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();
    // dump($form);exit;
        // dump($this->getContainer()->getRequest()->isSubmitted());
        // dump($this->getContainer()->getRequest()->getAll());exit;
        // $this->getContainer()->getRequest()->isSubmitted()
        // if ($this->getContainer()->getRequest()->get('BackgroundPackage_editFBSPageBackground_submit')) {
        //     dump($form);exit;
        // }

        // if ($form->isValid() && $this->getRequest()->get('BackgroundPackage_editFBSPageBackground_routeName')) {
        //     $pageBgRepo->store($form->getEntity());
        // }
        
        $pageBackground = $pageBgRepo->find($pageBackgroundId);
        if (!$pageBackground) {
            $pageBackground = new FBSPageBackground();
        }

        $viewPath = 'framework/packages/BackgroundPackage/view/widget/AdminBgBindingsWidget/form.php';
        $response = [
            'view' => $this->renderWidget('FBSPageBackgroundEditForm', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'pageBackground' => $pageBackground,
                'pageBackgroundId' => $pageBackgroundId,
                'backgrounds' => $bgRepo->findAll(),
                'routeMap' => $this->getPageBgRouteMap($pageBgRepo->findAll(), $pageBackground)
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll(),
                'requests' => BasicUtils::arrayToString($this->getContainer()->getRequest()->getAll())
            ]
        ];

        // dump($form);exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_background_binding_delete, paramChain: /admin/background/binding/delete]
    */
    public function adminBackgroundBindingDeleteAction()
    {
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSPageBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        $repo = new FBSPageBackgroundRepository();
        $pageBackgroundId = $this->getContainer()->getRequest()->get('id');
        // dump($pageBackgroundId);
        // dump($repo->findBy(['id' => $pageBackgroundId]));
        $repo->removeBy(['id' => $pageBackgroundId]);
    }

    /**
    * Route: [name: admin_background_delete, paramChain: /admin/background/delete]
    */
    public function adminBackgroundDeleteAction()
    {
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackgroundImage');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundImageRepository');
        $bgImgRepo = new FBSBackgroundImageRepository();
        $this->getContainer()->wireService('BackgroundPackage/entity/FBSPageBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        $pageBgRepo = new FBSPageBackgroundRepository();

        $backgroundId = $this->getContainer()->getRequest()->get('FBSBackgroundId');
        $background = $bgRepo->find($backgroundId);
        if (!$background) {
            return false;
        }
        $bgBinded = $pageBgRepo->findOneBy(['conditions' => [['key' => 'fbsBackgroundTheme', 'value' => $background->getTheme()]]]);

        if (!$bgBinded) {
            $bgImgRepo->remove($background);
            $bgRepo->removeBy(['id' => $backgroundId]);
        }
    }

    /**
    * name: admin_backgrounds_widget, paramChain: /admin/backgrounds/widget
    */
    public function adminBackgroundsWidgetAction()
    {
        // $this->getContainer()->wireService('BackgroundPackage/entity/FBSPageBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSPageBackgroundRepository');
        $pageBgRepo = new FBSPageBackgroundRepository();
        // $this->getContainer()->wireService('BackgroundPackage/entity/FBSBackground');
        $this->getContainer()->wireService('BackgroundPackage/repository/FBSBackgroundRepository');
        $bgRepo = new FBSBackgroundRepository();

        $bindedBgs = array();
        // $pageBgs = $pageBgRepo->findInEveryProject();
        $pageBgs = $pageBgRepo->findAll();



//temp
// foreach ($bgRepo->findAll() as $bg) {
//     $bgRepo->store($bg);
// }
// dump($bgRepo->findAll());exit;

        if ($pageBgs) {
            foreach ($pageBgs as $pageBg) {
                $bindedBgs[] = $pageBg->getFbsBackgroundTheme();
            }
        }
        // $pageBgRepo->removeAllObjects();

// dump($bgRepo->findAll());
// dump($pageBgs);exit;

        $viewPath = 'framework/packages/BackgroundPackage/view/widget/AdminBackgroundsWidget/widget.php';

        $response = [
            'view' => $this->renderWidget('AdminBackgroundsWidget', $viewPath, [
                'container' => $this->getContainer(),
                'backgrounds' => $bgRepo->findAll(),
                'bindedBgs' => $bindedBgs
            ]),
            'data' => [
                'refresh' => false
            ]
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_background_reset, paramChain: /admin/background/reset
    */
    public function adminBackgroundResetAction()
    {
        $rawBgTempDir = 'temp/rawBgImage';

        $files = FileHandler::getAllFileNames($rawBgTempDir, 'keep', 'dynamic');
        // dump($files);exit;
        foreach ($files as $fileName) {
            FileHandler::unlinkFile($rawBgTempDir.'/'.$fileName, 'dynamic');
        }
    }

    /**
    * name: admin_background_new, paramChain: /admin/background/new
    */
    public function adminBackgroundNewAction()
    {
        $this->wireService('BackgroundPackage/service/BackgroundMaker');
        $backgroundMaker = new BackgroundMaker();

        $fileName = $backgroundMaker->getRawBgImageName();
        // dump($fileName);exit;

        if (!$fileName) {
            $response = $this->adminBackgroundNew_uploadRaw();
        } else {
            $response = $this->adminBackgroundNew_form();
        }


        return $this->widgetResponse($response);
    }

    public function adminBackgroundNew_uploadRaw()
    {
        $viewPath = 'framework/packages/BackgroundPackage/view/widget/AdminBackgroundsWidget/uploadRaw.php';
        return [
            'view' => $this->renderWidget('BackgroundEdit', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => [
                'refresh' => false
            ]
        ];
    }

    /**
    * name: admin_background_save, paramChain: /admin/background/save
    */
    public function adminBackgroundSaveAction()
    {
        // dump('alma');exit;
        return $this->adminBackgroundNew_form();
    }

    public function adminBackgroundNew_form()
    {
        $this->setService('ToolPackage/service/ImageService');
        $this->wireService('FormPackage/service/FormBuilder');
        $this->wireService('BackgroundPackage/service/BackgroundMaker');
        $imageService = $this->getService('ImageService');
        $isSubmitted = $this->getRequest()->get('BackgroundPackage_newFBSBackground_engine');
        $backgroundMaker = new BackgroundMaker();
        $rawBgImageName = $backgroundMaker->getRawBgImageName();
        $rawBgImageName = BasicUtils::explodeAndRemoveElement($rawBgImageName, '.', 'last');
        $resolution = BasicUtils::explodeAndGetElement($rawBgImageName, '_', 'last');
        $resolutionParams = explode('x', $resolution);
        $imageWidth = isset($resolutionParams[0]) ? $resolutionParams[0] : null;
        $imageHeight = isset($resolutionParams[1]) ? $resolutionParams[1] : null;

        $formBuilder = new FormBuilder();
        $formBuilder->setPackageName('BackgroundPackage');
        $formBuilder->setSubject('newFBSBackground');
        $formBuilder->setSaveRequested(false);
        // $formBuilder->setPrimaryKeyValue($pageBackgroundId);
        // $formBuilder->addExternalPost('id');
        // $formBuilder->setSaveRequested(false);
        $form = $formBuilder->createForm();

        // $form = $this->getService('FormBuilder')->createForm(
        //     'BackgroundPackage',
        //     'newFBSBackground',
        //     null,
        //     false
        // );

        if ($isSubmitted && $form->isValid()) {
            // dump($form);exit;
            switch ($this->getRequest()->get('BackgroundPackage_newFBSBackground_engine')) {
                case 'Simple':
                    $backgroundMaker->makeSimple($form->getValueCollector()->getDisplayed('theme'));
                    break;
                case 'SlidingStripes':
                    $backgroundMaker->makeSlidingStripes($form->getValueCollector()->getDisplayed('theme'));
                    break;
            }
        }

        $rawBgImageName = $backgroundMaker->getRawBgImageName();
        // $extension = BasicUtils::explodeAndGetElement($rawBgImageName, '.', 'last');

        $imageService->createThumbnail('temp/rawBgImage/'.$rawBgImageName, $this->getSession()->get('userId').'_image_thumb');
        $viewPath = 'framework/packages/BackgroundPackage/view/widget/AdminBackgroundsWidget/form.php';
        return [
            'view' => $this->renderWidget('BackgroundEdit', $viewPath, [
                'container' => $this->getContainer(),
                'thumbPath' => 'admin/rawBgImage/'.$this->getSession()->get('userId').'_image_thumb',
                'form' => $form,
                'imageWidth' => $imageWidth,
                'imageHeight' => $imageHeight
            ]),
            'data' => [
                'refresh' => true,
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];
    }

    /**
    * name: admin_background_rawImage_upload, paramChain: /admin/background/rawImage/upload
    */
    public function adminBackgroundRawImageUploadAction()
    {
        $this->wireService('ToolPackage/service/ImageUploader');
        $this->wireService('BackgroundPackage/service/BackgroundMaker');

        $rawBgTempDir = 'temp/rawBgImage';
        $uploader = new ImageUploader();
        $uploader->setImgurFormat(false);
        $uploader->setFilePath(FileHandler::completePath($rawBgTempDir, 'dynamic'));
        $uploader->setFileName($this->getSession()->get('userId').'_image_full');
        $uploadResult = $uploader->upload();

        // dump($uploader);exit;

        if ($uploadResult['success']) {
            $backgroundMaker = new BackgroundMaker();
            $imageName = $backgroundMaker->getRawBgImageName();
            // dump($imageName);
            // $extension = BasicUtils::explodeAndGetElement($imageName, '.', 'last');
            $this->setService('ToolPackage/service/ImageService');
            $imageService = $this->getService('ImageService');
            $extension = $imageService->determineExtension($imageName, 'BackgroundWidgetController@adminBackgroundRawImageUploadAction');

            $newImageName = $this->getSession()->get('userId').'_image_full_'.$uploadResult['data']['width'].'x'.$uploadResult['data']['height'];
            $dir = FileHandler::completePath($rawBgTempDir, 'dynamic').'/';
            copy($dir.$imageName, $dir.$newImageName.'.'.$extension);
            unlink(FileHandler::completePath($rawBgTempDir, 'dynamic').'/'.$imageName);
        }

        // dump($uploadResult);exit;

        $viewPath = 'framework/packages/BackgroundPackage/view/widget/AdminBackgroundsWidget/uploadRaw.php';
        $response = [
            'view' => $this->renderWidget('BackgroundEdit', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => [
                'success' => $uploadResult ? true : false,
                'refresh' => false
            ]
        ];

        return $this->widgetResponse($response);
    }
}
