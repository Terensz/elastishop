<?php
namespace framework\packages\FormPackage\service;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;
use framework\packages\FormPackage\service\RequestKeyService;
use framework\packages\FormPackage\service\RequestKeyProcessor;
// use framework\kernel\EntityManager\entity\EntityCollector;

class SchemaProcessor extends Service
{
    // private $debug = true;
    // private $specsMap;
    // private $propertyAliases;
    // private $entityConfig;
    private $form;
    private $entitySpecs;
    private $schemaConfig;
    private $customValidators;
    private $defaultValues;

    public function __construct()
    {
        $this->wireService('FormPackage/service/RequestKeyService');
        $this->wireService('FormPackage/service/RequestKeyProcessor');
    }

    public function setDefaultValues($defaultValues)
    {
        $this->defaultValues = $defaultValues;
    }

    public function setForm($form)
    {
        $this->form = $form;
    }

    public function getSchemaConfig($formSchema)
    {
        if (method_exists($formSchema, 'getSchemaConfig')) {
            return $formSchema->getSchemaConfig();
        }
        return null;
    }

    public function process($formSchema)
    {
        $this->form->setValid($this->form->isSubmitted() ? true : false);
        $this->entitySpecs = $formSchema->getEntitySpecs();

        // dump($this->form);
        $this->processSpecs();

        $this->schemaConfig = $this->getSchemaConfig($formSchema);

        $this->customValidators = method_exists($formSchema, 'getCustomValidators')
            ? $formSchema->getCustomValidators() : array();

        if (count($this->entitySpecs) != 1) {
            throw new ElastiException('Schema must contain exactly 1 entity', ElastiException::ERROR_TYPE_SECRET_PROG);
        }

        if (isset($this->schemaConfig['storeDataMethod'])) {
            $this->form->setStoreDataMethod($this->schemaConfig['storeDataMethod']);
        }

        // dump($this->form);

        foreach ($this->form->getSpecsMap() as $propertyParams) {
            $this->processStored($propertyParams);
        }

        $this->form->getValueCollector()->setSpecsMap($this->form->getSpecsMap());

        $this->processAllPosted();

        $this->processAllDisplayed();

        $this->postsToEntityCollection();

        $this->processAllValidations();

        $this->form->setMessages($this->collectMessages());
    }

    public function collectMessages()
    {
        $messages = array();
        foreach ($this->form->getValueCollection() as $propertyAlias => $valueCollectionElement) {
            foreach ($valueCollectionElement as $requestKey => $valueArray) {
                if ($valueArray['message']) {
                    $messages[$requestKey] = $valueArray['message'];
                }
            }
        }
        return $messages;
    }

    public function processSpecs(
        $parentEntityName = null,
        $parentEntityKey = null,
        $entityName = null,
        $entityKey = null,
        $propertySpecs = null,
        $entities = array()
    )
    {
        $em = $this->getEntityManager();
        foreach (($propertySpecs ? $propertySpecs : $this->entitySpecs) as $key => $value) {
            $primaryEntityPos = strpos($key, 'primaryEntity:');
            $entityPos = strpos($key, 'ntity:');
            if ($primaryEntityPos === false && $entityPos === false) {
                // dump($key);
                // dump($value);
                $specsMap = array(
                    'propertyAlias' => $key,
                    'property' => isset($value['property']) ? $value['property'] : $key,
                    'parentEntityName' => $parentEntityName,
                    'parentEntityKey' => $parentEntityKey,
                    'entityName' => $entityName,
                    'entityKey' => $entityKey,
                    'mapped' => isset($value['mapped']) && $value['mapped'] == false ? false : true,
                    'technical' => isset($value['technical']) ? $value['technical'] : false,
                    'default' => isset($value['default']) ? $value['default'] : null,
                    'validatorRules' => isset($value['validatorRules']) ? $value['validatorRules'] : null
                    // 'multiple' => $this->form->isMultiple($entityKey)
                );
                // dump($specsMap);
                $valueCollectionElement['propertyAlias']['entityName'] = $entityName;
                $valueCollectionElement['propertyAlias']['propertyAlias'] = $entityName;
                // $this->form->addToValueCollection($valueCollectionElement);
                $this->form->addSpecsMap($key, $specsMap);
                // $this->specsMap[$key] = $specsMap;
            } else {
                $childEntityPath = str_replace('primaryEntity:', '', $key);
                $childEntityPath = str_replace('entity:', '', $childEntityPath);
                $childEntityName = BasicUtils::explodeAndGetElement($childEntityPath, '/', 'last');
                // dump($childEntityPath);
                $childServiceLinkParams = $this->getContainer()->wireService($childEntityPath);
                $childNamespace = $childServiceLinkParams['objectNamespace'];
                $childEntity = new $childNamespace();
                $this->form->addDummyEntity($childEntityName, $childEntity);
// dump($childEntity); //exit;
                $childRepo = $childEntity->getRepository();

// dump($childRepo->getPrimaryKeyField()); //exit;
                $childEntityKey = ($entityName ? $entityName : '0').'-'.$childEntityName;
                if (!$this->form->onEntityMap($childEntityKey)) {
                    $this->form->createAndAddEntityMapElement($childEntityKey);
                }
                if ($primaryEntityPos !== false) {
                    $this->form->setPrimaryEntityPath($childEntityPath);
                    $this->form->setPrimaryEntityKey($childEntityKey);
                    $this->form->setPrimaryKeyField($childRepo->getPrimaryKeyField());
                    // dump($childRepo);


                    $entityCollector = $em->createEntityCollection(
                        $childRepo,
                        ['conditions' => [['key' => $this->form->getPrimaryKeyField(), 'value' => $this->form->getPrimaryKeyValue()]]], // mainEntityFilter
                        '0', // parentEntityName
                        null, // parentEntityKey
                        1, // limit
                        'result', // queryType
                        true, // forceCollection,
                        'schemaProcessor'
                    );
// dump($childRepo);
// dump(get_class($childRepo));
// dump($entityCollector);// exit;
                    $this->form->setEntityCollector($entityCollector);
                    // $this->form->setRelationParams($entityCollector->getRelationParams());
                }

                // dump($entityCollector);

                $this->processSpecs(
                    $entityName,
                    $entityKey,
                    $childEntityName,
                    $childEntityKey,
                    $value
                );
            }
        }
    }

