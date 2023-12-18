<?php
namespace framework\packages\FormPackage\service;

use framework\component\parent\Service;
use framework\packages\FormPackage\entity\Form;
use framework\packages\UserPackage\entity\Person;
use framework\kernel\utility\BasicUtils;

class FormBuilder extends Service
{
    private $encodeRequestKeys;
    private $packageName;
    private $projectName;
    private $subject;
    private $primaryKeyValue;
    private $saveRequested = true;
    private $schemaPath;
    private $autoSubmit = true;
    private $submitted = false;
    private $externalPosts = array();
    private $defaultValues = array();

    public function __construct()
    {
        // $this->getContainer()->wireService('FormPackage/service/RequestKeyService');
    }

    /**
     * If no posted or stored value, this will be the default displayed.
     * This feature is half-developed, you cannot use this for multiple properties.
    */
    public function addDefaultValue($key, $defaultValue)
    {
        $this->defaultValues[$key] = $defaultValue;
    }

    public function setEncodeRequestKeys($encodeRequestKeys)
    {
        $this->encodeRequestKeys = $encodeRequestKeys;
    }

    public function getEncodeRequestKeys()
    {
        return $this->encodeRequestKeys;
    }

    public function setPackageName($packageName) 
    {
        $this->packageName = $packageName;
    }

    public function getPackageName() 
    {
        return $this->packageName;
    }

    public function setProjectName($projectName)
    {
        $this->packageName = $projectName;
    }

    public function getProjectName() 
    {
        return $this->packageName;
    }

    public function setSubject($subject) 
    {
        $this->subject = $subject;
    }

    public function getSubject() 
    {
        return $this->subject;
    }

    public function setPrimaryKeyValue($primaryKeyValue) 
    {
        $this->primaryKeyValue = $primaryKeyValue;
    }

    public function getPrimaryKeyValue() 
    {
        return $this->primaryKeyValue;
    }

    public function setSaveRequested($saveRequested) 
    {
        $this->saveRequested = $saveRequested;
    }

    public function getSaveRequested() 
    {
        return $this->saveRequested;
    }

    public function setSchemaPath($schemaPath) 
    {
        $this->schemaPath = $schemaPath;
    }

    public function getSchemaPath() 
    {
        return $this->schemaPath;
    }

    public function setAutoSubmit($autoSubmit) 
    {
        $this->autoSubmit = $autoSubmit;
    }

    public function getAutoSubmit() 
    {
        return $this->autoSubmit;
    }

    public function setSubmitted($submitted) 
    {
        if ($submitted === false || $submitted === 'false') {
            $submitted = false;
        }
        if ($submitted === true || $submitted === 'true') {
            $submitted = true;
        }
        $this->submitted = $submitted;
    }

    public function getSubmitted() 
    {
        return $this->submitted;
    }

    public function addExternalPost($externalPost) 
    {
        $this->externalPosts[] = $externalPost;
    }

    public function setExternalPosts($externalPosts) 
    {
        $this->externalPosts = $externalPosts;
    }

    public function getExternalPosts() 
    {
        return $this->externalPosts;
    }

    public function createForm()
    {
        $this->getContainer()->wireService('FormPackage/entity/Form');

        if (!$this->schemaPath) {
            $schemaName = ucfirst($this->subject).'Schema';
            $this->schemaPath = $this->packageName.'/form/'.$schemaName;
        }
        else {
            $schemaName = BasicUtils::explodeAndGetElement($this->schemaPath, '/', 'last');
        }
        // dump();
        $this->getContainer()->setService($this->schemaPath);
        $form = new Form();
        $form->setEncodeRequestKeys($this->encodeRequestKeys);
        $submitted = $this->autoSubmit ? $this->getContainer()->getRequest()->isSubmitted() : $this->submitted;
        // dump($this->submitted);
        $form->setSubmitted($submitted);
        $form->setPackageName($this->packageName);
        // $form->setProjectName($this->projectName);
        $form->setSubject($this->subject);
        $form->setPrimaryKeyValue($this->primaryKeyValue);
        if (!is_array($this->externalPosts) || (is_array($this->externalPosts) && !in_array('orderBy', $this->externalPosts))) {
            if (!is_array($this->externalPosts)) {
                $this->externalPosts = [];
            }
            $this->externalPosts[] = 'orderBy';
        }
        $form->setExternalPosts($this->externalPosts);
        // dump($form);
        $formSchema = $this->getContainer()->getService($schemaName);
        $this->getContainer()->setService('FormPackage/service/SchemaProcessor');
        $schemaProcessor = $this->getContainer()->getService('SchemaProcessor');
        $schemaProcessor->setForm($form);
        $schemaProcessor->setDefaultValues($this->defaultValues);
        $schemaProcessor->process($formSchema);
        $repo = $schemaProcessor->getForm()->getPrimaryRepository();
        // dump($repo::REPOSITORY_TYPE_TECHNICAL);
        // dump($form->getEntity());
        // dump($repo->getRepositoryType());exit;
        // if (!$form->getEntity() && $repo->getRepositoryType() == $repo::REPOSITORY_TYPE_TECHNICAL) {
        //     dump('Repa!!!');
        //     dump($repo);exit;
        // }

        $assembledEntity = $this->getEntityManager()->assembleEntity($form->getEntityCollector());
        $assembledEntity = $assembledEntity == array() ? null : $assembledEntity[0];
        // dump($form->getEntityCollector());exit;
        $form->setEntity($assembledEntity);
        // dump($form);exit;

        if ($this->saveRequested && $form->isSubmitted()) {
// dump($form);exit;
            if ($form->isValid()) {
                $form = $this->save($formSchema, $form);
            }
        }
        return $form;
    }

