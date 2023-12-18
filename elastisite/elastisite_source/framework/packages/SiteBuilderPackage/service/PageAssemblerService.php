<?php
namespace framework\packages\SiteBuilderPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\SiteBuilderPackage\entity\BuiltPage;
use framework\packages\SiteBuilderPackage\entity\BuiltPageWidget;

class PageAssemblerService extends Service
{


    public static $builtPageService;

    public static function setPlaceholders($text)
    {
        $text = str_replace('{App.webProject}', App::getWebProject(), $text);

        return $text;
    }

    public static function getBuiltPageService() : BuiltPageService
    {
        if (self::$builtPageService) {
            return self::$builtPageService;
        }
        App::getContainer()->wireService('SiteBuilderPackage/entity/BuiltPage');
        App::getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');
        self::$builtPageService = new BuiltPageService();

        return self::$builtPageService;
    }

    // public static function getBuiltPageByRouteName($routeName) : BuiltPage
    // {
    //     $builtPage = self::getBuiltPageService()::findByRouteName($routeName);

    //     return $builtPage;
    // }

    public static function getBuiltPageData($builtPageId)
    {
        $builtPage = self::getBuiltPageService()->find($builtPageId);
        if (!$builtPage) {
            return null;
        }
        $numberOfPanels = $builtPage->getNumberOfPanels();
        $availablePositions = self::getAvailablePositions($numberOfPanels);
        // $availableElementNames = self::getAvailableElements($availablePositions, true);
        $availableWidgets = [];
        $widgetsOnBuiltPage = BuiltPageService::getWidgetsOnBuiltPage($builtPage);
        foreach (BuiltPageService::PUBLIC_WIDGETS as $usableWidgetName => $usableWidgetProperties) {
            if (!in_array($usableWidgetName, $widgetsOnBuiltPage['total']) && in_array($usableWidgetProperties['position'], $availablePositions)) {
                $availableWidgets[$usableWidgetProperties['position']][] = $usableWidgetName;
            }
        }

        $positionFull = [];
        foreach (BuiltPageService::USABLE_POSITIONS as $usablePosition) {
            $newElementIndex = self::createElementIndex($builtPage, $usablePosition);
            $positionFull[$usablePosition] = $newElementIndex === null ? true : false;
        }

        // dump($availablePositions);exit;

        $result = [
            'publicWidgets' => BuiltPageService::PUBLIC_WIDGETS,
            'widgetsAlreadyUsed' => $widgetsOnBuiltPage['sortedOut'],
            'widgetDetails' => $widgetsOnBuiltPage['widgetDetails'],
            'builtPageObject' => $builtPage,
            'positionFull' => $positionFull,
            'availableWidgets' => $availableWidgets,
        ];

        return $result;
    }

    public static function getAvailableWidgets(int $builtPageId) : array
    {
        $builtPageData = self::getBuiltPageData($builtPageId);

        return $builtPageData['availableWidgets'];
    }

    public static function getAvailablePositions(int $numberOfPanels) : array
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');

        $availablePositions = [];
        foreach (BuiltPageService::USABLE_POSITIONS as $usablePositionIndex => $usablePosition) {
            if ($usablePositionIndex < $numberOfPanels) {
                $availablePositions[] = $usablePosition;
            }
        }