    public function processStored($propertyParams)
    {
        // dump($propertyParams);
        // dump($this->form->getEntityCollector()->getCollection());

        if (!$propertyParams['technical']) {
            $found = $this->findInCollectionAndSetStored($propertyParams, false);
            // dump($getter);
            // dump($this->form);
            // dump($propertyParams);
            // dump($this->form->getEntityCollector()->getCollection());exit;
            


            if (!$found) {
                $found = $this->findInCollectionAndSetStored($propertyParams, true);
                // dump('----------------------------------');
                // dump('processStored: property not found.');
                // dump($propertyParams);
                // dump($this->form->getEntityCollector()->getCollection());
                // exit;
            }
        }
// dump($this->form->getValueCollector());
// dump('----vege');exit;
    }

    private function findInCollectionAndSetStored($propertyParams, $acceptDummyEntity)
    {
        $originChains = array();
        $found = false;
        $getter = 'get'.ucfirst($propertyParams['property']);

        foreach ($this->form->getEntityCollector()->getCollection() as $entityCollectionElement) {
            if ($entityCollectionElement['entityKey'] == $propertyParams['entityKey'] && $entityCollectionElement['parentEntityKey'] == $propertyParams['parentEntityKey'] && $propertyParams['mapped']) {
                if (!$acceptDummyEntity && $entityCollectionElement['isDummyEntity']) {
                    continue;
                }
                $found = true;
                $object = $entityCollectionElement['entity'];

                if (!isset($originChains[$entityCollectionElement['entityName']])) {
                    $originChain = $this->getEntityManager()->createOriginChain(
                        $entityCollectionElement, 
                        $this->form->getEntityCollector()
                    );
                    $originChains[$entityCollectionElement['entityName']] = $originChain;
                } else {
                    $originChain = $originChains[$entityCollectionElement['entityName']];
                }

                $requestKey = RequestKeyService::concatRequestKey(
                    $this->form->getPackageName(), 
                    $this->form->getSubject(),
                    $originChain,
                    $propertyParams['propertyAlias']
                );
                $requestKeyProcessor = new RequestKeyProcessor($this->form);
                $requestKeyProcessor->line = 229;
                $requestKeyProcessor->setPostedRequestKey($requestKey);
                $requestKeyProcessor->process();

                if (!$this->form->getValueCollector()->hasValue($propertyParams['propertyAlias'], 'stored', $requestKey)) {
                    $this->form->getValueCollector()->addValue(
                        $entityCollectionElement['collectionId'],
                        $propertyParams['propertyAlias'],
                        'entityCollectionId',
                        $requestKeyProcessor->requestKey
                    );
                    $this->form->getValueCollector()->addValue(
                        $object->$getter(),
                        $propertyParams['propertyAlias'],
                        'stored',
                        $requestKeyProcessor->requestKey
                    );
                    break;
                }
            }
        }

        return $found;
    }

    // public function getPosted($propertyAlias)
    // {
    //     $inputName = $this->form->getPackageName().'_'.$this->form->getSubject().'_'.$propertyAlias;
    //     $posted = $this->getContainer()->getRequest()->get($inputName);
    //     return $posted;
    // }










    /**
     * @TODO !!!!!
    */

    // public function processAllPosted()
    // {
    //     $requests['inSubject'] = array();
    //     $requests['outSubject'] = array();
    //     if (!$this->getContainer()->getRequest()->getAll()) {
    //         return false;
    //     }
    //     $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');
    //     foreach ($this->getContainer()->getRequest()->getAll() as $requestKey => $posted) {
    //         // dump($requestKey);
    //         if (!in_array($requestKey, $this->form->getExternalPosts())) {
    //             $schemaContainsThisRequest = false;
    //             $requestKeyAttributes = RequestKeyAnalyzer::getRequestKeyAttributes(
    //                 $requestKey,
    //                 $this->form
    //             ); 
    //             if ($requestKeyAttributes['errors'] == array()) {
    //                 $counter = count($requests['inSubject']);
    //                 $requests['inSubject'][$counter][$requestKey] = $posted;
    //                 foreach ($this->form->getSpecsMap() as $propertyParams) {
    //                     if ($propertyParams['propertyAlias'] == $requestKeyAttributes['propertyAlias']) {
    //                         $schemaContainsThisRequest = true;
    //                         $this->form->getValueCollector()->addValue(
    //                             $posted,
    //                             $propertyParams['propertyAlias'],
    //                             'posted',
    //                             $requestKey
    //                         );
    //                         break;
    //                     }
    //                 }
    //             } else {
    //                 $counter = count($requests['outSubject']);
    //                 $requests['outSubject'][$counter][$requestKey] = $posted;
    //             }
    //             if (!$schemaContainsThisRequest) {
    //                 $securityEventHandler->addEvent('NON_LEGIT_POST_KEY', $requestKey, $posted);
    //             }
    //         }
    //     }
    //     $securityEventHandler->secureSchemaProcessing();
    //     $this->form->setRequests($requests);
    // }

