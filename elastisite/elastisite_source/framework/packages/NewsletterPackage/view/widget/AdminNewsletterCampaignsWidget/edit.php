<?php 
// dump($newsletters);
// dump($this->getContainer()->getRequest()->getAll());
// dump($form->getEntity());
$formView = $viewTools->create('form')->setForm($form);
$formView->setFormMethodPath('admin/newsletter/campaign/edit');

if ($isEditable) {
    $newsletterSelect = $formView->add('select')
        ->setPropertyReference('newsletter')
        ->setLabel(trans('newsletter'));
    $newsletterSelect->addOption('*null*', '-');
    if ($newsletters && is_array($newsletters)) {
        foreach ($newsletters as $newsletter) {
            $newsletterSelect->addOption($newsletter->getId(), $newsletter->getSubject());
        }
    }
} else {
    $formView->add('disabledText')->setPropertyReference('newsletter')->setValue($form->getEntity()->getNewsletter()->getSubject())->setLabel(trans('newsletter'));
}

$formView->add($isEditable ? 'text' : 'disabledText')->setPropertyReference('title')->setLabel(trans('title'));

$statusSelect = $formView->add('select')
->setPropertyReference('status')
->setLabel(trans('status'));
foreach ($statuses as $statusIndex => $statusName) {
    $statusSelect->addOption(
        $statusIndex, 
        $statusName, 
        false
    );
}

// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));

$formView->displayForm(true)->displayScripts();
?>

<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="form-group">
            <button name="" id="" type="button" class="btn btn-secondary btn-block" style="width: 200px;" onclick="EditNewsletterCampaign.save('<?php echo $id; ?>');" value=""><?php echo trans('save'); ?></button>
        </div>
    </div>
</div>

<script>

var EditNewsletterCampaign = {
    save: function(id) {
        // let editorData = CKEDITOR.instances.NewsletterPackage_EditNewsletter_body.getData();
        // console.log(CKEDITOR.instances.NewsletterPackage_EditNewsletter_body.getData());
        // $('#NewsletterPackage_EditNewsletter_body').val(editorData);
        NewsletterPackageEditNewsletterCampaignForm.call(id);
        // if (ajaxResponse.data.formIsValid == true) {
        //     AdminNewsletterCampaignsGrid.list(true);
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
});
</script>