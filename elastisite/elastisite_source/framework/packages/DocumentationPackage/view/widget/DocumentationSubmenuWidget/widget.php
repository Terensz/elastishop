<?php

use framework\kernel\utility\BasicUtils;

?>
<?php 
// dump($menuSystem);
foreach ($categorizedDocuments as $sequencedCategory => $documents):
    $sequencedCategoryParts = explode('_', $sequencedCategory);
    $category = count($sequencedCategoryParts) == 2 ? $sequencedCategoryParts[1] : $sequencedCategoryParts[0];
    $categoryTitle = str_replace('_', '.', BasicUtils::camelToSnakeCase($category));
?>
<div class="widgetWrapper-off">
    <div class="widgetContainer softWidgetChangeTransition" id="widgetContainer-left1">
        <div class="widgetWrapper-noPadding" style="position: relative; z-index: 3;">
            <div class="widgetHeader widgetHeader-color">
                <div class="widgetHeader-titleText"><?php echo trans($categoryTitle); ?></div>
            </div>
            <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">
<?php
        foreach ($documents as $slug):
            $slugParts = explode('_', $slug);
            $slug = count($slugParts) == 2 ? $slugParts[1] : $slugParts[0];
            // $activeStr = $container->getRouting()->getPageRoute()->getName() == $menuItem['routeName'] ? ' sideMenu-active' : '';
            $activeStr = ($slug == $actualSlug && $category == $actualCategory) ? ' sideMenu-active' : '';
            $documentTitle = str_replace('-', '.', $slug);

?>
                <div class="sideMenu-item">
                    <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo $httpDomain; ?>/documentation/<?php echo $category; ?>/<?php echo $slug; ?>"><?php echo trans($documentTitle); ?></a>
                </div>

<?php 
        endforeach;
?>
            </div>
        </div>
    </div>
</div>
<?php 
endforeach;
?>