    public function processAllPosted()
    {
        $requests['inSubject'] = array();
        $requests['outSubject'] = array();
        $requests['unprocessed'] = array();

        if (!$this->getContainer()->getRequest()->getAll()) {
            return false;
        }
        $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');

        foreach ($this->form->getSpecsMap() as $propertyAlias => $propertyParams) {
            $requestKeyProcessor1 = new RequestKeyProcessor($this->form);
            $requestKeyProcessor1->line = 354;

            // if (!$propertyAlias) {
            //     dump('WTF???? no $propertyAlias');
            // }

            $requestKeyProcessor1->setPropertyAlias($propertyAlias);
            $requestKeyProcessor1->process();

            $requestKey = null;
            $posted = false;

            $processedRequestKey = null;
            // dump($this->getContainer()->getRequest()->getAll());exit;
            foreach ($this->getContainer()->getRequest()->getAll() as $invalidatedRequestKey => $posted) {
                $requestKeyProcessor2 = new RequestKeyProcessor($this->form);
                $requestKeyProcessor2->line = 368;
                // if (!$invalidatedRequestKey) {
                //     dump('WTF???? no $invalidatedRequestKey');
                // }

                $requestKeyProcessor2->setPostedRequestKey($invalidatedRequestKey);
                $requestKeyProcessor2->process();

                if ($requestKeyProcessor1->requestKeyPattern == $requestKeyProcessor2->requestKeyPattern) {
                    // $requestKey = $requestKeyProcessor2->requestKey;
                    $processedRequestKey = $requestKeyProcessor2;
                }
            }
            // if (!$requestKey) {
            //     $requestKey = $requestKeyProcessor2->requestKeyPattern;
            // } 

            // $requestKeyProcessor2->requestKey 
            if ($this->form->isSubmitted()) {
                // dump($requestKey);
            }

            // dump($propertyParams);
            // dump($processedRequestKey);
            // if (!in_array($requestKey, $this->form->getExternalPosts()) && $this->form->isSubmitted() && !$propertyParams['technical']) {
            if ($this->form->isSubmitted() && !$propertyParams['technical'] && $processedRequestKey) {
                // dump($requestKey);
                $posted = $this->getContainer()->getRequest()->get($processedRequestKey->postedRequestKey);
                $posted = $this->autoTransformValue($posted);

                if (empty($processedRequestKey->errors)) {
                    $counter = count($requests['inSubject']);
                    $requests['inSubject'][$counter][$processedRequestKey->requestKey] = $posted;

                    // if (empty($processedRequestKey->requestKey)) {
                    //     dump('Empty valamiert');
                    //     dump($this->getContainer()->getRequest()->getAll());
                    //     dump($requestKey);
                    //     dump($requestKeyProcessor1);
                    //     dump($requestKeyProcessor2);
                    //     dump('====================');
                    // }

                    // if ($propertyAlias == 'subscribed') {
                    //     dump($propertyParams);
                    //     dump($processedRequestKey);
                    //     exit;
                    // }

                    $this->form->getValueCollector()->addValue(
                        $posted,
                        $propertyAlias,
                        'posted',
                        $processedRequestKey->requestKey
                    );

                } else {
                    $counter = count($requests['outSubject']);
                    $requests['outSubject'][$counter][$processedRequestKey->requestKey] = $posted;
                }
                // if (!$schemaContainsThisRequest) {
                //     $securityEventHandler->addEvent('NON_LEGIT_POST_KEY', $requestKey, $posted);
                // }
            } else {
                $counter = count($requests['unprocessed']);
                $requests['unprocessed'][$counter][$propertyAlias]['RequestKeyObject'] = $processedRequestKey;
                $requests['unprocessed'][$counter][$propertyAlias]['technical'] = $propertyParams['technical'];
                $requests['unprocessed'][$counter][$propertyAlias]['posted'] = $posted;
                // dump($invalidatedRequestKey);
                // if ($this->form->isSubmitted()) {
                //     dump($invalidatedRequestKey);
                //     dump($requestKeyProcessor2);
                // }
            }
        }

        // dump();
        // dump($requests);exit;
        // dump('posted');exit;
        // $securityEventHandler->secureSchemaProcessing();
        $this->form->setRequests($requests);
    }

    public function processAllDisplayed()
    {
        foreach ($this->form->getSpecsMap() as $specsMapElement) {
            $requestKeyProcessor = new RequestKeyProcessor($this->form);
            $requestKeyProcessor->debug = true;
            $requestKeyProcessor->line = 453;
            $requestKeyProcessor->setPropertyAlias($specsMapElement['propertyAlias']);
            $requestKeyProcessor->process();
            $displayed = null;
            $specsMapElementFound = false;

            // ALMA!!!
            foreach ($this->form->getValueCollection() as $propertyAlias => $valueCollectionElement) {

                if ($specsMapElement['propertyAlias'] == $propertyAlias) {
                    $specsMapElementFound = true;
                    // dump('found: '.$propertyAlias);

                    foreach ($valueCollectionElement as $requestKey => $valueArray) {
                        // dump('----------------------------');
                        // dump($requestKey);
                        $valueHolder = null;
                        if ($this->form->getValueCollector()->has($requestKey, 'stored')) {
                            $this->form->getValueCollector()->addValue($valueArray['stored'], $propertyAlias, 'displayed', $requestKey);
                            $valueHolder = 'stored';
                        }
                        if ($this->form->getValueCollector()->has($requestKey, 'posted')) {
                            $this->form->getValueCollector()->addValue($valueArray['posted'], $propertyAlias, 'displayed', $requestKey);
                            $valueHolder = 'posted';
                        }
                        // dump($valueHolder);
                        // dump($propertyAlias);
                        if (!isset($valueArray['displayed'])) {
                            $this->form->getValueCollector()->addValue(
                                ($valueHolder ? $valueArray[$valueHolder] : null),
                                $propertyAlias,
                                'displayed',
                                $requestKey
                            );
                        }
                    }
                }
            }

            if (!$specsMapElementFound && isset($this->defaultValues[$specsMapElement['propertyAlias']])) {
                $this->form->getValueCollector()->addValue(
                    $this->defaultValues[$specsMapElement['propertyAlias']],
                    $specsMapElement['propertyAlias'],
                    'displayed',
                    $requestKeyProcessor->requestKey
                );
            }
        }

        // dump($this->form->getValueCollector());exit;
        // if ($this->form->isSubmitted()) {
        //     dump($this->form->getValueCollector());
        // }
        // dump($this->form);
        // dump($this->form->getValueCollector());
        // dump('----vege');exit;
    }

