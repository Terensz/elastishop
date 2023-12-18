<?php
namespace framework\component\parent;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;

abstract class FileBasedStorageEntity extends Kernel
{
    const ENTITY_ATTRIBUTES = null;

    public function set($property, $value)
    {
        $setter = 'set'.ucfirst($property);
        $this->$setter($value);
    }

    public function get($property)
    {
        $getter = 'get'.ucfirst($property);
        return $this->$getter();
    }

    public function getIdFieldName()
    {
        return $this->getRepository()->getEmulateAutoIncrement();
    }

    public function getIdValue()
    {
        $idFieldName = $this->getRepository()->getEmulateAutoIncrement();
        $getter = 'get'.ucfirst($idFieldName);
        return $this->$getter();
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