<?php
namespace framework\kernel\ClassManager;

use App;
use framework\component\parent\Service;
use framework\kernel\base\Cache;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class FileMapper extends Service
{
    private $mappedParentDirNames = array('entity', 'routeMap', 'loader', 'widget', 'menu', 'autoScript');

    private $fileMap = [];

    public function __construct()
    {
        if (!Cache::cacheRefreshRequired()) {
            $fileMapCache = App::$cache->read('fileMap');
            if (!empty($fileMapCache)) {
                App::getContainer()->setFileMap($fileMapCache);
                return true;
            }
        }
        
        $this->getContainer()->addFileMapPart($this->scanDirectory('framework/kernel/exception', 'source', 'source'));
        $this->getContainer()->addFileMapPart($this->scanDirectory('framework/kernel/security', 'source', 'source'));
        $this->getContainer()->addFileMapPart($this->scanDirectory('framework/kernel/request', 'source', 'source'));
        $this->getContainer()->addFileMapPart($this->scanDirectory('framework/packages', 'packages', 'source'));
        $this->getContainer()->addFileMapPart($this->scanDirectory('projects/'.App::getWebProject(), 'projects', 'projects'));

        App::$cache->write('fileMap', $this->getContainer()->getFileMap());
        // App::getContainer()->setFileMap($this->fileMap);
        // $this->fileMap = [];
    }

    public function addFileMapPart($fileMapPart)
    {
        foreach ($fileMapPart as $fileMapPartElement) {
            $this->fileMap[] = $fileMapPartElement;
        }
    }

    public function scanDirectory($frameworkPath, $codeLocation, $pathBaseType)
    {
        $bannedPackages = $this->getContainer()->getProjectData('bannedPackages');
        $fullStartingPath = FileHandler::completePath($frameworkPath, $pathBaseType);
        $recursiveDirContent = FileHandler::getRecursiveDirContent($fullStartingPath, 'file', $this->mappedParentDirNames);
        $unnecessary = explode($frameworkPath, $fullStartingPath)[0];
        $fileMap = array();
        $packageName = null;
        foreach ($recursiveDirContent as $pathToFile) {
            $packageBanned = false;
            $fullPath = BasicUtils::explodeAndRemoveElement($pathToFile, '/', 'last');
            $fileName = BasicUtils::explodeAndGetElement($pathToFile, '/', 'last');
            $path = str_replace($unnecessary, '', $fullPath);
            $className = str_replace('.php', '', $fileName);
            if ($codeLocation == 'packages') {
                $packagePathParts1 = explode('framework/packages/', $fullPath);
                $packagePathParts2 = explode('/', $packagePathParts1[1]);
                $packageName = $packagePathParts2[0];
                if (is_array($bannedPackages) && in_array($packageName, $bannedPackages)) {
                    $packageBanned = true;
                }
            }
            if (!$packageBanned) {
                $fileMap[] = array(
                    'path' => $path,
                    'namespace' => str_replace('/', '\\', $path.'/'.$className),
                    'packageName' => $packageName,
                    'codeLocation' => $codeLocation,
                    'fileName' => $fileName,
                    'fileType' => 'file',
                    'className' => $className,
                    'classType' => BasicUtils::explodeAndGetElement($path, '/', 'last')
                );
            }
        }
        // dump($fileMap);exit;
        return $fileMap;
    }
}