    public function postsToEntityCollection()
    {
// dump('alma');
        // dump($this->form->getEntityCollector()->getCollection());
        $entityCollection = $this->form->getEntityCollector()->getCollection();
        $newEntityCollectionElements = array();
        $ancestoryRequestParamsCollection = array();
        $test = false;

        // $test3 = new RequestKeyProcessor($this->form);
        // $test3->setPropertyAlias('username');
        // $test3->process();
        // dump($test3);

        // $test1 = new RequestKeyProcessor($this->form);
        // $test1->setPropertyAlias('country');
        // $test1->process();
        // dump($test1);
        // //exit;

        // $test2 = new RequestKeyProcessor($this->form);
        // $test2->setPostedRequestKey('UserPackage_userRegistration_Address_0_country');
        // $test2->process();
        // dump($test2);
        // exit;

        // dump($this->form->getValueCollector());
        // dump('----vege');exit;

        // dump($this->form->getValueCollection());

        // if ($this->form->isSubmitted()) {
        //     // dump($this->form->getValueCollection());
        //     // dump($entityCollection);
        // }

        foreach ($this->form->getValueCollection() as $propertyAlias => $valueCollectionElement) {
            if (!$this->form->getSpecsMap()[$propertyAlias]['mapped'] || $this->form->getSpecsMap()[$propertyAlias]['technical']) {
                continue;
            }

            foreach ($valueCollectionElement as $requestKey => $valueArray) {

                /*
                We create a RequestKeyProcessor instance with the $requestKey.
                */
                $requestKeyProcessor = new RequestKeyProcessor($this->form);
                $requestKeyProcessor->line = 556;

                if ($this->form->isSubmitted()) {
                    // dump($this->form);
                    // dump($requestKeyProcessor);exit;
                }

                if (empty($requestKey)) {

                    dump('No requestKey');
                    dump($valueCollectionElement);
                }

                $requestKeyProcessor->setPostedRequestKey($requestKey);
                $requestKeyProcessor->process();

                /*
                $valueArray example:
                ====================
                (4)[mobile] => Array() (This is a $valueArray)
                    (0)[UserPackage_userRegistration_mobile] => Array()
                        (0)[entityCollectionId] => 2
                        (1)[stored] => null
                        (2)[posted] => 705150551
                        (3)[displayed] => 705150551
                (5)[retypedPassword] => Array() (This is another $valueArray)
                    (0)[UserPackage_userRegistration_retypedPassword] => Array()
                        (0)[posted] => aaaaaaa
                        (1)[displayed] => aaaaaaa
                As you can see: "mobile" propertyAlias has an entityCollectionId, that means, that posted value (of that foreach loop) 
                will be a property-value of a freshly created entity.

                if (!isset($valueArray['entityCollectionId'])) ...
                ==================================================
                In this IF we are checking, if the current loop of requestKey (and all, we know about it: posted, stored values, etc.) 
                will be some data to an existing entity of our entityCollection, or we just create and add a totally brand new entity to
                the collection, and fill up with data.
                */

                if (!isset($valueArray['entityCollectionId'])) {
                /*
                This IF-branch means: we don't have that entity in our collection, so we create a new one.
                */
                    /*
                    $ancestoryRequestParams
                    =======================
                    This array contains the entityName and the serial number of the child (counter) all 
                    Structure:
                    [ancestoryMapElement]
                        [entityName] => $ancestoryMapElement['entityName'],
                        [childCounter] => $ancestoryCounter
                    */
                    $ancestoryRequestParams = $requestKeyProcessor->ancestoryRequestParams;

                    /*
                    E.g.: UserPackage_edit_person_address_1 , everything of the requestKey, except the propertyAlias.
                    */
                    $fullPropertyPrefix = $requestKeyProcessor->fullPropertyPrefix;

                    /*
                    If we have no ancestoryRequestParams for that loop of valueCollection, that means, that this childCounter of that multiple child is not existing yet.
                    We put that new element to the "newEntityCollectionElements" pach, which we will process later. Now we technically mark this child for a later creation.
                    */
                    // dump($valueCollectionElement);
                    // dump($valueArray);
                    // dump($this->form->getValueCollection());
                    if ($ancestoryRequestParams) {
                        if (!in_array($fullPropertyPrefix, array_keys($ancestoryRequestParamsCollection))) {
                            $ancestoryRequestParamsCollection[$fullPropertyPrefix] = $ancestoryRequestParams;
                        }
                        $newEntityCollectionElements[$fullPropertyPrefix][$propertyAlias] = array(
                            'value' => $valueArray['displayed']
                        );
                    } else {
                        // dump('No ancestoryRequestParams!!!');exit;
                    }
                } else {

                    /*
                    In this else-branch we process posts for existing entities
                    ==========================================================
                    */

                    /*
                    Instead of looping also the entityCollection, we search the collection-element for every loop of the valueCollection.
                    */
                    $index = $this->form->getEntityCollector()->find($valueArray['entityCollectionId'], $entityCollection);
                    $entityCollectionElement = $entityCollection[$index];

                    /*
                    In this loop, it's the ID field, but the displayed value of ID is different to the one already existing in the collection.
                    In this case we'll remove the existing collectionElement, with it's all children, than we'll find the new one from it's repository.
                    */
                    if (($this->form->getSpecsMap()[$propertyAlias]['property'] == $entityCollectionElement['entity']->getIdFieldName()) 
                    && ($valueArray['displayed'] != $entityCollectionElement['entity']->getIdValue())) {
                        $test = true;

                        /*
                        Removing all childs of the changed entity
                        */
                        $counter = 0;
                        foreach ($entityCollection as $entityCollectionLoop) {
                            if ($entityCollectionLoop['parentId'] == $entityCollectionElement['collectionId']) {
                                $this->form->getEntityCollector()->remove($entityCollectionLoop['collectionId']);
                            }
                            $counter++;
                        }

                        /*
                        Finding the new entity from repo
                        */
                        $newEntity = $entityCollectionElement['entity']->getRepository()->find($valueArray['displayed']);
                        if (!$newEntity) {
                            /**
                            * If we don't find the posted ID in the database, than we'll remove that item from the collection.
                            * @todo: that's maybe a security event, so I need to work out the details of that case.
                            */
                            $this->form->getEntityCollector()->remove($entityCollectionElement['collectionId']);
                        } else {
                            $newEntityCollector = $this->getEntityManager()->disassembleEntity($newEntity);
                            $newCollectionPart = $newEntityCollector->getCollection();
    
                            /*
                            Setting changed entity's collection parent to the new collection-part's main element.
                            Because we will remove this element from our collection, and merge entirely the new collection-part
                            */
                            $newBaseCollectionId = null;
                            $newBaseParentId = null;
                            if (is_array($newCollectionPart) && count($newCollectionPart) > 0) {
                                $counter = 0;
                                foreach ($newCollectionPart as $index => $newElement) {
                                    if ($counter == 0) {
                                        $newBaseCollectionId = $entityCollectionElement['collectionId'];
                                        $newBaseParentId = $entityCollectionElement['parentId'];
                                        $newCollectionPart[$index]['entityKey'] = $entityCollectionElement['entityKey'];
                                        $newCollectionPart[$index]['parentId'] = $entityCollectionElement['parentId'];
                                        break;
                                    }
                                    $counter++;
                                }
                            }

                            /*
                            Removing changed main entity 
                            */
                            $this->form->getEntityCollector()->remove($entityCollectionElement['collectionId']);

                            /*
                            Merging the new collection-part to our collection. Merge will fit all collection ids and parent ids
                            */
                            if ($newBaseCollectionId && $newBaseParentId) {
                                $this->form->getEntityCollector()->merge($newCollectionPart, $newBaseCollectionId, $newBaseParentId);
                            }
                        }
                    } else {
                        $this->form->getEntityCollector()->modifyEntity(
                            $valueArray['entityCollectionId'], 
                            $this->form->getSpecsMap()[$propertyAlias]['property'],
                            $valueArray['displayed']
                        );
                    }
                    // dump('alma');
                    
                }
            } // end foreach

            // if ($this->form->isSubmitted()) {
            //     dump($this->form->getEntityCollector());exit;
            // }
        }

        /*
        $newEntityCollectionElements
        ============================
        Example:
        Array()
            (0)[UserPackage_userRegistration] => Array()
                (0)[retypedPassword] => Array()
                    (0)[value] => Apple1234
            (1)[UserPackage_userRegistration_Address_0] => Array()
                (0)[country] => Array()
                    (0)[value] => 348
                (1)[zipCode] => Array()
                    (0)[value] => 1138
        */
        ksort($newEntityCollectionElements);

        // dump($newEntityCollectionElements);
        foreach ($newEntityCollectionElements as $fullPropertyPrefix => $newEntityCollectionElement) {
            // dump();

            /**
             * @todo !!! 
            */

            $entityCollectionInfo = $this->gatherEntityCollectionInfo($ancestoryRequestParamsCollection[$fullPropertyPrefix]);
            // if ($entityCollectionInfo['collectionElement']['isDummyEntity'] === true) {
            //     continue;
            // }
            
            dump($newEntityCollectionElements);
            dump($entityCollectionInfo);
            dump($this->form);exit;

            // dump($fullPropertyPrefix);
            // dump($ancestoryRequestParamsCollection[$fullPropertyPrefix]);
            // dump($newEntityCollectionElement);
            // dump($entityChainInfo);

            // $entity = $this->createBlankEntity($entityCollectionInfo['data']['entityKey']);
            $entityParams = RequestKeyProcessor::findEntityParamsOnMap($entityCollectionInfo['data']['entityKey'], $this->form->getEntityMap());
            $repository = $entityParams['repository'];

            $entity = $repository->createNewEntity();
            foreach ($newEntityCollectionElement as $propertyAlias => $params) {
                $property = $this->form->getSpecsMap()[$propertyAlias]['property'];
                if ($property == $entity->getIdFieldName()) {
                    $entity = $repository->find($params['value']);
                    continue;
                } else {
                    $setter = 'set'.ucfirst($property);
                    if (!method_exists($entity, $setter)) {
                        // dump($this);
                        dump($setter);
                        dump($entity);
                        dump('newEntityCollectionElements:');
                        dump($newEntityCollectionElements);
                        dump('ancestoryRequestParamsCollection:');
                        dump($ancestoryRequestParamsCollection);
                    }
                    $entity->$setter($params['value']);
                }
            }

            // dump($entity);


            $this->form->getEntityCollector()->add(
                null,
                $entityCollectionInfo['data']['entityKey'],
                $entityCollectionInfo['data']['parentId'],
                $entityCollectionInfo['data']['parentEntityKey'],
                $entity,
                true // test
            );



            // if (!$entityChainInfo['result']) {
            //     // dump($entityChainInfo);
            //     $this->form->getEntityCollector()->add(
            //         null,
            //         $entityChainInfo['data']['entityKey'],
            //         $entityChainInfo['data']['parentId'],
            //         $entity
            //     );
            // } else {
            //     $this->form->getEntityCollector()->add(
            //         null,
            //         $entityChainInfo['data']['entityKey'],
            //         $entityChainInfo['data']['parentId'],
            //         $entity
            //     );
            // }
            // dump($entityChainInfo);
            // $entityName = $this->form->getSpecsMap()[$propertyAlias]['entityName'];
            // $blankEntity = $this->createBlankEntity($entityName);
        }

        if ($this->form->isSubmitted()) {
            // dump($newEntityCollectionElements);
            // dump($this->form);exit;
            // dump($this->form->getSpecsMap());
            // dump($this->form);exit;
        }
    }

