<?php
namespace framework\packages\ToolPackage\service\ViewTools;

use framework\component\exception\ElastiException;
use framework\packages\FormPackage\service\RequestKeyProcessor;

class FormViewElement
{
    private $formView;
    private $requestKeyProcessor;
    private $type;
    private $propertyReference;
    private $requestKey;
    private $options = [];
    // private $name;
    private $value;
    private $displayedValue;
    private $label;
    private $multiple;
    private $onclickFunction;
    private $placeholder;
    private $customData;

    public function __construct($type, $formView)
    {
        $this->formView = $formView;
        $this->type = $type;
        $primaryKeyStr = $formView->getForm()->getPrimaryKeyValue() ? "'".$formView->getForm()->getPrimaryKeyValue()."'" : "";
        if ($type == 'submit') {
            $this->onclickFunction = $this->formView->getScriptId().".call(".$primaryKeyStr.");";
        }
    }

    public function setFormView($formView)
    {
        $this->formView = $formView;
        // return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRequestKeyProcessor()
    {
        return $this->requestKeyProcessor;
    }

    public function setPropertyReference($propertyReference)
    {
        // $propertyReferenceParts = explode('_', $propertyReference);
        // if (count($propertyReferenceParts) == 1) {
        //     $specsMap = $this->formView->getForm()->getSpecsMap();
        //     if (isset($specsMap[$propertyReference])) {
        //         $entityKey = $this->formView->getForm()->getSpecsMap()[$propertyReference]['entityKey'];
        //         foreach ($this->formView->getForm()->getEntityCollector()->getCollection() as $collectionElement) {
        //             if ($collectionElement['entityKey'] == $entityKey) {
        //                 if (!isset($this->formView->getOriginChains()[$collectionElement['entityName']])) {
        //                     $originChain = $this->formView->getEntityManager()->createOriginChain(
        //                         $collectionElement, 
        //                         $this->formView->getForm()->getEntityCollector()
        //                     );
        //                     $this->formView->addOriginChain($collectionElement['entityName'], $originChain);
        //                 } else {
        //                     $originChain = $this->formView->getOriginChains()[$collectionElement['entityName']];
        //                 }
        //                 $propertyReference = $originChain.($originChain != '' ? '_' : '').$propertyReference;
        //                 dump($propertyReference);
        //             }
        //         }
        //     }
        // }
        
        $requestKeyProcessor = new RequestKeyProcessor($this->formView->getForm());
        $requestKeyProcessor->setPropertyAlias($propertyReference);
        $requestKeyProcessor->process();

        $this->requestKeyProcessor = $requestKeyProcessor;

        $this->propertyReference = $propertyReference;
        // $this->requestKey = ($this->formView->getRequestKeyPrefix() 
        //     ? $this->formView->getRequestKeyPrefix() : '').$this->propertyReference;

        $this->requestKey = $requestKeyProcessor->requestKey;
        // $this->name = $this->multiple ? $this->idBase.'[]' : $this->id;
        // $this->name = $this->requestKey;
        return $this;
    }

    public function getPropertyReference()
    {
        return $this->propertyReference;
    }

    public function getRequestKey()
    {
        return $this->requestKey;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Ne keverd ossze a displayedValue propertyvel!!!! Ezt pl. a Submit gombjanak ertekenel hasznaljuk
    */
    public function getValue()
    {
        return $this->value;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function addOption($key, $displayed, bool $translated = true, string $style = null, $forceSelected = false)
    {
        $this->options[] = array(
            'key' => $key,
            'displayed' => $displayed,
            'translated' => $translated,
            'style' => $style,
            'forceSelected' => $forceSelected
        );
        return $this;
    }

    public function addCustomData($key, $value)
    {
        $this->customData[$key] = $value;
        return $this;
    }

    public function getCustomData($key)
    {
        return isset($this->customData[$key]) ? $this->customData[$key] : null;
    }

    public function getValidationMessage()  
    {
        $messages = $this->formView->getForm()->getMessages();
        return isset($messages[$this->requestKey]) ? $messages[$this->requestKey] : null;
        // dump($this->formView->getForm());
        // return $this->formView->getForm()->getMessages()->get;
    }

    public function getIsInvalidString()  
    {
        $messages = $this->formView->getForm()->getMessages();
        $message = isset($messages[$this->requestKey]) ? $messages[$this->requestKey] : null;

        return $message ? ' is-invalid' : '';
        // dump($this->formView->getForm());
        // return $this->formView->getForm()->getMessages()->get;
    }

    public function setDisplayedValue($displayedValue)  
    {
        $this->displayedValue = $displayedValue;
        return $this;
    }

    public function getDisplayedValue()  
    {
        return !$this->displayedValue ? $this->formView->getForm()->getValueCollector()->getDisplayed($this->requestKey) : $this->displayedValue;
        // return $this->value ? $this->value : $this->formView->getForm()->getValueCollector()->getDisplayed($this->requestKey);
    }

    public function setLabel($label = null)
    {
        $this->label = $label;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setOnclickFunction($onclickFunction)
    {
        $this->onclickFunction = $onclickFunction;
        return $this;
    }

    public function getOnclickFunction()
    {
        return $this->onclickFunction;
    }

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }
}
