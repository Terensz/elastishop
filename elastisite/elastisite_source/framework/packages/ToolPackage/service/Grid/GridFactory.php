<?php
namespace framework\packages\ToolPackage\service\Grid;

use framework\kernel\component\Kernel;
use framework\packages\ToolPackage\entity\Grid;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;

class GridFactory extends Kernel
{
    private $properties;
    private $gridName = 'unnamed';
    private $limit = 10;
    private $repositoryServiceLink;
    private $repository;
    private $filter;
    private $usePager = true;
    private $allowCreateNew = false;
    private $addDeleteLink = false;
    private $orderByField;
    private $orderByDirection;
    private $filteredResult;
    // private $formName;
    // private $packageName;
    // private $subject;
    private $addClassBy = array(
        array(
            'column' => 'status',
            'value' => 0,
            'class' => 'grid-body-row-disabled'
        ),
        array(
            'column' => 'status',
            'value' => 2,
            'class' => 'grid-body-row-proven'
        )
    );

    public function getFilter()
    {
        return $this->filter;
    }

    public function __construct()
    {
        //dump($this->getContainer()->getRequest()->getAll()); exit;
        $this->getContainer()->wireService('ToolPackage/entity/Grid');
        $orderBy = $this->getContainer()->getRequest()->get('orderBy');
        if ($orderBy && isset($orderBy['prop'])) {
            $dotPos = strpos($orderBy['prop'], '.');
            if ($dotPos === false) {
                $this->orderByField = BasicUtils::camelToSnakeCase($orderBy['prop']);
            } else {
                // $this->orderByField = ucfirst($orderBy['prop']);
                $this->orderByField = $orderBy['prop'];
            }
            
            $this->orderByDirection = $orderBy['direction'];
            //dump($this->orderByField); exit;
        }
    }

    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    public function setUsePager($usePager)
    {
        $this->usePager = $usePager;
    }

    public function setAllowCreateNew($allowCreateNew)
    {
        $this->allowCreateNew = $allowCreateNew;
    }

    public function setGridName($gridName)
    {
        $this->gridName = $gridName;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    // public function setOrderBy($orderBy)
    // {
    //     $this->orderBy = $orderBy;
    // }

    public function setRepositoryServiceLink($repositoryServiceLink)
    {
        $this->repositoryServiceLink = $repositoryServiceLink;
        $this->getContainer()->setService($repositoryServiceLink);
        $repo = $this->getContainer()->getService(BasicUtils::explodeAndGetElement($repositoryServiceLink, '/', 'last'));
        $this->repository = $repo;

        if (!$this->orderByField) {
            //dump('Alma????');
            $this->orderByField = $repo->createNewEntity()->getIdFieldName();
            $this->orderByDirection = 'DESC';
        }
        // dump($repo->findAll());exit;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    // public function setFormName($formName)
    // {
    //     $formNameParts = explode('_', $formName);
    //     if (count($formNameParts) > 2) {
    //         $this->packageName = $formNameParts[0];
    //         $this->subject = $formNameParts[1];
    //     } elseif (count($formNameParts) == 2) {
    //         $this->subject = $formNameParts[0];
    //     }
    //     $this->formName = $formName;
    // }

    // public function setPackageName($packageName)
    // {
    //     $this->packageName = $packageName;
    // }

    // public function setSubject($subject)
    // {
    //     $this->subject = $subject;
    // }

    public function getFilteredResult()
    {
        return $this->filteredResult;
    }

    public function setAddClassBy($addClassBy)
    {
        $this->addClassBy = $addClassBy;
    }

    public function addDeleteLink()
    {
        if (!$this->properties) {
            throw new ElastiException(null, 1671);
        }
        $this->addDeleteLink = true;
    }

    public function create($filter = null, $page = 1)
    {
        $grid = new Grid();
        // $grid->setFormName('UserPackage_userAccountSearch_form');
        $grid->setGridName($this->gridName);
        $grid->setPage($page);
        // dump($this->repository);
        $originalFilter = $filter;
        $filter = $filter ? array_merge($this->repository->transformFilter($filter), ['maxResults' => $grid->getLimit(), 'currentPage' => $page]) : null;
        $filter = $this->removeBlankFromFilter($filter);
        if ($this->orderByField) {
            $filter['orderBy'][0] = [
                'field' => $this->orderByField,
                'direction' => $this->orderByDirection ? : 'ASC',
            ];
            $grid->setOrderByField($this->orderByField);
            $grid->setOrderByDirection($this->orderByDirection ? : 'ASC');
        }
        $this->filter = $filter;

        //dump($filter);
        if ($this->usePager) {
            $grid->setTotalCount($this->repository->getTotalCount($filter));
            $grid->setLimit($this->limit);
        }
        $grid->setAllowCreateNew($this->allowCreateNew);
        // $grid->setPackageName($this->packageName);
        // $grid->setSubject($this->subject);
        $grid->setProperties($this->properties);
        $grid->setAddDeleteLink($this->addDeleteLink);
        // dump($filter);//exit;
        // $tableFieldNames = $this->repository->getTableFieldNames();
        // dump($tableFieldNames);
        $filteredResult = $this->repository->getFilteredResult($filter);
        $this->filteredResult = $filteredResult;
        // dump($originalFilter);
        // dump($filter);
        // dump($filteredResult);
        $grid->setData($filteredResult);
        // $grid->setViewPath('framework/packages/UserPackage/view/widget/AdminUserAccountsWidget/grid.php');
        // dump($filteredResult);exit;

        if ($this->addClassBy) {
            $revisionedAddClassBy = array();
            foreach ($grid->getProperties() as $gridProperty => $attributes) {
                foreach ($this->addClassBy as $addClassByEntry) {
                    if ($attributes['name'] == $addClassByEntry['column']) {
                        $revisionedAddClassBy[] = $addClassByEntry;
                    }
                }
            }
            $grid->setAddClassBy($revisionedAddClassBy);
        }
        // $grid->setTotalPageCount();
        // dump($grid);exit;
        return $grid;
    }

    private function removeBlankFromFilter($filter)
    {
        if (!$filter || !isset($filter['conditions'])) {
            return null;
        }
        $conditions = array();
        foreach ($filter['conditions'] as $condition) {
            if ($condition['value'] !== '' && $condition['value'] !== null) {
                $conditions[] = $condition;
            }
        }
        $filter['conditions'] = $conditions;
        return $filter;
    }
}
