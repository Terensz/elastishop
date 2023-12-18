<?php
namespace framework\component\parent;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Reflector;

abstract class TechnicalEntity extends Kernel
{
    const ENTITY_ATTRIBUTES = null;

    // public function getEntityAttributes()
    // {
    //     return static::ENTITY_ATTRIBUTES;
    // }

    public function getEntityAttributes()
    {
        return array(
            'active' => $this->isActive(),
            'class' => get_class($this),
            'className' => BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last'),
            'propertyMap' => $this->getPropertyMap()
        );
    }

    public function getPropertyMap()
    {
        $className = BasicUtils::explodeAndGetElement(get_class($this), '\\', 'last');
        $reflector = new Reflector();
        $propertyNames = $this->getEntityManager()->getPredefinedPropertyNames($this);
        $propertyMap = array();
        // dump($propertyNames);
        foreach ($propertyNames as $propertyName) {
            $defaultValue = $reflector->getDefaultValue($this, $propertyName);
            $propertyMap[$propertyName] = array(
                'propertyName' => $propertyName,
                'className' => null,
                'singularPropertyName' => $propertyName,
                'multiple' => (is_array($defaultValue) ? true : false),
                'getter' => 'get'.ucfirst($propertyName),
                'setterPre' => 'set',
                'setter' => 'set'.ucfirst($propertyName),
                'isObject' => false
            );
        }
        return $propertyMap;
    }

    public function isActive() : bool
    {
        return true;
    }

    public function getRepository()
    {
        $repoPath = $this->getRepositoryPath();
        $repoName = BasicUtils::explodeAndGetElement($repoPath, '/', 'last');
        if ($repoName) {
            $this->getContainer()->setService($repoPath, null, BasicUtils::explodeAndGetElement(static::class, '\\', 'last'));
            return $this->getContainer()->getService($repoName);
        }
        else {
            return null;
        }
    }

    public function guessRepositoryPath()
    {
        $repoClass = str_replace('\\entity\\', '\\repository\\', static::class).'Repository';
        $repoPath = str_replace('\\', '/', $repoClass);
        return $repoPath;
    }

    public function getRepositoryPath()
    {
        return isset(static::ENTITY_ATTRIBUTES['repositoryPath']) 
            ? static::ENTITY_ATTRIBUTES['repositoryPath'] : $this->guessRepositoryPath();
    }
}
