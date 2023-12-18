<?php 

use framework\packages\FrontendPackage\service\ResponsivePageService;
use framework\packages\SiteBuilderPackage\service\ContentEditorDisplayTool;
?>
<div class="contentViewer-container-<?php echo $contentEditorId; ?>" <?php echo $viewerRounded ? ' style="border-radius: 6px !important;"' : ''; ?>>
<?php 
// $unitCaseStyleParams = [];
// $unitStyleParams = [];
$styleDesktop = '';
$styleTablet = '';
$styleSmallTablet = '';
$stylePhone = '';

?>
<?php 
/**
 * array of @var ContentEditorUnitCase
*/
foreach ($contentEditor->getSortedContentEditorUnitCases() as $unitCase): 

    $containerHorizontalDirectionClass = !empty($unitCase->getHorizontalPositioningDirection()) ? ' contentViewer-unit-horizontalPositioningDirection-'.$unitCase->getHorizontalPositioningDirection() : '';
    $containerVerticalDirectionClass = !empty($unitCase->getVerticalPositioningDirection()) ? ' contentViewer-unit-verticalPositioningDirection-'.$unitCase->getVerticalPositioningDirection() : '';
    $additionalClasses = $unitCase->getClass() ? trim($unitCase->getClass()).' ' : '';
    $unitCaseWrapperHtmlId = 'contentViewer_unitCaseWrapper_'.$unitCase->getId();

    $draggableClassStr = ' contentViewer-unit-draggable';
    $verticalBoth = false;
    if ($unitCase->getVerticalPositioningDirection() == $unitCase::VERTICAL_POSITIONING_DIRECTION_BOTH) {
        $verticalBoth = true;
    }
    $horizontalBoth = false;
    if ($unitCase->getHorizontalPositioningDirection() == $unitCase::HORIZONTAL_POSITIONING_DIRECTION_BOTH) {
        $horizontalBoth = true;
    }
    if ($verticalBoth || $horizontalBoth) {
        $draggableClassStr = '';
    }

    // dump($unitCase);
    // dump($verticalBoth);exit;

    // $unitCaseStyleParams[] = [
    //     'unitCaseWrapperHtmlId' => $unitCaseWrapperHtmlId,
    //     'verticalPositioningDirection' => $unitCase->getVerticalPositioningDirection(),
    //     'verticalPosition' => $unitCase->getVerticalPosition(),
    //     'horizontalPositioningDirection' => $unitCase->getHorizontalPositioningDirection(),
    //     'horizontalPosition' => $unitCase->getHorizontalPosition(),
    //     // 'width' => $unitCase->getWidth()
    // ];

    $actualWidth = $unitCase->getWidth();

if ($actualWidth) {
        /**
         * Desktop
        */
        $styleDesktop .= '
#contentViewer_unitCase_'.$unitCase->getId().' {
    max-width: '.$actualWidth.'px;
}
';
}

if ($actualWidth) {
        /**
         * Tablet
        */
        $styleTablet .= '
#contentViewer_unitCase_'.$unitCase->getId().' {
    max-width: '.round(0.75 * $actualWidth).'px;
}
';
}

if ($actualWidth) {
        /**
         * Small tablet
        */
        $styleSmallTablet .= '
#contentViewer_unitCase_'.$unitCase->getId().' {
    max-width: '.round(0.5 * $actualWidth).'px;
}
';
}

if ($actualWidth) {
        /**
         * Phone
        */
        $stylePhone .= '
#contentViewer_unitCase_'.$unitCase->getId().' {
    max-width: '.round(0.35 * $actualWidth).'px;
}
';
}

/**
 * For vertical center, you should apply a littre trick.
*/
// $unitCaseOuterWrapperClassAdd = $verticalBoth ? ' verticalCenterOuterWrapper' : '';
// dump($verticalBoth);exit;
if ($horizontalBoth || $verticalBoth) {
    $outerCaseWrapperStyleAdd = 'position: relative;';
    $middleCaseWrapperStyleAdd = '';
    // $middleCaseWrapperStyleAdd = 'position: relative;';
    $innerCaseWrapperStyleAdd = '';
} else {
    $outerCaseWrapperStyleAdd = '';
    $middleCaseWrapperStyleAdd = '';
    $innerCaseWrapperStyleAdd = '';
}