        return $availablePositions;
    }

    public static function getAvailableElements(array $positions, $namesOnly) : array
    {
        App::getContainer()->wireService('SiteBuilderPackage/service/BuiltPageService');

        $availableElements = [];
        foreach (BuiltPageService::USABLE_ELEMENTS as $usableElementName => $usableElement) {
            if (in_array($usableElement['position'], $positions)) {
                if ($namesOnly) {
                    $availableElements[] = $usableElementName;
                } else {
                    $availableElements[$usableElementName] = $usableElement;
                }
            }
        }

        return $availableElements;
    }

    public static function addWidget(BuiltPage $builtPage, string $position, string $widgetName, string $element = null) : BuiltPage
    {
        $widgetFound = false;
        foreach ($builtPage->getBuiltPageWidget() as $builtPageWidget) {
            if ($builtPageWidget->getWidget() == $widgetName) {
                $widgetFound = true;
            }
        }

        if (!$widgetFound) {
            $newElementIndex = $element ? : self::createElementIndex($builtPage, $position);
            if ($newElementIndex === null) {
                return $builtPage;
            }
            $newBuiltPageWidget = new BuiltPageWidget();
            $newBuiltPageWidget->setBuiltPage($builtPage);
            $newBuiltPageWidget->setPosition($position);
            $newBuiltPageWidget->setElementIndex($newElementIndex);
            $newBuiltPageWidget->setWidget($widgetName);
            $newBuiltPageWidget = $newBuiltPageWidget->getRepository()->store($newBuiltPageWidget);
            $builtPage->addBuiltPageWidget($newBuiltPageWidget);
        }

        return $builtPage;
    }

    public static function createElementIndex(BuiltPage $builtPage, $position) : ? string
    {
        /**
         * Now we are collecting all elements we used so far.
        */
        $usedElementIndexesOfPosition = [];
        foreach ($builtPage->getBuiltPageWidget() as $builtPageWidget) {
            if ($builtPageWidget->getPosition() == $position) {
                $usedElementIndexesOfPosition[] = $builtPageWidget->getElementIndex();
            }
        }

        /**
         * As looping through every possible elements, if we find some which are not used so far, we put those into an array.
        */
        $freeElementIndexesOfPosition = [];
        foreach (BuiltPageService::USABLE_ELEMENTS as $usableElementName => $usableElementProperties) {
            $loopElementIndex = $usableElementProperties['elementIndex'];
            if ($usableElementProperties['position'] == $position && !in_array($loopElementIndex, $usedElementIndexesOfPosition)) {
                $freeElementIndexesOfPosition[] = $loopElementIndex;
            }
        }

        /**
         * We are looping through the previous array. Trickily: we return in the first available loop.
        */
        foreach ($freeElementIndexesOfPosition as $freeElementIndexOfPosition) {
            return $freeElementIndexOfPosition;
        }

        /**
         * In case we did not have free elements in this position, we will return as false.
        */
        return null;
    }

    public static function removeWidget(BuiltPage $builtPage, $position, $widgetName)
    {
        $newBuiltPageWidgets = [];
        foreach ($builtPage->getBuiltPageWidget() as $builtPageWidget) {
            if ($builtPageWidget->getPosition() == $position && $builtPageWidget->getWidget() == $widgetName) {
                $builtPageWidget->getRepository()->remove($builtPageWidget->getId());
            } else {
                $newBuiltPageWidgets[] = $builtPageWidget;
            }
        }

        $builtPage->setAllBuiltPageWidgets($newBuiltPageWidgets);
        self::rearrangeElementIndexes($builtPage, $position);
    }

    public static function rearrangeElementIndexes(BuiltPage $builtPage, $position)
    {
        $reindexedBuiltPageWidgets = [];
        $counter = 0;
        foreach ($builtPage->getBuiltPageWidget() as $builtPageWidget) {
            $reindexedBuiltPageWidgets[$counter] = $builtPageWidget;
            $counter++;
        }
        ksort($reindexedBuiltPageWidgets);
        // dump($reindexedBuiltPageWidgets);
        foreach ($reindexedBuiltPageWidgets as $index => $builtPageWidget) {
            // $reindexedBuiltPageWidgets[(int)$builtPageWidget->getElementIndex()] = $builtPageWidget;
            $builtPageWidget->setElementIndex($index);
            $builtPageWidget = $builtPageWidget->getRepository()->store($builtPageWidget);
            // dump($builtPageWidget);exit;
        }
    }

    public static function sortWidgets(BuiltPage $builtPage, $position, $arrangedWidgetNames)
    {
        $builtPageWidgets = $builtPage->getBuiltPageWidget();
        $counter = 0;
        // dump($arrangedWidgetNames);
        foreach ($arrangedWidgetNames as $arrangedWidgetName) {
            foreach ($builtPageWidgets as $builtPageWidget) {
                if ($builtPageWidget->getWidget() == $arrangedWidgetName) {
                    $builtPageWidget->setElementIndex($counter);
                    $builtPageWidget->getRepository()->store($builtPageWidget);
                }
            }
            $counter++;
        }
        // dump('alma');exit;
    }
}
