<?php
namespace framework\packages\ToolPackage\service\ViewTools;

use framework\component\parent\Service;
use framework\component\parent\Rendering;
use framework\kernel\utility\BasicUtils;
use framework\packages\ToolPackage\service\ViewTools\FormViewElement;
use framework\kernel\utility\FileHandler;
use framework\packages\FormPackage\service\RequestKeyProcessor;

class FormView extends Service
{
    const INPUT_PLACEHOLDER_PARAMS = array(
        'labelRate' => array('propertyLocation' => 'this'),
        'labelAdditionalClass' => array('propertyLocation' => 'this'),
        'inputRate' => array('propertyLocation' => 'this'),
        'label' => array('propertyLocation' => 'element'),
        'requestKey' => array('propertyLocation' => 'element'),
        'validationMessage' => array('propertyLocation' => 'element'),
        'isInvalidString' => array('propertyLocation' => 'element'),
        // 'name' => array('propertyLocation' => 'element'),
        'value' => array('propertyLocation' => 'element'),
        'displayedValue' => array('propertyLocation' => 'element'),
        'placeholder' => array('propertyLocation' => 'element'),
        'onclickFunction' => array('propertyLocation' => 'element')
    );

    const SCRIPT_PLACEHOLDER_PARAMS = array(
        'scriptId',
        'formMethodPath',
        'responseLabelSelector',
        'responseBodySelector',
        'formId'
    );

    private $active = true; # Inactive form will create inactive fields, and no <form> tag.
    private $requestKeyProcessor;
    private $form;
    private $originChains;
    private $requestKeyPrefix;
    private $labelAdditionalClass = '';
    private $idReferenceName = 'id';
    private $labelRate = 3;
    private $inputRate;
    // private $idPostKey;
    private $formMethodPath;
    private $responseViewObjectRoute = 'response.view';
    private $callbackJSFunction;
    private $responseLabelSelector = '#editorModalLabel';
    private $responseBodySelector = '#editorModalBody';
    private $formViewElements;
    private $viewFilePath = 'framework/packages/ToolPackage/view/ViewTools/form';

    public function __construct()
    {
        $this->wireService('FormPackage/service/RequestKeyProcessor');
        // $this->getContainer()->wireService('FormPackage/service/RequestKeyService');
        $this->getContainer()->wireService('ToolPackage/service/ViewTools/FormView/FormViewElement');
    }

    public function setRequestKeyProcessor($requestKeyProcessor)
    {
        $this->requestKeyProcessor = $requestKeyProcessor;
    }

    public function setResponseViewObjectRoute($responseViewObjectRoute)
    {
        $this->responseViewObjectRoute = $responseViewObjectRoute;
    }

    public function setCallbackJSFunction($callbackJSFunction)
    {
        $this->callbackJSFunction = $callbackJSFunction;
    }

    public function setToInactive()
    {
        $this->active = false;
    }

    public function getOriginChains()
    {
        return $this->originChains;
    }

    public function addOriginChain($key, $value)
    {
        return $this->originChains[$key] = $value;
    }

    public function setForm($form)
    {
        $this->form = $form;
        $packageNameStr = $form->getPackageName() ? $form->getPackageName() : '';
        $subjectStr = ($packageNameStr == '' ? '' : '_').($form->getSubject() ? $form->getSubject() : '');
        $this->requestKeyPrefix = $packageNameStr.$subjectStr == '' ? '' : $packageNameStr.$subjectStr.'_';
        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }

    // public function setIdPostKey($idPostKey)
    // {
    //     $this->idPostKey = $idPostKey;
    //     return $this;
    // }

    public function getScriptId()
    {
        return ucfirst(BasicUtils::snakeToCamelCase($this->getFormId()));
    }

    public function getFormId()
    {
        return $this->requestKeyPrefix ? $this->requestKeyPrefix.'form' : 'form';
    }

    public function setIdReferenceName($idReferenceName)
    {
        $this->idReferenceName = $idReferenceName;
    }

    public function getIdReferenceName()
    {
        return $this->idReferenceName;
    }

    public function setFormMethodPath($formMethodPath)
    {
        $this->formMethodPath = $formMethodPath;
    }

    public function getFormMethodPath()
    {
        return $this->formMethodPath;
    }

    public function setResponseLabelSelector($responseLabelSelector)
    {
        $this->responseLabelSelector = $responseLabelSelector;
    }

    public function getResponseLabelSelector()
    {
        return $this->responseLabelSelector;
    }

    public function setResponseBodySelector($responseBodySelector)
    {
        $this->responseBodySelector = $responseBodySelector;
    }

    public function getResponseBodySelector()
    {
        return $this->responseBodySelector;
    }

