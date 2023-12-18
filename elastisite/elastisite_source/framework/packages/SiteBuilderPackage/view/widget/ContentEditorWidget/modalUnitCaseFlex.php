<div class="widgetWrapper-info">
<?php echo trans('link.placeholder.info'); ?>
</div>
<div class="widgetWrapper-info">
<?php echo trans('page.title.placeholder.info'); ?>
</div>
<?php  
// dump($form);
$formView = $viewTools->create('form')->setForm($form);
// $formView->setResponseLabelSelector('#editorModalLabel');
// $formView->setResponseBodySelector('#ElastiSite_editContentEditorUnit_form');
// $formView->setFormMethodPath('ContentEditorWidget/editor/editContentEditorUnit/form');
$formView->add('hidden')->setPropertyReference('contentEditorUnitCaseId')->setLabel(trans('contentEditorUnitCaseId'));

$verticalPositionDirectionSelect = $formView->add('select')
    ->setPropertyReference('verticalPositioningDirection')
    ->setLabel(trans('vertical.positioning.direction'));
$verticalPositionDirectionSelect->addOption($form->getEntity()::VERTICAL_POSITIONING_DIRECTION_TOP, 'to.top', true);
$verticalPositionDirectionSelect->addOption($form->getEntity()::VERTICAL_POSITIONING_DIRECTION_BOTTOM, 'to.bottom', true);
$verticalPositionDirectionSelect->addOption($form->getEntity()::VERTICAL_POSITIONING_DIRECTION_BOTH, 'to.both', true);

$formView->add('text')->setPropertyReference('verticalPosition')->setLabel(trans('vertical.position'));

$horizontalPositionDirectionSelect = $formView->add('select')
    ->setPropertyReference('horizontalPositioningDirection')
    ->setLabel(trans('horizontal.positioning.direction'));
$horizontalPositionDirectionSelect->addOption($form->getEntity()::HORIZONTAL_POSITIONING_DIRECTION_LEFT, 'to.left', true);
$horizontalPositionDirectionSelect->addOption($form->getEntity()::HORIZONTAL_POSITIONING_DIRECTION_RIGHT, 'to.right', true);
$horizontalPositionDirectionSelect->addOption($form->getEntity()::HORIZONTAL_POSITIONING_DIRECTION_BOTH, 'to.both', true);

$formView->add('text')->setPropertyReference('horizontalPosition')->setLabel(trans('horizontal.position'));
$formView->add('text')->setPropertyReference('height')->setLabel(trans('height'));
$formView->add('text')->setPropertyReference('width')->setLabel(trans('width'));

$classSelect = $formView->add('select')
    ->setPropertyReference('class')
    ->setLabel(trans('text.wrapping'));
    $classSelect->addOption('', 'none', true);
foreach ($classes as $class) {
    $classSelect->addOption($class['class'], $class['translationReference'], true);
}

// $formViewDummy = clone $formView;
// // $formViewDummy->setForm(null);
// dump($formViewDummy);

$formView->displayForm(false, false);
?>
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <div class="form-group">
                <button name="" id="" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="ElastiSiteEditContentEditorUnitCaseForm.call(<?php echo $form->getEntity()->getId() ? : 'null'; ?>);" value=""><?php echo trans('save'); ?></button>
            </div>
        </div>
    </div>

<script>
    $('document').ready(function() {
        $('#ElastiSite_editContentEditorUnit_positionTop').mask('9999');
        $('#ElastiSite_editContentEditorUnit_positionRight').mask('9999');
        $('#ElastiSite_editContentEditorUnit_width').mask('9999');
    });
</script>