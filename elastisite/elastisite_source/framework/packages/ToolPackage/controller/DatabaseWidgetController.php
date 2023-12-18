<?php
namespace framework\packages\ToolPackage\controller;

use framework\component\parent\WidgetController;
use framework\kernel\EntityManager\EntityChecker;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\repository\FileRepository;
use framework\packages\ToolPackage\entity\Grid;
use framework\packages\ToolPackage\service\Grid\GridFactory;

class DatabaseWidgetController extends WidgetController
{
    /**
    * Route: [name: admin_database_info_widget, paramChain: /admin/database/info/widget]
    */
    public function adminDatabaseInfoWidgetAction()
    {
        $activeEntityAttributes = $this->getContainer()->getEntityMap();
        // dump($activeEntityAttributes);

        // $this->getContainer()->wireService('ToolPackage/service/Grid/GridFactory');
        // $gridFactory = new GridFactory();
        // $gridFactory->setUsePager(false);
        // $gridFactory->setGridName('editFBSUser');
        // // $gridFactory->setRepositoryServiceLink('UserPackage/repository/FBSUserRepository');
        // $gridFactory->setProperties([
        //     ['name' => 'id', 'title' => 'id'],
        //     ['name' => 'name', 'title' => trans('name'), 'colWidth' => '2'],
        //     ['name' => 'username', 'title' => trans('username'), 'colWidth' => '2'],
        //     ['name' => 'email', 'title' => trans('email'), 'colWidth' => '4'],
        //     ['name' => 'highestPermissionGroup', 'title' => trans('highest.permission.group'), 'colWidth' => '4']
        // ]);
        // $grid = $gridFactory->create();

        // dump('alma');exit;

        $grid = new Grid();
        // $grid->setFormName('UserPackage_userAccountSearch_form');
        $grid->setGridName('showActiveDbEntityAttributes');
        $grid->setAllowCreateNew(false);
        $grid->setProperties([
            ['name' => 'entityName', 'title' => trans('entity.name'), 'colWidth' => '6'],
            ['name' => 'parentClassName', 'title' => trans('parent.class.name'), 'colWidth' => '3'],
            ['name' => 'condition', 'title' => trans('entity.condition'), 'colWidth' => '3'],
        ]);
        $grid->setData($activeEntityAttributes);
        $grid->setAddClassBy(array(
            array(
                'column' => 'condition',
                'value' => 'inactive',
                'class' => 'grid-body-row-disabled'
            ),
            array(
                'column' => 'condition',
                'value' => 'missing.class',
                'class' => 'grid-body-row-removed'
            ),
            array(
                'column' => 'condition',
                'value' => 'missing.table',
                'class' => 'grid-body-row-error'
            )
        ));

        $viewPath = 'ToolPackage/view/grid/defaultGridWidget.php';

        $response = [
            'view' => $this->renderWidget('AdminDatabaseInfoWidget', $viewPath, [
                'container' => $this->getContainer(),
                'renderedGrid' => $grid->render(),
                'gridAjaxInterface' => ''
            ]),
            'data' => []
        ];

        // $dbHelper = $this->getContainer()->getKernelObject('DbSchemaManager');
        // $submitted = $this->getContainer()->getRequest()->get('AdminDatabaseInfoWidget_submit');
        // $schemaInfo = $dbHelper->updateSchema($submitted ? 'update' : 'infoOnly');

        // $this->getContainer()->wireService('ToolPackage/entity/Grid');
        // $grid1 = new Grid();
        // $grid1->setGridName('DatabaseSchemaUpdateMap');
        // $grid1->setData($schemaInfo['schemaUpdateMap']);
        // $grid1->setAllowCreateNew(false);
        // $grid1->setProperties([
        //     ['name' => 'tableName', 'title' => trans('table.name'), 'colWidth' => '2'],
        //     ['name' => 'columnName', 'title' => trans('column.name'), 'colWidth' => '2'],
        //     ['name' => 'diffType', 'title' => trans('diff.type'), 'colWidth' => '2'],
        //     ['name' => 'param', 'title' => trans('param'), 'colWidth' => '2'],
        //     ['name' => 'mapValue', 'title' => trans('table.map.value'), 'colWidth' => '2'],
        //     ['name' => 'schemaValue', 'title' => trans('table.schema.value'), 'colWidth' => '2']
        // ]);

        // $grid2 = new Grid();
        // $grid2->setGridName('DatabaseNotExistingTables');
        // $grid2->setData($schemaInfo['notExistingTables']);
        // $grid2->setAllowCreateNew(false);
        // $grid2->setProperties([
        //     ['name' => 'tableName', 'title' => trans('table.name'), 'colWidth' => '8'],
        //     ['name' => 'fieldsNum', 'title' => trans('fields.num'), 'colWidth' => '4']
        // ]);

        // $schemaUpToDate = $schemaInfo['schemaUpdateMap'] == array()
        //     && $schemaInfo['notExistingTables'] == array() ? true : false;

        // $viewPath = 'framework/packages/ToolPackage/view/widget/AdminDatabaseInfoWidget/widget.php';

        // $response = [
        //     'view' => $this->renderWidget('AdminDatabaseInfoWidget', $viewPath, [
        //         'container' => $this->getContainer(),
        //         'schemaUpToDate' => $schemaUpToDate,
        //         'grid1' => $schemaUpToDate ? null : $grid1->render(),
        //         'grid2' => $schemaUpToDate ? null : $grid2->render(),
        //         'alterSchemaQueries' => $schemaInfo['alterSchemaQueries']
        //     ]),
        //     'data' => []
        // ];

        return $this->widgetResponse($response);
    }
}
