<?php
namespace framework\packages\ToolPackage\service;

use framework\component\parent\PageController;

class GridAjaxInterface extends PageController
{
    private $packageName;
    private $onSaveReloadFunction;
    private $gridName;
    // private $entityName;
    private $searchActionParamChain;
    private $editActionParamChain;
    private $deleteActionParamChain;
    private $viewPath = 'framework/packages/FrameworkPackage/view/GridAjaxInterface/GridAjaxInterfaceJs.php';
    private $searchResponseSelector;
    private $searchFormName;
    private $callResponseScript = '';
    private $deleteResponseScript = 'Structure.update();';

    public function getPackageName()
    {
        return $this->packageName;
    }

    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
    }

    public function getOnSaveReloadFunction()
    {
        return $this->onSaveReloadFunction ? $this->onSaveReloadFunction : ucfirst($this->gridName).'GridAjaxInterface.call(id);';
    }

    public function setOnSaveReloadFunction($onSaveReloadFunction)
    {
        $this->onSaveReloadFunction = $onSaveReloadFunction;
    }

    public function getGridName()
    {
        return $this->gridName;
    }

    public function setGridName($gridName)
    {
        $this->gridName = $gridName;
    }

    // public function getEntityName()
    // {
    //     return $this->entityName;
    // }

    // public function setEntityName($entityName)
    // {
    //     $this->entityName = $entityName;
    // }

    public function setSearchActionParamChain($searchActionParamChain)
    {
        $this->searchActionParamChain = $searchActionParamChain;
    }

    public function getSearchActionParamChain()
    {
        return $this->searchActionParamChain;
    }

    public function setEditActionParamChain($editActionParamChain)
    {
        $this->editActionParamChain = $editActionParamChain;
    }

    public function getEditActionParamChain()
    {
        return $this->editActionParamChain;
    }

    public function setDeleteActionParamChain($deleteActionParamChain)
    {
        $this->deleteActionParamChain = $deleteActionParamChain;
    }

    public function getDeleteActionParamChain()
    {
        return $this->deleteActionParamChain;
    }

    public function getViewPath()
    {
        return $this->viewPath;
    }

    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    public function getCallResponseScript()
    {
        return $this->callResponseScript;
    }

    public function setCallResponseScript($callResponseScript)
    {
        $this->callResponseScript = $callResponseScript;
    }

    public function getSearchResponseSelector()
    {
        return $this->searchResponseSelector;
    }

    public function setSearchResponseSelector($searchResponseSelector)
    {
        $this->searchResponseSelector = $searchResponseSelector;
    }

    public function getSearchFormName()
    {
        return $this->searchFormName;
    }

    public function setSearchFormName($searchFormName)
    {
        $this->searchFormName = $searchFormName;
    }

    public function getDeleteResponseScript()
    {
        return $this->deleteResponseScript;
    }

    public function setDeleteResponseScript($deleteResponseScript)
    {
        $this->deleteResponseScript = $deleteResponseScript;
    }

    public function render()
    {
        $view = $this->renderView($this->viewPath, [
            'onSaveReloadFunction' => $this->getOnSaveReloadFunction(),
            'packageName' => $this->packageName,
            'gridName' => $this->gridName,
            // 'entityName' => $this->entityName,
            'searchResponseSelector' => $this->searchResponseSelector,
            'searchFormName' => $this->searchFormName,
            'callResponseScript' => $this->callResponseScript,
            'deleteResponseScript' => $this->deleteResponseScript,
            'searchActionParamChain' => $this->searchActionParamChain,
            'editActionParamChain' => $this->editActionParamChain,
            'deleteActionParamChain' => $this->deleteActionParamChain,
            'container' => $this->getContainer()
        ]);

        return $view;
    }
}