    public function save($formSchema, $form)
    {
        // $form->setEntity($this->getEntityManager()->assembleEntity($form->getEntityCollector()));
        // $assembled = $this->getEntityManager()->assembleEntity($form->getEntityCollector());
        // dump($assembled);
// dump($form);exit;
// dump($form);exit;
// dump($form->getEntity());exit;
        // $repo = $form->getDataRepository();
        // $storeDataMethod = $form->getStoreDataMethod() ? $form->getStoreDataMethod() : 'store';
        // $entity = $repo->$storeDataMethod($form->getEntity());
        // dump($entity);exit;
        // if (is_object($entity)) {
        //     $form->setEntity($entity);
        // }
        $entity = $form->getEntity();
        // dump($entity);exit;
        $entity = $entity->getRepository()->store($entity);
        $form->setEntity($entity);
        $form->setSaved(true);
        
        return $form;
    }
    
    // public function processPost($entity, $package, $subject)
    // {
    //     $default = $this->getDefault($entity, $package, $subject, $entitySpecs);
    //     dump($this->getContainer()->getRequest()->getAll());exit;
    //     $result = true;
    //     foreach ($this->getContainer()->getRequest()->getAll() as $key => $requestedValue) {
    //         $keyParts = explode('_', $key);
    //         if (isset($keyParts[0]) && $keyParts[0] == $package && isset($keyParts[1]) && $keyParts[1] == $subject) {
    //             foreach ($entitySpecs as $attribute => $attributeConfig) {
    //                 $defaultObject = isset($attributeConfig['compareAttributes']) ? $this->persist($entity, $default) : null;
    //                 if (!isset($attributeConfig['validatorRules'])) {
    //                     $attributeConfig['validatorRules'] = array();
    //                 }
    //                 if (isset($attributeConfig['multiple']) && $attributeConfig['multiple'] && !is_array($requestedValue)) {
    //                     $requestedValue = array($requestedValue);
    //                 }
    //                 if (is_array($requestedValue)) {
    //                     # Ellenorizni!!!!
    //                     if (isset($keyParts[2])) {
    //                         if ($attribute == $keyParts[2]) {
    //                             $request[$attribute] = array();
    //                             $message[$attribute] = null;
    //                             foreach ($requestedValue as $requestedValue1) {
    //                                 $requestedValue1 = $this->transformValue($requestedValue1, $attributeConfig);
    //                                 $request[$attribute][] = $requestedValue1;
    //                                 $validation = $this->validateAttribute($attribute, $requestedValue1, $attributeConfig, $defaultObject, $customValidators);
    //                                 $message[$attribute] = $validation['message']
    //                                     ? (!$message[$attribute] ? $validation['message'] : $message[$attribute].', '.$validation['message'])
    //                                     : $message[$attribute];
    //                                 $result = $result == false ? false : ($validation['result'] == false ? false : true);
    //                             }
    //                         }
    //                     }
    //                 }
    //                 else {
    //                     if (isset($keyParts[2])) {
    //                         if ($attribute == $keyParts[2]) {
    //                             if (isset($attributeConfig['type']) && $attributeConfig['type'] == 'password' && $requestedValue == '') {
    //                                 continue;
    //                             }
    //                             $requestedValue = $this->transformValue($requestedValue, $attributeConfig);
    //                             $request[$attribute] = $requestedValue;
    //                             $validation = $this->validateAttribute($attribute, $requestedValue, $attributeConfig, $defaultObject, $customValidators);
    //                             $message[$attribute] = $validation['message'];
    //                             $result = $result == false ? false : ($validation['result'] == false ? false : true);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //
    //     return array(
    //         'request' => $request,
    //         'result' => $result,
    //         'message' => $message,
    //         'default' => $default,
    //         'entity' => $entity
    //     );
    // }
}
