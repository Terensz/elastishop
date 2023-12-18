<?php
namespace framework\packages\ToolPackage\entity;

use framework\component\parent\PageController;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;

class Grid extends PageController
{
    private $gridName = '';
    // private $formName;
    private $repository;
    private $showId = false;
    private $data;
    private $allowCreateNew = true;
    private $limit = 20;
    private $totalCount;
    private $page = 1;
    private $totalPageCount;
    private $colsNum;
    private $properties;
    private $viewPath = 'framework/packages/ToolPackage/view/grid/defaultGrid.php';
    private $editUrl;
    private $addClassBy;
    private $addDeleteLink;
    private $orderByField;
    private $orderByDirection;
    private $allowOrderBy = true;

    public function setAddClassBy($addClassBy)
    {
        // dump($this->properties);
        $this->addClassBy = $addClassBy;
    }

    public function getAddClassBy()
    {
        return $this->addClassBy;
    }

    public function setGridName($gridName)
    {
        $this->gridName = $gridName;
    }

    public function getGridName()
    {
        return $this->gridName;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setAddDeleteLink($addDeleteLink)
    {
        $this->addDeleteLink = $addDeleteLink;
    }

    public function addDeleteLink()
    {
        if (!$this->properties) {
            throw new ElastiException(null, 1671);
        }
        $this->addDeleteLink = true;
    }

    public function getAddDeleteLink()
    {
        return $this->addDeleteLink;
    }

    public function setOrderByField($orderByField)
    {
        $this->orderByField = $orderByField;
    }

    public function getOrderByField()
    {
        return $this->orderByField;
    }

    public function setOrderByDirection($orderByDirection)
    {
        $this->orderByDirection = $orderByDirection;
    }

    public function getOrderByDirection()
    {
        return $this->orderByDirection;
    }

    public function setAllowOrderBy($allowOrderBy)
    {
        $this->allowOrderBy = $allowOrderBy;
    }

    public function getAllowOrderBy()
    {
        return $this->allowOrderBy;
    }

    // public function setFormName($formName)
    // {
    //     $this->formName = $formName;
    // }
    //
    // public function getFormName()
    // {
    //     return $this->formName;
    // }

    public function setShowId($showId)
    {
        $this->showId = $showId;
    }

    public function getShowId()
    {
        return $this->showId;
    }

    public function getAllowCreateNew()
    {
        return $this->allowCreateNew;
    }

    public function setAllowCreateNew($allowCreateNew)
    {
        $this->allowCreateNew = $allowCreateNew;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getTotalPageCount()
    {
        return $this->totalPageCount;
    }

    public function setTotalPageCount()
    {
        if ($this->totalCount && $this->limit) {
            $this->totalPageCount = ceil($this->totalCount / $this->limit);
        }
    }

    public function getColsNum()
    {
        return $this->colsNum;
    }

    public function setColsNum($colsNum)
    {
        $this->colsNum = $colsNum;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    public function autoSetObjectProperties()
    {
        if (!$this->properties) {
            $reflector = new Reflector();
            $propertiesArray = $reflector->getProperties($this->data[0]);
            foreach ($propertiesArray as $reflectionProperty) {
                $propertyName = $reflectionProperty->name;
                $this->properties[] = array(
                    'name' => $propertyName,
                    //'backgroundColor' => 
                    'title' => trans($propertyName)
                );
            }
        }
    }

    public function dataObjectsToArray()
    {
        if (isset($this->data[0]) && is_object($this->data[0])) {
            $this->repository = $this->data[0]->getRepository();
        }
        $data = array();
        for ($i = 0; $i < count($this->data); $i++) {
            $object = $this->data[$i];
            $dataRow = array();
            foreach ($this->properties as $property) {
                $dotPos = strpos($property['name'], '.');
                if ($dotPos !== false) {
                    $propertyParts = explode('.', $property['name']);
                    $childObjectGetter = 'get'.ucfirst($propertyParts[0]);
                    $childObject = $object->$childObjectGetter();
                    if ($childObject) {
                        $childPropertyGetter = 'get'.ucfirst($propertyParts[1]);
                        $childProperty = $childObject->$childPropertyGetter();
                        $dataRow[$property['name']] = $childProperty;
                    } else {
                        $dataRow[$property['name']] = null;
                    }
                } else {
                    $getter = 'get'.ucfirst($property['name']);
                    $dataRow[$property['name']] = $object->$getter();
                }
            }
            $data[] = $dataRow;
        }

        $this->data = $data;
    }

    public function autoSetColWidth()
    {
        for ($i = 0; $i < count($this->properties); $i++) {
            if (!isset($this->properties[$i]['colWidth'])) {
                $this->properties[$i]['colWidth'] = '';
            }

            $colWidth = $this->properties[$i]['colWidth'];
            $colWidth = str_replace('-', '', $colWidth);
            $colWidth = ctype_digit($colWidth) ? '-'.$colWidth : '';
            $this->properties[$i]['colWidth'] = $colWidth;
        }
    }

    public function render()
    {
        $this->setTotalPageCount();
        if (isset($this->data[0]) && is_object($this->data[0])) {
            $this->autoSetObjectProperties();
            $this->dataObjectsToArray();
        }
        // dump($this);exit;
        // dump($this->data);exit;

        $this->colsNum = count($this->properties);

        $this->autoSetColWidth();

        $newLinkView = '';
        if ($this->allowCreateNew) {
            $newLinkView = $this->renderView('framework/packages/ToolPackage/view/grid/newLink.php', [
                'grid' => $this,
                'container' => $this->getContainer()
            ]);
        }

        $pagerView = $this->renderView('framework/packages/ToolPackage/view/grid/pager.php', [
            'grid' => $this,
            'container' => $this->getContainer()
        ]);

        $gridView = $this->renderView($this->viewPath, [
            'grid' => $this,
            'repository' => $this->repository,
            'container' => $this->getContainer()
        ]);

        return $newLinkView.$pagerView.$gridView;
    }
}
