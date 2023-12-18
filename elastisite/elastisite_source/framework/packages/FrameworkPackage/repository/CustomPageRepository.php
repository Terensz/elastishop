<?php
namespace framework\packages\FrameworkPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\ToolPackage\service\PageTool;

class CustomPageRepository extends DbRepository
{
    public function __construct()
    {
        $this->wireService('ToolPackage/service/PageTool');
    }

    public function store($entity)
    {
        App::$cache->clear();
        
        return parent::store($entity);
    }

    public function remove($id)
    {
        $dbm = $this->getDbManager();

        $stm = "DELETE FROM custom_page_open_graph WHERE custom_page_id = :custom_page_id ";
        $dbm->execute($stm, ['custom_page_id' => $id]);

        $stm = "DELETE FROM custom_page_param_chain WHERE custom_page_id = :custom_page_id ";
        $dbm->execute($stm, ['custom_page_id' => $id]);

        // $stm = "DELETE FROM custom_page_background WHERE custom_page_id = :custom_page_id ";
        // $dbm->execute($stm, ['custom_page_id' => $id]);
// dump($dbm);exit;
        return parent::remove($id);
    }

    // public function getPageTool()
    // {
    //     $this->setService('ToolPackage/service/PageTool');
    //     return $this->getService('PageTool');
    // }

    public function getCustomPageRoutes()
    {
        $dbm = $this->getDbManager();

        $res = [];
        $stm = "SELECT route_name FROM custom_page WHERE website = '".App::getWebsite()."' ";
        $res0 = $dbm->findAll($stm);

        foreach ($res0 as $row) {
            $res[] = $row['route_name'];
        }

        return $res;
    }

    public function getTitles()
    {
        $dbm = $this->getDbManager();

        $res = [];
        $stm = "SELECT route_name, title FROM custom_page WHERE website = '".App::getWebsite()."' ";
        $res0 = $dbm->findAll($stm);

        foreach ($res0 as $row) {
            if ($row['title'] && $row['title'] !== '') {
                $res[$row['route_name']] = $row['title'];
            }
        }

        return $res;
    }

    public function getGridData($filter, $dataArrayRequired = true)
    {
        $filter = $this->transformFilter($filter);
        // dump($filter);
        $dbm = $this->getDbManager();
        $builtInPageRoutes = PageTool::getAllBuiltInPageRoutes();

        $dataArray = [];
        $query = $this->getGridDataQuery($filter);
        // $stm = "SELECT id, route_name, title, title_en FROM custom_page";

        if ($dataArrayRequired) {
            $res0 = $dbm->findAll($query['statement']);

            foreach ($res0 as $row) {
                $row['title'] = '';
                $row['title_en'] = '';
                if (isset($builtInPageRoutes[$row['route_name']])) {
                    $route = $builtInPageRoutes[$row['route_name']];
                    $row['title'] = trans($route['title']);
                    $row['title_en'] = trans($route['title'], null, 'en');
                }
                $dataArray[] = [
                    'id' => $row['id'],
                    'routeName' => $row['route_name'],
                    'title' => $row['title'],
                    'titleEn' => $row['title_en'],
                    'deletable' => $row['deletable']
                ];
            }
        }

        $return = [
            'dataArray' => $dataArrayRequired ? $dataArray : null,
            'statement' => $query['statement'],
            'usedFieldNames' => $this->getUsedFieldNames($query['statement'])
        ];

        // dump($return); exit;
        return $return;
    }

    public function getGridDataQuery($filter)
    {
        $filteredQuery = $this->getGridDataFilteredQuery($filter);
        $innerStatement = "SELECT * FROM (SELECT cp.id, cp.route_name, cp.title, 1 as 'deletable'
        -- , title_en 
        FROM custom_page cp WHERE cp.website = '".App::getWebsite()."' AND route_name <> 'reserved_default_route') _table0 ";
        return array(
            // 'countStatement' => "SELECT count(*) as count FROM (".$filteredQuery['statement'].") table2 ",
            'innerStatement' => $innerStatement,
            'statement' => "SELECT * FROM (".$innerStatement.") table2 ".$filter['orderByStr'].$filter['limitStr'],
            'params' => $filteredQuery['params'],
            'filteredQueryClassSource' => (isset($filteredQuery['filteredQueryClassSource']) && $filteredQuery['filteredQueryClassSource'] == 'parent') ? 'parent' : 'child',
            'queryClassSource' => 'parent'
        );
    }
}
