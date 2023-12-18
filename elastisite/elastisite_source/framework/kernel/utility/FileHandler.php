<?php

namespace framework\kernel\utility;

use App;
use framework\kernel\base\Reflector;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;
use framework\kernel\base\Container;

class FileHandler
{
    public static function includeDirectory($path, $pathBaseType = null)
    {
        $pathToDir = self::completePath($path, $pathBaseType);
        $files = self::getRecursiveDirContent($pathToDir, 'file');
        foreach($files as $file) {
            include_once($file);
        }
    }

    public static function getRecursiveDirContent($pathToDir, $type = 'all', $mappedParentDirNames = [])
    {
        // var_dump($pathToDir);exit;
        $totalResult = array();
        $pathToDir = rtrim($pathToDir, '/');
        if (!$pathToDir || !is_string($pathToDir)) {
            return $totalResult;
            // dump('alma'); exit;
        }
        $globPathToDir = glob($pathToDir);
        if (empty($globPathToDir)) {
            $globPathToDir = array();
        }
        // dump($pathToDir);
        // dump($globPathToDir);
        if ($type == 'dir') {
            $result = array_filter($globPathToDir, 'is_dir');
        } else {
            $result = array_filter($globPathToDir);
        }
        if ($type != 'file') {
            $totalResult = array_merge($totalResult, $result);
        } else {
            foreach ($result as $loop) {
                if (is_file($loop)) {
                    if (!empty($mappedParentDirNames)) {
                        $pathParts = explode('/', $loop);
                        if (count($pathParts) >= 2 && in_array($pathParts[count($pathParts) - 2], $mappedParentDirNames)) {
                            $totalResult[] = $loop;
                        }
                    } else {
                        $totalResult[] = $loop;
                    }
                }
            }
        }
        
        foreach ($result as $fileOrDir) {
            $totalResult = array_merge($totalResult, self::getRecursiveDirContent($fileOrDir.'/*', $type, $mappedParentDirNames));
        }
        return $totalResult;
    }

    public static function determineCompetence($filePath, $container)
    {
        $configPos = strpos($filePath, $container->getPathBase('config'));
        $dynamicPos = strpos($filePath, $container->getPathBase('dynamic'));
        $projectsPos = strpos($filePath, $container->getPathBase('projects'));
        if ($configPos === false && $dynamicPos === false && $projectsPos === false) {
            return 1;
        } else {
            return 2;
        }
    }

    public static function getAllDirNames($path, $pathBaseType = null, $returnFullPath = false)
    {
        $path = $pathBaseType ? self::completePath($path, $pathBaseType) : $path;
        $rawDirNames = glob(rtrim($path, '/').'/*' , GLOB_ONLYDIR);
        for ($i = 0; $i < count($rawDirNames); $i++) {
            if ($returnFullPath) {
                $rawDirNames[$i] = $rawDirNames[$i];
            } else {
                $rawDirNames[$i] = BasicUtils::explodeAndGetElement($rawDirNames[$i], '/', 'last');
            }
        }
        return $rawDirNames;
    }

    public static function getAllFileNames($path, $extensionAction = 'keep', $pathBaseType = null)
    {
        // $path = self::completePath($path, $pathBaseType);
        $path = $pathBaseType ? self::completePath($path, $pathBaseType) : $path;
        // dump($path);
        $rawFilePaths = glob($path.'/*');
        $fileNames = array();
        foreach($rawFilePaths as $rawFilePath) {
            if(is_file($rawFilePath)) {
                $rawFileName = BasicUtils::explodeAndGetElement($rawFilePath, '/', 'last');
                $fileNames[] = $extensionAction == 'remove'
                    ? BasicUtils::explodeAndRemoveElement($rawFileName, '.', 'last')
                    : $rawFileName;
            }
        }

        return $fileNames;
    }

    public static function checkFile($path)
    {
        if (!is_file($path)) {
            throw new ElastiException('Missing file: '.$path, ElastiException::ERROR_TYPE_SECRET_PROG);
        }
    }

    public static function includeFile($path, $pathBaseType = null)
    {
        $path = self::completePath($path, $pathBaseType);
        self::checkFile($path);
        include($path);
    }

    public static function includeFileOnce($path, $pathBaseType = null)
    {
        $path = self::completePath($path, $pathBaseType);
        self::checkFile($path);
        include_once($path);
    }

    public static function fileExists($path, $pathBaseType = null)
    {
        $pathToFile = self::completePath($path, $pathBaseType);
        // dump($pathToFile);
        $return = file_exists($pathToFile);
        // if (!$return) {
        //     dump('NIIIINCS!!!!!!!!: '.$pathToFile);
        //     dump(file_exists($pathToFile));
        // } else {
        //     dump('VAAAAAN!!!!!!!!: '.$pathToFile);
        //     dump(file_exists($pathToFile));
        // }
        return $return;
    }

    public static function isFile($path, $pathBaseType = null)
    {
        return is_file(self::completePath($path, $pathBaseType));
    }

    public static function moveUploadedFile($tmpName, $pathToFile)
    {
        $moved = @move_uploaded_file($tmpName, $pathToFile);
        if (!$moved) {
            $container = Container::getSelfObject();
            throw new ElastiException(
                App::getContainer()->wrapExceptionParams(array(
                    'filePath' => $pathToFile
                )), 
                (self::determineCompetence($pathToFile, $container) == 2 ? 1415 : 1615)
            );
        }
        return true;
    }

    public static function unlinkFile($path, $pathBaseType = null)
    {
        return unlink(self::completePath($path, $pathBaseType));
    }

    public static function completePath($path, $pathBaseType = null)
    {
        if (!$pathBaseType) {
            return $path;
        }
        // dump('FileHandler::completePath()');
        $pathBase = self::getAbsolutePathBase($pathBaseType);
        // dump($pathBase);
        $return = $pathBase.'/'.ltrim($path, '/');
        // dump($return);
        return $return;
    }

    public static function getShortPath($fullPath, $pathBaseType)
    {
        // $container = Container::getSelfObject();
        // dump('FileHandler::getShortPath()');
        $pathBase = self::getAbsolutePathBase($pathBaseType);
        $return = trim(str_replace($pathBase, '', $fullPath), '/');
        // dump($return);
        return $return;
    }

    public static function getAbsolutePathBase($pathBaseType)
    {
        $container = Container::getSelfObject();
        $pathBase = $container->getPathBase($pathBaseType);
        // dump('ORIGINAL: '.$pathBase);
        $pathBaseParts = explode('/', $pathBase);
        $result = [];
        for ($i = 0; $i < count($pathBaseParts); $i++) {
            if ($i < (count($pathBaseParts) - 1)) {
                if ($pathBaseParts[$i + 1] != '..' && $pathBaseParts[$i] != '..') {
                    $result[] = $pathBaseParts[$i];
                }
            } else {
                $result[] = $pathBaseParts[$i];
            }
        }
        // dump(implode('/', $result));
        return '/'.trim(implode('/', $result), '/');
    }
}
