<?php
namespace framework\kernel\ClassManager;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;

class PackageLoader extends Kernel
{
    public function __construct()
    {
        $packageNames = FileHandler::getAllDirNames('framework/packages');
        $autoLoaders = array();
        foreach ($packageNames as $packageName) {
            $hasAutoLoader = FileHandler::fileExists('framework/packages/'.$packageName.'/AutoLoader.php');
            if ($hasAutoLoader) {
                $this->getContainer()->wireService('framework/packages/'.$packageName.'/AutoLoader');
                $autoLoaderClass = 'framework\\packages\\'.$packageName.'\AutoLoader';
                if (defined(''.$autoLoaderClass.'::CONFIG')) {
                    $config = $autoLoaderClass::CONFIG;
                    $dependsFrom = isset($config['dependsFrom']) ? $config['dependsFrom'] : null;
                } else {
                    $dependsFrom = null;
                }

                $autoLoaders[] = array(
                    'packageName' => $packageName,
                    'class' => $autoLoaderClass,
                    'dependsFrom' => isset($dependsFrom)
                        ? (is_array($dependsFrom) ? $dependsFrom : array($dependsFrom))
                        : null
                );
            }
        }

        // dump($autoLoaders);exit;
        $arrangedAutoLoaders = $this->arrangeAutoLoaders($autoLoaders);
        // dump($arrangedAutoLoaders);exit;
        foreach ($arrangedAutoLoaders as $arrangedAutoLoader) {
            new $arrangedAutoLoader['class']();
        }
        // dump($packageNames);exit;
    }

    public function arrangeAutoLoaders($items)
    {
        $res = array();
        $doneList = array();
        $unresolved = array();

        while (count($items) > count($res)) {
            $processed = false;

            foreach ($items as $itemIndex => $item) {
                if(isset($doneList[$item['packageName']])) {
                    continue;
                }
                $resolved = true;

                if(isset($item['dependsFrom'])) {
                    foreach($item['dependsFrom'] as $dep) {
                        if(!isset($doneList[$dep])) {
                            $unresolved = array(
                                'packageName' => $item['packageName'],
                                'dependsFrom' => $dep
                            );
                            $resolved = false;
                            break;
                        }
                    }
                }
                if($resolved) {
                    $doneList[$item['packageName']] = true;
                    $res[] = $item;
                    $processed = true;
                }
            }
            if(!$processed) {
                throw new ElastiException('Unresolvable dependency: "'.BasicUtils::arrayToString($unresolved).'"', ElastiException::ERROR_TYPE_SECRET_PROG);
            }
        }
        return $res;
    }
}
