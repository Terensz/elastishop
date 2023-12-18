<div class="widgetWrapper-info"><?php echo trans('newsletter.create.dispatch.process.info'); ?></div>
<div id="NewsletterPackage_EditNewsletterDispatchProcess_id" style="display: none;"><?php echo $form->getEntity()->getId() ? : ''; ?></div>
<?php 
$id = $form->getEntity()->getId();
// dump($this->getContainer()->getRequest()->getAll());
$formView = $viewTools->create('form')->setForm($form);
$formView->setFormMethodPath('admin/newsletter/dispatchProcess/edit');

if ($form->getEntity()->getNewsletterCampaign()->getStatus() == 0) {
    $formView->add('disabledText')->setPropertyReference('newsletterCampaign')->setValue($form->getEntity()->getNewsletterCampaign()->getTitle())->setLabel(trans('newsletter.campaign'));
} else {
    $newsletterCampaignSelect = $formView->add($id ? 'disabledSelect' :'select')
        ->setPropertyReference('newsletterCampaign')
        ->setLabel(trans('newsletter.campaign'));

    /**
     * Just for new dispatch process
    */
    if (!$id) {
        $newsletterCampaignSelect->addOption('*null*', '-');
    }
    // dump($form->getEntity()->getNewsletterCampaign());
    if ($newsletterCampaigns && is_array($newsletterCampaigns)) {
        foreach ($newsletterCampaigns as $newsletterCampaign) {
            /**
            * Only new will pass, or the edited only in that case, if the loop is the same as the campaign of the edited one.
            * Because we don't want the administrator to change the campaign while the sending is in progress.
            */
            if ((!$form->getEntity()->getNewsletterCampaign() || !$form->getEntity()->getNewsletterCampaign()->getId()) || ($newsletterCampaign->getId() == $form->getEntity()->getNewsletterCampaign()->getId())) {
                $newsletterCampaignSelect->addOption($newsletterCampaign->getId(), $newsletterCampaign->getTitle());
            }
        }
    }
}

$modeSelect = $formView->add($id ? 'disabledSelect' :'select')
    ->setPropertyReference('mode')
    ->setLabel(trans('mode'));
    // dump($modes);
foreach ($modes as $modeIndex => $modeName) {
    $modeSelect->addOption(
        $modeIndex, 
        $modeName, 
        false
    );
}

$statusInfoText = '<div class="widgetWrapper-info">'.trans('newsletter.dispatch.process.status.info').'</div>';
$formView->add('custom')->setPropertyReference(null)->setLabel(trans('status'))->addCustomData('view', $statusInfoText);

$statusSelect = $formView->add($id ? 'select' :'disabledSelect')
    ->setPropertyReference('status')
    ->setLabel(trans('status'));
foreach ($statuses as $statusIndex => $statusName) {
    $statusSelect->addOption(
        $statusIndex, 
        $statusName, 
        false
    );
}

$formView->displayForm(true)->displayScripts();
?>

<?php if (!$id): ?>
<div class="widgetWrapper-info"><?php echo trans('newsletter.create.dispatch.process.create.info'); ?></div>
<?php endif; ?>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <button name="" id="" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="EditNewsletterDispatchProcess.save('<?php echo $id; ?>');" value=""><?php echo trans('save'); ?></button>
        </div>
    </div>
</div>

<script>

var EditNewsletterDispatchProcess = {
    save: function(id) {
        LoadingHandler.start();
        // let editorData = CKEDITOR.instances.NewsletterPackage_EditNewsletter_body.getData();
        // console.log(CKEDITOR.instances.NewsletterPackage_EditNewsletter_body.getData());
        // $('#NewsletterPackage_EditNewsletter_body').val(editorData);
        NewsletterPackageEditNewsletterDispatchProcessForm.call(id);
        // console.log('ajaxResponse: ', ajaxResponse);
        // if (ajaxResponse.data.formIsValid == true) {
        //     AdminNewsletterDispatchProcessesGrid.list(true);
        //     $('#editorModal').modal('hide');
        // }
    }
};

$(document).ready(function() {
    $('textarea').keypress(function(e) {
        if (e.which == 13) {
            e.stopPropagation();
        }
    });

    $('#NewsletterPackage_EditNewsletterDispatchProcess_NewsletterCampaign_newsletter').off('change');
    $('#NewsletterPackage_EditNewsletterDispatchProcess_NewsletterCampaign_newsletter').on('change', function() {
        console.log('NewsletterPackage_EditNewsletterDispatchProcess_NewsletterCampaign_newsletter change!!!');
    });
});
</script>