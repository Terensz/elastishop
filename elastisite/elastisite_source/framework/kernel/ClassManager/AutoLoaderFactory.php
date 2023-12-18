<?php
namespace framework\kernel\ClassManager;

use framework\kernel\component\Kernel;
use framework\kernel\utility\BasicUtils;
use framework\component\exception\ElastiException;
use framework\kernel\utility\FileHandler;

class AutoLoaderFactory extends Kernel
{
    public function initAutoLoaders($className)
    {
        // dump('Most ez jon.');
        $mappedAutoLoaders = $this->getContainer()->searchFileMap(['classType' => 'loader', 'className' => $className]);
        $autoLoaders = array();
        foreach ($mappedAutoLoaders as $autoLoader) {
            $this->getContainer()->wireService($autoLoader['path'].'/'.$autoLoader['className']);
            $autoLoaderClass = $autoLoader['namespace'];
            if (defined($autoLoaderClass.'::CONFIG')) {
                $config = $autoLoaderClass::CONFIG;
                $dependsFrom = isset($config['dependsFrom']) ? $config['dependsFrom'] : null;
            } else {
                $dependsFrom = null;
            }

            $autoLoaders[] = array(
                'packageName' => $autoLoader['packageName'],
                'class' => $autoLoaderClass,
                'dependsFrom' => isset($dependsFrom)
                    ? (is_array($dependsFrom) ? $dependsFrom : array($dependsFrom))
                    : null
            );
        }
        $arrangedAutoLoaders = $this->arrangeAutoLoaders($autoLoaders);
        foreach ($arrangedAutoLoaders as $arrangedAutoLoader) {
            new $arrangedAutoLoader['class']();
        }
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