    // public function getRequestKeyOriginMap($requestKey, $debug)
    // {
    //     /*
    //     @var $requestKeyAttributes
    //     ==========================
    //     [] => Array()
    //         (0)[requestKeyPrefix] => UserPackage_userRegistration_
    //         (1)[propertyIdentifier] => Address_0_zipCode
    //         (2)[originChain] => Address_0
    //         (3)[propertyAlias] => zipCode
    //         (4)[errors] => Array()
    //     */
    //     $requestKeyAttributes = RequestKeyService::getRequestKeyAttributes($requestKey, $this->form);

    //     if ($debug) {
    //         dump('==== getRequestKeyOriginMap() ====');
    //         dump('====getRequestKeyAttributes====');
    //         dump($requestKeyAttributes);
    //     }
    //     // dump($requestKeyAttributes);
    //     // $requestKeyParts = explode('_', $requestKey);
    //     // unset($requestKeyParts[count($requestKeyParts) - 1]);
    //     // unset($requestKeyParts[0]);
    //     // array_values($requestKeyParts);
    //     // if (count($requestKeyParts) == 0) {
    //     //     return null;
    //     // }
    //     // if (BasicUtils::getParity(count($requestKeyParts)) == 'odd') {
    //     //     return false;
    //     // }
    //     // $return['origin'] = array();
    //     $return = array();
    //     $originChainParts = explode('_', $requestKeyAttributes['originChain']);
    //     // dump($originChainParts);
    //     // dump($requestKeyAttributes);
    //     for ($i = 0; $i < count($originChainParts); $i++) {
    //         if (BasicUtils::getParity($i) == 'odd') {
    //             // $return['origin'][] = array(
    //             $return[] = array(
    //                 'entityName' => $originChainParts[$i],
    //                 'childCounter' => $originChainParts[$i]
    //                 // 'childCounter' => $originChainParts[$i + 1]
    //             );
    //         }
    //     }