if ($horizontalBoth) {
    $outerCaseWrapperHorizontalStyleAdd = 'width: 100%; height: 100%;';
    // $middleCaseWrapperHorizontalStyleAdd = 'left: 50%; transform: translateX(-50%);';
    $middleCaseWrapperHorizontalStyleAdd = 'left: '.$unitCase->getHorizontalPosition().'px; right: '.$unitCase->getHorizontalPosition().'px;';
    $innerCaseWrapperHorizontalStyleAdd = '';
} else {
    $outerCaseWrapperHorizontalStyleAdd = '';
    $middleCaseWrapperHorizontalStyleAdd = '';
    $innerCaseWrapperHorizontalStyleAdd = '';
}

if ($verticalBoth) {
    $outerCaseWrapperVerticalStyleAdd = 'width: 100%; height: 100%;';
    $middleCaseWrapperVerticalStyleAdd = 'top: 50%; transform: translateY(-50%);';
    $innerCaseWrapperVerticalStyleAdd = '';
} else {
    $outerCaseWrapperVerticalStyleAdd = '';
    $middleCaseWrapperVerticalStyleAdd = '';
    $innerCaseWrapperVerticalStyleAdd = '';
}

?>
    <div class="unitCase-outerWrapper" style="<?php echo $outerCaseWrapperStyleAdd.$outerCaseWrapperHorizontalStyleAdd.$outerCaseWrapperVerticalStyleAdd; ?>">
        <div data-id="<?php echo $unitCase->getId(); ?>" data-parentid="<?php echo $contentEditor->getId(); ?>" id="contentViewer_unitCase_<?php echo $unitCase->getId(); ?>" 
        class="contentViewer-unitCase-container-<?php echo $contentEditorId; ?><?php echo $draggableClassStr; ?> contentViewer-unitCase-container<?php echo $containerHorizontalDirectionClass; ?><?php echo $containerVerticalDirectionClass; ?>"
        style="<?php echo $middleCaseWrapperStyleAdd.$middleCaseWrapperHorizontalStyleAdd.$middleCaseWrapperVerticalStyleAdd; ?> <?php echo $unitCase->getContainerStyleString(); ?>">
            <div data-id="<?php echo $unitCase->getId(); ?>" id="<?php echo $unitCaseWrapperHtmlId; ?>" 
            class="<?php echo $additionalClasses; ?> contentViewer-unit-<?php echo $contentEditorId; ?> contentViewer-unit" 
            style="<?php echo $unitCase->getWrapperStyleString(); ?> <?php echo $innerCaseWrapperHorizontalStyleAdd; ?>">
<?php 
    /**
     * array of @var ContentEditorUnit
    */
    foreach ($unitCase->getSortedContentEditorUnits() as $unit):

        /**
         * Desktop
        */
        $actualFontSize = $unit->getFontSize();
        $styleDesktop .= '
#contentViewer_unit_'.$unit->getId().' {
    font-size: '.$actualFontSize.'px;
}
';

        /**
         * Tablet
        */
        $actualFontSize = $unit->getFontSize();
        $decreasedFontSize = round(0.8 * $actualFontSize);
        $actualFontSize = $decreasedFontSize < ResponsivePageService::MIN_FONT_SIZE ? : $decreasedFontSize;
        $styleTablet .= '
#contentViewer_unit_'.$unit->getId().' {
    font-size: '.$actualFontSize.'px;
}
';

        /**
         * Small tablet
        */
        $actualFontSize = $unit->getFontSize();
        $decreasedFontSize = round(0.6 * $actualFontSize);
        $actualFontSize = $decreasedFontSize < ResponsivePageService::MIN_FONT_SIZE ? : $decreasedFontSize;
        $styleSmallTablet .= '
#contentViewer_unit_'.$unit->getId().' {
    font-size: '.$actualFontSize.'px;
}
';

        /**
         * Phone
        */
        $actualFontSize = $unit->getFontSize();
        $decreasedFontSize = round(0.4 * $actualFontSize);
        $actualFontSize = $decreasedFontSize < ResponsivePageService::MIN_FONT_SIZE ? : $decreasedFontSize;
        $stylePhone .= '
