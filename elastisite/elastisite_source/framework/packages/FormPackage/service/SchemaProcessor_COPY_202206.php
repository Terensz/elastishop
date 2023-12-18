<?php
namespace framework\packages\FormPackage\service;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\Service;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;
use framework\packages\FormPackage\service\RequestKeys;
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
                        true // forceCollection
                    );

// dump($entityCollector); exit;
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
        $originChains = array();

        if (!$propertyParams['technical']) {
            $getter = 'get'.ucfirst($propertyParams['property']);
            // dump($getter);
            // dump($this->form);
            // dump($propertyParams);
            // dump($this->form->getEntityCollector()->getCollection());exit;
            
            foreach ($this->form->getEntityCollector()->getCollection() as $entityCollectionElement) {
                if ($entityCollectionElement['entityKey'] == $propertyParams['entityKey'] && $entityCollectionElement['parentEntityKey'] == $propertyParams['parentEntityKey'] && $propertyParams['mapped']) {
                    // if ($entityCollectionElement['entityKey'] == 'Address-Country') {
                    //     dump($entityCollectionElement);
                    // }
                    // dump('-'.$entityCollectionElement['entityKey']);
                    $object = $entityCollectionElement['entity'];
                    // dump($object);
                    // if ($propertyParams['propertyAlias'] == 'productCategoryId') {
                    //     dump($object);
                    // }
// dump($entityCollectionElement);
                    if (!isset($originChains[$entityCollectionElement['entityName']])) {
                        $originChain = $this->getEntityManager()->createOriginChain(
                            $entityCollectionElement, 
                            $this->form->getEntityCollector()
                        );
                        $originChains[$entityCollectionElement['entityName']] = $originChain;
                    } else {
                        $originChain = $originChains[$entityCollectionElement['entityName']];
                    }

                    // dump($originChain);
                    // dump($originChain);
                    $requestKey = RequestKeys::concatRequestKey(
                        $this->form->getPackageName(), 
                        $this->form->getSubject(),
                        $originChain,
                        $propertyParams['propertyAlias']
                    );
                    // dump($requestKey);
                    if (!$this->form->getValueCollector()->hasValue($propertyParams['propertyAlias'], 'stored', $requestKey)) {
                        // addValue($value, $propertyAlias, $attribute, $requestKey)
                        $this->form->getValueCollector()->addValue(
                            $entityCollectionElement['collectionId'],
                            $propertyParams['propertyAlias'],
                            'entityCollectionId',
                            $requestKey
                        );
                        // dump($object->$getter());
                        $this->form->getValueCollector()->addValue(
                            $object->$getter(),
                            $propertyParams['propertyAlias'],
                            'stored',
                            $requestKey
                        );
                        break;
                    }
                    // $this->form->getValueCollector()->addValue(
                    //     null,
                    //     $propertyParams['propertyAlias'],
                    //     'posted',
                    //     $requestKey
                    // );
                }
            }
        }
