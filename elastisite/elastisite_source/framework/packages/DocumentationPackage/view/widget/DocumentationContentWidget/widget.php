<?php 
if ($docView):
?>
<div class="widgetWrapper-off">
    <div class="widgetContainer softWidgetChangeTransition" id="widgetContainer-left1">
        <div class="widgetWrapper-noPadding" style="position: relative; z-index: 3;">
            <div class="widgetHeader widgetHeader-color">
                <div class="widgetHeader-titleText"><?php echo trans($documentTitle); ?></div>
            </div>
            <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">
<?php 
echo $docView;
// include('files/'.$file.'.php'); 
?>
            </div>
        </div>
    </div>
</div>
<?php 
endif;
?>