    //     return $return;
    // }

    public function gatherEntityCollectionInfo($requestKeyOriginMap, $debug = false)
    {
        // dump($requestKeyOrigin);
        if ($debug) {
            dump($requestKeyOriginMap);
        }
        $primaryEntityName = BasicUtils::explodeAndGetElement($this->form->getPrimaryEntityKey(), '-', 'last');
        $lastFoundCollectionId = 1;
        $lastFoundEntityKey = '0-'.$primaryEntityName;
        for ($i = 0; $i < count($requestKeyOriginMap); $i++) {
            $entityKey = $i == 0 ? $primaryEntityName.'-'.$requestKeyOriginMap[$i]['entityName']
            // $entityKey = $i == 0 ? $requestKeyOriginMap[$i]['entityName'].'-'.$primaryEntityName
                : $requestKeyOriginMap[$i - 1]['entityName'].'-'.$requestKeyOriginMap[$i]['entityName'];
            foreach ($this->form->getEntityCollector()->getCollection() as $collectionElement) {
                if ($collectionElement['entityKey'] == $entityKey) {
                    if ($collectionElement['childCounter'] == $requestKeyOriginMap[$i]['childCounter']) {
                        if ($i == count($requestKeyOriginMap) - 1) {
                            $lastFoundCollectionId = $collectionElement['collectionId'];
                            $lastFoundEntityKey = $collectionElement['entityKey'];
                            return array(
                                'result' => true,
                                'data' => array(
                                    'entityKey' => $entityKey,
                                    'childCounter' => $requestKeyOriginMap[$i]['childCounter'],
                                    'parentId' => $collectionElement['parentId'],
                                    'parentEntityKey' => $collectionElement['parentEntityKey'],
                                ),
                                'collectionElement' => $collectionElement
                            );
                        }
                    }
                }
            }
            if ($i == count($requestKeyOriginMap) - 1) {
                return array(
                    'result' => false,
                    'data' => array(
                        'entityKey' => $entityKey,
                        'childCounter' => $requestKeyOriginMap[$i]['childCounter'],
                        'parentId' => $lastFoundCollectionId,
                        'parentEntityKey' => $lastFoundEntityKey
                    ),
                    'collectionElement' => null
                );
            } else {

            }
        }
    }

    public function createBlankEntity($entityKey)
    {
        // dump($entityKey);
        // dump($this->form->getEntityCollector()->getCollection());
        foreach ($this->form->getEntityCollector()->getCollection() as $collectionElement) {
            if ($collectionElement['entityKey'] == $entityKey) {
                $repo = $collectionElement['entity']->getRepository();
                // dump($repo);
                return $repo->createNewEntity();
            }
        }
        return null;
    }