// dump($this->form->getValueCollector());
// dump('----vege');exit;
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
        if (!$this->getContainer()->getRequest()->getAll()) {
            return false;
        }
        $securityEventHandler = $this->getContainer()->getKernelObject('SecurityEventHandler');


        // if ($this->form->isSubmitted()) {
        //     dump($this->getContainer()->getRequest()->getAll());
        // }
        // dump(RequestKeys::getRequestKeyProperties('UserPackage_userRegistration_Address_0_floor'));
        // dump(RequestKeys::getPattern('countryId', $this->form));exit;
        // dump($this->form->getSpecsMap());//exit;
        foreach ($this->form->getSpecsMap() as $propertyAlias => $propertyParams) {
            $requestKey = null;
            $posted = false;
            $requestKeyPattern = RequestKeys::getPattern($propertyAlias, $this->form);
            // dump($requestKeyPattern);exit;

            foreach ($this->getContainer()->getRequest()->getAll() as $invalidatedRequestKey => $posted) {
                $requestKeyProperties = RequestKeys::getRequestKeyProperties($invalidatedRequestKey);
                if ($requestKeyProperties['pattern'] == $requestKeyPattern) {
                    $requestKey = $invalidatedRequestKey;
                }
            }
            if (!$requestKey) {
                $requestKey = $requestKeyPattern;
            }

            // dump($propertyParams);//exit;
            if (!in_array($requestKey, $this->form->getExternalPosts()) && $this->form->isSubmitted() && !$propertyParams['technical']) {
                $posted = $this->getContainer()->getRequest()->get($requestKey);
                // $schemaContainsThisRequest = false;
                $requestKeyAttributes = RequestKeys::getRequestKeyAttributes(
                    $requestKey,
                    $this->form
                ); 
                // dump($requestKeyAttributes);
                if ($requestKeyAttributes['errors'] == array()) {
                    $counter = count($requests['inSubject']);
                    $requests['inSubject'][$counter][$requestKey] = $posted;
                    // foreach ($this->form->getSpecsMap() as $propertyParams) {
                    //     if ($propertyParams['propertyAlias'] == $requestKeyAttributes['propertyAlias']) {
                    //         $schemaContainsThisRequest = true;
                    //         $this->form->getValueCollector()->addValue(
                    //             $posted,
                    //             $propertyParams['propertyAlias'],
                    //             'posted',
                    //             $requestKey
                    //         );
                    //         break;
                    //     }
                    // }
                    $posted = $this->autoTransformValue($posted);
                    $this->form->getValueCollector()->addValue(
                        $posted,
                        $propertyAlias,
                        'posted',
                        $requestKey
                    );
                    // break;
                } else {
                    $counter = count($requests['outSubject']);
                    $requests['outSubject'][$counter][$requestKey] = $posted;
                }
                // if (!$schemaContainsThisRequest) {
                //     $securityEventHandler->addEvent('NON_LEGIT_POST_KEY', $requestKey, $posted);
                // }
            }
        }
        // $securityEventHandler->secureSchemaProcessing();
        $this->form->setRequests($requests);
    }

    public function processAllDisplayed()
    {
        // dump($this->form->getValueCollector());
        foreach ($this->form->getValueCollection() as $propertyAlias => $valueCollectionElement) {
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

        // if ($this->form->isSubmitted()) {
        //     dump($this->form->getValueCollector());
        // }
        // dump($this->form);
        // dump($this->form->getValueCollector());
        // dump('----vege');exit;
    }

    public function postsToEntityCollection()
    {

        // dump($this->form->getEntityCollector()->getCollection());
        $entityCollection = $this->form->getEntityCollector()->getCollection();
        $newEntityCollectionElements = array();
        $requestKeyOrigins = array();
        $test = false;



        // dump($this->form->getValueCollector());
        // dump('----vege');exit;

// if ($this->form->isSubmitted()) {
//     dump($this->form->getValueCollection());exit;
// }
        // dump($this->form->getValueCollection());
        foreach ($this->form->getValueCollection() as $propertyAlias => $valueCollectionElement) {
            foreach ($valueCollectionElement as $requestKey => $valueArray) {
                # Ket eset van.
                # 1.: teljesen uj entity, ami meg nincs a collection-ben.
                # Ekkor hozza kell rakni a collection-hoz.
                # 2.: Meglevo entity-hez erkezett post feldolgozasa.

                if (!isset($valueArray['entityCollectionId'])) {
                    // dump($this->form->getValueCollection());
                    # new entity
                    $fullPropertyPrefix = BasicUtils::explodeAndRemoveElement($requestKey, '_', 'last');
                    $requestKeyOriginMap = $this->getRequestKeyOriginMap($requestKey);
                    // dump($requestKeyOriginMap);//exit;
                    if ($requestKeyOriginMap) {
                        // dump($requestKeyOriginMap);
                        if (!in_array($fullPropertyPrefix, array_keys($requestKeyOrigins))) {
                            $requestKeyOrigins[$fullPropertyPrefix] = $requestKeyOriginMap;
                        }
                        $newEntityCollectionElements[$fullPropertyPrefix][$propertyAlias] = array(
                            'value' => $valueArray['displayed']
                            // 'fullPropertyPrefix' => $fullPropertyPrefix
                        );
                    }
                } else {
                    # Process posts for existing entities
                    // dump($valueCollectionElement);
                    // dump($this->form->getSpecsMap()[$propertyAlias]['property']);
                    // dump($valueArray['displayed']);
                    // dump();
                    $index = $this->form->getEntityCollector()->find($valueArray['entityCollectionId'], $entityCollection);
                    $entityCollectionElement = $entityCollection[$index];
                    // $id = $entityCollectionElement['entity']->getIdValue();
                    if (($this->form->getSpecsMap()[$propertyAlias]['property'] == $entityCollectionElement['entity']->getIdFieldName()) 
                    && ($valueArray['displayed'] != $entityCollectionElement['entity']->getIdValue())) {
                        $test = true;
                        // dump($this->form->getEntityCollector()->getCollection());

                        // var_dump($requestKey);
                        // echo '<br>';
                        // var_dump($valueArray);
                        // dump($valueArray['displayed'].' - '.$entityCollectionElement['entity']->getIdValue());
                        // dump($entityCollectionElement['entity']);

                        # Removing all childs of the changed entity
                        $counter = 0;
                        foreach ($entityCollection as $entityCollectionLoop) {
                            if ($entityCollectionLoop['parentId'] == $entityCollectionElement['collectionId']) {
                                // dump('-----------remove 1');
                                $this->form->getEntityCollector()->remove($entityCollectionLoop['collectionId']);
                            }
                            $counter++;
                        }

                        # Finding the new entity from repo
                        $newEntity = $entityCollectionElement['entity']->getRepository()->find($valueArray['displayed']);
                        if (!$newEntity) {
                            // dump('-----------remove 2');
                            $this->form->getEntityCollector()->remove($entityCollectionElement['collectionId']);
                        } else {
                            $newEntityCollector = $this->getEntityManager()->disassembleEntity($newEntity);
                            $newCollectionPart = $newEntityCollector->getCollection();

                            # Setting changed entity's collection parent to the new collection-part's main element.
                            # Because we will remove this element from our collection, and merge entirely the new collection-part
    
                            $newBaseCollectionId = null;
                            $newBaseParentId = null;
                            if (is_array($newCollectionPart) && count($newCollectionPart) > 0) {
                                $counter = 0;
                                foreach ($newCollectionPart as $index => $newElement) {
                                    if ($counter == 0) {
                                        $newBaseCollectionId = $entityCollectionElement['collectionId'];
                                        $newBaseParentId = $entityCollectionElement['parentId'];
                                        // $newElementEntityKeyParts = explode('-', $newCollectionPart[$index]['entityKey']);
                                        $newCollectionPart[$index]['entityKey'] = $entityCollectionElement['entityKey'];
                                        $newCollectionPart[$index]['parentId'] = $entityCollectionElement['parentId'];
                                        break;
                                    }
                                    $counter++;
                                }
                            }
// dump($entityCollectionElement);
// dump($newCollectionPart);
                            # Removing changed main entity 
                            $this->form->getEntityCollector()->remove($entityCollectionElement['collectionId']);
// dump($this->form->getEntityCollector()->getCollection());
                            # Merging the new collection-part to our collection. Merge will fit all collection ids and parent ids
                            if ($newBaseCollectionId && $newBaseParentId) {
                                $this->form->getEntityCollector()->merge($newCollectionPart, $newBaseCollectionId, $newBaseParentId);
                            }
// dump($this->form->getEntityCollector()->getCollection()); 
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
            }
        }

        // dump($newEntityCollectionElements);exit;

        ksort($newEntityCollectionElements);
        // dump($entityCollection);
        // dump('hello');
        // if ($test) {
        //     dump($entityCollection);
        //     dump('hello');
        // }

        # 1. eset: teljesen uj entity
        // dump($this->form->getSpecsMap());
        // dump($this->form->getEntityCollector()->getCollection());
        // dump($newEntityCollectionElements);
        // if ($this->form->isSubmitted()) {
        //     dump($newEntityCollectionElements);
        // }
        foreach ($newEntityCollectionElements as $fullPropertyPrefix => $newEntityCollectionElement) {
            $entityCollectionInfo = $this->gatherEntityCollectionInfo($requestKeyOrigins[$fullPropertyPrefix]);
            // dump($fullPropertyPrefix);
            // dump($requestKeyOrigins[$fullPropertyPrefix]);
            // dump($newEntityCollectionElement);
            // dump($entityChainInfo);
            $entity = $this->createBlankEntity($entityCollectionInfo['data']['entityKey']);
            // dump($entityCollectionInfo);
            // dump($entity);
            // dump($newEntityCollectionElement);
            foreach ($newEntityCollectionElement as $propertyAlias => $params) {
                $property = $this->form->getSpecsMap()[$propertyAlias]['property'];
                $setter = 'set'.ucfirst($property);
                // dump($entityCollectionInfo);
                // dump($setter);
                $entity->$setter($params['value']);
            }
            $this->form->getEntityCollector()->add(
                null,
                $entityCollectionInfo['data']['entityKey'],
                $entityCollectionInfo['data']['parentId'],
                $entity
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

        // if ($this->form->isSubmitted()) {
        //     dump($this->form);exit;
        // }
    }

    public function getRequestKeyOriginMap($requestKey)
    {
        $requestKeyAttributes = RequestKeys::getRequestKeyAttributes($requestKey, $this->form);
        // dump($requestKeyAttributes);
        // $requestKeyParts = explode('_', $requestKey);
        // unset($requestKeyParts[count($requestKeyParts) - 1]);
        // unset($requestKeyParts[0]);
        // array_values($requestKeyParts);
        // if (count($requestKeyParts) == 0) {
        //     return null;
        // }
        // if (BasicUtils::getParity(count($requestKeyParts)) == 'odd') {
        //     return false;
        // }
        // $return['origin'] = array();
        $return = array();
        $originChainParts = explode('_', $requestKeyAttributes['originChain']);
        // dump($originChainParts);
        // dump($requestKeyAttributes);
        for ($i = 0; $i < count($originChainParts); $i++) {
            if (BasicUtils::getParity($i) == 'odd') {
                // $return['origin'][] = array(
                $return[] = array(
                    'entityName' => $originChainParts[$i],
                    'counter' => $originChainParts[$i]
                    // 'counter' => $originChainParts[$i + 1]
                );
            }
        }
        return $return;
    }

    public function gatherEntityCollectionInfo($requestKeyOriginMap)
    {
        // dump($requestKeyOrigin);
        $primaryEntityName = BasicUtils::explodeAndGetElement($this->form->getPrimaryEntityKey(), '-', 'last');
        $lastFoundCollectionId = 1;
        for ($i = 0; $i < count($requestKeyOriginMap); $i++) {
            $entityKey = $i == 0 ? $primaryEntityName.'-'.$requestKeyOriginMap[$i]['entityName']
                : $requestKeyOriginMap[$i - 1]['entityName'].'-'.$requestKeyOriginMap[$i]['entityName'];
            foreach ($this->form->getEntityCollector()->getCollection() as $collectionElement) {
                if ($collectionElement['entityKey'] == $entityKey) {
                    if ($collectionElement['childCounter'] == $requestKeyOriginMap[$i]['counter']) {
                        if ($i == count($requestKeyOriginMap) - 1) {
                            $lastFoundCollectionId = $collectionElement['collectionId'];
                            return array(
                                'result' => true,
                                'data' => array(
                                    'entityKey' => $entityKey,
                                    'childCounter' => $requestKeyOriginMap[$i]['counter'],
                                    'parentId' => $collectionElement['parentId'],
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
                        'childCounter' => $requestKeyOriginMap[$i]['counter'],
                        'parentId' => $lastFoundCollectionId,
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
        foreach ($this->form->getValueCollection() as $propertyAlias => $valueCollectionElement) {
            foreach ($valueCollectionElement as $requestKey => $valueArray) {
                $validatorRules = $this->form->getSpecsMap()[$propertyAlias]['validatorRules'];
                if (isset($validatorRules) && $this->form->isSubmitted()) {
                    // if (!isset($valueArray['posted'])) {
                    //     dump($valueArray);
                    // }
                    $validation = $this->validateAttribute(
                        $propertyAlias,
                        // (isset($valueArray['posted']) ? $valueArray['posted'] : null),
                        (isset($valueArray['displayed']) ? $valueArray['displayed'] : null),
                        $validatorRules
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
                $this->form->getValueCollector()->addValue($validation['result'], $propertyAlias, 'valid', $requestKey);
                $this->form->getValueCollector()->addValue($validation['message'], $propertyAlias, 'message', $requestKey);
            }
        }
    }

    public function validateAttribute($propertyAlias, $displayed, $validatorRules)
    {
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
                $itemValidationResult = $this->validateBuiltInRule($ruleName, $displayed, $ruleValue, $this->form);
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

    public function validateBuiltInRule($ruleName, $posted, $ruleValue, $form)
    {
        if ($ruleName == 'requiredCheckbox' && $ruleValue == true) {
            if (!$posted || $posted == '') {
                return [
                    'result' => false,
                    'message' => trans('required.to.check')
                ];
            }
            else {
                return [
                    'result' => true,
                    'message' => null
                ];
            }
        }
        if ($ruleName == 'required' && $ruleValue == true) {
            if (!$posted || $posted == '') {
                return [
                    'result' => false,
                    'message' => trans('required.field')
                ];
            }
            else {
                return [
                    'result' => true,
                    'message' => null
                ];
            }
        }
        if ($ruleName == 'integer' && $ruleValue == true) {
            if (!ctype_digit($posted)) {
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