#contentViewer_unit_'.$unit->getId().' {
    font-size: '.$actualFontSize.'px;
}
';

?>
                <div data-id="<?php echo $unit->getId(); ?>" data-parentid="<?php echo $unitCase->getId(); ?>" style="<?php echo $unit->getWrapperStyleString(); ?>" id="contentViewer_unit_<?php echo $unit->getId(); ?>">
                    <?php echo ContentEditorDisplayTool::displayDescription($unit->getDescription()); ?>
                </div>
<?php 
    endforeach; 
?>
            </div>
        </div>
    </div><!-- END OF unitCase outer wrapper -->
<?php 
endforeach; 
?>
<?php 
// dump($contentEditor->getSortedContentEditorUnits());
// dump($contentEditorUnitStyleParams);exit;
?>
</div>
<!-- <div id="contentViewer_background_<?php echo $contentEditorId; ?>" class="contentViewer-background-<?php echo $contentEditorId; ?>">
</div> -->
<style>
.contentViewer-container-<?php echo $contentEditorId; ?> {
    position: relative;
    background-image: url('<?php echo $backgroundImageLink; ?>?<?php echo time(); ?>');
    background-repeat: no-repeat;
    background-position: center top;
    background-size: cover;
    background-color: #fff;
    /* min-height: 0px; */
    <?php 
    if ($contentEditor->getBoxShadowStyle() && isset($contentEditor::BOX_SHADOW_STYLES[$contentEditor->getBoxShadowStyle()])) {
        echo $contentEditor::BOX_SHADOW_STYLES[$contentEditor->getBoxShadowStyle()];
    }
    ?>
}
.contentViewer-unitCase-container-<?php echo $contentEditorId; ?> {
    position: absolute;
    <?php $viewerRounded ? 'border-radius: 6px;' : ''; ?>
}

/* generated style */
@media (min-width: 1200px) {
    .contentViewer-container-<?php echo $contentEditorId; ?> {
        height: <?php echo ($contentEditor->getHeight() ? $contentEditor->getHeight().'px' : 'auto'); ?>;
    }
<?php echo $styleDesktop; ?>
}

@media (min-width: <?php echo  ResponsivePageService::SMALL_TABLET_SCREEN_MAX_WIDTH + 1; ?>px) and (max-width: <?php echo  ResponsivePageService::TABLET_SCREEN_MAX_WIDTH; ?>px) {
    .contentViewer-container-<?php echo $contentEditorId; ?> {
        height: <?php echo ($contentEditor->getHeight() ? round(0.75 * $contentEditor->getHeight()).'px' : 'auto'); ?>;
    }
<?php echo $styleTablet; ?>
}

@media (min-width: <?php echo  ResponsivePageService::PHONE_SCREEN_MAX_WIDTH + 1; ?>px) and (max-width: <?php echo  ResponsivePageService::SMALL_TABLET_SCREEN_MAX_WIDTH; ?>px) {
    .contentViewer-container-<?php echo $contentEditorId; ?> {
        height: <?php echo ($contentEditor->getHeight() ? round(0.5 * $contentEditor->getHeight()).'px' : 'auto'); ?>;
    }
<?php echo $styleSmallTablet; ?>
}

@media (max-width: <?php echo  ResponsivePageService::PHONE_SCREEN_MAX_WIDTH; ?>px) {
    .contentViewer-container-<?php echo $contentEditorId; ?> {
        height: <?php echo ($contentEditor->getHeight() ? round(0.35 * $contentEditor->getHeight()).'px' : 'auto'); ?>;
    }
<?php echo $stylePhone; ?>
}
</style>

<script>
// var scrollar = new Scrollar("#contentViewer_container_<?php echo $contentEditorId; ?>", {
//     // the parent of scrollar object,
//     wrapper: null,
//     // direction of the scroll (supports only vertical for now)
//     vertical: true, // horizontal: true,
//     // speed of the blocks (data-scrollar tags override this config)
//     // movement value to 1px scroll (e.g. 0.6 : 1 means the element will scroll 0.6px when the window is scrolled 1px)
//     speed: 0.6,
//     // amount of travel until stop (in px)
//     // prevent extra scrolling
//     distance: 1000,
//     // callback when element is moved
//     callback: null
// });
</script>