    public function isMultiple($entityKey)
    {
        foreach ($this->form->getEntityCollector()->getCollection() as $collectionElement) {
            if ($collectionElement['entityKey'] == $entityKey) {
                return $collectionElement['multiple'];
            }
        }
        return null;
    }

    public function processAllValidations()
    {
        // dump($this->form->getSpecsMap());exit;
        foreach ($this->form->getSpecsMap() as $specsMapElement) {
            $requestKeyProcessor = new RequestKeyProcessor($this->form);
            $requestKeyProcessor->line = 941;
            $requestKeyProcessor->setPropertyAlias($specsMapElement['propertyAlias']);
            $requestKeyProcessor->process();
            $displayed = null;

            foreach ($this->form->getValueCollection() as $propertyAlias => $valueCollectionElement) {
                if ($propertyAlias == $specsMapElement['propertyAlias']) {
                    foreach ($valueCollectionElement as $requestKey => $valueArray) {
                        $displayed = isset($valueArray['displayed']) ? $valueArray['displayed'] : null;
                    }
                }

            }

            if (isset($specsMapElement['validatorRules']) && $this->form->isSubmitted()) {

                $validation = $this->validateAttribute(
                    $specsMapElement['propertyAlias'],
                    $displayed,
                    $specsMapElement['validatorRules']
                );

                if (!$validation['result']) {
                    $this->form->setValid(false);
                }
            } else {
                $validation = array(
                    'result' => true,
                    'message' => null
                );
            }

            $this->form->getValueCollector()->addValue($validation['result'], $specsMapElement['propertyAlias'], 'valid', $requestKeyProcessor->requestKey);
            $this->form->getValueCollector()->addValue($validation['message'], $specsMapElement['propertyAlias'], 'message', $requestKeyProcessor->requestKey);
        }
    }

    public function validateAttribute($propertyAlias, $displayed, $validatorRules)
    {
        // dump($propertyAlias);
        $result = true;
        $message = null;
        foreach ($validatorRules as $ruleName => $ruleValue) {
            if (!$result) {
                return array(
                    'result' => $result,
                    'message' => $message
                );
            }
            if ($ruleValue !== false) {
                $itemValidationResult = $this->validateBuiltInRule($ruleName, $displayed, $ruleValue, $this->form, $propertyAlias);
                if ($itemValidationResult === false) {
                    $customValidator = $this->findCustomValidator($ruleName);
                    if ($customValidator) {
                        $customClass = '\\'.str_replace('/', '\\', $customValidator['class']);
                        $customMethod = $customValidator['method'];
                        $linkParams = $this->getContainer()->getServiceLinkParams($customValidator['class']);
                        // dump($linkParams);exit;
                        FileHandler::includeFileOnce(
                            $customValidator['class'].'.php', 
                            $linkParams['pathBaseName']
                        );
                        $customObject = new $customClass();
                        if (!method_exists($customObject, $customMethod)) {
                            dump($customObject);
                            dump($customMethod);
                            exit;
                        }
                        $itemValidationResult = $customObject->$customMethod($displayed, $ruleValue, $this->form);
                        $result = $itemValidationResult['result'];
                        $message = $itemValidationResult['message'];
                    }
                    else {
                        throw new ElastiException('Missing custom validator for rule '.$ruleName, ElastiException::ERROR_TYPE_SECRET_PROG);
                    }
                }
                else {
                    $result = $itemValidationResult['result'];
                    $message = $itemValidationResult['message'];
                }
            }
        }

        return array(
            'result' => $result,
            'message' => $message
        );
    }

    public function findCustomValidator($method)
    {
        foreach ($this->customValidators as $customValidator) {
            if (!isset($customValidator['class']) || !isset($customValidator['method'])) {
                throw new ElastiException('Existing custom validator, but not filled properly', ElastiException::ERROR_TYPE_SECRET_PROG);
            }
            if ($customValidator['method'] == $method) {
                return $customValidator;
            }
        }
        return null;
    }

    public function validateBuiltInRule($ruleName, $posted, $ruleValue, $form, $propertyAlias)
    {
        if ($ruleName == 'requiredCheckbox' && $ruleValue == true) {
            if (is_numeric($posted) || ($posted && $posted !== '')) {
                return [
                    'result' => true,
                    'message' => null
                ];
            }
            else {
                return [
                    'result' => false,
                    'message' => trans('required.to.check')
                ];
            }

            // if (!$posted || $posted == '') {
            //     return [
            //         'result' => false,
            //         'message' => trans('required.to.check')
            //     ];
            // }
            // else {
            //     return [
            //         'result' => true,
            //         'message' => null
            //     ];
            // }
        }
        if ($ruleName == 'required' && $ruleValue == true) {
            // dump($form->getValueCollector()->getValue('triggerCorporate', 'displayed'));exit;
            // if ($propertyAlias == 'subscribed') {
            //     dump($form);exit;
            // }
            if (($posted && ctype_digit($posted)) || ($posted && $posted !== '') || $posted === false) {
                return [
                    'result' => true,
                    'message' => null
                ];
            }
            else {
                return [
                    'result' => false,
                    'message' => trans('required.field')
                ];
            }

            // if (!$posted || $posted == '') {
            //     return [
            //         'result' => false,
            //         'message' => trans('required.field')
            //     ];
            // }
            // else {
            //     return [
            //         'result' => true,
            //         'message' => null
            //     ];
            // }
        }
        if ($ruleName == 'integer' && $ruleValue == true) {
            if (!$posted || !ctype_digit($posted)) {
                return [
                    'result' => false,
                    'message' => trans('integer.required')
                ];
            }
            else {
                return [
                    'result' => true,
                    'message' => null
                ];
            }
        }
        if ($ruleName == 'dateTime') {
            if (!\DateTime::createFromFormat('Y-m-d H:i', $posted)) {
                return [
                    'result' => false,
                    'message' => trans('datetime.required')
                ];
            }
            else {
                return [
                    'result' => true,
                    'message' => null
                ];
            }
        }
        return false;
    }