    // public function setRequestKeyPrefix($requestKeyPrefix)
    // {
    //     $this->requestKeyPrefix = $requestKeyPrefix;
    //     return $this;
    // }

    public function getRequestKeyPrefix()
    {
        return $this->requestKeyPrefix;
    }

    public function setLabelRate($labelRate)
    {
        $this->labelRate = $labelRate;
    }

    public function getLabelRate()
    {
        return $this->labelRate;
    }

    public function getInputRate()
    {
        return $this->inputRate ? $this->inputRate : (12 - $this->labelRate);
    }

    public function setInputRate($inputRate)
    {
        $this->inputRate = $inputRate;
    }

    public function setViewFilePath($viewFilePath)
    {
        $this->viewFilePath = $viewFilePath;
    }

    public function setLabelAdditionalClass($labelAdditionalClass)
    {
        $this->labelAdditionalClass = $labelAdditionalClass;
    }

    public function getLabelAdditionalClass()
    {
        return $this->labelAdditionalClass;
    }

    public function start()
    {

    }

    public function add($type)
    {
        $formViewElement = new FormViewElement($type, $this);
        // $formViewElement->setFormView($this);
        $this->formViewElements[] = $formViewElement;
        return $formViewElement;
    }

    public function displayForm($displayFormTag = true, $styleNicedit = true)
    {
        if ($styleNicedit && $this->active) {
            echo '
            <style>
            .nicEdit-main {
                border: 0px;
                padding: 0px;
                margin: 0px;
                outline:none;
                user-select: all;
                line-height: normal;
            }
            </style>
            ';
        }

        if ($displayFormTag && $this->active) {
            echo '<form name="'.$this->getFormId().'" id="'.$this->getFormId().'" method="POST" action="" enctype="multipart/form-data">';
        }
        foreach ($this->formViewElements as $formViewElement) {
            $inputType = $formViewElement->getType();
            if (!$this->active && in_array($inputType, array('text', 'textarea', 'simpleTextarea'))) {
                $inputType = 'inactiveField';
            }

            if (!$this->active && $inputType == 'select') {
                $inputType = 'inactiveSelect';
            }


            // dump($formViewElement->getInputName());
            $view = $this->renderView(FileHandler::completePath($this->viewFilePath.'/input/'.$inputType.'.php'), [
                    'form' => $this->form, 
                    'formViewElement' => $formViewElement
                ]
            );
            // dump($this->viewFilePath.'/input/'.$formViewElement->getType().'Input.php');exit;
            echo $this->resolveInputPlaceholders($view, $formViewElement);
        }
        if ($displayFormTag && $this->active) {
            echo '</form>';
        }
        return $this;
    }

    public function resolveInputPlaceholders($view, $formViewElement)
    {
        // dump($formViewElement->getRequestKey());
        foreach (self::INPUT_PLACEHOLDER_PARAMS as $placeholderParam => $placeholderConfig) {
            $object = $placeholderConfig['propertyLocation'] == 'this' ? $this : $formViewElement;
            $getter = 'get'.ucfirst($placeholderParam);
            $value = $object->$getter();
            if (is_object($value) || is_array($value)) {
                dump($value);//exit;
            }

            if ((is_string($value) && $value === '0') || $value === 0) {
                // dump('nulla....');
                $view = str_replace('{{ '.$placeholderParam.' }}', '0', $view);
            } else {
                $view = str_replace('{{ '.$placeholderParam.' }}', ($value ? : ''), $view);
            }
        }

        return $view;
    }

    public function displayScripts()
    {
        // dump($this->viewFilePath);exit;
        $viewPath = FileHandler::completePath($this->viewFilePath.'/script/formScripts.php');
        // dump($viewPath);
        echo '<script>';
        $view = $this->renderView($viewPath, [
            'container' => $this->getContainer(),
            'idReferenceName' => $this->idReferenceName,
            'responseViewObjectRoute' => $this->responseViewObjectRoute,
            'callbackJSFunction' => $this->callbackJSFunction,
            'data' => [
                'formIsValid' => $this->form->isValid(),
                'messages' => $this->form->getMessages()
            // 'request' => $this->getContainer()->getRequest()->getAll()
            ]
        ]);
        echo $this->resolveScriptPlaceholders($view);
        echo '</script>';
        return $this;
    }

    public function resolveScriptPlaceholders($view)
    {
        foreach (self::SCRIPT_PLACEHOLDER_PARAMS as $placeholderParam) {
            $getter = 'get'.ucfirst($placeholderParam);
            if ($this->$getter()) {
                $view = str_replace('{{ '.$placeholderParam.' }}', $this->$getter(), $view);
            }
        }
        return $view;
    }
}
