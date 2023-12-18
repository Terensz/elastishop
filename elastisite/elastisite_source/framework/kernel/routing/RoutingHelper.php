<?php
namespace framework\kernel\routing;

use framework\component\entity\Route;
use framework\kernel\component\Kernel;
use framework\component\exception\ElastiException;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;

class RoutingHelper extends Kernel
{
    public function searchRoute($urlParamChain)
    {
        $routeMap = $this->getContainer()->getFullRouteMap();
        // dump($routeMap);exit;
        $urlParamArray = explode('/', trim($urlParamChain, '/'));
        if ($urlParamChain == '') {
            return $this->getPageRoute($routeMap, '');
        }
        foreach ($routeMap as $routeName => $route) {
            if (!isset($route['paramChains'])) {
                continue;
                // dump($routeMapElement);
            }
            foreach ($route['paramChains'] as $paramChain => $locale) {
                $routeParamArray = ((string)trim($paramChain, '/') == '') ? array() : explode('/', (string)trim($paramChain, '/'));
                if (count($routeParamArray) == count($urlParamArray)) {
                    $fails = 0;
                    for ($i3 = 0; $i3 < count($routeParamArray); $i3++) {
                        if ((substr($routeParamArray[$i3], 0, 1) == '{') || ($routeParamArray[$i3] == $urlParamArray[$i3])) {
                        } else {
                            $fails++;
                        }
                    }
                    if ($fails == 0) {
                        $found = $route;
                        $found['paramChain'] = $paramChain;
                        $returnRoute = new Route();
                        $returnRoute->set($found);
                        // dump($returnRoute);
                        return $returnRoute;
                    }
                }
            }
        }
        // dump($urlParamChain);exit;
        $this->getContainer()->setFailedRoute($urlParamChain);
        return $this->getPageRoute($routeMap, 'error/404');
    }

    public function findRouteByMethod($controller, $action)
    {
        $routeMap = $this->getContainer()->getFullRouteMap();
        foreach ($routeMap as $routeName => $route) {
            if ($route['controller'] == $controller && $route['action'] == $action) {
                return $route;
            }
        }
        return false;
    }

    public function getRoute($routeName)
    {
        $routeMap = $this->getContainer()->getFullRouteMap();
        return isset($routeMap[$routeName]) ? $routeMap[$routeName] : null;
    }

    public function getObviousParamChain($paramChains)
    {
        $paramChains = array_flip($paramChains);
        if (isset($paramChains[$this->getSession()->getLocale()])) {
            return $paramChains[$this->getSession()->getLocale()];
        }
        if (isset($paramChains['default'])) {
            return $paramChains['default'];
        }
        foreach ($paramChains as $locale => $paramChain) {
            return $paramChain;
        }
    }

    public function getController($controllerPath, $action)
    {
        $routeParams = $this->getContainer()->getServiceLinkParams($controllerPath);
        // dump($routeParams);
        // $controllerRawPath = $controllerPath.'_END_';
        // $controllerArray = explode('/', $controllerRawPath);
        // $controllerEnd = $controllerArray[count($controllerArray) - 1];
        // $path = str_replace('/'.$controllerEnd, '', $controllerRawPath);
        // $controller = str_replace('_END_', '', $controllerEnd);
        // dump('controllerRawPath: '.$controllerRawPath);
        // dump($path.'/'.$controller.'.php');
        // $routeParams = $this->getContainer()->getServiceLinkParams($path.'/'.$controller);
        // dump($routeParams);
        FileHandler::includeFileOnce($routeParams['pathToFile']);
        $namespace = $routeParams['objectNamespace'];
        $object = new $namespace();
        return array(
            'object' => $object,
            'action' => $action,
            'namespace' => $namespace
        );
    }

    public function getPageRoute($routeMap = null, $paramChain = '')
    {
        // return $this->getContainer()->getRouting()->getPageRoute();
        $routeMap = $routeMap ? $routeMap : $this->getContainer()->getFullRouteMap();
        foreach ($routeMap as $routeName => $route) {
            foreach ($route['paramChains'] as $key => $value) {
                if ($key == $paramChain) {
                    $found = $route;
                    $found['paramChain'] = $paramChain;
                    $returnRoute = new Route();
                    $returnRoute->set($found);
                    return $returnRoute;
                }
            }
        }
    }

    public function routeExists($routeName)
    {
        foreach ($this->getContainer()->getFullRouteMap() as $route) {
            if ($routeName == $route['name']) {
                return true;
            }
        }
        return false;
    }

    public function getLink($routeName, array $data = [])
    {
        $paramChain = $this->findParamChain($routeName, $data);
        // dump($paramChain);
        return $paramChain === false ? false : $this->getContainer()->getUrl()->getHttpDomain().'/'.$paramChain;
    }

    public function findParamChain($routeName, array $data = []) // pl. homepage, news
    {
        // $paramChain = null;
        // dump($this->getContainer()->getFullRouteMap()); exit;
        foreach ($this->getContainer()->getFullRouteMap() as $route) {
            if ($routeName == $route['name']) {
                // dump($route);
                $paramChain = $this->findProperParamChain($routeName, $data, $route['paramChains']);
                if ($paramChain == '' || $paramChain) {
                    return $paramChain;
                }
                // dump($paramChain );exit;
                // foreach ($route['paramChains'] as $paramChain => $locale) {
                //     if ($locale == 'default') {
                //         // dump($route);
                //         return $this->completeParamChain($routeName, $paramChain, $data);
                //     }
                //     if ($locale == $this->getContainer()->getSession()->getLocale()) {
                //         return $this->completeParamChain($routeName, $paramChain, $data);
                //     }
                //     # Ha nem volt talalat, akkor masik nyelvvel csinalunk linket
                //     return $this->completeParamChain($routeName, $paramChain, $data);
                // }
            }
        }
        // echo 'routeName: '.$routeName.' - ';
        // dump($this->getContainer()->getFullRouteMap()['contact']);exit;
        return false;
        // throw new ElastiException("Route not found: ".$routeName, ElastiException::ERROR_TYPE_SECRET_PROG);
    }

    public function findProperParamChain($routeName, $data, $paramChains, $round = 1)
    {
        foreach ($paramChains as $paramChain => $locale) {
            if ($locale == 'default' || $locale == $this->getContainer()->getSession()->getLocale() || $round > 1) {
                return $this->completeParamChain($routeName, $paramChain, $data);
            }
        }

        return $this->findProperParamChain($routeName, $data, $paramChains, ($round + 1));
    }

    public function completeParamChain($routeName, $rawLink, array $data = [])
    {
        $foundVars = 0;
        $changedVars = 0;
        $parts = explode('/', $rawLink);
        for ($i = 0; $i < count($parts); $i++) {
            if (substr($parts[$i], 0, 1) == '{') {
                $foundVars++;
                $var = str_replace(['{', '}'], '', $parts[$i]);
                foreach ($data as $key => $value) {
                    if ($var == $key) {
                        $parts[$i] = $value;
                        $changedVars++;
                    }
                }
            }
        }

        if ($foundVars != $changedVars) {
            throw new ElastiException("Error linking ".$routeName.", number of data [".BasicUtils::arrayToString($data)."] not equals required in route: ".$rawLink, ElastiException::ERROR_TYPE_SECRET_PROG);

        } else {
            return implode('/', $parts);
        }
    }
}
