<?php
namespace framework\kernel\operation;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;

class DirChecker extends Kernel
{
    const DYNAMIC_WRITABLE_RECURSIVE_DIRS = array(
        // 'background/image',
        // 'background/thumbnail',
        'log',
        'projects',
        'temp',
        'upload',
        'video'
    );

    private $unwritableDynamicFiles;
    private $writablePublicDirs;
    private $writablePublicFiles;

    public function checkDynamicFilePermissions()
    {
        foreach (self::DYNAMIC_WRITABLE_RECURSIVE_DIRS as $dir) {
            $pathToDir = FileHandler::completePath($dir, 'dynamic', true);
            $this->checkFilePermissions($pathToDir, 'dynamic');
        }
    }

    public function checkPublicFilePermissions()
    {
        $pathToDir = FileHandler::completePath('/', 'source', true);
        $pathToDir = rtrim(str_replace('elastisite_source', 'webroot', $pathToDir), '/');
        //dump($pathToDir );
        $this->checkFilePermissions($pathToDir, 'publicDir');
        $this->checkFilePermissions($pathToDir, 'publicFile');
    }

    public function checkFilePermissions($pathToDir, $fileType) {
        $recDirContent = FileHandler::getRecursiveDirContent($pathToDir, ($fileType == 'publicDir' ? 'dir' : 'file'));
        //dump($recDirContent);
        foreach ($recDirContent as $file) {
            if ($fileType == 'dynamic') {
                if (!is_writable($file)) {
                    $this->unwritableDynamicFiles[] = $file;
                }
            } else {
                if (is_writable($file)) {
                    $prop = $fileType == 'publicDir' ? 'writablePublicDirs' : 'writablePublicFiles';
                    $this->$prop[] = $file;
                }
            }
        }
        // dump($this->unwritableFiles);
    }

    public function getUnwritableDynamicFiles()
    {
        return $this->unwritableDynamicFiles;
    }

    public function getWritablePublicDirs()
    {
        return $this->writablePublicDirs;
    }

    public function getWritablePublicFiles()
    {
        return $this->writablePublicFiles;
    }
}