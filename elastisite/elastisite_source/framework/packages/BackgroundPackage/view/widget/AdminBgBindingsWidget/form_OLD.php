
<form name="BackgroundPackage_editFBSPageBackground_form" id="BackgroundPackage_editFBSPageBackground_form" method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="BackgroundPackage_editFBSPageBackground_routeName"><?php echo trans('route.name'); ?></label>
        <select name="BackgroundPackage_editFBSPageBackground_routeName" id="BackgroundPackage_editFBSPageBackground_routeName" class="inputField form-control">
            <option value="*null*"><?php echo trans('please.choose'); ?></option>
<?php
foreach ($routeMap as $routeMapElement) {
    if (isset($routeMapElement['title'])) {
        $selectedStr = $pageBackground->getRouteName() == $routeMapElement['name'] ? ' selected' : '';
?>

            <option value="<?php echo $routeMapElement['name']; ?>"<?php echo $selectedStr; ?>><?php echo $container->getRoutingHelper()->getObviousParamChain($routeMapElement['paramChains']).' - ('.trans($routeMapElement['title']).')'; ?></option>
<?php
    }
}
?>
        </select>
    </div>
    <div class="form-group">
        <label for="BackgroundPackage_editFBSPageBackground_fbsBackgroundTheme"><?php echo trans('background.name'); ?></label>
        <select name="BackgroundPackage_editFBSPageBackground_fbsBackgroundTheme" id="BackgroundPackage_editFBSPageBackground_fbsBackgroundTheme" class="inputField form-control">
            <option value="*null*"><?php echo trans('please.choose'); ?></option>
<?php
foreach ($backgrounds as $background) {
    $selectedStr = $background->getTheme() == $pageBackground->getFbsBackgroundTheme() ? ' selected' : '';
?>
            <option value="<?php echo $background->getTheme(); ?>"<?php echo $selectedStr; ?>><?php echo $background->getTitle(); ?></option>
<?php
}
?>
        </select>
    </div>
    <button id="BackgroundPackage_editFBSPageBackground_submit" style="width: 200px;"
        type="button" class="btn btn-secondary btn-block"
        onclick="AdminBgBindingsWidget.call(<?php echo $pageBackgroundId; ?>);"><?php echo trans('save.changes'); ?></button>
</form>
