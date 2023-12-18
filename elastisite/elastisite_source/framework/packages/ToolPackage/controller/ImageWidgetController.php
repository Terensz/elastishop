<?php
namespace framework\packages\ToolPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\repository\TechnicalFileRepository;
use framework\kernel\utility\FileHandler;
use framework\packages\ToolPackage\service\ImageService;
use framework\packages\BackgroundPackage\entity\FBSBackground;
use framework\packages\BackgroundPackage\repository\FBSBackgroundRepository;
use framework\packages\BackgroundPackage\repository\FBSBackgroundImageRepository;

class ImageWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_images_widget, paramChain: /admin/images/widget]
    */
    public function adminImagesWidgetAction()
    {
        $grid = $this->getListImagesGrid(['conditions' => [
            ['key' => 'type', 'value' => 'image']
        ]]);
        $this->getContainer()->setService('FrameworkPackage/service/GridAjaxInterface');
        $gridAjaxInterface = $this->getContainer()->getService('GridAjaxInterface');
        $gridAjaxInterface->setPackageName('Tool');
        $gridAjaxInterface->setSubject('editImage');
        $gridAjaxInterface->setEntity($grid->getGridName());
        // $gridAjaxInterface->setOnSaveReloadFunction('UserAccountSearch.search(UserAccountGridPager.page);');
        $gridAjaxInterface->setEditActionParamChain('admin/image/edit');
        $gridAjaxInterface->setDeleteActionParamChain('admin/image/delete');
        $gridAjaxInterface->setDeleteResponseScript("ImageSearch.search(ImageGridPager.page);");
        // dump($gridAjaxInterface);exit;
        $viewPath = 'framework/packages/ToolPackage/view/widget/AdminImagesWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminImagesWidget', $viewPath, [
                'container' => $this->getContainer(),
                'renderedGrid' => $grid->render(),
                'gridAjaxInterface' => $gridAjaxInterface->render()
            ]),
            'data' => []
        ];

        // dump($response);exit;

        return $this->widgetResponse($response);
    }

    public function getListImagesGrid($filter = null, $page = 1)
    {
        $this->getContainer()->wireService('ToolPackage/repository/TechnicalFileRepository');
        $imageRepo = new TechnicalFileRepository();
        $this->getContainer()->setService('ToolPackage/entity/Grid');
        $grid = $this->getContainer()->getService('Grid');
        // $grid->setFormName('UserPackage_userAccountSearch_form');
        $grid->setGridName('image');
        $grid->setPage($page);
        $grid->setTotalCount($imageRepo->getTotalCount($filter));
        $grid->setLimit(5);
        $grid->setAllowCreateNew(true);
        $grid->setData($imageRepo->getFilteredResult($filter ? array_merge($imageRepo->transformFilter($filter), ['maxResults' => $grid->getLimit(), 'currentPage' => $page]) : null));
        // $grid->setViewPath('framework/packages/UserPackage/view/widget/AdminUserAccountsWidget/grid.php');
        $grid->setProperties([
            ['name' => 'id', 'title' => 'id'],
            ['name' => 'title', 'title' => trans('title'), 'colWidth' => '9'],
            ['name' => 'mime', 'title' => trans('mime'), 'colWidth' => '3']
            // ['entity' => , 'name' => 'permissionGroups', 'title' => trans('permission.groups'), 'colWidth' => '4']
        ]);
        // $grid->setTotalPageCount();
        // dump($grid);exit;
        return $grid;
    }

    /**
    * Route: [name: admin_image_search, paramChain: /admin/image/search]
    */
    public function adminImageSearchAction()
    {

    }

    /**
    * Route: [name: admin_image_edit, paramChain: /admin/image/edit]
    */
    public function adminImageEditAction()
    {
        $this->wireService('ToolPackage/entity/UserAccountFile');
        $this->wireService('ToolPackage/repository/UserAccountFileRepository');
        $this->wireService('ToolPackage/entity/TechnicalFile');
        $this->wireService('ToolPackage/repository/TechnicalFileRepository');
        // $repo = new TechnicalFileRepository();
        $this->setService('FormPackage/service/FormBuilder');
        $imageId = (int)$this->getContainer()->getRequest()->get('imageId');

        $form = $this->getService('FormBuilder')->createForm(
            'ToolPackage',
            'editImage',
            $imageId
        );
        // dump($form);exit;

        $viewPath = 'framework/packages/ToolPackage/view/widget/AdminImagesWidget/editImage.php';
        $response = [
            'view' => $this->renderWidget('editImageForm', $viewPath, [
                'container' => $this->getContainer(),
                'form' => $form,
                'imageId' => $imageId
                // 'guestSelectedStr' => $guestSelectedStr,
                // 'userSelectedStr' => $userSelectedStr,
                // 'projectAdminSelectedStr' => $projectAdminSelectedStr,
                // 'systemAdminSelectedStr' => $systemAdminSelectedStr
            ]),
            'data' => [
                'formIsValid' => $form->isValid(),
                'messages' => $form->getMessages(),
                // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ];

        return $this->widgetResponse($response);
    }
}