    public function autoTransformValue($value)
    {
        // if ($value == null) {
        //     $value = 'null';
        // }
        // if ($value === 0) {
        //     $value = '0';
        // }
        // dump($value);
        if ($value == '*false*') {
            $value = null;
        }
        if ($value == '*null*') {
            $value = null;
        }
        if ($value == '*true*') {
            $value = true;
        }
        // dump($value);
        return $value;
    }

    // public function getEntityCollectionElementProperty($entityKey, $property)
    // {
    //     $getter = 'get'.ucfirst($property);
    //     $entityCollectionElementProperty = array();
    //     foreach ($this->form->getEntityCollection() as $entityCollectionElement) {
    //         if ($entityCollectionElement['entityKey'] == $entityKey) {
    //             $entityCollectionElementProperty[] = $entityCollectionElement['entity']->$getter();
    //         }
    //     }
    //     return count($entityCollectionElementProperty) == 1 ? $entityCollectionElementProperty[0] : $entityCollectionElementProperty;
    // }

    // public function findSpecsMapElement($propertyAlias)
    // {
    //     foreach ($this->form->getSpecsMap() as $specsElement) {
    //         if ($specsElement['propertyAlias'] == $propertyAlias) {
    //             return $specsElement;
    //         }
    //     }
    //     return false;
    // }

    // public function setEntityCollectionElementProperty($propertyAlias, $value)
    // {
    //     $specsElement = $this->findSpecsMapElement($propertyAlias);
    //     $setter = 'set'.ucfirst($specsElement['property']);
    //     $entityCollectionElementProperty = array();
    //     foreach ($this->form->getEntityCollection() as $entityCollectionElement) {
    //         if ($entityCollectionElement['entityKey'] == $specsElement['entityKey']) {
    //             $entityCollectionElementProperty[] = $entityCollectionElement['entity']->$setter($value);
    //         }
    //     }
    // }

    // public function createValueCollectionKeys($propertyAlias, $propertyParams)
    // {
    //     // dump($this->form->getValueCollection());exit;
    //     // $this->form->getValueCollection();
    //     dump($propertyAlias);
    //     if ($propertyParams['multiple']) {

    //     }
    // }

    // public function processStored($propertyAlias, $property, $mapped, $entityKey, $technical)
    // {
    //     if (!$technical) {
    //         $existingProperty = $mapped == false ? null : $this->getEntityCollectionElementProperty($entityKey, $property);
    //         $valueCollectionElement[$propertyAlias]['stored'] = $existingProperty ? $existingProperty : null;
    //         $this->form->addToValueCollection($valueCollectionElement);
    //     }
    // }

    // public function processDisplayed($propertyAlias, $default, $technical)
    // {
    //     $displayed = !$this->form->getPosted($propertyAlias) ? $this->form->getStored($propertyAlias)
    //         : $this->form->getPosted($propertyAlias);
    //     $valueCollectionElement[$propertyAlias]['displayed'] = $displayed;

    //     if ((!$displayed || $technical) && $default) {
    //         $displayed = $default;
    //     }

    //     if (!$technical) {
    //         $this->form->addToValueCollection($valueCollectionElement);
    //         $this->setEntityCollectionElementProperty($propertyAlias, $displayed);
    //     }
    // }

    // public function getPrimaryEntity()
    // {
    //     if ($this->form->getEntity()) {
    //         return $this->form->getEntity();
    //     }
    //     foreach ($this->entitySpecs as $key => $value) {
    //         $primaryEntityPos = strpos($key, 'primaryEntity:');
    //         if ($primaryEntityPos !== false) {
    //             $primaryEntityPath = str_replace('primaryEntity:', '', $key);
    //             $this->getContainer()->setService($primaryEntityPath, $this->form->getSubject().'Form-primaryEntity');
    //             return $this->getContainer()->getService($this->form->getSubject().'Form-primaryEntity');
    //         }
    //     }
    //     return false;
    // }

    // public function createMessages()
    // {
    //     $messages = array();
    //     foreach ($this->form->getValueCollection() as $propertyAlias => $valueCollectionElement) {
    //         foreach ($valueCollectionElement as $spec => $value) {
    //             if ($spec == 'message') {
    //                 $messages[$propertyAlias] = $value;
    //             }
    //         }
    //     }
    //     return $messages;
    // }

    // public function processValidations($propertyAlias, $validatorRules)
    // {
    //     if (isset($validatorRules) && $this->form->isSubmitted()) {
    //         $validation = $this->validateAttribute(
    //             $propertyAlias,
    //             $this->form->getPosted($propertyAlias),
    //             $validatorRules
    //         );
    //         if (!$validation['result']) {
    //             $this->form->setValid(false);
    //         }

    //         $valueCollectionElement[$propertyAlias]['valid'] = $validation['result'];
    //         $valueCollectionElement[$propertyAlias]['message'] = $validation['message'];
    //     } else {
    //         $valueCollectionElement[$propertyAlias]['valid'] = true;
    //         $valueCollectionElement[$propertyAlias]['message'] = null;
    //     }
    //     $this->form->addToValueCollection($valueCollectionElement);
    // }
}
