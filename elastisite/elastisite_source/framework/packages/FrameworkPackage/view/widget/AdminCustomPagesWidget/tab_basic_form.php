<form name="FrameworkPackage_customPageBasic_form" id="FrameworkPackage_customPageBasic_form" method="POST" action="" enctype="multipart/form-data">
<?php 
$customPage = clone $form->getEntity();
// $requests = $container->getRequest()->getAll();

$pageToolView = $viewTools->create('pageTool');
// dump($pageToolView);exit;
$pageToolView->setPageToolIdPre('customPageBasic');

// $formView = $viewTools->create('form')->setForm($form);
// $formView->add('text')->setPropertyReference('routeName')->setLabel(trans('route.name'));
// $formView->add('text')->setPropertyReference('titleReference')->setLabel(trans('title.reference'));
// $formView->setFormMethodPath('admin/customPage/basic/editForm');
// $formView->displayForm(false, false)->displayScripts();
// dump($customPageId);
// dump($requestedRouteName);
// dump($newRouteRequest);
// dump($form->getEntity());
// dump('alma');
$routeSelected = false;
?>

<?php if (($customPageId && !$newRouteRequest) || (!$customPageId && $requestedRouteName && $requestedRouteName != '')): ?>
<?php 
    $routeSelected = true; 
?>
<div class="mb-3">
    <label for="FrameworkPackage_customPageBasicEdit_titleReference" class="form-label"><b><?php echo trans('page'); ?></b></label>
    <div class="">
        <div class="card">
            <div class="card-body">
            <?php if (!$customPageId): ?>
                <a href="" onclick="CustomPageBasic.modifyRoute(event)"><?php echo trans('modify'); ?></a>
            <?php endif; ?>
                <div class="tagFrame-col" id="">
                    <div class="tag-light">
                        <table style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td id="">
        <?php 
        // dump($form->getEntity()); 
        ?>
                                    <?php if ($form->getEntity()->getRouteName() == 'reserved_default_route'): ?>
                                        <b><?php echo trans('default.page'); ?></b>
                                    <?php else: ?>
                                        <?php echo trans($pageToolView->getTitleString($form->getEntity()->getRouteName())); ?>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="">
                                    <?php if ($form->getEntity()->getRouteName() == 'reserved_default_route'): ?>
                                        
                                    <?php else: ?>
                                        <?php echo $pageToolView->getParamChainString($form->getEntity()->getRouteName()); ?>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="mb-3">
    <label for="UserPackage_editFBSUser_username" class="form-label">Felhasználónév</label>
    <div class="input-group has-validation">
        <input type="text" class="form-control inputField" name="UserPackage_editFBSUser_username" id="UserPackage_editFBSUser_username" maxlength="250" placeholder="" value="terenszman">
        <div class="invalid-feedback validationMessage" id="UserPackage_editFBSUser_username-validationMessage"></div>
    </div>
</div> -->


    <?php if ($form->getEntity()->getRouteName() == 'reserved_default_route'): ?>
<div class="card">
    <!-- <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    </div> -->
    <div class="card-body">
        <div class="mb-0">
            <?php echo trans('default.page.description'); ?>
        </div>
    </div>
</div>
    <?php endif; ?>
<?php else: ?>
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <?php echo trans('page'); ?>
    </div>
    <div class="card-body">
        <div class="">
        <?php $pageToolView->showCustomizablePageRoutesSelector(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- <div class="rowSeparator-noLine"></div> -->

<!-- <div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div class="form-group formLabel">
            <label for="FrameworkPackage_customPageBasicEdit_titleReference">
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <div class="input-group">
                <input name="FrameworkPackage_customPageBasicEdit_titleReference" id="FrameworkPackage_customPageBasicEdit_titleReference" type="text" class="inputField form-control" value="" aria-describedby="" placeholder="">

            </div>
            <div class="validationMessage error" id="FrameworkPackage_customPageBasicEdit_titleReference-validationMessage" style="padding-top:4px;"></div>
        </div>
    </div>
</div> -->

<?php if ($routeSelected && !$form->getEntity()->getId()): ?>
<div class="mb-3">
    <div class="input-group">
        <button name="FrameworkPackage_customPageBasic_submit" id="FrameworkPackage_customPageBasic_submit" type="button" class="btn btn-primary btn-block" style="width: 200px;" onclick="CustomPageBasic.submitForm();" value=""><?php echo trans('create'); ?></button>
    </div>
</div>
<!-- <div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <button name="FrameworkPackage_customPageBasic_submit" id="FrameworkPackage_customPageBasic_submit" type="button" class="btn btn-primary btn-block" style="width: 200px;" onclick="CustomPageBasic.submitForm();" value=""><?php echo trans('create'); ?></button>
        </div>
    </div>
</div> -->
<?php else: ?>
<div class="mb-3">
    <label for="UserPackage_editFBSUser_username" class="form-label"><?php echo trans('page.title'); ?></label>
    <div class="input-group has-validation">
        <input type="text" class="form-control inputField" name="FrameworkPackage_customPageTitle_title" id="FrameworkPackage_customPageTitle_title" maxlength="250" placeholder="" value="<?php echo $customPage->getTitle(); ?>">
        <div class="invalid-feedback validationMessage" id="FrameworkPackage_customPageTitle_title-validationMessage"></div>
    </div>
</div>

<div class="mb-3">
    <div class="input-group">
        <button name="FrameworkPackage_customPageBasic_submit" id="FrameworkPackage_customPageBasic_submit" type="button" class="btn btn-primary btn-block" style="width: 200px;" 
            onclick="LoadingHandler.start(); CustomPageTitle.saveTitle(event);" value=""><?php echo trans('save.title'); ?></button>
    </div>
</div>


<!-- <div class="widgetWrapper">
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="form-group formLabel">
                <label>
                    <b><?php echo trans('page.title'); ?></b>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
            <div class="input-group">
                <input id="FrameworkPackage_customPageTitle_title" type="text" class="inputField form-control enterSubmits" value="<?php echo $customPage->getTitle(); ?>" aria-describedby="" placeholder="">
            </div>
                <div class="validationMessage error" id="FrameworkPackage_customPageTitle_title-validationMessage" style="padding-top:4px;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="FrameworkPackage_customPageTitle_submit" id="FrameworkPackage_customPageTitle_submit" type="button" class="btn btn-secondary btn-block" style="width: 200px;" 
                    onclick="LoadingHandler.start(); CustomPageTitle.saveTitle(event);" value=""><?php echo trans('save.title'); ?></button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="FrameworkPackage_customPageTitle-message" style="padding-top:4px;"></div>
        </div>
    </div>
</div> -->
<?php endif; ?>
</form>