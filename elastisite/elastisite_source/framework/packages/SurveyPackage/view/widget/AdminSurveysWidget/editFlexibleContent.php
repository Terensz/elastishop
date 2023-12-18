<div id="editSurvey_id" style="display: none;"><?php echo $form->getEntity()->getId(); ?></div>

<div>
    <form name="SurveyPackage_editSurvey_form" id="SurveyPackage_editSurvey_form" method="POST" action="" enctype="multipart/form-data">
        
<?php 
$formView = $viewTools->create('form')->setForm($form);
$formView->setLabelAdditionalClass(' textAlignLeft');
$formView->add('text')->setPropertyReference('title')->setLabel(trans('title'));
// $formView->add('inactiveField')->setPropertyReference('slug')->setLabel(trans('slug'));

$formView->add($form->getEntity()->getId() ? 'text' : 'hidden')->setPropertyReference('slug')->setLabel(trans('slug'));

if ($form->getEntity()->getId()):
$surveyLink = $httpDomain.'/'.trans('survey.link.factory.slug').'/'.$form->getEntity()->getSlug();
$surveyLinkView = '<div class="row">
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    <div class="form-group formLabel textAlignLeft">
        <label>
            <b>'.trans('survey.link').'</b>
        </label>
    </div>
</div>
<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
    <div class="form-group">
        <a href="'.$surveyLink.'" target="_blank"><div class="widgetWrapper-info" style="word-break: break-all;">'.$surveyLink.'</div></a>
    </div>
</div>
</div>';
$formView->add('custom')->setPropertyReference(null)->setLabel()->addCustomData('view', $surveyLinkView);
endif;

$formView->add('textarea')->setPropertyReference('description')->setLabel(trans('description'));
$statusSelect = $formView->add('select')->setPropertyReference('status')->setLabel(trans('status'));
if ($form->getEntity()->getId()) {
    $statusSelect->addOption('1', 'active');
}
$statusSelect->addOption('0', 'disabled');

$formView->setFormMethodPath('admin/survey/editSurvey');
$formView->displayForm(false, false);
// ->displayScripts();
?>
        <!-- <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                    <button name="" id="" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="SurveyPackageEditSurveyForm.call(<?php echo $form->getEntity()->getId() ? : 'null'; ?>);" value="">Fejlécadatok mentése</button>
                </div>
            </div>
        </div> -->

        <div class="mb-3">
            <button name="" id="" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="SurveyPackageEditSurveyForm.call(<?php echo $form->getEntity()->getId() ? : 'null'; ?>);" 
                value="">Fejlécadatok mentése
            </button>
        </div>

    </form>

    <div id="SurveyCreator_questionList">
    </div>

<?php if ($form->getEntity()->getId() && !$form->getEntity()->getRepository()->isDisabled($form->getEntity()->getId())): ?>
        <div class="mb-3" id="SurveyCreator_addQuestionButtonFrame">
            <button type="button" class="btn btn-success" onclick="SurveyCreator.addQuestion();"><?php echo trans('add.question'); ?></button>
        </div>
<?php endif; ?>
    <?php 
    // dump($form);
    ?>
</div>