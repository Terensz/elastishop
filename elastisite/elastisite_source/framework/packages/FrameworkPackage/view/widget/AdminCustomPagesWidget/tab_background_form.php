<?php 
// dump($pageBackground);
// dump($pageBgRepo->findAll());
?>

<form name="FrameworkPackage_customPageBackground_form" id="FrameworkPackage_customPageBackground_form" method="POST" action="" enctype="multipart/form-data">

    <input name="FrameworkPackage_customPageBackground_originalBackgroundColor" id="FrameworkPackage_customPageBackground_originalBackgroundColor" type="hidden" class="inputField form-control" value="<?php echo $originalBackgroundColor; ?>" aria-describedby="" placeholder="">

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label for="FrameworkPackage_customPageBackground_backgroundColor">
                    <b><?php echo trans('background.color'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <div class="input-group">
                    <input name="FrameworkPackage_customPageBackground_backgroundColor" id="FrameworkPackage_customPageBackground_backgroundColor" type="color" class="inputField form-control" value="<?php echo $originalBackgroundColor; ?>" aria-describedby="" placeholder="">

                </div>
                <div class="validationMessage error" id="FrameworkPackage_customPageBackground_backgroundColor-validationMessage" style="padding-top:4px;"></div>
            </div>

            <div id="FrameworkPackage_customPageBackground_saveBackgroundColor_buttons" style="display: none;">
                <div class="widgetWrapper-info">
                <?php echo trans('background.color.changed'); ?>
                </div>

                <div class="form-group">
                    <button name="FrameworkPackage_customPageBackground_saveBackgroundColor_submit" id="FrameworkPackage_customPageBackground_saveBackgroundColor_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" 
                    onclick="CustomPageBackground.saveBackgroundColor(event);" value=""><?php echo trans('save'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label for="FrameworkPackage_customPageBackground_fbsBackgroundTheme">
                    <b><?php echo trans('background'); ?></b>
                </label>
            </div>
        </div>


        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
<?php if ($background): ?>
<?php 
// dump($background);
$backgroundId = $background->getId();
$backgroundEngine = $background->getEngine();
$backgroundTitle = $background->getTitle();
$theme = $background->getTheme();
$extension = $background->getExtension();
?>
    <a href="" onclick="CustomPageBackground.removeBackground(event)"><?php echo trans('modify'); ?></a>
    <?php 
        $tagClass = 'light';
        include('tab_background_form_pageBackgroundCard.php'); 
    ?>
<?php else: ?>
    <div class="widgetWrapper-info">
    <?php echo trans('please.choose'); ?>
    </div>
    <?php foreach ($backgrounds as $background): ?>
        <?php
        $backgroundId = $background->getId();
        $backgroundEngine = $background->getEngine();
        $backgroundTitle = $background->getTitle();
        $theme = $background->getTheme();
        $extension = $background->getExtension();
        $tagClass = 'ultraLight';
        include('tab_background_form_pageBackgroundCard.php');
        ?>
    <?php endforeach; ?>
<?php endif; ?>

        </div>


    </div>
        
    <div class="rowSeparator-noLine"></div>

</form>